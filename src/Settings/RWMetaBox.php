<?php

namespace GeminiLabs\Pollux\Settings;

use GeminiLabs\Pollux\Helper;
use RW_Meta_Box;
use RWMB_Field;

class RWMetaBox extends RW_Meta_Box
{
	protected $pollux_caller;
	protected $pollux_id;

	public function __construct( $metabox, $id = null, $caller )
	{
		parent::__construct( $metabox );
		$this->meta_box = static::normalize( $this->meta_box );

		$this->pollux_caller = $caller;
		$this->pollux_id = $id;

		remove_action( 'add_meta_boxes', [$this, 'add_meta_boxes'] );
		remove_action( 'save_post_post', [$this, 'save_post'] );

		add_action( 'pollux/archives/init', [$this, 'add_meta_boxes'] );
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
			? $this->pollux_caller->getMetaValue( $field['slug'], $meta, $this->meta_box['slug'] )
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
	 * @action pollux/archives/init
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
		return get_current_screen()->id == $this->pollux_caller->hook;
	}

	/**
	 * @return bool
	 */
	public function is_saved()
	{
		foreach( array_column( $this->fields, 'slug' ) as $field ) {
			if( !is_null( $this->pollux_caller->getMetaValue( $field, null, $this->meta_box['slug'] ))) {
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
