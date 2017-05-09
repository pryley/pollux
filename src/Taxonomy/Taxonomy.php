<?php

namespace GeminiLabs\Pollux\Taxonomy;

use GeminiLabs\Pollux\Component;
use GeminiLabs\Pollux\Helper;
use WP_Query;

class Taxonomy extends Component
{
	const CUSTOM_KEYS = [
		'menu_name', 'plural', 'single',
	];

	const TAXONOMY_DEFAULTS = [
		'hierarchical'      => true,
		'labels'            => [],
		'menu_name'         => '',
		'plural'            => '',
		'post_types'        => [],
		'public'            => true,
		'rewrite'           => true,
		'show_admin_column' => true,
		'show_ui'           => true,
		'single'            => '',
	];

	/**
	 * @var array
	 */
	public $taxonomies = [];

	/**
	 * {@inheritdoc}
	 */
	public function init()
	{
		if( empty( $this->app->config->taxonomies ))return;

		$this->normalize();

		add_action( 'restrict_manage_posts', [ $this, 'printFilters'] );
		add_action( 'init',                  [ $this, 'register'] );
		add_filter( 'parse_query',           [ $this, 'filterByTaxonomy'] );
	}

	/**
	 * @return null|WP_Query
	 * @filter parse_query
	 */
	public function filterByTaxonomy( WP_Query $query )
	{
		if( !is_admin() || ( new Helper )->getCurrentScreen()->base != 'edit' )return;
		$vars = &$query->query_vars;
		foreach( array_keys( $this->taxonomies ) as $taxonomy ) {
			if( !isset( $vars[$taxonomy] ))return;
			if( $term = get_term_by( 'id', $vars[$taxonomy], $taxonomy )) {
				$vars[$taxonomy] = $term->slug;
			}
		}
		return $query;
	}

	/**
	 * @return void
	 * @action restrict_manage_posts
	 */
	public function printFilters()
	{
		global $wp_query;
		foreach( $this->taxonomies as $taxonomy => $args ) {
			if( !in_array( get_current_screen()->post_type, $args['post_types'] ))continue;
			$selected = isset( $wp_query->query[$taxonomy] )
				? $wp_query->query[$taxonomy]
				: false;
			wp_dropdown_categories([
				'hide_if_empty' => true,
				'name' => $taxonomy,
				'orderby' => 'name',
				'selected' => $selected,
				'show_option_all' => $args['labels']['all_items'],
				'taxonomy' => $taxonomy,
			]);
		}
	}

	/**
	 * @return void
	 * @action register
	 */
	public function register()
	{
		array_walk( $this->taxonomies, function( $args, $taxonomy ) {
			register_taxonomy( $taxonomy, $args['post_types'], array_diff_key( $args, array_flip( static::CUSTOM_KEYS )));
			foreach( $args['post_types'] as $type ) {
				register_taxonomy_for_object_type( $taxonomy, $type );
			}
		});
	}

	/**
	 * {@inheritdoc}
	 */
	protected function normalize()
	{
		foreach( $this->app->config->taxonomies as $taxonomy => $args ) {
			$this->taxonomies[$taxonomy] = apply_filters( 'pollux/taxonomy/args',
				$this->normalizeThis( $args, static::TAXONOMY_DEFAULTS, $taxonomy )
			);
		}
		$this->taxonomies = array_diff_key(
			$this->taxonomies,
			get_taxonomies( ['_builtin' => true] )
		);
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
	protected function setLabels( array $args )
	{
		return apply_filters( 'pollux/taxonomy/labels', [
			'add_new_item' => sprintf( _x( 'Add New %s', 'Add new taxonomy', 'pollux' ), $args['single'] ),
			'all_items' => sprintf( _x( 'All %s', 'All taxonomies', 'pollux' ), $args['plural'] ),
			'edit_item' => sprintf( _x( 'Edit %s', 'Edit taxonomy', 'pollux' ), $args['single'] ),
			'menu_name' => $this->normalizeMenuName( $args['menu_name'], $args ),
			'name' => $args['plural'],
			'new_item_name' => sprintf( _x( 'New %s Name', 'New taxonomy name', 'pollux' ), $args['single'] ),
			'not_found' => sprintf( _x( 'No %s found', 'No taxonomies found', 'pollux' ), $args['plural'] ),
			'search_items' => sprintf( _x( 'Search %s', 'Search taxonomies', 'pollux' ), $args['plural'] ),
			'singular_name' => $args['single'],
			'update_item' => sprintf( _x( 'Update %s', 'Update taxonomy', 'pollux' ), $args['single'] ),
			'view_item' => sprintf( _x( 'View %s', 'View taxonomy', 'pollux' ), $args['single'] ),
		], $args );
	}
}
