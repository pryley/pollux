<?php

namespace GeminiLabs\Pollux;

use GeminiLabs\Pollux\Application;
use GeminiLabs\Pollux\Helper;

abstract class Component
{
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
	 * @return void
	 */
	abstract protected function normalize();

	/**
	 * @param string $view
	 * @return void
	 */
	public function render( $view, array $data = [] )
	{
		$file = apply_filters( 'pollux/views/file',
			$this->app->path( sprintf( 'views/%s.php', str_replace( '.php', '', $view ))),
			$view,
			$data
		);
		if( file_exists( $file )) {
			extract( $data );
			return include $file;
		}
	}

	/**
	 * @param string $id
	 * @return array
	 */
	protected function normalizeThis( array $data, array $defaults, $id )
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
