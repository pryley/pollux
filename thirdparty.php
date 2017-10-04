<?php

/**
 * Compatibility with Give WP plugin
 */
add_filter( 'give_load_admin_scripts', function( $is_admin_page, $hook ) {
	$needle = sprintf( '_page_%s', filter_input( INPUT_GET, 'page' ));
	return substr( $hook, - strlen( $needle )) !== $needle
		? $is_admin_page
		: true;
}, 10, 2 );
