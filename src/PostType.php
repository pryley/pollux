<?php

namespace GeminiLabs\Pollux;

use GeminiLabs\Pollux\Component;
use GeminiLabs\Pollux\PostMeta;

class PostType extends Component
{
	const CUSTOM_KEYS = [
		'columns', 'menu_name', 'plural', 'single', 'slug',
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
		'menu_position' => 5,
		'plural' => '',
		'public' => true,
		'query_var' => true,
		'rewrite' => true,
		'show_in_menu' => true,
		'show_ui' => true,
		'single' => '',
		'slug' => '',
		'supports' => ['title', 'editor', 'thumbnail'],
		'taxonomies' => [],
	];

	/**
	 * @var array
	 */
	public $columns;

	/**
	 * @var array
	 */
	public $types;

	/**
	 * {@inheritdoc}
	 */
	public function init()
	{
		$this->normalize();

		add_action( 'init', [$this, 'register'] );

		foreach( $this->types as $type => $args ) {
			add_action( "manage_{$type}_posts_custom_column", [$this, 'printColumnValue'], 10, 2 );
			add_filter( "manage_{$type}_posts_columns", function( $columns ) use( $args ) {
				return count( $args['columns'] ) > 1
					? $args['columns']
					: $columns;
			});
		}
	}

	/**
	 * @return void
	 */
	public function register()
	{
		$types = array_diff_key(
			$this->types,
			get_post_types( ['_builtin' => true] )
		);
		array_walk( $types, function( $args, $type ) {
			register_post_type( $type, array_diff_key( $args, array_flip( self::CUSTOM_KEYS )));
		});
	}

	/**
	 * @param string $name
	 * @param int $postId
	 * @return void
	 */
	public function printColumnValue( $name, $postId )
	{
		$method = $this->app->buildMethodName( $name, 'getColumn' );
		echo method_exists( $this, $method )
			? $this->$method( $postId )
			: apply_filters( "pollux/post_type/column/{$name}", '' );
	}

	/**
	 * {@inheritdoc}
	 */
	protected function normalize()
	{
		$this->setColumns();
		foreach( $this->app->config['post_types'] as $type => $args ) {
			$this->types[$type] = apply_filters( 'pollux/post_type/args',
				$this->normalizeThis( $args, self::POST_TYPE_DEFAULTS, $type ),
				$type
			);
		}
	}

	/**
	 * @return array
	 */
	protected function normalizeColumns( array $columns )
	{
		$columns = array_flip( $columns );
		$columns = array_merge( $columns, array_intersect_key( $this->columns, $columns ));
		return ['cb' => '<input type="checkbox">'] + $columns;
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
	 * @param mixed $rewrite
	 * @return mixed
	 */
	protected function normalizeRewrite( $rewrite, array $args )
	{
		if( $rewrite === true ) {
			$slug = empty( $args['slug'] )
				? sanitize_title( $args['plural'] )
				: $args['slug'];
			$rewrite = ['slug' => $slug, 'with_front' => false];
		}
		return $rewrite;
	}

	/**
	 * @param int $postId
	 * @return string
	 */
	protected function getColumnImage( $postId )
	{
		if( has_post_thumbnail( $postId ) ) {
			list( $src, $width, $height ) = wp_get_attachment_image_src( get_post_thumbnail_id( $postId ), [96, 48] );
			$image = sprintf( '<img src="%s" alt="%s" width="%s" height="%s">',
				esc_url( set_url_scheme( $src )),
				esc_attr( get_the_title( $postId )),
				$width,
				$height
			);
		}
		return empty( $image )
			? '&mdash;'
			: $image;
	}

	/**
	 * @return int
	 */
	protected function getColumnMedia()
	{
		return count( (new PostMeta)->get( 'media', [
			'fallback' => [],
			'single' => false,
		]));
	}

	/**
	 * @param int $postId
	 * @return string
	 */
	protected function getColumnSlug( $postId )
	{
		return get_post( $postId )->post_name;
	}

	/**
	 * @return void
	 */
	protected function setColumns()
	{
		$comments = sprintf(
			'<span class="vers comment-grey-bubble" title="%1$s"><span class="screen-reader-text">%1$s</span></span>',
			$this->app->config['columns']['comments']
		);
		$columns = wp_parse_args( $this->app->config['columns'], [
			'comments' => $comments,
		]);
		$this->columns = apply_filters( 'pollux/post_type/columns', $columns );
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
