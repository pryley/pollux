<?php

namespace GeminiLabs\Pollux\Config;

use GeminiLabs\Pollux\Application;
use GeminiLabs\Pollux\Config\Config;
use GeminiLabs\Pollux\MetaBox\SiteMetaManager;
use Symfony\Component\Yaml\Exception\DumpException;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

/**
 * @property int $updated
 */
class ConfigManager extends SiteMetaManager
{
	const RAW_STRINGS = [
		// '__', '_n', '_x', 'esc_attr__', 'esc_html__', 'sprintf',
	];

	public $compiled;

	public $parseError = false;

	/**
	 * @var Application
	 */
	protected $app;

	public function __construct( Application $app )
	{
		$this->app = $app;
		$this->options = $this->buildConfig();
		$this->compiled = $this->compile();
	}

	/**
	 * @return array
	 */
	public function buildConfig()
	{
		$yamlFile = $this->getYamlFile();
		$yaml = $this->normalizeYamlValues( $this->normalize(
			$this->parseYaml( file_get_contents( $yamlFile ), $yamlFile )
		));
		if( !$yaml['disable_config'] ) {
			$config = array_filter( (array) get_option( Config::id(), [] ));
		}
		return empty( $config )
			? $this->setTimestamp( $yaml, filemtime( $yamlFile ))
			: $this->normalizeYamlValues( $this->normalize( $config ));
	}

	/**
	 * @param bool $force
	 * @return object
	 */
	public function compile( $force = false )
	{
		$configFile = $this->getCompileDestination();
		if( $force || $this->shouldCompile( $configFile )) {
			$config = $this->normalizeArray( $this->options );
			if( $this->parseError ) {
				return (object) $config;
			}
			file_put_contents( $configFile, sprintf( '<?php // DO NOT MODIFY THIS FILE DIRECTLY!%sreturn (object) %s;',
				PHP_EOL,
				$this->parseRawStrings( var_export( $this->setTimestamp( $config ), true ))
			));
		}
		return include $configFile;
	}

	/**
	 * @return string
	 */
	public function convertArrayToYaml( array $array )
	{
		return !empty( $array )
			? trim( $this->parseRawStrings( $this->dumpYaml( $array )))
			: '';
	}

	/**
	 * @return string
	 */
	public function getCompileDestination( $filename = 'pollux-config.php' )
	{
		$filename = apply_filters( 'pollux/config/dist/file', $filename );
		$storagePath = apply_filters( 'pollux/config/dist/location', WP_CONTENT_DIR );
		wp_mkdir_p( $storagePath );
		return sprintf( '%s%s', trailingslashit( $storagePath ), $filename );
	}

	/**
	 * @return string
	 */
	public function getYamlFile()
	{
		$theme = wp_get_theme();
		$configYaml = apply_filters( 'pollux/config/src/file', 'pollux.yml' );
		$configLocations = apply_filters( 'pollux/config/src/location', [
			trailingslashit( trailingslashit( $theme->theme_root ) . $theme->stylesheet ),
			trailingslashit( trailingslashit( $theme->theme_root ) . $theme->template ),
			trailingslashit( WP_CONTENT_DIR ),
			trailingslashit( ABSPATH ),
			trailingslashit( dirname( ABSPATH )),
			trailingslashit( dirname( dirname( ABSPATH ))),
		]);
		foreach( (array) $configLocations as $location ) {
			if( !file_exists( $location . $configYaml ))continue;
			return $location . $configYaml;
		}
		return $this->app->path( 'defaults.yml' );
	}

	/**
	 * @return array
	 */
	public function normalizeArray( array $array )
	{
		array_walk( $array, function( &$value, $key ) {
			if( !is_numeric( $value ) && is_string( $value )) {
				$value = $this->parseYaml( $value, $key );
				if( $this->parseError == $key ) {
					$value = [];
				}
			}
		});
		return $array;
	}

	/**
	 * @return array
	 */
	public function normalizeYamlValues( array $array )
	{
		return array_map( function( $value ) {
			return is_array( $value )
				? $this->convertArrayToYaml( $value )
				: $value;
		}, $array );
	}

	/**
	 * @return array
	 */
	public function setTimestamp( array $config, $timestamp = null )
	{
		$timestamp || $timestamp = time();
		$config['updated'] = $timestamp;
		return $config;
	}

	/**
	 * @return string|null
	 */
	protected function dumpYaml( array $array )
	{
		try {
			return Yaml::dump( $array, 13, 2 );
		}
		catch( DumpException $e ) {
			$this->app->make( 'Notice' )->addError( $e->getMessage() );
		}
	}

	/**
	 * @return array
	 */
	protected function normalize( array $config )
	{
		return wp_parse_args(
			$config,
			$this->parseYaml(
				file_get_contents( $this->app->path( 'defaults.yml' )),
				$this->app->path( 'defaults.yml' )
			)
		);
	}

	/**
	 * @param string $configString
	 * @return string
	 * @todo only allow raw strings when we can parse them properly without using eval()
	 */
	protected function parseRawStrings( $configString )
	{
		$strings = apply_filters( 'pollux/config/raw_strings', static::RAW_STRINGS );
		if( empty( $strings )) {
			return $configString;
		}
		$pattern = '/(\')((' . implode( '|', $strings ) . ')\(?.+\))(\')/';
		return stripslashes(
			preg_replace_callback( $pattern, function( $matches ) {
				return str_replace( "''", "'", $matches[2] );
			}, $configString )
		);
	}

	/**
	 * @link http://api.symfony.com/3.2/Symfony/Component/Yaml/Exception/ParseException.html
	 * @return array
	 */
	protected function parseYaml( $value, $file = null )
	{
		try {
			return (array) Yaml::parse( $value );
		}
		catch( ParseException $e ) {
			$this->parseError = $file;
			if( $file ) {
				$file = sprintf( '<code>%s</code>', $file );
			}
			$this->app->make( 'Notice' )->addError([
				sprintf( '<strong>Pollux Error:</strong> Unable to parse config at line %s (near "%s").',
					$e->getParsedLine(),
					$e->getSnippet()
				),
				$file
			]);
			return $value;
		}
	}

	/**
	 * @param string $configFile
	 * @return bool
	 */
	protected function shouldCompile( $configFile )
	{
		if( !file_exists( $configFile )) {
			return true;
		}
		$config = include $configFile;
		if( $this->updated >= $config->updated ) {
			return true;
		}
		return filemtime( $this->getYamlFile() ) >= $config->updated;
	}
}
