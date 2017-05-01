<?php

namespace GeminiLabs\Pollux\PostType;

use GeminiLabs\Pollux\Application;
use GeminiLabs\Pollux\Facades\ArchiveMeta;
use GeminiLabs\Pollux\Helper;
use GeminiLabs\Pollux\Settings\Settings;

class Archive extends Settings
{
	/**
	 * @var string
	 */
	CONST ID = 'archives';

	/**
	 * {@inheritdoc}
	 */
	public function init()
	{
		parent::init();

		add_action( 'pollux/archives/editor',              [$this, 'renderEditor'] );
		add_filter( 'pollux/archives/metabox/submit',      [$this, 'filterSubmitMetaBox'] );
		add_filter( 'pollux/archives/before/instructions', [$this, 'generateArchiveInstructions'] );
		add_filter( 'pollux/archives/show/instructions',   '__return_true' );
	}

	/**
	 * @return void
	 * @action admin_menu
	 */
	public function addPage()
	{
		// post => edit.php
		// page|cpt => edit.php?post_type=%s
		$this->hook = call_user_func_array( 'add_submenu_page', $this->filter( 'page', [
			'edit.php',
			sprintf( __( '%s Archive', 'pollux' ), 'Post' ),
			__( 'Archive', 'pollux' ),
			'edit_theme_options',
			static::id(),
			[$this, 'renderPage'],
		]));
	}

	/**
	 * @param string $instruction
	 * @param string $fieldId
	 * @param string $metaboxId
	 * @return string
	 * @action pollux/{static::ID}/instruction
	 */
	public function filterInstruction( $instruction, $fieldId, $metaboxId )
	{
		return sprintf( "ArchiveMeta::get('%s');", $fieldId );
	}

	/**
	 * @return array
	 * @action pollux/{static::ID}/metabox/submit
	 */
	public function filterSubmitMetaBox( array $args )
	{
		$args[1] = __( 'Save Archive', 'pollux' );
		return $args;
	}

	/**
	 * @return string
	 * @filter pollux/archives/before/instructions
	 */
	public function generateArchiveInstructions()
	{
		return sprintf( '<pre class="my-sites nav-tab-active misc-pub-section">%s</pre>',
			array_reduce( ['title', 'content'], function( $instructions, $id ) {
				return $instructions . $this->filterInstruction( null, $id, null ) . PHP_EOL;
			})
		);
	}

	/**
	 * @return void
	 * @action pollux/archives/editor
	 */
	public function renderEditor( $content )
	{
		wp_editor( $content, 'content', [
			'_content_editor_dfw' => true,
			'drag_drop_upload' => true,
			'tabfocus_elements' => 'content-html, publishing-action',
			'editor_height' => 300,
			'tinymce' => [
				'resize' => false,
				'wp_autoresize_on' => true,
				'add_unload_trigger' => false,
			],
		]);
	}

	/**
	 * @return void
	 * @callback add_menu_page
	 */
	public function renderPage()
	{
		$this->render( 'archive/index', [
			'columns' => get_current_screen()->get_columns(),
			'content' => ArchiveMeta::get( 'content' ),
			'heading' => sprintf( __( '%s Archive', 'pollux' ), 'Post' ),
			'id' => static::id(),
			'title' => ArchiveMeta::get( 'title' ),
		]);
	}

	/**
	 * @return array
	 */
	protected function getDefaults()
	{
		return [];
	}

	/**
	 * @return string|array
	 */
	protected function getValue( $key, $group )
	{
		return ArchiveMeta::get( $key, false );
	}
}
