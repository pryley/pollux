<?php

namespace GeminiLabs\Pollux\MetaBox;

/**
 * @property array $metaboxes
 */
trait Instruction
{
    /**
     * @return string
     */
    protected function generateInstructions()
    {
        $instructions = array_reduce($this->getInstructionGroups(), function ($html, $metabox) {
            $fields = $this->getInstructionFields($metabox);
            if (empty($fields)) {
                return $html;
            }
            return $html.sprintf('<p><strong>%s</strong></p><pre class="my-sites nav-tab-active misc-pub-section">%s</pre>',
                $metabox['title'],
                $fields
            );
        });
        return $this->filter('before/instructions', '').$instructions.$this->filter('after/instructions', '');
    }

    protected function getInstructionFields($metabox): string
    {
        $skipFields = ['custom_html', 'divider', 'heading', 'taxonomy'];
        return array_reduce($metabox['fields'], function ($carry, $field) use ($metabox, $skipFields) {
            return $this->validate($field['condition']) && !in_array($field['type'], $skipFields)
                ? $carry.$this->filter('instruction', "PostMeta::get('{$field['slug']}');", $field, $metabox).PHP_EOL
                : $carry;
        }, '');
    }

    /**
     * @return array
     */
    protected function getInstructionGroups()
    {
        return array_filter($this->metaboxes, function ($metabox) {
            return $this->validate($metabox['condition'])
                && $this->hasPostType($metabox);
        });
    }

    /**
     * @return array|null
     */
    protected function initInstructions()
    {
        if (!$this->showInstructions()) {
            return null;
        }
        return [
            'infodiv' => [
                'context' => 'side',
                'fields' => [[
                    'slug' => '',
                    'std' => $this->generateInstructions(),
                    'type' => 'custom_html',
                ]],
                'post_types' => $this->getPostTypes(),
                'priority' => 'low',
                'title' => __('How to use in your theme', 'pollux'),
            ],
        ];
    }

    /**
     * @return bool
     */
    protected function showInstructions()
    {
        return $this->filter('show/instructions', count(array_filter($this->metaboxes, function ($metabox) {
            return $this->show(false, $metabox);
        })) > 0);
    }

    /**
     * @param string $name
     * @param mixed  ...$args
     *
     * @return mixed
     */
    abstract public function filter($name, ...$args);

    /**
     * @return bool
     *
     * @filter rwmb_show
     */
    abstract public function show($bool, array $metabox);

    /**
     * @return bool
     */
    abstract public function validate(array $conditions);

    /**
     * @return array
     */
    abstract protected function getPostTypes();

    /**
     * @return bool
     */
    abstract protected function hasPostType(array $metabox);
}
