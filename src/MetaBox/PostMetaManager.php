<?php

namespace GeminiLabs\Pollux\MetaBox;

use GeminiLabs\Pollux\Application;

/**
 * PostMeta::get('media');
 * PostMeta::get('media',[
 *    'fallback' => [],
 * ]);
 */
class PostMetaManager
{
	/**
	 * @param string $metaKey
	 * @return mixed
	 */
	public function get( $metaKey, array $args = [] )
	{
		if( empty( $metaKey ))return;

		$args = $this->normalize( $args );
		$metaKey = $this->buildMetaKey( $metaKey, $args['prefix'] );
		$metaValue = get_post_meta( $args['id'], $metaKey, $args['single'] );

		if( is_string( $metaValue )) {
			$metaValue = trim( $metaValue );
		}
		return empty( $metaValue )
			? $args['fallback']
			: $metaValue;
	}

	/**
	 * @param string $metaKey
	 * @param string $prefix
	 * @return string
	 */
	protected function buildMetaKey( $metaKey, $prefix )
	{
		return ( substr( $metaKey, 0, 1 ) == '_' && !empty( $prefix ))
			? sprintf( '_%s%s', rtrim( $prefix, '_' ), $metaKey )
			: $prefix . $metaKey;
	}

	/**
	 * @return array
	 */
	protected function normalize( array $args )
	{
		$defaults = [
			'id'       => get_the_ID(),
			'fallback' => '',
			'single'   => true,
			'prefix'   => Application::prefix(),
		];
		return shortcode_atts( $defaults, array_change_key_case( $args ));
	}
}
