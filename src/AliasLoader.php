<?php

namespace GeminiLabs\Pollux;

final class AliasLoader
{
	/**
	 * The singleton instance of the loader.
	 *
	 * @var AliasLoader
	 */
	protected static $instance;

	/**
	 * The array of class aliases.
	 *
	 * @var array
	 */
	protected $aliases;

	/**
	 * Indicates if a loader has been registered.
	 *
	 * @var bool
	 */
	protected $registered = false;

	private function __construct( array $aliases )
	{
		$this->aliases = $aliases;
	}

	private function __clone()
	{}

	/**
	 * Get or create the singleton alias loader instance.
	 *
	 * @return AliasLoader
	 */
	public static function getInstance( array $aliases = [] )
	{
		if( is_null( static::$instance )) {
			return static::$instance = new static( $aliases );
		}

		$aliases = array_merge( static::$instance->aliases, $aliases );

		static::$instance->aliases = $aliases;

		return static::$instance;
	}

	/**
	 * Load a class alias if it is registered.
	 *
	 * @param string $alias
	 *
	 * @return bool|null
	 */
	public function load( $alias )
	{
		if( isset( $this->aliases[$alias] )) {
			return class_alias( $this->aliases[$alias], $alias );
		}
	}

	/**
	 * Register the loader on the auto-loader stack.
	 *
	 * @return void
	 */
	public function register()
	{
		if( !$this->registered ) {
			spl_autoload_register( [$this, 'load'], true, true );
			$this->registered = true;
		}
	}
}
