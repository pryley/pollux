<?php

namespace GeminiLabs\Pollux\MetaBox;

use GeminiLabs\Pollux\MetaBox\SiteMetaManager;
use GeminiLabs\Pollux\PostType\Archive;

/**
 * ArchiveMeta::all();
 * ArchiveMeta::post();
 * ArchiveMeta::post('title','fallback');
 * ArchiveMeta::get('title');
 * ArchiveMeta::get('title','fallback','post');
 */
class ArchiveMetaManager extends SiteMetaManager
{
	public function __construct()
	{
		$this->options = get_option( Archive::id(), [] );
	}

	/**
	 * @param string|null $key
	 * @param mixed $fallback
	 * @param string $group
	 * @return mixed
	 */
	public function get( $key = '', $fallback = null, $group = '' )
	{
		return parent::get( $group, $key, $fallback );
	}

	/**
	 * @return string
	 */
	protected function getDefaultGroup()
	{
		return get_post_type();
	}
}
