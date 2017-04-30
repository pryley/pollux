<?php

namespace GeminiLabs\Pollux\MetaBox;

use GeminiLabs\Pollux\Application;
use GeminiLabs\Pollux\Component;
use GeminiLabs\Pollux\Helper;
use GeminiLabs\Pollux\MetaBox\Instruction;
use GeminiLabs\Pollux\MetaBox\Validator;
use RecursiveArrayIterator;
use RecursiveIteratorIterator;

trait Instruction
{
	/**
	 * @var array
	 */
	public $metaboxes = [];

	/**
	 * @return void
	 */
	protected function addInstructions()
	{
		if( !count( array_filter( $this->metaboxes, function( $metabox ) {
			return $this->show( false, $metabox );
		})))return;
		$this->metaboxes[] = [
			'id' => 'infodiv',
			'post_types' => $this->getPostTypes(),
			'title' => __( 'How to use in your theme', 'pollux' ),
			'context' => 'side',
			'priority' => 'low',
			'fields' => [[
				'slug' => '',
				'std' => $this->generateInstructions(),
				'type' => 'custom_html',
			]],
		];
	}

	/**
	 * @return string
	 */
	protected function generateInstructions()
	{
		return array_reduce( $this->getInstructions(), function( $html, $metabox ) {
			$fields = array_reduce( array_column( $metabox['fields'], 'slug' ), function( $html, $slug ) use( $metabox ) {
				$hook = sprintf( 'pollux/%s/instruction', strtolower(( new Helper )->getClassname( $this )));
				error_log( print_r( $hook, 1 ));
				return $html . apply_filters( $hook, "PostMeta::get('{$slug}');", $slug, $metabox['slug'] ) . PHP_EOL;
			});
			return $html . sprintf( '<p><strong>%s</strong></p><pre class="my-sites nav-tab-active misc-pub-section">%s</pre>',
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
		return array_filter( $this->metaboxes, function( $metabox ) {
			return $this->validate( $metabox['condition'] )
				&& $this->hasPostType( $metabox );
		});
	}

	/**
	 * @return bool
	 * @filter rwmb_show
	 */
	abstract public function show( $bool, array $metabox );

	/**
	 * @return bool
	 */
	abstract public function validate( array $conditions );

	/**
	 * @return array
	 */
	abstract protected function getPostTypes();

	/**
	 * @return bool
	 */
	abstract protected function hasPostType( array $metabox );
}
