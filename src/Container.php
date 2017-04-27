<?php

namespace GeminiLabs\Pollux;

use Closure;
use Exception;
use ReflectionClass;
use ReflectionParameter;

abstract class Container
{
	/**
	 * The current globally available container (if any).
	 *
	 * @var static
	 */
	protected static $instance;

    /**
     * The container's bound services.
     *
     * @var array
     */
	protected $services = [];

    /**
     * The container's bucket items
     *
     * @var array
     */
	protected $bucket = [];

	/**
	 * Set the globally available instance of the container.
	 *
	 * @return static
	 */
	public static function getInstance()
	{
		if( is_null( static::$instance )) {
			static::$instance = new static;
		}

		return static::$instance;
	}

	/**
	 * Bind a service to the container.
	 *
	 * @param string $alias
	 * @param mixed  $concrete
	 *
	 * @return mixed
	 */
	public function bind( $alias, $concrete )
	{
		$this->services[$alias] = $concrete;
	}

	/**
	 * Resolve the given type from the container.
	 * Allow unbound aliases that omit the root namespace
	 * i.e. 'Controller' translates to 'GeminiLabs\Pollux\Controller'
	 *
	 * @param mixed $abstract
	 *
	 * @return mixed
	 */
	public function make( $abstract )
	{
		$service = isset( $this->services[$abstract] )
			? $this->services[$abstract]
			: $this->addNamespace( $abstract );

		if( is_callable( $service )) {
			return call_user_func_array( $service, [$this] );
		}
		if( is_object( $service )) {
			return $service;
		}

		return $this->resolve( $service );
	}

	/**
	 * Register a shared binding in the container.
	 *
	 * @param string               $abstract
	 * @param \Closure|string|null $concrete
	 *
	 * @return void
	 */
	public function singleton( $abstract, $concrete )
	{
		$this->bind( $abstract, $this->make( $concrete ));
	}

	/**
	 * Dynamically access container bucket items.
	 *
	 * @param string $item
	 *
	 * @return mixed
	 */
	public function __get( $item )
	{
		return isset( $this->bucket[$item] )
			? $this->bucket[$item]
			: null;
	}

	/**
	 * Dynamically set container bucket items.
	 *
	 * @param string $item
	 * @param mixed  $value
	 *
	 * @return void
	 */
	public function __set( $item, $value )
	{
		$this->bucket[$item] = $value;
	}

   /**
	 * Register a Provider.
	 *
	 * @return void
	 */
	public function register( $provider )
	{
		$provider->register( $this );
	}

	/**
	 * Prefix the current namespace to the abstract if absent
	 *
	 * @param string $abstract
	 *
	 * @return string
	 */
	protected function addNamespace( $abstract )
	{
		if( strpos( $abstract, __NAMESPACE__ ) === false && !class_exists( $abstract )) {
			$abstract = __NAMESPACE__ . "\\$abstract";
		}

		return $abstract;
	}

	/**
	 * Throw an exception that the concrete is not instantiable.
	 *
	 * @param string $concrete
	 *
	 * @return void
	 * @throws Exception
	 */
	protected function notInstantiable( $concrete )
	{
		$message = "Target [$concrete] is not instantiable.";

		throw new Exception( $message );
	}

	/**
	 * Resolve a class based dependency from the container.
	 *
	 * @param mixed $concrete
	 *
	 * @return mixed
	 * @throws Exception
	 */
	protected function resolve( $concrete )
	{
		if( $concrete instanceof Closure ) {
			return $concrete( $this );
		}

		$reflector = new ReflectionClass( $concrete );

		if( !$reflector->isInstantiable() ) {
			return $this->notInstantiable( $concrete );
		}

		if( is_null(( $constructor = $reflector->getConstructor() ))) {
			return new $concrete;
		}

		return $reflector->newInstanceArgs(
			$this->resolveDependencies( $constructor->getParameters() )
		);
	}

	/**
	 * Resolve a class based dependency from the container.
	 *
	 * @return mixed
	 * @throws Exception
	 */
	protected function resolveClass( ReflectionParameter $parameter )
	{
		try {
			return $this->make( $parameter->getClass()->name );
		}
		catch( Exception $e ) {
			if( $parameter->isOptional() ) {
				return $parameter->getDefaultValue();
			}
			throw $e;
		}
	}

	/**
	 * Resolve all of the dependencies from the ReflectionParameters.
	 *
	 * @return array
	 */
	protected function resolveDependencies( array $dependencies )
	{
		$results = [];

		foreach( $dependencies as $dependency ) {
			// If the class is null, the dependency is a string or some other primitive type
			$results[] = !is_null( $class = $dependency->getClass() )
				? $this->resolveClass( $dependency )
				: null;
		}

		return $results;
	}
}
