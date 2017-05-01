<?php

namespace GeminiLabs\Pollux;

use GeminiLabs\Pollux\Application;

class Helper
{
	/**
	 * @param string $name
	 * @param string $path
	 * @return string
	 */
	public function buildClassName( $name, $path = '' )
	{
		$className = array_map( 'ucfirst', array_map( 'strtolower', preg_split( '/[-_]/', $name )));
		$className = implode( '', $className );
		return !empty( $path )
			? str_replace( '\\\\', '\\', sprintf( '%s\%s', $path, $className ))
			: $className;
	}

	/**
	 * @param string $name
	 * @param string $prefix
	 * @return string
	 */
	public function buildMethodName( $name, $prefix = 'get' )
	{
		return lcfirst( $this->buildClassName( $prefix . '-' . $name ));
	}

	/**
	 * @param string $needle
	 * @param string $haystack
	 * @return bool
	 */
	public function endsWith( $needle, $haystack )
	{
		$length = strlen( $needle );
		return $length != 0
			? substr( $haystack, -$length ) === $needle
			: true;
	}

	/**
	 * @param mixed $fromClass
	 * @return string
	 */
	public function getClassname( $fromClass )
	{
		$paths = explode( '\\', get_class( $fromClass ));
		return end( $paths );
	}

	/**
	 * get_current_screen() is unreliable because it is defined on most admin pages, but not all.
	 * @return WP_Screen|null
	 */
	public function getCurrentScreen()
	{
		global $current_screen;
		return isset( $current_screen ) ? $current_screen : (object) [
			'base' => '',
			'id' => '',
		];
	}

	/**
	 * @param string $needle
	 * @param string $haystack
	 * @return bool
	 */
	public function startsWith( $needle, $haystack )
	{
		return substr( $haystack, 0, strlen( $needle )) === $needle;
	}

	/**
	 * @param mixed $value
	 * @return array
	 */
	public function toArray( $value )
	{
		return array_filter( (array) $value );
	}
}
