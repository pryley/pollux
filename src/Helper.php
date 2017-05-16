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
		$className = is_string( $fromClass )
			? $fromClass
			: get_class( $fromClass );
		$paths = explode( '\\', $className );
		return end( $paths );
	}

	/**
	 * get_current_screen() is unreliable because it is not defined on all admin pages.
	 * @return WP_Screen|stdClass
	 */
	public function getCurrentScreen()
	{
		global $hook_suffix, $pagenow;
		if( function_exists( 'get_current_screen' )) {
			$screen = get_current_screen();
		}
		if( empty( $screen )) {
			$screen = new \stdClass();
			$screen->base = $screen->id = $hook_suffix;
		}
		$screen->pagenow = $pagenow;
		return $screen;
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
