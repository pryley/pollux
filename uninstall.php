<?php

defined( 'WP_UNINSTALL_PLUGIN' ) || die;

require_once __DIR__.'/pollux.php';
if( !(new GL_Plugin_Check_v3( __FILE__ ))->isValid() )return;

delete_option( GeminiLabs\Pollux\Config\Config::id() );
delete_option( GeminiLabs\Pollux\PostType\Archive::id() );
delete_option( GeminiLabs\Pollux\Settings\Settings::id() );
