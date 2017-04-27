<?php

namespace GeminiLabs\Pollux;

use GeminiLabs\Pollux\Application;
use RuntimeException;

abstract class Facade
{
	/**
	 * The application instance being facaded.
	 *
	 * @var Application
	 */
	protected static $app;

	/**
	 * The resolved object instances.
	 *
	 * @var array
	 */
	protected static $resolvedInstance;

	/**
	 * Handle dynamic, static calls to the object.
	 *
	 * @param string $method
	 * @param array  $args
	 *
	 * @return mixed
	 * @throws \RuntimeException
	 */
	public static function __callStatic( $method, $args )
	{
		$instance = static::getFacadeRoot();

		if( !$instance ) {
			throw new RuntimeException( 'A facade root has not been set.' );
		}

		return $instance->$method( ...$args );
	}

	/**
	 * Clear all of the resolved instances.
	 *
	 * @return void
	 */
	public static function clearResolvedInstances()
	{
		static::$resolvedInstance = [];
	}

	/**
	 * Get the application instance behind the facade.
	 *
	 * @return Application
	 */
	public static function getFacadeApplication()
	{
		return static::$app;
	}

	/**
	 * Get the root object behind the facade.
	 *
	 * @return mixed
	 */
	public static function getFacadeRoot()
	{
		return static::resolveFacadeInstance( static::getFacadeAccessor() );
	}

	/**
	 * Set the application instance.
	 *
	 * @return void
	 */
	public static function setFacadeApplication( Application $app )
	{
		static::$app = $app;
	}

	/**
	 * Get the registered name of the component.
	 *
	 * @return string
	 *
	 * @throws \RuntimeException
	 */
	protected static function getFacadeAccessor()
	{
		throw new RuntimeException( 'Facade does not implement getFacadeAccessor method.' );
	}

	/**
	 * Resolve the facade root instance from the container.
	 *
	 * @param string|object $name
	 *
	 * @return mixed
	 */
	protected static function resolveFacadeInstance( $name )
	{
		if( is_object( $name )) {
			return $name;
		}

		if( isset( static::$resolvedInstance[$name] )) {
			return static::$resolvedInstance[$name];
		}

		return static::$resolvedInstance[$name] = static::$app->make( $name );
	}
}
