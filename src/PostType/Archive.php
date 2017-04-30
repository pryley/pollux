<?php

namespace GeminiLabs\Pollux\PostType;

use GeminiLabs\Pollux\Application;
use GeminiLabs\Pollux\Facades\MetaArchive;
use GeminiLabs\Pollux\Helper;
use GeminiLabs\Pollux\Settings\Settings;

class Archive extends Settings
{
	/**
	 * @var string
	 */
	CONST ID = 'pollux_archives';

	/**
	 * {@inheritdoc}
	 */
	public function init()
	{
		// @todo: run GateKeeper to check dependencies and capability (make sure it it run on the correct hook!)
		// if( !is_plugin_active( 'meta-box/meta-box.php' ))return;

		$this->id = apply_filters( 'pollux/archives/option', static::ID );

		// $this->normalize( $this->app->config['archives'] );

		add_action( 'admin_menu',                             [$this, 'addPage'] );
		add_action( 'pollux/archive/editor',                  [$this, 'renderEditor'] );
		// add_action( 'pollux/settings/init',                   [$this, 'addSubmitMetaBox'] );
		// add_action( 'current_screen',                         [$this, 'register'] );
		// add_action( 'admin_menu',                             [$this, 'registerSetting'] );
		// add_action( 'pollux/settings/init',                   [$this, 'reset'] );
		// add_action( "admin_footer-toplevel_page_{$this->id}", [$this, 'renderFooterScript'] );
		// add_filter( 'pollux/settings/instruction',            [$this, 'filterInstruction'], 10, 3 );
		// add_filter( 'wp_redirect',                            [$this, 'filterRedirectOnSave'] );
	}

	/**
	 * @return void
	 * @action admin_menu
	 */
	public function addPage()
	{
		$this->hook = call_user_func_array( 'add_menu_page', apply_filters( 'pollux/archive/page', [
			__( 'Archive Page', 'pollux' ),
			__( 'Archive Page', 'pollux' ),
			'edit_theme_options',
			$this->id,
			[$this, 'renderPage'],
			'dashicons-screenoptions',
			1310
		]));
	}

	/**
	 * @return void
	 * @callback add_menu_page
	 */
	public function renderPage()
	{
		$this->render( 'archive/index', [
			'columns' => get_current_screen()->get_columns(),
			'id' => $this->id,
			'title' => __( 'Archive Page', 'pollux' ),
		]);
	}

	/**
	 * @return void
	 * @callback pollux/archive/editor
	 */
	public function renderEditor()
	{
		wp_editor( '', 'content', [
			'_content_editor_dfw' => true,
			'drag_drop_upload' => true,
			'tabfocus_elements' => 'content-html, save-post',
			'editor_height' => 300,
			'tinymce' => [
				'resize' => false,
				'wp_autoresize_on' => true,
				'add_unload_trigger' => false,
			],
		]);
	}
}
