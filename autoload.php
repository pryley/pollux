<?php

defined( 'WPINC' ) || die;

/**
 * PSR-4 autoloader
 */
spl_autoload_register( function( $class )
{
	$namespaces = [
		'GeminiLabs\\Pollux\\' => __DIR__ . '/src/',
		'GeminiLabs\\Pollux\\Tests\\' => __DIR__ . '/tests/',
		'Symfony\\Component\\Yaml\\' => __DIR__ . '/vendor/symfony/yaml/',
	];
	foreach( $namespaces as $prefix => $base_dir ) {
		$len = strlen( $prefix );
		if( strncmp( $prefix, $class, $len ) !== 0 )continue;
		$file = $base_dir . str_replace( '\\', '/', substr( $class, $len ) ) . '.php';
		if( !file_exists( $file ) )continue;
		require $file;
		break;
	}
});
