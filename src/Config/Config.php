<?php

namespace GeminiLabs\Pollux\Config;

use GeminiLabs\Pollux\Application;
use GeminiLabs\Pollux\Config\ConfigManager;

class Config
{
	const ID = 'config';

	/**
	 * @var string
	 */
	public $hook;

	/**
	 * @var Application
	 */
	protected $app;

	/**
	 * @return string
	 */
	public static function id()
	{
		return Application::PREFIX . static::ID;
	}

	public function __construct( Application $app )
	{
		$this->app = $app;
	}

	/**
	 * @return void
	 */
	public function init()
	{
		if( $this->app->config->disable_config )return;

		add_action( 'admin_menu',     [$this, 'registerPage'] );
		add_action( 'admin_menu',     [$this, 'registerSetting'] );
		add_action( 'current_screen', [$this, 'resetPage'] );
	}

	/**
	 * @return array
	 * @callback register_setting
	 */
	public function filterSavedSettings( array $settings )
	{
		return $this->app->make( ConfigManager::class )->setTimestamp( $settings );
	}

	/**
	 * @return void
	 * @action admin_menu
	 */
	public function registerPage()
	{
		$this->hook = add_submenu_page(
			'options-general.php',
			__( 'Pollux', 'pollux' ),
			__( 'Pollux', 'pollux' ),
			'manage_options',
			$this->app->id,
			[$this, 'renderPage']
		);
	}

	/**
	 * @return void
	 * @action admin_menu
	 */
	public function registerSetting()
	{
		register_setting( static::id(), static::id(), [$this, 'filterSavedSettings'] );
	}

	/**
	 * @return void
	 * @callback add_submenu_page
	 */
	public function renderPage()
	{
		$this->app->render( 'config/script' );
		$query = [
			'page' => $this->app->id,
			'action' => 'reset',
			'_wpnonce' => wp_create_nonce( static::id() ),
		];
		$this->app->render( 'config/index', [
			'config' => $this->app->make( ConfigManager::class ),
			'heading' => __( 'Pollux Settings', 'pollux' ),
			'id' => static::id(),
			'reset_url' => esc_url( add_query_arg( $query, admin_url( 'options-general.php' ))),
			'has_meta_box' => $this->app->gatekeeper->hasDependency( 'meta-box/meta-box.php' ),
		]);
	}

	/**
	 * @return void
	 * @action pollux/{static::ID}/init
	 */
	public function resetPage()
	{
		if( filter_input( INPUT_GET, 'page' ) !== $this->app->id
			|| filter_input( INPUT_GET, 'action' ) !== 'reset'
		)return;
		if( wp_verify_nonce( filter_input( INPUT_GET, '_wpnonce' ), static::id() )) {
			delete_option( static::id() );
			$this->app->make( ConfigManager::class )->compile( true );
			add_settings_error( static::id(), 'reset', __( 'Reset successful.', 'pollux' ), 'updated' );
		}
		else {
			add_settings_error( static::id(), 'failed', __( 'Failed to reset. Please try again.', 'pollux' ));
		}
		set_transient( 'settings_errors', get_settings_errors(), 30 );
		wp_safe_redirect( add_query_arg( 'settings-updated', 'true',  wp_get_referer() ));
		exit;
	}
}
