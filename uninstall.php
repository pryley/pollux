<?php

defined( 'WP_UNINSTALL_PLUGIN' ) || die;

require_once __DIR__ . '/autoload.php';

$options = [
	\GeminiLabs\Pollux\Config\Config::id(),
	\GeminiLabs\Pollux\PostType\Archive::id(),
	\GeminiLabs\Pollux\Settings\Settings::id(),
];

foreach( $options as $option ) {
	delete_option( $option );
}
