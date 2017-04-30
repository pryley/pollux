<?php

namespace GeminiLabs\Pollux;

use GeminiLabs\Pollux\Application;
use GeminiLabs\Pollux\Helper;
use GeminiLabs\Pollux\PostType\Archive;
use GeminiLabs\Pollux\Settings\Settings;

class Controller
{
	/**
	 * @var Application
	 */
	protected $app;

	public function __construct( Application $app )
	{
		$this->app = $app;
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
		$screenId = ( new Helper )->getCurrentScreen()->id;
		if( $screenId == sprintf( 'toplevel_page_%s', apply_filters( 'pollux/archive/option', Archive::ID ))) {
			wp_enqueue_script('editor-expand');
			if( wp_is_mobile() ) {
				wp_enqueue_script( 'jquery-touch-punch' );
			}
		}
		if( screenId == sprintf( 'toplevel_page_%s', apply_filters( 'pollux/settings/option', Settings::ID ))) {
			wp_enqueue_script( 'common' );
			wp_enqueue_script( 'wp-lists' );
			wp_enqueue_script( 'postbox' );
		}
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
	 * @action admin_init
	 */
	public function removeDashboardWidgets()
	{
		if( !$this->app->config['remove_dashboard_widgets'] )return;
		$widgets = apply_filters( 'pollux/dashoard/widgets', [
			'dashboard_primary',
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
}
