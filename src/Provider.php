<?php

namespace GeminiLabs\Pollux;

use GeminiLabs\Pollux\Application;

class Provider
{
	public function register( Application $app )
	{
		$app->bind( Application::class, $app );
	}
}
