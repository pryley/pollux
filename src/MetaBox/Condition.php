<?php

namespace GeminiLabs\Pollux\MetaBox;

use GeminiLabs\Pollux\Helper;
use WP_Post;

/**
 * @property Application $app
 */
trait Condition
{
	/**
	 * @return array
	 */
	public static function absolutes()
	{
		$defaults = [
			'class_exists', 'defined', 'function_exists', 'hook', 'is_front_page', 'is_home',
			'is_plugin_active', 'is_plugin_inactive',
		];
		return defined( 'static::ABSOLUTE_CONDITIONS' )
			? Helper::toArray( static::ABSOLUTE_CONDITIONS )
			: $defaults;
	}

	/**
	 * @return array
	 */
	public static function conditions()
	{
		$defaults = [
			'class_exists', 'defined', 'function_exists', 'hook', 'is_front_page', 'is_home',
			'is_page_template', 'is_plugin_active', 'is_plugin_inactive',
		];
		return defined( 'static::CONDITIONS' )
			? Helper::toArray( static::CONDITIONS )
			: $defaults;
	}

	/**
	 * @param string $name
	 * @param mixed ...$args
	 * @return mixed
	 */
	abstract public function filter( $name, ...$args );

	/**
	 * @return bool
	 */
	public function validate( array $conditions )
	{
		array_walk( $conditions, function( &$value, $key ) {
			$value = $this->isConditionValid( $key, $value );
		});
		return !in_array( false, $conditions );
	}

	/**
	 * @return int
	 */
	abstract protected function getPostId();

	/**
	 * @param string $method
	 * @return bool
	 */
	protected function isAbsoluteConditionValid( $method, array $values )
	{
		foreach( $values as $value ) {
			if( $this->$method( $value ))continue;
			return false;
		}
		return true;
	}

	/**
	 * @param string $key
	 * @param string|array $values
	 * @return bool
	 */
	protected function isConditionValid( $key, $values )
	{
		$method = Helper::buildMethodName( $key, 'validate' );
		if( !method_exists( $this, $method )) {
			return $this->validateUnknown( $key, $values );
		}
		$values = Helper::toArray( $values );
		return in_array( $key, $this->absolutes() )
			? $this->isAbsoluteConditionValid( $method, $values )
			: $this->isLooseConditionValid( $method, $values );
	}

	/**
	 * @param string $method
	 * @return bool
	 */
	protected function isLooseConditionValid( $method, array $values )
	{
		foreach( $values as $value ) {
			if( !$this->$method( $value ))continue;
			return true;
		}
		return false;
	}

	/**
	 * @param mixed $conditions
	 * @return array
	 */
	protected function normalizeCondition( $conditions )
	{
		if( !is_array( $conditions )) {
			$conditions = [];
		}
		if( count( array_filter( array_keys( $conditions ), 'is_string' )) == 0 ) {
			foreach( $conditions as $key ) {
				$conditions[str_replace( '!', '', $key )] = substr( $key, 0, 1 ) == '!' ? 0 : 1;
			}
			$conditions = array_filter( $conditions, function( $key ) {
				return !is_numeric( $key );
			}, ARRAY_FILTER_USE_KEY );
		}
		return array_intersect_key(
			$conditions,
			array_flip( $this->filter( 'conditions', static::conditions() ))
		);
	}

	/**
	 * @param string $value
	 * @return bool
	 */
	protected function validateClassExists( $value )
	{
		return class_exists( $value );
	}

	/**
	 * @param string $value
	 * @return bool
	 */
	protected function validateDefined( $value )
	{
		return defined( $value );
	}

	/**
	 * @param string $value
	 * @return bool
	 */
	protected function validateFunctionExists( $value )
	{
		return function_exists( $value );
	}

	/**
	 * @param string $value
	 * @return bool
	 */
	protected function validateHook( $value )
	{
		return apply_filters( $value, true );
	}

	/**
	 * @param bool $value
	 * @return bool
	 */
	protected function validateIsFrontPage( $value )
	{
		return $value == ( $this->getPostId() == get_option( 'page_on_front' ));
	}

	/**
	 * @param bool $value
	 * @return bool
	 */
	protected function validateIsHome( $value )
	{
		return $value == ( $this->getPostId() == get_option( 'page_for_posts' ));
	}

	/**
	 * @param string $value
	 * @return bool
	 */
	protected function validateIsPageTemplate( $value )
	{
		return Helper::endsWith( $value, basename( get_page_template_slug( $this->getPostId() )));
	}

	/**
	 * @param string $value
	 * @return bool
	 */
	protected function validateIsPluginActive( $value )
	{
		return $this->app->gatekeeper->isPluginActive( $value );
	}

	/**
	 * @param string $value
	 * @return bool
	 */
	protected function validateIsPluginInactive( $value )
	{
		return !$this->validateIsPluginActive( $value );
	}

	/**
	 * @param string $key
	 * @param mixed $value
	 * @return bool
	 */
	protected function validateUnknown( $key, $value )
	{
		return apply_filters( 'pollux/metabox/condition', true, $key, $value );
	}
}
