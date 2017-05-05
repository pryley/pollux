<?php

namespace GeminiLabs\Pollux\MetaBox;

use GeminiLabs\Pollux\Settings\Settings;

/**
 * SiteMeta::all();
 * SiteMeta::group();
 * SiteMeta::group('option','fallback');
 * SiteMeta::get('group');
 * SiteMeta::get('group','option','fallback');
 *
 * @property object all
 */
class SiteMetaManager
{
	protected $options;

	public function __construct()
	{
		$this->options = get_option( Settings::id(), [] );
	}

	/**
	 * @param string $group
	 * @return object|array|null
	 */
	public function __call( $group, $args )
	{
		$args = array_pad( $args, 2, null );
		$group = $this->$group;
		if( is_object( $group )) {
			return $group;
		}
		return $this->get( $group, $args[0], $args[1] );
	}

	/**
	 * @param string $group
	 * @return object|array|null
	 */
	public function __get( $group )
	{
		if( $group == 'all' ) {
			return (object) $this->options;
		}
		if( empty( $group )) {
			$group = $this->getDefaultGroup();
		}
		return isset( $this->options[$group] )
			? $this->options[$group]
			: null;
	}

	/**
	 * @param string $group
	 * @param string|null $key
	 * @param mixed $fallback
	 * @return mixed
	 */
	public function get( $group = '', $key = '', $fallback = null )
	{
		if( func_num_args() < 1 ) {
			return $this->all;
		}
		if( is_string( $group )) {
			$group = $this->$group;
		}
		if( !is_array( $group )) {
			return $fallback;
		}
		if( is_null( $key )) {
			return $group;
		}
		return $this->getValue( $group, $key, $fallback );
	}

	/**
	 * @return string
	 */
	protected function getDefaultGroup()
	{
		return '';
	}

	/**
	 * @param string $key
	 * @param mixed $fallback
	 * @return mixed
	 */
	protected function getValue( array $group, $key, $fallback )
	{
		if( !array_key_exists( $key, $group )) {
			return $fallback;
		}
		return empty( $group[$key] ) && !is_null( $fallback )
			? $fallback
			: $group[$key];
	}
}
