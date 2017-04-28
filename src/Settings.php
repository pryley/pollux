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
		if( !is_plugin_active( 'meta-box/meta-box.php' ))return;

		$this->normalize();

		add_action( 'admin_menu',                             [$this, 'addPage'] );
		add_action( 'pollux/settings/init',                   [$this, 'addSubmitMetaBox'] );
		add_filter( 'pollux/settings/instruction',            [$this, 'filterInstruction'], 10, 3 );
		add_action( 'current_screen',                         [$this, 'register'] );
		add_action( 'admin_footer-toplevel_page_' . self::ID, [$this, 'renderFooterScript'] );

		// add_filter( 'pollux/settings/save',        [$this, 'save'] );
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
			self::ID,
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
	 * @param bool $bool
	 * @return bool
	 */
	public function isVisible( $bool, array $metabox )
	{
		if( defined( 'DOING_AJAX' ) && DOING_AJAX || !isset( $metabox['condition'] )) {
			return $bool;
		}
		return $this->verifyMetaBoxCondition( $metabox['condition'] );
	}

	/**
	 * @return void
	 */
	public function register( $metaboxes = [] )
	{
		if( get_current_screen()->id != $this->hook )return;
		foreach( parent::register() as $metabox ) {
			new SettingsMetaBox( $metabox );
		}

		do_action( 'pollux/settings/init' );
	}

	/**
	 * @return void
	 */
	public function renderFooterScript()
	{
		$this->render( 'settings/script', [
			'confirm' => __( 'Are you sure want to do this?', 'pollux' ),
			'hook' => $this->hook,
			'id' => self::ID,
		]);
	}

	/**
	 * @return void
	 */
	public function renderPage()
	{
		// add_screen_option( 'layout_columns', ['max' => 2, 'default' => 2] );
		$this->render( 'settings/index', [
			'columns' => 2,//get_current_screen()->get_columns(),
			'id' => self::ID,
			'title' => __( 'Site Settings', 'pollux' ),
		]);
	}

	/**
	 * @return void
	 */
	public function renderSubmitMetaBox()
	{
		$query = [
			'_wpnonce' => wp_create_nonce( sprintf( '%s-reset', self::ID )),
			'action' => 'reset_settings',
			'page' => self::ID,
		];
		$this->render( 'settings/submit', [
			'reset' => __( 'Reset Settings', 'pollux' ),
			'reset_url' => esc_url( add_query_arg( $query, admin_url( 'admin.php' ))),
			'submit' => get_submit_button( __( 'Save', 'pollux' ), 'primary', 'submit', false ),
		]);
	}

	/**
	 * @return array
	 */
	protected function getInstructions()
	{
		return array_filter( $this->metaboxes, function( $metabox ) {
			return $this->verifyMetaBoxCondition( $metabox['condition'] );
		});
	}

	/**
	 * @return array
	 */
	protected function getPostTypes()
	{
		return [];
	}

	/**
	 * {@inheritdoc}
	 */
	protected function normalize()
	{
		$this->metaboxes = [];
		foreach( $this->app->config['settings'] as $id => $metabox ) {
			unset( $metabox['post_types'], $metabox['pages'] );
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
		$name = str_replace( sprintf( '%s-%s-', self::ID, $parentId ), '', $data['id'] );
		return sprintf( '%s[%s][%s]', self::ID, $parentId, $name );
	}

	/**
	 * @param string $id
	 * @param string $parentId
	 * @return string
	 */
	protected function normalizeId( $id, array $data, $parentId )
	{
		return $parentId == $id
			? sprintf( '%s-%s', self::ID, $id )
			: sprintf( '%s-%s-%s', self::ID, $parentId, $id );
	}
}
