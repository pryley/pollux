<?php

namespace GeminiLabs\Pollux\Facades;

use GeminiLabs\Pollux\Facade;

class SiteMeta extends Facade
{
	/**
	 * Get the fully qualified class name of the component.
	 *
	 * @return string
	 */
	protected static function getFacadeAccessor()
	{
		return \GeminiLabs\Pollux\MetaBox\SiteMetaManager::class;
	}
}
