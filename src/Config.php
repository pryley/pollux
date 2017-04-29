<?php

namespace GeminiLabs\Pollux;

use GeminiLabs\Pollux\Application;
use Symfony\Component\Yaml\Yaml;

class Config
{
	const RAW_STRINGS = [
		'__', '_n', '_x', 'sprintf',
	];

	/**
	 * @var Application
	 */
	protected $app;

	public function __construct( Application $app )
	{
		$this->app = $app;
	}

	/**
	 * @return array
	 */
	public function get()
	{
		$configFile = $this->getFile();
		$configYaml = $this->getYaml();
		if( $this->shouldGenerate( $configYaml )) {
			file_put_contents( $configFile, sprintf( '<?php // DO NOT MODIFY THIS FILE DIRECTLY!%sreturn %s;',
				PHP_EOL,
				$this->parseYaml( $configYaml )
			));
		}
		return include $configFile;
	}

	/**
	 * @return string
	 */
	public function getFile( $filename = 'pollux-config.php' )
	{
		$filename = apply_filters( 'pollux/config/dist/file', $filename );
		$storagePath = trailingslashit( apply_filters( 'pollux/config/dist/location', WP_CONTENT_DIR ));
		if( !is_dir( $storagePath )) {
			mkdir( $storagePath, 0775 );
		}
		return sprintf( '%s%s', $storagePath, $filename );
	}

	/**
	 * @return string
	 */
	public function getYaml()
	{
		$theme = wp_get_theme();
		$configYaml = apply_filters( 'pollux/config/src/file', 'pollux.yml' );
		$configLocations = apply_filters( 'pollux/config/src/location', [
			trailingslashit( trailingslashit( $theme->theme_root ) . $theme->stylesheet ),
			trailingslashit( trailingslashit( $theme->theme_root ) . $theme->template ),
			trailingslashit( WP_CONTENT_DIR ),
			trailingslashit( ABSPATH ),
			trailingslashit( dirname( ABSPATH )),
		]);
		foreach( (array) $configLocations as $location ) {
			if( !file_exists( $location . $configYaml ))continue;
			return $location . $configYaml;
		}
		return $this->app->path( 'defaults.yml' );
	}

	/**
	 * @param string $yamlFile
	 * @return string
	 */
	public function parseYaml( $yamlFile )
	{
		$config = wp_parse_args(
			Yaml::parse( file_get_contents( $yamlFile )),
			Yaml::parse( file_get_contents( $this->app->path( 'defaults.yml' )))
		);
		return $this->parseRawStrings( var_export( $config, true ));
	}

	/**
	 * @param string $config
	 * @return string
	 */
	protected function parseRawStrings( $config )
	{
		$strings = apply_filters( 'pollux/config/raw_strings', static::RAW_STRINGS );
		return stripslashes(
			preg_replace( '/(\')((' . implode( '|', $strings ) . ')\(?.+\))(\')/', '$2', $config )
		);
	}

	/**
	 * @param string $configYaml
	 * @return bool
	 */
	protected function shouldGenerate( $configYaml )
	{
		$configFile = $this->getFile();
		if( !file_exists( $configFile )) {
			return true;
		}
		return filemtime( $configYaml ) >= filemtime( $configFile );
	}
}
