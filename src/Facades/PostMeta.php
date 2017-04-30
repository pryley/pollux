<?php

namespace GeminiLabs\Pollux\Facades;

use GeminiLabs\Pollux\Facade;

class PostMeta extends Facade
{
	/**
	 * Get the fully qualified class name of the component.
	 *
	 * @return string
	 */
	protected static function getFacadeAccessor()
	{
		return \GeminiLabs\Pollux\MetaBox\PostMetaManager::class;
	}
}
