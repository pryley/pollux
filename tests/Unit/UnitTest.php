<?php

namespace GeminiLabs\Pollux\Tests\Unit;

use WP_UnitTestCase;

class UnitTest extends WP_UnitTestCase
{
	public function setUp()
	{
		parent::setUp();

		$_SERVER['SERVER_PROTOCOL'] = 'HTTP/1.1';
		$_SERVER['SERVER_NAME'] = '';
		$PHP_SELF = $GLOBALS['PHP_SELF'] = $_SERVER['PHP_SELF'] = '/index.php';

		$this->app = pollux_app();
		$this->app->onActivation();

		// save initial plugin settings here if needed
	}
}
