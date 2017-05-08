<?php

namespace GeminiLabs\Pollux;

use GeminiLabs\Pollux\Application;
use GeminiLabs\Pollux\Helper;
use GeminiLabs\Pollux\PostType\Archive;
use GeminiLabs\Pollux\Settings\Settings;
use WP_Screen;

class Controller
{
	/**
	 * @var string
	 */
	public $hook;

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
	 * @filter plugin_action_links_pollux/pollux.php
	 */
	public function filterPluginLinks( array $links )
	{
		$settings_url = admin_url( sprintf( 'options-general.php?page=%s', $this->app->id ));
		$links[] = sprintf( '<a href="%s">%s</a>', $settings_url, __( 'Settings', 'pollux' ));
		return $links;
	}

	/**
	 * @return void
	 * @filter admin_footer_text
	 */
	public function filterWordPressFooter( $text )
	{
		if( $this->app->config['remove_wordpress_footer'] )return;
		return $text;
	}

	/**
	 * @return void
	 * @action admin_enqueue_scripts
	 */
	public function registerAssets()
	{
		$screen = ( new Helper )->getCurrentScreen();

		$this->registerArchiveAssets( $screen );
		$this->registerCodemirrorAssets( $screen );
		$this->registerSettingsAssets( $screen );

		wp_enqueue_style( 'pollux/main.css',
			$this->app->url( 'assets/main.css' ),
			apply_filters( 'pollux/enqueue/css/deps', [] ),
			$this->app->version
		);
		wp_enqueue_script( 'pollux/main.js',
			$this->app->url( 'assets/main.js' ),
			apply_filters( 'pollux/enqueue/js/deps', [] ),
			$this->app->version
		);
		wp_localize_script( 'pollux/main.js',
			apply_filters( 'pollux/enqueue/js/localize/name', $this->app->id ),
			apply_filters( 'pollux/enqueue/js/localize/variables', [] )
		);
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
	 * @action admin_init
	 */
	public function removeDashboardWidgets()
	{
		if( !$this->app->config['remove_dashboard_widgets'] )return;
		$widgets = apply_filters( 'pollux/dashoard/widgets', [
			'dashboard_quick_press',
		]);
		foreach( $widgets as $widget ) {
			remove_meta_box( $widget, 'dashboard', 'normal' );
		}
	}

	/**
	 * @return void
	 * @action wp_before_admin_bar_render
	 */
	public function removeWordPressMenu()
	{
		if( !$this->app->config['remove_wordpress_menu'] )return;
		global $wp_admin_bar;
		$wp_admin_bar->remove_menu( 'wp-logo' );
	}

	/**
	 * @return void
	 * @callback add_submenu_page
	 */
	public function renderPage()
	{
		$this->app->render( 'index', [
			'heading' => __( 'Pollux Settings', 'pollux' ),
			'id' => $this->hook,
		]);
	}

	/**
	 * @return void
	 */
	protected function registerArchiveAssets( WP_Screen $screen )
	{
		if(( new Helper )->endsWith( '_archive', $screen->id ) && $screen->pagenow == 'edit.php' ) {
			wp_enqueue_script( 'common' );
			wp_enqueue_script( 'editor-expand' );
			wp_enqueue_script( 'post' );
			wp_enqueue_script( 'postbox' );
			wp_enqueue_script( 'wp-lists' );
			if( wp_is_mobile() ) {
				wp_enqueue_script( 'jquery-touch-punch' );
			}
		}
	}

	/**
	 * @return void
	 */
	protected function registerCodemirrorAssets( WP_Screen $screen )
	{
		if( $screen->id == 'settings_page_pollux' && $screen->pagenow == 'options-general.php' ) {
			wp_enqueue_style( 'pollux/codemirror.css',
				$this->app->url( 'assets/codemirror.css' ),
				[],
				$this->app->version
			);
			wp_enqueue_script( 'pollux/codemirror.js',
				$this->app->url( 'assets/codemirror.js' ),
				['pollux/main.js'],
				$this->app->version
			);
		}
	}

	/**
	 * @return void
	 */
	protected function registerSettingsAssets( WP_Screen $screen )
	{
		if( $screen->id == sprintf( 'toplevel_page_%s', Settings::id() )) {
			wp_enqueue_script( 'common' );
			wp_enqueue_script( 'postbox' );
			wp_enqueue_script( 'wp-lists' );
		}
	}
}
