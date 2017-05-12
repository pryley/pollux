<?php

namespace GeminiLabs\Pollux;

use GeminiLabs\Pollux\AliasLoader;
use GeminiLabs\Pollux\Config\ConfigManager;
use GeminiLabs\Pollux\Container;
use GeminiLabs\Pollux\Facade;
use GeminiLabs\Pollux\GateKeeper;
use GeminiLabs\Pollux\PostType\PostType;

final class Application extends Container
{
	const PREFIX = 'pollux_';

	public $config;
	public $file;
	public $gatekeeper;
	public $id;
	public $name;
	public $notices = [];
	public $version;

	/**
	 * @return string
	 */
	public static function prefix()
	{
		return apply_filters( 'pollux/prefix', self::PREFIX );
	}

	public function __construct()
	{
		$this->file = realpath( dirname( dirname( __FILE__ )) . '/pollux.php' );
		$this->gatekeeper = new GateKeeper( plugin_basename( $this->file ));

		$data = get_file_data( $this->file, array(
			'id' => 'Text Domain',
			'name' => 'Plugin Name',
			'version' => 'Version',
		), 'plugin' );

		foreach( $data as $key => $value ) {
			$this->$key = $value;
		}
	}

	/**
	 * The Application entry point
	 *
	 * @return void
	 */
	public function init()
	{
		$this->bootstrap();

		$basename = plugin_basename( $this->file );
		$controller = $this->make( 'Controller' );

		add_action( 'admin_enqueue_scripts',           array( $controller, 'registerAssets' ));
		add_action( 'admin_init',                      array( $controller, 'removeDashboardWidgets' ));
		add_action( 'wp_before_admin_bar_render',      array( $controller, 'removeWordPressMenu' ));
		add_filter( "plugin_action_links_{$basename}", array( $controller, 'filterPluginLinks' ));
		add_filter( 'admin_footer_text',               array( $controller, 'filterWordPressFooter' ));

		// Disallow indexing of the site on non-production environments
		if( !$this->environment( 'production' ) && !is_admin() ) {
			add_filter( 'pre_option_blog_public', '__return_zero' );
		}
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
	 * @param string $file
	 * @return string
	 */
	public function path( $file = '' )
	{
		return plugin_dir_path( $this->file ) . ltrim( trim( $file ), '/' );
	}

	/**
	 * @param string $view
	 * @return bool
	 */
	public function render( $view, array $data = [] )
	{
		$file = apply_filters( 'pollux/views/file',
			$this->path( sprintf( 'views/%s.php', str_replace( '.php', '', $view ))),
			$view,
			$data
		);
		if( file_exists( $file )) {
			extract( $data );
			return include $file;
		}
		return false;
	}

	/**
	 * @param string $path
	 * @return string
	 */
	public function url( $path = '' )
	{
		return esc_url( plugin_dir_url( $this->file ) . ltrim( trim( $path ), '/' ));
	}

	/**
	 * @return void
	 */
	protected function bootstrap()
	{
		Facade::clearResolvedInstances();
		Facade::setFacadeApplication( $this );
		$this->registerAliases();
		$this->config = $this->make( ConfigManager::class )->compile();
		$classNames = array(
			'Config\Config',
			'MetaBox\MetaBox',
			'PostType\Archive',
			'PostType\DisablePosts',
			'PostType\PostType',
			'Settings\Settings',
			'Taxonomy\Taxonomy',
		);
		foreach( $classNames as $className ) {
			$this->make( $className )->init();
 		}
	}

	/**
	 * @return void
	 */
	protected function registerAliases()
	{
		AliasLoader::getInstance( apply_filters( 'pollux/aliases', array(
			'ArchiveMeta' => 'GeminiLabs\Pollux\Facades\ArchiveMeta',
			'PostMeta' => 'GeminiLabs\Pollux\Facades\PostMeta',
			'SiteMeta' => 'GeminiLabs\Pollux\Facades\SiteMeta',
		)))->register();
	}
}
