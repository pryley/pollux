<?php

namespace GeminiLabs\Pollux\MetaBox;

use GeminiLabs\Pollux\Application;
use GeminiLabs\Pollux\Component;
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
	 * @var array
	 */
	public $metaboxes = [];

	/**
	 * {@inheritdoc}
	 */
	public function init()
	{
		$this->normalize();

		add_filter( 'rwmb_show',       [$this, 'show'], 10, 2 );
		add_filter( 'rwmb_meta_boxes', [$this, 'register'] );
	}

	/**
	 * @return array
	 * @filter rwmb_meta_boxes
	 */
	public function register()
	{
		if( current_user_can( 'switch_themes' )) {
			$this->addInstructions();
		}
		$metaboxes = func_num_args()
			? ( new Helper )->toArray( func_get_arg(0) )
			: [];
		return array_merge( $metaboxes, $this->metaboxes );
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
		return in_array( get_post_type( $this->getPostId() ), $metabox['post_types'] );
	}

	/**
	 * {@inheritdoc}
	 */
	protected function normalize()
	{
		foreach( $this->app->config['meta_boxes'] as $id => $metabox ) {
			$defaults = [
				'condition' => [],
				'fields' => [],
				'id' => $id,
				'post_types' => [],
				'slug' => $id,
			];
			$this->metaboxes[] = $this->setDependencies(
				$this->normalizeThis( $metabox, $defaults, $id )
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
	 * @return array
	 */
	protected function normalizeFields( array $fields, array $data, $parentId )
	{
		return array_map( function( $id, $field ) use( $parentId ) {
			$defaults =  [
				'attributes' => [],
				// 'condition' => [],
				'depends' => '',
				'id' => $id,
				'field_name' => '',
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
		return apply_filters( 'pollux/prefix', Application::PREFIX ) . $id;
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
	protected function setDependencies( array $metabox )
	{
		$fields = &$metabox['fields'];
		$depends = array_column( $fields, 'depends' );
		array_walk( $depends, function( $value, $index ) use( &$fields ) {
			if( empty( $value ))return;
			$fields[$index]['attributes']['data-depends'] = $value;
		});
		return $metabox;
	}
}
