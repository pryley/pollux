<?php

namespace GeminiLabs\Pollux\MetaBox;

use GeminiLabs\Pollux\Settings\Settings;

class SiteMetaManager
{
	/**
	 * @param null|string $group
	 * @param null|string $key
	 * @param mixed $fallback
	 * @return mixed
	 */
	public function get( $group = null, $key = null, $fallback = null )
	{
		$options = $this->getOption();
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
	 * @return array
	 */
	protected function getOption()
	{
		return get_option( Settings::id(), [] );
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
