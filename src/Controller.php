<?php

namespace GeminiLabs\Pollux;

use GeminiLabs\Pollux\Application;

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
		wp_enqueue_style( 'pollux/main.css',
			sprintf( '%sassets/css/main.css', $this->app->path ),
			apply_filters( 'pollux/enqueue/css/deps', [] ),
			$this->app->version
		);
		wp_enqueue_script( 'pollux/main.js',
			sprintf( '%sassets/js/main.js', $this->app->path ),
			apply_filters( 'pollux/enqueue/js/deps', [] ),
			$this->app->version,
			true
		);
		wp_localize_script( 'pollux/main.js',
			apply_filters( 'pollux/enqueue/js/localize/variable', 'pollux' ),
			apply_filters( 'pollux/enqueue/js/localize/variables', [
				'ajax' => admin_url( 'admin-ajax.php' ),
			])
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
