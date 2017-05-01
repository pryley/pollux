<?php

namespace GeminiLabs\Pollux\Settings;

use GeminiLabs\Pollux\Facades\SiteMeta;
use GeminiLabs\Pollux\Helper;
use GeminiLabs\Pollux\Settings\Settings;
use RW_Meta_Box;
use RWMB_Field;

class RWMetaBox extends RW_Meta_Box
{
	public function __construct( $metabox )
	{
		parent::__construct( $metabox );
		$this->meta_box = static::normalize( $this->meta_box );

		remove_action( 'add_meta_boxes', [$this, 'add_meta_boxes'] );
		remove_action( 'save_post_post', [$this, 'save_post'] );

		add_action( 'pollux/settings/init', [$this, 'add_meta_boxes'] );
		add_filter( 'rwmb_field_meta',      [$this, '_get_field_meta'], 10, 3 );
	}

	/**
	 * @param mixed $meta
	 * @param bool $meta
	 * @return mixed
	 * @filter rwmb_field_meta
	 */
	public function _get_field_meta( $meta, array $field, $saved )
	{
		if( !$this->is_edit_screen() || !empty(( new Helper )->toArray( $meta )) || empty( $field['slug'] )) {
			return $meta;
		}
		$meta = call_user_func( [RWMB_Field::get_class_name( $field ), 'esc_meta'], ( $saved
			? SiteMeta::get( $this->meta_box['slug'], $field['slug'], $meta )
			: $field['std']
		));
		return $this->_normalize_field_meta( $meta, $field );
	}

	/**
	 * @param mixed $meta
	 * @return array
	 */
	public function _normalize_field_meta( $meta, array $field )
	{
		if( !empty( $meta ) && is_array( $meta )) {
			return $meta;
		}
		if( $field['clone'] ) {
			return [''];
		}
		if( $field['multiple'] ) {
			return [];
		}
		return $meta;
	}

	/**
	 * @return void
	 * @action pollux/settings/init
	 */
	public function add_meta_boxes()
	{
		add_meta_box(
			$this->meta_box['id'],
			$this->meta_box['title'],
			[$this, 'show'],
			null,
			$this->meta_box['context'],
			$this->meta_box['priority']
		);
	}

	/**
	 * @return bool
	 */
	public function is_edit_screen( $screen = null )
	{
		return get_current_screen()->id == sprintf( 'toplevel_page_%s', Settings::id() );
	}

	/**
	 * @return bool
	 */
	public function is_saved()
	{
		foreach( array_column( $this->fields, 'slug' ) as $field ) {
			if( !is_null( SiteMeta::get( $this->meta_box['slug'], $field, null ))) {
				return true;
			}
		}
		return false;
	}

	/**
	 * @param array $metabox
	 * @return array
	 */
	public static function normalize( $metabox )
	{
		unset( $metabox['post_types'] );
		return wp_parse_args( $metabox, ['slug' => ''] );
	}
}
