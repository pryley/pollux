<?php

defined( 'WPINC' ) || die;

class Pollux_Activate
{
	const BASENAME = 'pollux.php';
	const MIN_PHP_VERSION = '5.6.0';
	const MIN_WORDPRESS_VERSION = '4.7.0';

	/**
	 * @var static
	 */
	protected static $instance;

	/**
	 * @return bool
	 */
	public static function isValid()
	{
		return static::isPhpValid() && static::isWpValid();
	}

	/**
	 * @return bool
	 */
	public static function isPhpValid()
	{
		return !version_compare( PHP_VERSION, static::MIN_PHP_VERSION, '<' );
	}

	/**
	 * @return bool
	 */
	public static function isWpValid()
	{
		global $wp_version;
		return !version_compare( $wp_version, static::MIN_WORDPRESS_VERSION, '<' );
	}

	/**
	 * @return bool
	 */
	public static function shouldDeactivate()
	{
		if( empty( static::$instance )) {
			static::$instance = new static;
		}
		if( !static::isValid() ) {
			add_action( 'activated_plugin', array( static::$instance, 'deactivate' ));
			add_action( 'admin_notices', array( static::$instance, 'deactivate' ));
			return true;
		}
		return false;
	}

	/**
	 * @return void
	 */
	public function deactivate( $plugin )
	{
		if( static::isValid() )return;
		$pluginName = plugin_basename( dirname( realpath( __FILE__ )).'/'.static::BASENAME );
		if( $plugin == $pluginName ) {
			$this->redirect(); //exit
		}
		deactivate_plugins( $pluginName );
		$this->printNotice();
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

	/**
	 * @return void
	 */
	protected function printNotice()
	{
		$noticeTemplate = '<div id="message" class="notice notice-error error is-dismissible"><p><strong>%s</strong></p><p>%s</p><p>%s</p></div>';
		$messages = array(
			__( 'The Pollux plugin was deactivated.', 'pollux' ),
			__( 'Sorry, this plugin requires %s or greater in order to work properly.', 'pollux' ),
			__( 'Please contact your hosting provider or server administrator to upgrade the version of PHP on your server (your server is running PHP version %s), or try to find an alternative plugin.', 'pollux' ),
			__( 'PHP version', 'pollux' ).' '.static::MIN_PHP_VERSION,
			__( 'WordPress version', 'pollux' ).' '.static::MIN_WORDPRESS_VERSION,
			__( 'Update WordPress', 'pollux' ),
		);
		if( !static::isPhpValid() ) {
			printf( $noticeTemplate,
				$messages[0],
				sprintf( $messages[1], $messages[3] ),
				sprintf( $messages[2], PHP_VERSION )
			);
		}
		else if( !static::isWpValid() ) {
			printf( $noticeTemplate,
				$messages[0],
				sprintf( $messages[1], $messages[4] ),
				sprintf( '<a href="%s">%s</a>', admin_url( 'update-core.php' ), $messages[5] )
			);
		}
	}
}
