<?php

namespace GeminiLabs\Pollux;

class GateKeeper
{
	const MIN_PHP_VERSION = '5.6.0';
	const MIN_WORDPRESS_VERSION = '4.7';

	/**
	 * @var string
	 */
	protected $plugin;

	public function __construct( $plugin )
	{
		if( $this->proceed() )return;

		$this->plugin = $plugin;

		add_action( 'activated_plugin', array( $this, 'deactivate' ));
		add_action( 'admin_notices',    array( $this, 'deactivate' ));
	}

	/**
	 * @return bool
	 */
	public function checkPhpVersion()
	{
		return version_compare( PHP_VERSION, self::MIN_PHP_VERSION, '>=' );
	}

	/**
	 * @return bool
	 */
	public function checkWordPressVersion()
	{
		global $wp_version;
		return version_compare( $wp_version, self::MIN_WORDPRESS_VERSION, '>=' );
	}

	/**
	 * @return void
	 * @action activated_plugin
	 * @action admin_notices
	 */
	public function deactivate( $plugin )
	{
		if( $plugin == $this->plugin ) {
			$this->redirect();
		}
		deactivate_plugins( $this->plugin );
		$this->printNotice();
	}

	/**
	 * @return void
	 */
	public function printNotice()
	{
		$message1 = $this->checkPhpVersion()
			? sprintf( __( 'Sorry, Pollux requires WordPress %s or greater in order to work properly.', 'pollux' ), self::MIN_WORDPRESS_VERSION )
			: sprintf( __( 'Sorry, Pollux requires PHP %s or greater in order to work properly (your server is running PHP %s).', 'pollux' ), self::MIN_PHP_VERSION, PHP_VERSION );

		$message2 = $this->checkPhpVersion()
			? sprintf( '<a href="%s">%s</a>', admin_url( 'update-core.php' ), __( 'Update WordPress', 'pollux' ))
			: __( 'Please contact your hosting provider or server administrator to upgrade the version of PHP running on your server, or find an alternate plugin.', 'pollux' );

		printf( '<div id="message" class="notice notice-error error is-dismissible"><p><strong>%s</strong></p><p>%s %s</p></div>',
			__( 'The Pollux plugin was deactivated.', 'pollux' ),
			$message1,
			$message2
		);
	}

	/**
	 * @return bool
	 */
	public function proceed()
	{
		return $this->checkPhpVersion() && $this->checkWordPressVersion();
	}

	/**
	 * @return void
	 */
	protected function redirect()
	{
		wp_safe_redirect( self_admin_url( sprintf( 'plugins.php?plugin_status=%s&paged=%s&s=%s',
			filter_input( INPUT_GET, 'plugin_status' ),
			filter_input( INPUT_GET, 'paged' ),
			filter_input( INPUT_GET, 's' )
		)));
		exit;
	}
}
