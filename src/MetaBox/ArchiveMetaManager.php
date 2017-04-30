<?php

namespace GeminiLabs\Pollux\MetaBox;

use GeminiLabs\Pollux\MetaBox\SiteMetaManager;
use GeminiLabs\Pollux\PostType\Archive;

class ArchiveMetaManager extends SiteMetaManager
{
	/**
	 * @return array
	 */
	protected function getOption()
	{
		return get_option( apply_filters( 'pollux/archive/option', Archive::ID ), [] );
	}
}
