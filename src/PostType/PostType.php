<?php

namespace GeminiLabs\Pollux\PostType;

use GeminiLabs\Pollux\Component;
use GeminiLabs\Pollux\PostType\Columns;

class PostType extends Component
{
	use Columns;

	const CUSTOM_KEYS = [
		'columns', 'menu_name', 'plural', 'single',
	];

	const POST_TYPE_DEFAULTS = [
		'capability_type' => 'post',
		'columns' => [],
		'has_archive' => false,
		'hierarchical' => false,
		'labels' => [],
		'map_meta_cap' => true,
		'menu_icon' => null,
		'menu_name' => '',
		'menu_position' => 20,
		'plural' => '',
		'public' => true,
		'query_var' => true,
		'rewrite' => true,
		'show_in_menu' => true,
		'show_ui' => true,
		'single' => '',
		'supports' => ['title', 'editor', 'thumbnail'],
		'taxonomies' => [],
	];

	/**
	 * @var array
	 */
	public $types = [];

	/**
	 * {@inheritdoc}
	 */
	public function init()
	{
		if( empty( $this->app->config->post_types ))return;

		$this->setColumns();
		$this->normalize();
		$this->initColumns();

		add_action( 'init', [$this, 'register'] );
	}

	/**
	 * @return void
	 * @action init
	 */
	public function register()
	{
		$types = array_diff_key(
			$this->types,
			get_post_types( ['_builtin' => true] )
		);
		array_walk( $types, function( $args, $type ) {
			register_post_type( $type, array_diff_key( $args, array_flip( static::CUSTOM_KEYS )));
		});
	}

	/**
	 * {@inheritdoc}
	 */
	protected function normalize()
	{
		foreach( $this->app->config->post_types as $type => $args ) {
			$this->types[$type] = apply_filters( 'pollux/post_type/args',
				$this->normalizeThis( $args, static::POST_TYPE_DEFAULTS, $type ),
				$type
			);
		}
	}

	/**
	 * @param mixed $labels
	 * @return array
	 */
	protected function normalizeLabels( $labels, array $args )
	{
		return wp_parse_args( $labels, $this->setLabels( $args ));
	}

	/**
	 * @param string $menuname
	 * @return string
	 */
	protected function normalizeMenuName( $menuname, array $args )
	{
		return empty( $menuname )
			? $args['plural']
			: $menuname;
	}

	/**
	 * @return array
	 */
	protected function setLabels( array $args )
	{
		return apply_filters( 'pollux/post_type/labels', [
			'add_new' => __( 'Add New', 'pollux' ),
			'add_new_item' => sprintf( _x( 'Add New %s', 'Add new post', 'pollux' ), $args['single'] ),
			'all_items' => sprintf( _x( 'All %s', 'All posts', 'pollux' ), $args['plural'] ),
			'edit_item' => sprintf( _x( 'Edit %s', 'Edit post', 'pollux' ), $args['single'] ),
			'menu_name' => $this->normalizeMenuName( $args['menu_name'], $args ),
			'name' => $args['plural'],
			'new_item' => sprintf( _x( 'New %s', 'New post', 'pollux' ), $args['single'] ),
			'not_found' => sprintf( _x( 'No %s found', 'No posts found', 'pollux' ), $args['plural'] ),
			'not_found_in_trash' => sprintf( _x( 'No %s found in Trash', 'No posts found in trash', 'pollux' ), $args['plural'] ),
			'search_items' => sprintf( _x( 'Search %s', 'Search posts', 'pollux' ), $args['plural'] ),
			'singular_name' => $args['single'],
			'view_item' => sprintf( _x( 'View %s', 'View post', 'pollux' ), $args['single'] ),
		], $args );
	}
}
