<?php

namespace GeminiLabs\Pollux;

use GeminiLabs\Pollux\Application;
use GeminiLabs\Pollux\Component;
use RecursiveArrayIterator;
use RecursiveIteratorIterator;

class MetaBox extends Component
{
	const CONDITIONS = [
		'hook', 'is_front_page', 'is_home', 'is_page_template', 'is_plugin_active',
		'is_plugin_inactive',
	];

	/**
	 * @var array
	 */
	public $metaboxes;

	/**
	 * {@inheritdoc}
	 */
	public function init()
	{
		$this->normalize();

		add_filter( 'rwmb_show',       [$this, 'isVisible'], 10, 2 );
		add_action( 'rwmb_meta_boxes', [$this, 'register'] );
	}

	/**
	 * @return array
	 */
	public function register( array $metaboxes )
	{
		if( current_user_can( 'switch_themes' )) {
			$this->addInstructions();
		}
		return array_merge( $metaboxes, $this->metaboxes );
	}

	/**
	 * @return bool
	 */
	public function isVisible( $bool, array $metabox )
	{
		if( defined( 'DOING_AJAX' ) && DOING_AJAX
			|| !isset( $metabox['condition'] )
			|| !in_array( $this->getPostType(), $metabox['post_types'] )) {
			return $bool;
		}
		return $this->verifyMetaBoxCondition( $metabox['condition'] );
	}

	/**
	 * @return bool
	 */
	public function verifyMetaBoxCondition( array $conditions )
	{
		array_walk( $conditions, function( &$value, $key ) {
			$method = $this->app->buildMethodName( $key, 'validate' );
			$value = method_exists( $this, $method )
				? $this->$method( $value )
				: $this->validateUnknown( $key, $value );
		});
		return !in_array( false, $conditions );
	}

	protected function addInstructions()
	{
		if( !count( array_filter( $this->metaboxes, function( $metabox ) {
			return $this->isVisible( false, $metabox );
		})))return;
		$this->metaboxes[] = [
			'id' => 'infodiv',
			'post_types' => $this->getPostTypes(),
			'title' => __( 'How to use in your theme', 'pollux' ),
			'context' => 'side',
			'priority' => 'low',
			'fields' => [[
				'type' => 'custom_html',
				'std' => $this->generateInstructions(),
			]],
		];
	}

	/**
	 * @return string
	 */
	protected function generateInstructions()
	{
		return array_reduce( $this->getInstructions(), function( $html, $metabox ) {
			$fields = array_reduce( array_column( $metabox['fields'], 'id' ), function( $html, $id ) {
				$id = str_replace( Application::PREFIX, '', $id );
				return $html . sprintf( "PostMeta::get('%s');%s", $id, PHP_EOL );
			});
			return $html . sprintf( '<p><strong>%s</strong></p><pre class="nav-tab-active misc-pub-section">%s</pre>',
				$metabox['title'],
				$fields
			);
		});
	}

	/**
	 * @return array
	 */
	protected function getInstructions()
	{
		$type = get_post_type( $this->getPostId() );
		return array_filter( $this->metaboxes, function( $metabox ) use( $type ) {
			return $this->verifyMetaBoxCondition( $metabox['condition'] )
				&& in_array( $type, $metabox['post_types'] );
		});
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
	 * @return string
	 */
	protected function getPostType()
	{
		return get_post_type( $this->getPostId() );
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
	 * {@inheritdoc}
	 */
	protected function normalize()
	{
		$this->metaboxes = [];
		foreach( $this->app->config['meta_boxes'] as $id => $metabox ) {
			$this->metaboxes[] = $this->normalizeThis( $metabox, [
				'condition' => [],
				'fields' => [],
				'id' => $id,
				'post_types' => [],
			]);
		}
	}

	/**
	 * @param mixed $condition
	 * @return array
	 */
	protected function normalizeCondition( $conditions )
	{
		$conditions = $this->toArray( $conditions );
		if( count( array_filter( array_keys( $conditions ), 'is_string' )) == 0 ) {
			foreach( $conditions as $key ) {
				$conditions[str_replace( '!', '', $key )] = substr( $key, 0, 1 ) == '!' ? 0 : 1;
			}
			$conditions = array_filter( $conditions, function( $key ) {
				return !is_numeric( $key );
			}, ARRAY_FILTER_USE_KEY );
		}
		return array_intersect_key(
			$conditions,
			array_flip( apply_filters( 'pollux/metabox/conditions', self::CONDITIONS ))
		);
	}

	/**
	 * @return array
	 */
	protected function normalizeFields( array $fields )
	{
		return array_map( function( $id, $field ) {
			return $this->normalizeThis( $field, [
				'id' => Application::PREFIX . $id,
				'condition' => [],
			]);
		}, array_keys( $fields ), $fields );
	}

	/**
	 * @param mixed $types
	 * @return array
	 */
	protected function normalizePostTypes( $types )
	{
		return $this->toArray( $types );
	}

	/**
	 * @param string $value
	 * @return bool
	 */
	protected function validateHook( $value )
	{
		return apply_filters( $value, true );
	}

	/**
	 * @param bool $value
	 * @return bool
	 */
	protected function validateIsFrontPage( $value )
	{
		return $value == ( $this->getPostId() == get_option( 'page_on_front' ));
	}

	/**
	 * @param bool $value
	 * @return bool
	 */
	protected function validateIsHome( $value )
	{
		return $value == ( $this->getPostId() == get_option( 'page_for_posts' ));
	}

	/**
	 * @param string $value
	 * @return bool
	 */
	protected function validateIsPageTemplate( $value )
	{
		return basename( get_page_template_slug( $this->getPostId() )) == $value;
	}

	/**
	 * @param string $value
	 * @return bool
	 */
	protected function validateIsPluginActive( $value )
	{
		return is_plugin_active( $value );
	}

	/**
	 * @param string $value
	 * @return bool
	 */
	protected function validateIsPluginInactive( $value )
	{
		return is_plugin_inactive( $value );
	}

	/**
	 * @param string $key
	 * @param mixed $value
	 * @return bool
	 */
	protected function validateUnknown( $key, $value )
	{
		return apply_filters( 'pollux/metabox/condition', true, $key, $value );
	}
}
