<?php

namespace GeminiLabs\Pollux\PostType;

use GeminiLabs\Pollux\Application;
use GeminiLabs\Pollux\Helper;
use WP_Query;

class DisablePosts
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
	 */
	public function init()
	{
		if( !$this->app->config->disable_posts )return;

		remove_action( 'welcome_panel', 'wp_welcome_panel' );

		add_action( 'init',               [$this, 'disable'] );
		add_action( 'wp_dashboard_setup', [$this, 'modifyDashboardWidgets'] );
		add_action( 'welcome_panel',      [$this, 'modifyWelcomePanel'] );
		add_action( 'admin_bar_menu',     [$this, 'removeFromAdminBar'], 999 );
		add_action( 'admin_menu',         [$this, 'removeFromAdminMenu'] );
		add_action( 'admin_init',         [$this, 'unregisterDashboardWidgets'] );
		add_action( 'widgets_init',       [$this, 'unregisterWidgets'], 1 );
		add_filter( 'posts_results',      [$this, 'filterPostQuery'] );
		add_filter( 'pre_get_posts',      [$this, 'filterSearchQuery'] );
	}

	/**
	 * @return void
	 * @action init
	 */
	public function disable()
	{
		if( !in_array(( new Helper )->getCurrentScreen()->pagenow, [
			'edit.php', 'edit-tags.php', 'post-new.php',
		]))return;

		if( !filter_input_array( INPUT_GET, [
			'post_type' => FILTER_DEFAULT,
			'taxonomy' => FILTER_DEFAULT,
		])) {
			wp_safe_redirect( get_admin_url(), 301 );
			exit;
		}
	}

	/**
	 * http://localhost/?m=2013     - yearly archives
	 * http://localhost/?m=201303   - monthly archives
	 * http://localhost/?m=20130327 - daily archives
	 * http://localhost/?cat=1      - category archives
	 * http://localhost/?tag=foobar - tag archives
	 * http://localhost/?p=1        - single post
	 *
	 * @param array $posts
	 * @return array
	 * @filter posts_results
	 */
	public function filterPostQuery( $posts = [] )
	{
		global $wp_query;
		return $this->isAdmin() || strpos( $wp_query->request, "wp_posts.post_type = 'post'" ) === false
			? $posts
			: [];
	}

	/**
	 * @return WP_Query
	 * @filter pre_get_posts
	 */
	public function filterSearchQuery( WP_Query $query )
	{
		if( $this->isAdmin() || !$query->is_main_query() || !is_search() ) {
			return $query;
		}
		$post_types = get_post_types( ['exclude_from_search' => false ] );
		unset( $post_types['post'] );
		$query->set( 'post_type', array_values( $post_types ) );
		return $query;
	}

	/**
	 * @return void
	 * @action wp_dashboard_setup
	 */
	public function modifyDashboardWidgets()
	{
		if( !is_blog_admin() || !current_user_can( 'edit_posts' ))return;

		global $wp_meta_boxes;
		$widgets = &$wp_meta_boxes['dashboard']['normal']['core'];
		if( !isset( $widgets['dashboard_right_now']['callback'] ))return;
		$widgets['dashboard_right_now']['callback'] = function() {
			ob_start();
			wp_dashboard_right_now();
			echo preg_replace( '/<li class="post-count">(.*?)<\/li>/', '', ob_get_clean() );
		};
	}

	/**
	 * @return void
	 * @action welcome_panel
	 */
	public function modifyWelcomePanel()
	{
		ob_start();
		wp_welcome_panel();
		echo preg_replace( '/(<li><a href="(.*?)" class="welcome-icon welcome-write-blog">(.*?)<\/li>)/', '', ob_get_clean() );
	}

	/**
	 * @return void
	 * @action admin_bar_menu
	 */
	public function removeFromAdminBar()
	{
		global $wp_admin_bar;
		$wp_admin_bar->remove_node( 'new-post' );
	}

	/**
	 * @return void
	 * @action admin_menu
	 */
	public function removeFromAdminMenu()
	{
		remove_menu_page( 'edit.php' );
	}

	/**
	 * @return void
	 * @action wp_dashboard_setup
	 */
	public function unregisterDashboardWidgets()
	{
		remove_meta_box( 'dashboard_activity', 'dashboard', 'normal' );
		remove_meta_box( 'dashboard_quick_press', 'dashboard', 'normal' );
	}

	/**
	 * @return void
	 * @action widgets_init
	 */
	public function unregisterWidgets()
	{
		unregister_widget( 'WP_Widget_Archives' );
		unregister_widget( 'WP_Widget_Calendar' );
		unregister_widget( 'WP_Widget_Recent_Posts' );
	}

	/**
	 * @return bool
	 */
	protected function isAdmin()
	{
		return is_admin() || ( new Helper )->getCurrentScreen()->pagenow == 'wp-login.php';
	}
}
