<?php

namespace GeminiLabs\Pollux;

use GeminiLabs\Pollux\Application;
use GeminiLabs\Pollux\Helper;

abstract class Component
{
	const DEPENDENCY = '';
	const CAPABILITY = '';

	/**
	 * @var Application
	 */
	protected $app;

	public function __construct( Application $app )
	{
		$this->app = $app;
	}

	/**
	 * @return void
	 */
	abstract public function init();

	/**
	 * @return void|array
	 */
	abstract public function register();

	/**
	 * @param string $id
	 * @return array
	 */
	protected function normalizeThis( $data, array $defaults, $id )
	{
		$data = wp_parse_args( $data, $defaults );
		foreach( $defaults as $key => $value ) {
			$method = ( new Helper )->buildMethodName( $key, 'normalize' );
			if( method_exists( $this, $method )) {
				$data[$key] = $this->$method( $data[$key], $data, $id );
			}
		}
		return $data;
	}
}
