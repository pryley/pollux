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
	public $metaboxes;

	/**
	 * @return void
	 */
	protected function addInstructions()
	{
		if( !$this->showInstructions() )return;
		$this->normalize([
			'infodiv' => [
				'context' => 'side',
				'fields' => [[
					'slug' => '',
					'std' => $this->generateInstructions(),
					'type' => 'custom_html',
				]],
				'post_types' => $this->getPostTypes(),
				'priority' => 'low',
				'title' => __( 'How to use in your theme', 'pollux' ),
			],
		]);
	}

	/**
	 * @return string
	 */
	protected function generateInstructions()
	{
		$instructions = array_reduce( $this->getInstructions(), function( $html, $metabox ) {
			$fields = array_reduce( $metabox['fields'], function( $html, $field ) use( $metabox ) {
				return $this->validate( $field['condition'] )
					? $html . $this->filter( 'instruction', "PostMeta::get('{$field['slug']}');", $field, $metabox ) . PHP_EOL
					: $html;
			});
			return $html . sprintf( '<p><strong>%s</strong></p><pre class="my-sites nav-tab-active misc-pub-section">%s</pre>',
				$metabox['title'],
				$fields
			);
		});
		return $this->filter( 'before/instructions', '' ) . $instructions . $this->filter( 'after/instructions', '' );
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
	 */
	protected function showInstructions()
	{
		return $this->filter( 'show/instructions', count( array_filter( $this->metaboxes, function( $metabox ) {
			return $this->show( false, $metabox );
		})) > 0 );
	}

	/**
	 * @param string $name
	 * @param mixed ...$args
	 * @return mixed
	 */
	abstract public function filter( $name, ...$args );

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

	/**
	 * @return void
	 */
	abstract protected function normalize( array $metaboxes, array $defaults = [] );
}
