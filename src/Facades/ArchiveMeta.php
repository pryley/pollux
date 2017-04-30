<?php

namespace GeminiLabs\Pollux\Facades;

use GeminiLabs\Pollux\Facade;

class ArchiveMeta extends Facade
{
	/**
	 * Get the fully qualified class name of the component.
	 *
	 * @return string
	 */
	protected static function getFacadeAccessor()
	{
		return \GeminiLabs\Pollux\MetaBox\ArchiveMetaManager::class;
	}
}
