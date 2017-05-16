<?php

namespace GeminiLabs\Pollux\Tests;

final class Bootstrap
{
	const PLUGIN_FILE = 'pollux.php';

	/**
	 * @var self
	 */
	protected static $instance = null;

	/**
	 * @var string
	 */
	public $plugin_dir;

	/**
	 * @var string
	 */
	public $tests_dir;

	/**
	 * @var string
	 */
	public $wp_tests_dir;

	/**
	 * @return self
	 */
	public static function init()
	{
		if( is_null( static::$instance )) {
			static::$instance = new static();
		}
		return static::$instance;
	}

	public function __construct()
	{
		ini_set( 'display_errors', 'on' );
		error_reporting( E_ALL );

		$this->tests_dir = dirname( __FILE__ );
		$this->plugin_dir = dirname( $this->tests_dir );
		$this->wp_tests_dir = $this->get_tests_dir();

		// load test function so tests_add_filter() is available
		require_once( $this->wp_tests_dir . '/includes/functions.php' );

		tests_add_filter( 'setup_theme',      array( $this, 'install_plugin_environment' ));
		tests_add_filter( 'muplugins_loaded', array( $this, 'load_plugin_environment' ));

		// Finally load the WP testing environment
		require_once( $this->wp_tests_dir.'/includes/bootstrap.php' );
	}

	/**
	 * @return string
	 */
	public function get_tests_dir()
	{
		$path = $_SERVER['HOME'].'/Sites/wordpress/tests/current/';
		if( file_exists( $path )) {
			putenv( "WP_TESTS_DIR=$path" );
		}
		return getenv( 'WP_TESTS_DIR' )
			? getenv( 'WP_TESTS_DIR' )
			: '/tmp/wordpress-tests-lib';
	}

	/**
	 * @return void
	 */
	public function install_plugin_environment()
	{
		define( 'WP_UNINSTALL_PLUGIN', true );
		include( $this->plugin_dir.'/uninstall.php' );
		$GLOBALS['wp_roles'] = new \WP_Roles();
	}

	/**
	 * @return void
	 */
	public function load_plugin_environment()
	{
		require_once( trailingslashit( $this->plugin_dir ).static::PLUGIN_FILE );
	}
}

Bootstrap::init();
