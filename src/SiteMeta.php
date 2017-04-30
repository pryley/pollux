<?php

namespace GeminiLabs\Pollux;

use GeminiLabs\Pollux\Settings\Settings;

class SiteMeta
{
	/**
	 * @param null|string $group
	 * @param null|string $key
	 * @param mixed $fallback
	 * @return mixed
	 */
	public function get( $group = null, $key = null, $fallback = null )
	{
		$options = get_option( apply_filters( 'pollux/settings/option', Settings::ID ));
		if( empty( $options )) {
			return $fallback;
		}
		if( !is_string( $group )) {
			return $options;
		}
		$group = $this->normalize( $options, $group, $fallback );
		return is_string( $key )
			? $this->normalize( (array) $group, $key, $fallback )
			: $group;
	}

	/**
	 * @param string $key
	 * @param mixed $fallback
	 * @return mixed
	 */
	protected function normalize( array $options, $key, $fallback )
	{
		if( !array_key_exists( $key, $options )) {
			return $fallback;
		}
		$option = $options[$key];
		$option = is_array( $option )
			? array_filter( $option )
			: trim( $option );
		return empty( $option )
			? $fallback
			: $option;
	}
}
