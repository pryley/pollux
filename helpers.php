<?php

/**
 * Global helper function to return post_meta
 *
 * @return mixed
 */
function pollux_get_post_meta( $metaKey, array $args = [] ) {
	return \GeminiLabs\Pollux\Facades\PostMeta::get( $metaKey, $args );
}

/**
 * Global helper function to return site options
 *
 * @return mixed
 */
function pollux_get_option( $group, $key = false, $fallback = '' ) {
	return \GeminiLabs\Pollux\Facades\SiteMeta::get( $group, $key, $fallback );
}
