<?php

namespace GeminiLabs\Pollux;

use GeminiLabs\Pollux\AliasLoader;
use GeminiLabs\Pollux\Config;
use GeminiLabs\Pollux\Container;
use GeminiLabs\Pollux\Controller;
use GeminiLabs\Pollux\Facade;
use GeminiLabs\Pollux\PostType;

final class Application extends Container
{
	const PREFIX = 'pollux_';

	public $config;
	public $file;
	public $id;
	public $name;
	public $version;

	public function __construct()
	{
		$this->file = realpath( dirname( __DIR__ ) . '/pollux.php' );
		$data = get_file_data( $this->file, [
			'id' => 'Text Domain',
			'name' => 'Plugin Name',
			'version' => 'Version',
		], 'plugin' );
		array_walk( $data, function( $value, $key ) {
			$this->$key = $value;
		});
	}

	/**
	 * @return void
	 */
	public function bootstrap()
	{
		$this->config = (new Config( $this ))->get();
		Facade::clearResolvedInstances();
		Facade::setFacadeApplication( $this );
		$this->registerAliases();
		$classNames = [
			'MetaBox', 'PostType', 'Taxonomy', 'Settings',
		];
		foreach( $classNames as $className ) {
			$this->make( $className )->init();
 		}
	}

	/**
	 * @param string $name
	 * @param string $path
	 * @return string
	 */
	public function buildClassName( $name, $path = '' )
	{
		$className = array_map( 'ucfirst', array_map( 'strtolower', preg_split( '/[-_]/', $name )));
		$className = implode( '', $className );
		return !empty( $path )
			? str_replace( '\\\\', '\\', sprintf( '%s\%s', $path, $className ))
			: $className;
	}

	/**
	 * @param string $name
	 * @param string $prefix
	 * @return string
	 */
	public function buildMethodName( $name, $prefix = 'get' )
	{
		return lcfirst( $this->buildClassName( $prefix . '-' . $name ));
	}

	/**
	 * @param string $checkFor
	 * @return string|bool
	 */
	public function environment( $checkFor = '' )
	{
		$environment = defined( 'WP_ENV' ) ? WP_ENV : 'production';
		if( !empty( $checkFor )) {
			return $environment == $checkFor;
		}
		return $environment;
	}

	/**
	 * The Application entry point
	 *
	 * @return void
	 */
	public function init()
	{
		$this->bootstrap();

		$controller = $this->make( Controller::class );

		add_filter( 'admin_footer_text',          [$controller, 'filterWordPressFooter'] );
		add_action( 'admin_enqueue_scripts',      [$controller, 'registerAssets'] );
		add_action( 'admin_init',                 [$controller, 'removeDashboardWidgets'] );
		add_action( 'wp_before_admin_bar_render', [$controller, 'removeWordPressMenu'] );

		// Disallow indexing of the site on non-production environments
		if( !$this->environment( 'production' ) && !is_admin() ) {
			add_filter( 'pre_option_blog_public', '__return_zero' );
		}
	}

	/**
	 * @return void
	 */
	public function onActivation()
	{
	}

	/**
	 * @return void
	 */
	public function onDeactivation()
	{
	}

	/**
	 * @return void
	 */
	public function registerAliases()
	{
		$aliases = [
			'PostMeta' => Facades\PostMeta::class,
			'SiteMeta' => Facades\SiteMeta::class,
		];
		AliasLoader::getInstance( $aliases )->register();
	}

	/**
	 * @param string $file
	 * @return string
	 */
	public function path( $file = '' )
	{
		return plugin_dir_path( $this->file ) . ltrim( trim( $file ), '/' );
	}

	/**
	 * @param string $path
	 * @return string
	 */
	public function url( $path = '' )
	{
		return esc_url( plugin_dir_url( $this->file ) . ltrim( trim( $path ), '/' ));
	}
}
