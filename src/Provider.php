<?php

namespace GeminiLabs\Pollux;

use GeminiLabs\Pollux\Application;
use GeminiLabs\Pollux\Config\ConfigManager;

class Provider
{
	public function register( Application $app )
	{
		$app->bind( Application::class, $app );
		$app->singleton( ConfigManager::class, ConfigManager::class );
	}
}
