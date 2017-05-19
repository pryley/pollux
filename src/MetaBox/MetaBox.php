<?php

namespace GeminiLabs\Pollux\MetaBox;

use GeminiLabs\Pollux\Application;
use GeminiLabs\Pollux\Component;
use GeminiLabs\Pollux\Facades\PostMeta;
use GeminiLabs\Pollux\Facades\SiteMeta;
use GeminiLabs\Pollux\Helper;
use GeminiLabs\Pollux\MetaBox\Condition;
use GeminiLabs\Pollux\MetaBox\Instruction;
use RecursiveArrayIterator;
use RecursiveIteratorIterator;

class MetaBox extends Component
{
	use Condition;
	use Instruction;

	/**
	 * @var string
	 */
	const ID = 'metaboxes';

	/**
	 * @var array
	 */
	public $metaboxes = [];

	/**
	 * {@inheritdoc}
	 */
	public function init()
	{
		if( empty( $this->app->config->{static::ID} ))return;

		$this->normalize( $this->app->config->{static::ID}, [
			'post_types' => [],
		]);

		add_filter( 'rwmb_normalize_map_field', [$this, 'normalizeMapField'] );
		add_filter( 'rwmb_show',                [$this, 'show'], 10, 2 );
		add_filter( 'rwmb_meta_boxes',          [$this, 'register'] );
		add_filter( 'rwmb_outer_html',          [$this, 'renderField'], 10, 2 );

	}

	/**
	 * @param string $name
	 * @param mixed ...$args
	 * @return void
	 */
	public function action( $name, ...$args )
	{
		return do_action_ref_array( sprintf( 'pollux/%s/%s', static::ID, $name ), $args );
	}

	/**
	 * @param string $name
	 * @param mixed ...$args
	 * @return mixed
	 */
	public function filter( $name, ...$args )
	{
		return apply_filters_ref_array( sprintf( 'pollux/%s/%s', static::ID, $name ), $args );
	}

	/**
	 * @param string $key
	 * @param mixed $fallback
	 * @param string $group
	 * @return string|array
	 */
	public function getMetaValue( $key, $fallback = '', $group = '' )
	{
		return PostMeta::get( $key, [
			'id' => $this->getPostId(),
		]);
	}

	/**
	 * @param array $field
	 * @return array
	 */
	public function normalizeMapField( $field )
	{
		if( empty( $field['address_field'] )) {
			return $field;
		}
		if( !$this->app->make( Helper::class )->startsWith( Application::PREFIX, $field['address_field'] )) {
			$field['address_field'] = Application::PREFIX . $field['address_field'];
		}
		$apiKey = SiteMeta::services( $field['api_key'] );
		if( !empty( $apiKey ) && is_string( $apiKey )) {
			$field['api_key'] = $apiKey;
		}
		return $field;
	}

	/**
	 * @return array
	 * @filter rwmb_meta_boxes
	 */
	public function register()
	{
		if( current_user_can( 'switch_themes' )) {
			$instructions = $this->initInstructions();
			if( is_array( $instructions )) {
				$this->normalize( $instructions );
			}
		}
		$metaboxes = func_num_args()
			? ( new Helper )->toArray( func_get_arg(0) )
			: [];
		return array_merge( $metaboxes, $this->metaboxes );
	}

	/**
	 * @return string
	 * @filter rwmb_outer_html
	 */
	public function renderField( $html, $field )
	{
		return $this->validate( $field['condition'] )
			? $html
			: '';
	}

	/**
	 * @return bool
	 * @filter rwmb_show
	 */
	public function show( $bool, array $metabox )
	{
		if( defined( 'DOING_AJAX' )
			|| !isset( $metabox['condition'] )
			|| !$this->hasPostType( $metabox )) {
			return $bool;
		}
		return $this->validate( $metabox['condition'] );
	}

	/**
	 * @return int
	 */
	protected function getPostId()
	{
		if( !( $postId = filter_input( INPUT_GET, 'post' ))) {
			$postId = filter_input( INPUT_POST, 'post_ID' );
		}
		return intval( $postId );
	}

	/**
	 * @return array
	 */
	protected function getPostTypes()
	{
		return array_unique( iterator_to_array(
			new RecursiveIteratorIterator(
				new RecursiveArrayIterator( array_column( $this->metaboxes, 'post_types' ))
			),
			false
		));
	}

	/**
	 * @return bool
	 */
	protected function hasPostType( array $metabox )
	{
		if( !isset( $metabox['post_types'] )) {
			return true;
		}
		if( !( $type = filter_input( INPUT_GET, 'post_type' ))) {
			$type = get_post_type( $this->getPostId() );
		}
		return in_array( $type, $metabox['post_types'] );
	}

	/**
	 * @return void
	 */
	protected function normalize( array $metaboxes, array $defaults = [] )
	{
		foreach( $metaboxes as $id => $metabox ) {
			$data = wp_parse_args( $defaults, [
				'condition' => [],
				'fields' => [],
				'id' => $id,
				'slug' => $id,
				'validation' => [],
			]);
			$this->metaboxes[] = $this->setDependencies(
				$this->normalizeThis( $metabox, $data, $id )
			);
		}
	}

	/**
	 * @param string $depends
	 * @param string $parentId
	 * @return string
	 */
	protected function normalizeDepends( $depends, array $data, $parentId )
	{
		return is_string( $depends ) && !empty( $depends )
			? $this->normalizeId( $depends, $data, $parentId )
			: '';
	}

	/**
	 * @param string $name
	 * @param string $parentId
	 * @return string
	 */
	protected function normalizeFieldName( $name, array $data, $parentId )
	{
		return $this->normalizeId( $name, $data, $parentId );
	}

	/**
	 * @return array
	 */
	protected function normalizeFields( array $fields, array $data, $parentId )
	{
		return array_map( function( $id, $field ) use( $parentId ) {
			$defaults =  [
				'attributes' => [],
				'class' => '',
				'condition' => [],
				'depends' => '',
				'field_name' => $id,
				'id' => $id,
				'slug' => $id,
			];
			return $this->normalizeThis( $field, $defaults, $parentId );
		}, array_keys( $fields ), $fields );
	}

	/**
	 * @param string $id
	 * @param string $parentId
	 * @return string
	 */
	protected function normalizeId( $id, array $data, $parentId )
	{
		return Application::prefix() . $id;
	}

	/**
	 * @param mixed $types
	 * @return array
	 */
	protected function normalizePostTypes( $types )
	{
		return ( new Helper )->toArray( $types );
	}

	/**
	 * @return array
	 */
	protected function normalizeValidation( array $validation, array $data, $parentId )
	{
		foreach( ['messages', 'rules'] as $key ) {
			if( empty( $validation[$key] ))continue;
			foreach( $validation[$key] as $id => $value ) {
				$validation[$key][$this->normalizeFieldName( $id, ['slug' => $id], $parentId )] = $value;
				unset( $validation[$key][$id] );
			}
		}
		return $validation;
	}

	/**
	 * @return array
	 */
	protected function setDependencies( array $metabox )
	{
		$fields = &$metabox['fields'];
		$depends = array_column( $fields, 'depends' );
		array_walk( $depends, function( $value, $index ) use( &$fields, $metabox ) {
			if( empty( $value ))return;
			$dependency = array_search( $value, array_column( $fields, 'id' ));
			$fields[$index]['attributes']['data-depends'] = $value;
			if( !$this->getMetaValue( $fields[$dependency]['slug'], '', $metabox['slug'] )) {
				$fields[$index]['class'] = trim( 'hidden ' . $fields[$index]['class'] );
			}
		});
		return $metabox;
	}
}
