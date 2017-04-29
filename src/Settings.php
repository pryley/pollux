<?php

namespace GeminiLabs\Pollux;

use GeminiLabs\Pollux\Application;
use GeminiLabs\Pollux\MetaBox;
use GeminiLabs\Pollux\SettingsMetaBox;

class Settings extends MetaBox
{
	const CONDITIONS = [
		'hook', 'is_plugin_active', 'is_plugin_inactive',
	];

	/**
	 * @var string
	 */
	CONST ID = 'pollux-settings';

	/**
	 * @var string
	 */
	public $hook;

	/**
	 * {@inheritdoc}
	 */
	public function init()
	{
		// @todo: run GateKeeper to check dependencies and capability (make sure it it run on the correct hook!)
		// if( !is_plugin_active( 'meta-box/meta-box.php' ))return;

		$this->normalize();

		add_action( 'admin_menu',                               [$this, 'addPage'] );
		add_action( 'pollux/settings/init',                     [$this, 'addSubmitMetaBox'] );
		add_filter( 'pollux/settings/instruction',              [$this, 'filterInstruction'], 10, 3 );
		add_filter( 'wp_redirect',                              [$this, 'filterRedirectOnSave'] );
		add_action( 'current_screen',                           [$this, 'register'] );
		add_action( 'admin_menu',                               [$this, 'registerSetting'] );
		add_action( 'pollux/settings/init',                     [$this, 'reset'] );
		add_action( 'admin_footer-toplevel_page_' . static::ID, [$this, 'renderFooterScript'] );
	}

	/**
	 * @return void
	 */
	public function addPage()
	{
		$this->hook = call_user_func_array( 'add_menu_page', apply_filters( 'pollux/settings/page', [
			__( 'Site Settings', 'pollux' ),
			__( 'Site Settings', 'pollux' ),
			'edit_theme_options',
			static::ID,
			[$this, 'renderPage'],
			'dashicons-screenoptions',
			1313
		]));
	}

	/**
	 * @return void
	 */
	public function addSubmitMetaBox()
	{
		call_user_func_array( 'add_meta_box', apply_filters( 'pollux/settings/metabox/submit', [
			'submitdiv',
			__( 'Save Settings', 'pollux' ),
			[ $this, 'renderSubmitMetaBox'],
			$this->hook,
			'side',
			'high',
		]));
	}

	/**
	 * @param string $instruction
	 * @param string $fieldId
	 * @param string $metaboxId
	 * @return string
	 */
	public function filterInstruction( $instruction, $fieldId, $metaboxId )
	{
		return sprintf( "SiteMeta::get('%s', '%s');", $metaboxId, $fieldId );
	}

	/**
	 * @param string $location
	 * @return string
	 */
	public function filterRedirectOnSave( $location )
	{
		if( strpos( $location, 'settings-updated=true' ) === false
			|| strpos( $location, sprintf( 'page=%s', static::ID )) === false ) {
			return $location;
		}
		return add_query_arg([
			'page' => static::ID,
			'settings-updated' => 'true',
		], admin_url( 'admin.php' ));
	}

	/**
	 * @param null|array $settings
	 * @return array
	 */
	public function filterSavedSettings( $settings )
	{
		if( is_null( $settings )) {
			$settings = [];
		}
		return apply_filters( 'pollux/settings/save', $settings );
	}

	/**
	 * @return void
	 */
	public function register( $metaboxes = [] )
	{
		if( $this->app->screen()->id != $this->hook )return;
		foreach( parent::register() as $metabox ) {
			new SettingsMetaBox( $metabox );
		}
		add_screen_option( 'layout_columns', [
			'max' => 2,
			'default' => 2,
		]);
		do_action( 'pollux/settings/init' );
	}

	/**
	 * @return void
	 */
	public function registerSetting()
	{
		register_setting( static::ID, static::ID, [$this, 'filterSavedSettings'] );
	}

	/**
	 * @return void
	 */
	public function renderFooterScript()
	{
		$this->render( 'settings/script', [
			'confirm' => __( 'Are you sure want to do this?', 'pollux' ),
			'hook' => $this->hook,
			'id' => static::ID,
		]);
	}

	/**
	 * @return void
	 */
	public function renderPage()
	{
		$this->render( 'settings/index', [
			'columns' => get_current_screen()->get_columns(),
			'id' => static::ID,
			'title' => __( 'Site Settings', 'pollux' ),
		]);
	}

	/**
	 * @return void
	 */
	public function renderSubmitMetaBox()
	{
		$query = [
			'_wpnonce' => wp_create_nonce( $this->hook ),
			'action' => 'reset',
			'page' => static::ID,
		];
		$this->render( 'settings/submit', [
			'reset' => __( 'Reset Settings', 'pollux' ),
			'reset_url' => esc_url( add_query_arg( $query, admin_url( 'admin.php' ))),
			'submit' => get_submit_button( __( 'Save', 'pollux' ), 'primary', 'submit', false ),
		]);
	}

	/**
	 * @return void
	 */
	public function reset()
	{
		if( filter_input( INPUT_GET, 'page' ) !== static::ID
			|| filter_input( INPUT_GET, 'action' ) !== 'reset'
		)return;
		if( wp_verify_nonce( filter_input( INPUT_GET, '_wpnonce' ), $this->hook )) {
			update_option( static::ID, $this->getDefaults() );
			return add_settings_error( static::ID, 'reset', __( 'Settings reset to defaults.', 'pollux' ), 'updated' );
		}
		add_settings_error( static::ID, 'reset', __( 'Failed to reset settings. Please refresh the page and try again.', 'pollux' ));
	}

	/**
	 * @param string $key
	 * @return array
	 */
	protected function filterArrayByKey( array $array, $key )
	{
		return array_filter( $array, function( $value ) use( $key ) {
			return !empty( $value[$key] );
		});
	}

	/**
	 * @return array
	 */
	protected function getDefaults()
	{
		$metaboxes = $this->filterArrayByKey( $this->metaboxes, 'slug' );

		array_walk( $metaboxes, function( &$metabox ) {
			$fields = array_map( function( $field ) {
				$field = wp_parse_args( $field, ['std' => ''] );
				return [$field['slug'] => $field['std']];
			}, $this->filterArrayByKey( $metabox['fields'], 'slug' ));
			$metabox = [
				$metabox['slug'] => call_user_func_array( 'array_merge', $fields ),
			];
		});
		return call_user_func_array( 'array_merge', $metaboxes );
	}

	/**
	 * {@inheritdoc}
	 */
	protected function normalize()
	{
		foreach( $this->app->config['settings'] as $id => $metabox ) {
			$defaults = [
				'condition' => [],
				'fields' => [],
				'id' => $id,
				'slug' => $id,
			];
			$this->metaboxes[] = $this->normalizeThis( $metabox, $defaults, $id );
		}
	}

	/**
	 * @param string $name
	 * @param string $parentId
	 * @return string
	 */
	protected function normalizeFieldName( $name, array $data, $parentId )
	{
		if( !empty( $name )) {
			return $name;
		}
		$name = str_replace( sprintf( '%s-%s-', static::ID, $parentId ), '', $data['id'] );
		return sprintf( '%s[%s][%s]', static::ID, $parentId, $name );
	}

	/**
	 * @param string $id
	 * @param string $parentId
	 * @return string
	 */
	protected function normalizeId( $id, array $data, $parentId )
	{
		return $parentId == $id
			? sprintf( '%s-%s', static::ID, $id )
			: sprintf( '%s-%s-%s', static::ID, $parentId, $id );
	}
}
