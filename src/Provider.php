<?php

namespace GeminiLabs\Pollux;

use GeminiLabs\Pollux\Application;
use GeminiLabs\Pollux\Controllers\MainController;
use GeminiLabs\Pollux\Controllers\MetaBoxController;
use GeminiLabs\Pollux\Controllers\PostTypeController;
use GeminiLabs\Pollux\Controllers\TaxonomyController;

class Provider
{
	public function register( Application $app )
	{
		$app->bind( Application::class, $app );
		$app->singleton( Controller::class, Controller::class );
	}
}
