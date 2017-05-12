<?php

namespace GeminiLabs\Pollux\MetaBox;

use GeminiLabs\Pollux\MetaBox\SiteMetaManager;
use GeminiLabs\Pollux\PostType\Archive;

/**
 * ArchiveMeta::all();
 * ArchiveMeta::group();
 * ArchiveMeta::group('option','fallback');
 * ArchiveMeta::get('group');
 * ArchiveMeta::get('group','option','fallback');
 */
class ArchiveMetaManager extends SiteMetaManager
{
	public function __construct()
	{
		$this->options = get_option( Archive::id(), [] );
	}

	/**
	 * @return string
	 */
	protected function getDefaultGroup()
	{
		return get_post_type();
	}
}
