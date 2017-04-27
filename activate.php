<?php

defined( 'WPINC' ) || die;

$minPHP = '5.6';
$minWordPress = '4.7';

if( !function_exists( 'pollux_version_check' )) {
	function pollux_version_check() {
		global $wp_version;
		return [
			'php' => version_compare( PHP_VERSION, $minPHP, '<' ),
			'wordpress' => version_compare( $wp_version, $minWordPress, '<' ),
		];
	}
}

if( !function_exists( 'pollux_deactivate_plugin' )) {
	function pollux_deactivate_plugin( $plugin )
	{
		$check = pollux_version_check();

		if( !$check['php'] && !$check['wordpress'] )return;

		$plugin_name = plugin_basename( dirname( __FILE__ ) . '/pollux.php' );

		if( $plugin == $plugin_name ) {
			$paged  = filter_input( INPUT_GET, 'paged' );
			$s      = filter_input( INPUT_GET, 's' );
			$status = filter_input( INPUT_GET, 'plugin_status' );

			wp_safe_redirect( self_admin_url( sprintf( 'plugins.php?plugin_status=%s&paged=%s&s=%s', $status, $paged, $s )));
			die;
		}

		deactivate_plugins( $plugin_name );

		$title = __( 'The Pollux plugin was deactivated.', 'pollux' );
		$msg_1 = '';
		$msg_2 = '';

		if( $check['php'] ) {
			$msg_1 = sprintf( __( 'Sorry, the Pollux plugin requires PHP version %s or greater in order to work properly.', 'pollux' ), $minPHP );
			$msg_2 = sprintf( __( 'Please contact your hosting provider or server administrator to upgrade the version of PHP on your server (your server is running PHP version %s), or try to find an alternative plugin.', 'pollux' ), PHP_VERSION );
		}
		// WordPress check overrides the PHP check
		if( $check['wordpress'] ) {
			$msg_1 = sprintf( __( 'Sorry, this plugin requires WordPress version %s or greater in order to work properly.', 'pollux' ), $minWordPress );
			$msg_2 = sprintf( '<a href="%s">%s</a>', admin_url( 'update-core.php' ), __( 'Update WordPress', 'pollux' ));
		}

		printf( '<div id="message" class="notice notice-error error is-dismissible"><p><strong>%s</strong></p><p>%s</p><p>%s</p></div>',
			$title,
			$msg_1,
			$msg_2
		);
	}
}

$check = pollux_version_check();

if( $check['php'] || $check['wordpress'] ) {
	add_action( 'activated_plugin', 'pollux_deactivate_plugin' );
	add_action( 'admin_notices', 'pollux_deactivate_plugin' );
}
