<?php

namespace {
    /**
     * A registry for storing all meta boxes.
     *
     * @link https://designpatternsphp.readthedocs.io/en/latest/Structural/Registry/README.html
     */
    class RWMB_Meta_Box_Registry
    {
        /**
         * Create a meta box object.
         *
         * @param array $settings Meta box settings.
         * @return \RW_Meta_Box
         */
        public function make(array $settings)
        {
        }
        public function add(\RW_Meta_Box $meta_box)
        {
        }
        public function get($id)
        {
        }
        /**
         * Get meta boxes under some conditions.
         *
         * @param array $args Custom argument to get meta boxes by.
         */
        public function get_by(array $args) : array
        {
        }
        public function all()
        {
        }
    }
    /**
     * Load plugin's files with check for installing it as a standalone plugin or
     * a module of a theme / plugin. If standalone plugin is already installed, it
     * will take higher priority.
     */
    class RWMB_Loader
    {
        protected function constants()
        {
        }
        /**
         * Get plugin base path and URL.
         * The method is static and can be used in extensions.
         *
         * @link https://deluxeblogtips.com/get-url-of-php-file-in-wordpress/
         * @param string $path Base folder path.
         * @return array Path and URL.
         */
        public static function get_path(string $path = '') : array
        {
        }
        /**
         * Bootstrap the plugin.
         */
        public function init()
        {
        }
    }
    /**
     * Add support for editing attachment custom fields in the media modal.
     */
    class RWMB_Media_Modal
    {
        /**
         * List of custom fields.
         *
         * @var array
         */
        protected $fields = [];
        public function init()
        {
        }
        public function get_fields()
        {
        }
        /**
         * Add fields to the attachment edit popup.
         *
         * @param array   $form_fields An array of attachment form fields.
         * @param WP_Post $post The WP_Post attachment object.
         *
         * @return mixed
         */
        public function add_fields($form_fields, $post)
        {
        }
        /**
         * Save custom fields.
         *
         * @param array $post An array of post data.
         * @param array $attachment An array of attachment metadata.
         *
         * @return array
         */
        public function save_fields($post, $attachment)
        {
        }
    }
    /**
     * Storage interface.
     */
    interface RWMB_Storage_Interface
    {
        /**
         * Get value from storage.
         *
         * @param  int    $object_id Object id.
         * @param  string $name      Field name.
         * @param  array  $args      Custom arguments..
         * @return mixed
         */
        public function get($object_id, $name, $args = []);
    }
    /**
     * Base storage.
     */
    class RWMB_Base_Storage implements \RWMB_Storage_Interface
    {
        /**
         * Object type.
         *
         * @var string
         */
        protected $object_type;
        /**
         * Retrieve metadata for the specified object.
         *
         * @param int        $object_id ID of the object metadata is for.
         * @param string     $meta_key  Optional. Metadata key. If not specified, retrieve all metadata for
         *                              the specified object.
         * @param bool|array $args      Optional, default is false.
         *                              If true, return only the first value of the specified meta_key.
         *                              If is array, use the `single` element.
         *                              This parameter has no effect if meta_key is not specified.
         * @return mixed Single metadata value, or array of values.
         *
         * @see get_metadata()
         */
        public function get($object_id, $meta_key, $args = \false)
        {
        }
        /**
         * Add metadata
         *
         * @param int    $object_id  ID of the object metadata is for.
         * @param string $meta_key   Metadata key.
         * @param mixed  $meta_value Metadata value. Must be serializable if non-scalar.
         * @param bool   $unique     Optional, default is false.
         *                           Whether the specified metadata key should be unique for the object.
         *                           If true, and the object already has a value for the specified metadata key,
         *                           no change will be made.
         * @return int|false The meta ID on success, false on failure.
         *
         * @see add_metadata()
         */
        public function add($object_id, $meta_key, $meta_value, $unique = \false)
        {
        }
        /**
         * Update metadata.
         *
         * @param int    $object_id  ID of the object metadata is for.
         * @param string $meta_key   Metadata key.
         * @param mixed  $meta_value Metadata value. Must be serializable if non-scalar.
         * @param mixed  $prev_value Optional. If specified, only update existing metadata entries with
         *                           the specified value. Otherwise, update all entries.
         * @return int|bool Meta ID if the key didn't exist, true on successful update, false on failure.
         *
         * @see update_metadata()
         */
        public function update($object_id, $meta_key, $meta_value, $prev_value = '')
        {
        }
        /**
         * Delete metadata.
         *
         * @param int    $object_id  ID of the object metadata is for.
         * @param string $meta_key   Metadata key.
         * @param mixed  $meta_value Optional. Metadata value. Must be serializable if non-scalar. If specified, only delete
         *                           metadata entries with this value. Otherwise, delete all entries with the specified meta_key.
         *                           Pass `null, `false`, or an empty string to skip this check. (For backward compatibility,
         *                           it is not possible to pass an empty string to delete those entries with an empty string
         *                           for a value).
         * @param bool   $delete_all Optional, default is false. If true, delete matching metadata entries for all objects,
         *                           ignoring the specified object_id. Otherwise, only delete matching metadata entries for
         *                           the specified object_id.
         * @return bool True on successful delete, false on failure.
         *
         * @see delete_metadata()
         */
        public function delete($object_id, $meta_key, $meta_value = '', $delete_all = \false)
        {
        }
    }
    /**
     * Post storage
     *
     * @package Meta Box
     */
    /**
     * Class RWMB_Post_Storage
     */
    class RWMB_Post_Storage extends \RWMB_Base_Storage
    {
        /**
         * Object type.
         *
         * @var string
         */
        protected $object_type = 'post';
    }
    class RWMB_Shortcode
    {
        public function init()
        {
        }
        public function register_shortcode($atts)
        {
        }
    }
    /**
     * The WPML compatibility module, allowing all fields are translatable by WPML plugin.
     */
    class RWMB_WPML
    {
        /**
         * List of fields that need to translate values (because they're saved as IDs).
         *
         * @var array
         */
        protected $field_types = ['post', 'taxonomy_advanced'];
        public function init()
        {
        }
        public function register_hooks()
        {
        }
        /**
         * Translating IDs stored as field values upon WPML post/page duplication.
         *
         * @param mixed  $value           Meta value.
         * @param string $target_language Target language.
         * @param array  $meta_data       Meta arguments.
         * @return mixed
         */
        public function translate_ids($value, $target_language, $meta_data)
        {
        }
        /**
         * Modified field depends on its translation status.
         * If the post is a translated version of another post and the field is set to:
         * - Do not translate: hide the field.
         * - Copy: make it disabled so users cannot edit.
         * - Translate: do nothing.
         *
         * @param array $field Field parameters.
         *
         * @return mixed
         */
        public function modify_field($field)
        {
        }
    }
    /**
     * The field base class.
     * This is the parent class of all custom fields defined by the plugin, which defines all the common methods.
     * Fields must inherit this class and overwrite methods with its own.
     */
    abstract class RWMB_Field
    {
        public static function add_actions()
        {
        }
        public static function admin_enqueue_scripts()
        {
        }
        /**
         * Show field HTML
         * Filters are put inside this method, not inside methods such as "meta", "html", "begin_html", etc.
         * That ensures the returned value are always been applied filters.
         * This method is not meant to be overwritten in specific fields.
         *
         * @param array $field   Field parameters.
         * @param bool  $saved   Whether the meta box is saved at least once.
         * @param int   $post_id Post ID.
         */
        public static function show(array $field, bool $saved, $post_id = 0)
        {
        }
        /**
         * Get field HTML.
         *
         * @param mixed $meta  Meta value.
         * @param array $field Field parameters.
         *
         * @return string
         */
        public static function html($meta, $field)
        {
        }
        protected static function begin_html(array $field) : string
        {
        }
        protected static function end_html(array $field) : string
        {
        }
        protected static function label_description(array $field) : string
        {
        }
        protected static function input_description(array $field) : string
        {
        }
        /**
         * Get raw meta value.
         *
         * @param int   $object_id Object ID.
         * @param array $field     Field parameters.
         * @param array $args      Arguments of {@see rwmb_meta()} helper.
         *
         * @return mixed
         */
        public static function raw_meta($object_id, $field, $args = [])
        {
        }
        /**
         * Get meta value.
         *
         * @param int   $post_id Post ID.
         * @param bool  $saved   Whether the meta box is saved at least once.
         * @param array $field   Field parameters.
         *
         * @return mixed
         */
        public static function meta($post_id, $saved, $field)
        {
        }
        /**
         * Process the submitted value before saving into the database.
         *
         * @param mixed $value     The submitted value.
         * @param int   $object_id The object ID.
         * @param array $field     The field settings.
         */
        public static function process_value($value, $object_id, array $field)
        {
        }
        /**
         * Set value of meta before saving into database.
         *
         * @param mixed $new     The submitted meta value.
         * @param mixed $old     The existing meta value.
         * @param int   $post_id The post ID.
         * @param array $field   The field parameters.
         *
         * @return mixed
         */
        public static function value($new, $old, $post_id, $field)
        {
        }
        /**
         * Save meta value.
         *
         * @param mixed $new     The submitted meta value.
         * @param mixed $old     The existing meta value.
         * @param int   $post_id The post ID.
         * @param array $field   The field parameters.
         */
        public static function save($new, $old, $post_id, $field)
        {
        }
        /**
         * Normalize parameters for field.
         *
         * @param array|string $field Field settings.
         * @return array
         */
        public static function normalize($field)
        {
        }
        /**
         * Get the attributes for a field.
         *
         * @param array $field Field parameters.
         * @param mixed $value Meta value.
         *
         * @return array
         */
        public static function get_attributes($field, $value = \null)
        {
        }
        public static function render_attributes(array $attributes) : string
        {
        }
        /**
         * Get the field value.
         * The difference between this function and 'meta' function is 'meta' function always returns the escaped value
         * of the field saved in the database, while this function returns more meaningful value of the field, for ex.:
         * for file/image: return array of file/image information instead of file/image IDs.
         *
         * Each field can extend this function and add more data to the returned value.
         * See specific field classes for details.
         *
         * @param  array $field   Field parameters.
         * @param  array $args    Additional arguments. Rarely used. See specific fields for details.
         * @param  ?int  $post_id Post ID.
         *
         * @return mixed Field value
         */
        public static function get_value($field, $args = [], $post_id = \null)
        {
        }
        /**
         * Output the field value.
         * Depends on field value and field types, each field can extend this method to output its value in its own way
         * See specific field classes for details.
         *
         * Note: we don't echo the field value directly. We return the output HTML of field, which will be used in
         * rwmb_the_field function later.
         *
         * @use self::get_value()
         * @see rwmb_the_value()
         *
         * @param  array    $field   Field parameters.
         * @param  array    $args    Additional arguments. Rarely used. See specific fields for details.
         * @param  int|null $post_id Post ID. null for current post. Optional.
         *
         * @return string HTML output of the field
         */
        public static function the_value($field, $args = [], $post_id = \null)
        {
        }
        /**
         * Format value for the helper functions.
         *
         * @param array        $field   Field parameters.
         * @param string|array $value   The field meta value.
         * @param array        $args    Additional arguments. Rarely used. See specific fields for details.
         * @param int|null     $post_id Post ID. null for current post. Optional.
         *
         * @return string
         */
        public static function format_value($field, $value, $args, $post_id)
        {
        }
        /**
         * Format value for a clone.
         *
         * @param array        $field   Field parameters.
         * @param string|array $value   The field meta value.
         * @param array        $args    Additional arguments. Rarely used. See specific fields for details.
         * @param int|null     $post_id Post ID. null for current post. Optional.
         *
         * @return string
         */
        public static function format_clone_value($field, $value, $args, $post_id)
        {
        }
        /**
         * Format a single value for the helper functions. Sub-fields should overwrite this method if necessary.
         *
         * @param array    $field   Field parameters.
         * @param string   $value   The value.
         * @param array    $args    Additional arguments. Rarely used. See specific fields for details.
         * @param int|null $post_id Post ID. null for current post. Optional.
         *
         * @return string
         */
        public static function format_single_value($field, $value, $args, $post_id)
        {
        }
        /**
         * Call a method of a field.
         */
        public static function call()
        {
        }
        /**
         * Apply various filters based on field type, id.
         * Filters:
         * - rwmb_{$name}
         * - rwmb_{$field['type']}_{$name}
         * - rwmb_{$field['id']}_{$name}
         *
         * @return mixed
         */
        public static function filter()
        {
        }
        protected static function get_std(array $field)
        {
        }
        protected static function get_single_std(array $field)
        {
        }
    }
    class RWMB_About
    {
        public function __construct($update_checker)
        {
        }
        public function init() : void
        {
        }
        public function plugin_links(array $links) : array
        {
        }
        public function add_menu() : void
        {
        }
        public function add_submenu() : void
        {
        }
        public function hide_page() : void
        {
        }
        public function render() : void
        {
        }
        public function enqueue() : void
        {
        }
        /**
         * Redirect to about page after Meta Box has been activated.
         *
         * @param string $plugin       Path to the main plugin file from plugins directory.
         * @param bool   $network_wide Whether to enable the plugin for all sites in the network
         *                             or just the current site. Multisite only. Default is false.
         */
        public function redirect($plugin, $network_wide = \false) : void
        {
        }
    }
    /**
     * The clone module, allowing users to clone (duplicate) fields.
     */
    class RWMB_Clone
    {
        public static function html(array $meta, array $field) : string
        {
        }
        /**
         * Set value of meta before saving into database
         *
         * @param mixed $new       The submitted meta value.
         * @param mixed $old       The existing meta value.
         * @param int   $object_id The object ID.
         * @param array $field     The field parameters.
         *
         * @return mixed
         */
        public static function value($new, $old, $object_id, array $field)
        {
        }
        public static function add_clone_button(array $field) : string
        {
        }
        public static function remove_clone_button(array $field) : string
        {
        }
    }
    /**
     * Sanitize field value before saving.
     */
    class RWMB_Sanitizer
    {
        public function init()
        {
        }
        /**
         * Sanitize a field value.
         *
         * @param mixed $value     The submitted new value.
         * @param array $field     The field settings.
         * @param mixed $old_value The old field value in the database.
         * @param int   $object_id The object ID.
         */
        public function sanitize($value, $field, $old_value = \null, $object_id = \null)
        {
        }
    }
    /**
     * A very simple request class that handles form inputs.
     * Based on the code of Symphony framework, (c) Fabien Potencier <fabien@symfony.com>
     *
     * @link https://github.com/laravel/framework/blob/6.x/src/Illuminate/Http/Request.php
     * @link https://github.com/symfony/symfony/blob/4.4/src/Symfony/Component/HttpFoundation/ParameterBag.php
     */
    class RWMB_Request
    {
        public function __construct()
        {
        }
        public function set_get_data(array $data)
        {
        }
        public function set_post_data(array $data)
        {
        }
        public function get(string $name, $default = \null)
        {
        }
        public function post(string $name, $default = \null)
        {
        }
        public function cleanup(array $data)
        {
        }
        /**
         * Filter a GET parameter.
         *
         * @param string $name    Parameter name.
         * @param int    $filter  FILTER_* constant.
         * @param mixed  $options Filter options.
         *
         * @return mixed
         */
        public function filter_get(string $name, $filter = \FILTER_DEFAULT, $options = [])
        {
        }
        /**
         * Filter a POST parameter.
         *
         * @param string $name    Parameter name.
         * @param int    $filter  FILTER_* constant.
         * @param mixed  $options Filter options.
         *
         * @return mixed
         */
        public function filter_post(string $name, $filter = \FILTER_DEFAULT, $options = [])
        {
        }
    }
    /**
     * Base walker.
     * Walkers must inherit this class and overwrite methods with its own.
     */
    abstract class RWMB_Walker_Base extends \Walker
    {
        /**
         * Field settings.
         *
         * @var array
         */
        public $field;
        /**
         * Field meta data.
         *
         * @var array
         */
        public $meta;
        /**
         * Constructor.
         *
         * @param array $field Field parameters.
         * @param mixed $meta  Meta value.
         */
        public function __construct($field, $meta)
        {
        }
    }
    /**
     * Select walker select fields.
     */
    class RWMB_Walker_Select extends \RWMB_Walker_Base
    {
        /**
         * Start the element output.
         *
         * @see Walker::start_el()
         *
         * @param string $output            Passed by reference. Used to append additional content.
         * @param object $object            The data object.
         * @param int    $depth             Depth of the item.
         * @param array  $args              An array of additional arguments.
         * @param int    $current_object_id ID of the current item.
         */
        public function start_el(&$output, $object, $depth = 0, $args = [], $current_object_id = 0)
        {
        }
    }
    /**
     * The input list walker for checkbox and radio list fields.
     */
    class RWMB_Walker_Input_List extends \RWMB_Walker_Base
    {
        /**
         * Starts the list before the elements are added.
         *
         * @param string $output Passed by reference. Used to append additional content.
         * @param int    $depth  Depth of the item.
         * @param array  $args   An array of additional arguments.
         */
        public function start_lvl(&$output, $depth = 0, $args = [])
        {
        }
        /**
         * Ends the list of after the elements are added.
         *
         * @param string $output Passed by reference. Used to append additional content.
         * @param int    $depth  Depth of the item.
         * @param array  $args   An array of additional arguments.
         */
        public function end_lvl(&$output, $depth = 0, $args = [])
        {
        }
        /**
         * Start the element output.
         *
         * @param string $output            Passed by reference. Used to append additional content.
         * @param object $object            The data object.
         * @param int    $depth             Depth of the item.
         * @param array  $args              An array of additional arguments.
         * @param int    $current_object_id ID of the current item.
         */
        public function start_el(&$output, $object, $depth = 0, $args = [], $current_object_id = 0)
        {
        }
    }
    /**
     * Select tree walker for cascading select fields.
     */
    class RWMB_Walker_Select_Tree
    {
        /**
         * Field settings.
         *
         * @var array
         */
        public $field;
        /**
         * Field meta value.
         *
         * @var array
         */
        public $meta;
        /**
         * Constructor.
         *
         * @param array $field Field parameters.
         * @param mixed $meta  Meta value.
         */
        public function __construct($field, $meta)
        {
        }
        /**
         * Display array of elements hierarchically.
         *
         * @param array $options An array of options.
         *
         * @return string
         */
        public function walk($options)
        {
        }
        /**
         * Display a hierarchy level.
         *
         * @param array $options   An array of options.
         * @param int   $parent_id Parent item ID.
         * @param bool  $active    Whether to show or hide.
         *
         * @return string
         */
        public function display_level($options, $parent_id = 0, $active = \false)
        {
        }
    }
    /**
     * A class to rapid develop meta boxes for custom & built in content types
     *
     * @property string $id             Meta Box ID.
     * @property string $title          Meta Box title.
     * @property array  $fields         List of fields.
     * @property array  $post_types     List of post types that the meta box is created for.
     * @property string $style          Meta Box style.
     * @property bool   $closed         Whether to collapse the meta box when page loads.
     * @property string $priority       The meta box priority.
     * @property string $context        Where the meta box is displayed.
     * @property bool   $default_hidden Whether the meta box is hidden by default.
     * @property bool   $autosave       Whether the meta box auto saves.
     * @property bool   $media_modal    Add custom fields to media modal when viewing/editing an attachment.
     */
    class RW_Meta_Box
    {
        /**
         * Meta box parameters.
         *
         * @var array
         */
        public $meta_box;
        /**
         * Detect whether the meta box is saved at least once.
         * Used to prevent duplicated calls like revisions, manual hook to wp_insert_post, etc.
         *
         * @var bool
         */
        public $saved = \false;
        /**
         * The object ID.
         *
         * @var int
         */
        public $object_id = \null;
        /**
         * The object type.
         *
         * @var string
         */
        protected $object_type = 'post';
        public function __construct(array $meta_box)
        {
        }
        public function register_fields()
        {
        }
        public function is_shown() : bool
        {
        }
        protected function global_hooks()
        {
        }
        /**
         * Specific hooks for meta box object. Default is 'post'.
         * This should be extended in subclasses to support meta fields for terms, user, settings pages, etc.
         */
        protected function object_hooks()
        {
        }
        public function enqueue()
        {
        }
        /**
         * Add meta box for multiple post types
         */
        public function add_meta_boxes()
        {
        }
        public function postbox_classes(array $classes) : array
        {
        }
        public function hide(array $hidden, $screen) : array
        {
        }
        public function show()
        {
        }
        protected function get_cleanup_fields($fields, $prefix = '')
        {
        }
        protected function render_cleanup()
        {
        }
        /**
         * Save data from meta box
         *
         * @param int $object_id Object ID.
         */
        public function save_post($object_id)
        {
        }
        public function save_field(array $field)
        {
        }
        public function validate() : bool
        {
        }
        public static function normalize($meta_box)
        {
        }
        public static function normalize_fields(array $fields, $storage = \null) : array
        {
        }
        /**
         * Check if meta box is saved before.
         * This helps to save empty value in meta fields (text, check box, etc.) and set the correct default values.
         */
        public function is_saved()
        {
        }
        /**
         * Check if we're on the right edit screen.
         *
         * @param ?WP_Screen $screen Screen object.
         */
        public function is_edit_screen($screen = \null)
        {
        }
        public function __get(string $key)
        {
        }
        /**
         * Set the object ID.
         *
         * @param mixed $id Object ID.
         */
        public function set_object_id($id = \null)
        {
        }
        public function get_object_type() : string
        {
        }
        /**
         * Get storage object.
         *
         * @return RWMB_Storage_Interface
         */
        public function get_storage()
        {
        }
        /**
         * Get current object id.
         *
         * @return int
         */
        protected function get_current_object_id()
        {
        }
        /**
         * Get real object ID when submitting.
         *
         * @param int $object_id Object ID.
         * @return int
         */
        protected function get_real_object_id($object_id)
        {
        }
    }
    /**
     * A registry for storing all fields.
     *
     * @link https://designpatternsphp.readthedocs.io/en/latest/Structural/Registry/README.html
     */
    class RWMB_Field_Registry
    {
        /**
         * Add a single field to the registry.
         *
         * @param array  $field       Field configuration.
         * @param string $type        Post type|Taxonomy|'user'|Setting page which the field belongs to.
         * @param string $object_type Object type which the field belongs to.
         */
        public function add(array $field, string $type, string $object_type = 'post')
        {
        }
        /**
         * Retrieve a field.
         *
         * @param string $id          A meta box instance id.
         * @param string $type        Post type|Taxonomy|'user'|Setting page which the field belongs to.
         * @param string $object_type Object type which the field belongs to.
         *
         * @return bool|array False or field configuration.
         */
        public function get($id, $type, $object_type = 'post')
        {
        }
        /**
         * Retrieve fields by object type.
         *
         * @param string $object_type Object type which the field belongs to.
         *
         * @return array List of fields.
         */
        public function get_by_object_type(string $object_type = 'post') : array
        {
        }
    }
    /**
     * The slider field which users jQueryUI slider widget.
     */
    class RWMB_Slider_Field extends \RWMB_Field
    {
        public static function admin_enqueue_scripts()
        {
        }
        /**
         * Get div HTML.
         *
         * @param mixed $meta  Meta value.
         * @param array $field Field parameters.
         *
         * @return string
         */
        public static function html($meta, $field)
        {
        }
        /**
         * Normalize parameters for field.
         *
         * @param array $field Field parameters.
         *
         * @return array
         */
        public static function normalize($field)
        {
        }
    }
    /**
     * The WYSIWYG (editor) field.
     */
    class RWMB_Wysiwyg_Field extends \RWMB_Field
    {
        public static function admin_enqueue_scripts()
        {
        }
        /**
         * Change field value on save.
         *
         * @param mixed $new     The submitted meta value.
         * @param mixed $old     The existing meta value.
         * @param int   $post_id The post ID.
         * @param array $field   The field parameters.
         * @return string
         */
        public static function value($new, $old, $post_id, $field)
        {
        }
        /**
         * Get field HTML.
         *
         * @param mixed $meta  Meta value.
         * @param array $field Field parameters.
         * @return string
         */
        public static function html($meta, $field)
        {
        }
        /**
         * Normalize parameters for field.
         *
         * @param array $field Field parameters.
         * @return array
         */
        public static function normalize($field)
        {
        }
    }
    /**
     * The abstract input field which is used for all <input> fields.
     */
    abstract class RWMB_Input_Field extends \RWMB_Field
    {
        public static function admin_enqueue_scripts()
        {
        }
        /**
         * Get field HTML.
         *
         * @param mixed $meta  Meta value.
         * @param array $field Field parameters.
         * @return string
         */
        public static function html($meta, $field)
        {
        }
        /**
         * Normalize parameters for field.
         *
         * @param array $field Field parameters.
         * @return array
         */
        public static function normalize($field)
        {
        }
        /**
         * Get the attributes for a field.
         *
         * @param array $field Field parameters.
         * @param mixed $value Meta value.
         * @return array
         */
        public static function get_attributes($field, $value = \null)
        {
        }
        protected static function datalist(array $field) : string
        {
        }
    }
    /**
     * The date and time picker field which allows users to select both date and time via jQueryUI datetime picker.
     */
    class RWMB_Datetime_Field extends \RWMB_Input_Field
    {
        /**
         * Translate date format from jQuery UI date picker to PHP date().
         * It's used to store timestamp value of the field.
         * Missing:  '!' => '', 'oo' => '', '@' => '', "''" => "'".
         *
         * @var array
         */
        protected static $date_formats = ['d' => 'j', 'dd' => 'd', 'oo' => 'z', 'D' => 'D', 'DD' => 'l', 'm' => 'n', 'mm' => 'm', 'M' => 'M', 'MM' => 'F', 'y' => 'y', 'yy' => 'Y', 'o' => 'z'];
        /**
         * Translate time format from jQuery UI time picker to PHP date().
         * It's used to store timestamp value of the field.
         * Missing: 't' => '', T' => '', 'm' => '', 's' => ''.
         *
         * @var array
         */
        protected static $time_formats = ['H' => 'G', 'HH' => 'H', 'h' => 'g', 'hh' => 'h', 'mm' => 'i', 'ss' => 's', 'l' => 'u', 'tt' => 'a', 'TT' => 'A'];
        public static function register_assets()
        {
        }
        /**
         * Enqueue scripts and styles.
         */
        public static function admin_enqueue_scripts()
        {
        }
        /**
         * Get field HTML.
         *
         * @param mixed $meta  The field meta value.
         * @param array $field The field parameters.
         *
         * @return string
         */
        public static function html($meta, $field)
        {
        }
        /**
         * Calculates the timestamp from the datetime string and returns it if $field['timestamp'] is set or the datetime string if not.
         *
         * @param mixed $new     The submitted meta value.
         * @param mixed $old     The existing meta value.
         * @param int   $post_id The post ID.
         * @param array $field   The field parameters.
         *
         * @return string|int
         */
        public static function value($new, $old, $post_id, $field)
        {
        }
        /**
         * Get meta value.
         *
         * @param int   $post_id The post ID.
         * @param bool  $saved   Whether the meta box is saved at least once.
         * @param array $field   The field parameters.
         *
         * @return mixed
         */
        public static function meta($post_id, $saved, $field)
        {
        }
        /**
         * Format meta value if set 'timestamp'.
         */
        public static function from_timestamp($meta, array $field) : array
        {
        }
        /**
         * Transform meta value from save format to the JS format.
         */
        public static function from_save_format($meta, array $field) : string
        {
        }
        /**
         * Normalize parameters for field.
         *
         * @param array $field The field parameters.
         * @return array
         */
        public static function normalize($field)
        {
        }
        /**
         * Get the attributes for a field.
         *
         * @param array $field The field parameters.
         * @param mixed $value The meta value.
         *
         * @return array
         */
        public static function get_attributes($field, $value = \null)
        {
        }
        /**
         * Returns a date() compatible format string from the JavaScript format.
         * @link http://www.php.net/manual/en/function.date.php
         */
        protected static function get_php_format(array $js_options) : string
        {
        }
        /**
         * Format a single value for the helper functions. Sub-fields should overwrite this method if necessary.
         *
         * @param array    $field   Field parameters.
         * @param string   $value   The value.
         * @param array    $args    Additional arguments. Rarely used. See specific fields for details.
         * @param int|null $post_id Post ID. null for current post. Optional.
         *
         * @return string
         */
        public static function format_single_value($field, $value, $args, $post_id)
        {
        }
    }
    /**
     * The abstract choice field.
     */
    abstract class RWMB_Choice_Field extends \RWMB_Field
    {
        /**
         * Get field HTML.
         *
         * @param mixed $meta  Meta value.
         * @param array $field Field parameters.
         * @return string
         */
        public static function html($meta, $field)
        {
        }
        /**
         * Normalize parameters for field.
         *
         * @param array $field Field parameters.
         * @return array
         */
        public static function normalize($field)
        {
        }
        public static function transform_options($options) : array
        {
        }
        /**
         * Format a single value for the helper functions. Sub-fields should overwrite this method if necessary.
         *
         * @param array    $field   Field parameters.
         * @param string   $value   The value.
         * @param array    $args    Additional arguments. Rarely used. See specific fields for details.
         * @param int|null $post_id Post ID. null for current post. Optional.
         *
         * @return string
         */
        public static function format_single_value($field, $value, $args, $post_id)
        {
        }
    }
    /**
     * The object choice class which allows users to select specific objects (post, user, taxonomy) in WordPress.
     */
    abstract class RWMB_Object_Choice_Field extends \RWMB_Choice_Field
    {
        /**
         * Show field HTML.
         * Populate field options before showing to make sure query is made only once.
         *
         * @param array $field   Field parameters.
         * @param bool  $saved   Whether the meta box is saved at least once.
         * @param int   $post_id Post ID.
         */
        public static function show(array $field, bool $saved, $post_id = 0)
        {
        }
        public static abstract function query($meta, array $field) : array;
        /**
         * Get field HTML.
         *
         * @param mixed $meta  Meta value.
         * @param array $field Field parameters.
         * @return string
         */
        public static function html($meta, $field)
        {
        }
        public static function add_new_form(array $field) : string
        {
        }
        /**
         * Normalize parameters for field.
         *
         * @param array $field Field parameters.
         *
         * @return array
         */
        public static function normalize($field)
        {
        }
        /**
         * Set ajax parameters.
         *
         * @param array $field Field settings.
         */
        protected static function set_ajax_params(&$field)
        {
        }
        /**
         * Get the attributes for a field.
         *
         * @param array $field Field parameters.
         * @param mixed $value Meta value.
         *
         * @return array
         */
        public static function get_attributes($field, $value = \null)
        {
        }
        public static function admin_enqueue_scripts()
        {
        }
        /**
         * Get correct rendering class for the field.
         */
        protected static function get_type_class(array $field) : string
        {
        }
    }
    /**
     * The taxonomy field which aims to replace the built-in WordPress taxonomy UI with more options.
     */
    class RWMB_Taxonomy_Field extends \RWMB_Object_Choice_Field
    {
        public static function add_actions()
        {
        }
        public static function ajax_get_terms()
        {
        }
        /**
         * Add default value for 'taxonomy' field.
         *
         * @param array $field Field parameters.
         * @return array
         */
        public static function normalize($field)
        {
        }
        public static function query($meta, array $field) : array
        {
        }
        /**
         * Get meta values to save.
         *
         * @param mixed $new     The submitted meta value.
         * @param mixed $old     The existing meta value.
         * @param int   $post_id The post ID.
         * @param array $field   The field parameters.
         *
         * @return array
         */
        public static function value($new, $old, $post_id, $field)
        {
        }
        /**
         * Save meta value.
         *
         * @param mixed $new     The submitted meta value.
         * @param mixed $old     The existing meta value.
         * @param int   $post_id The post ID.
         * @param array $field   The field parameters.
         */
        public static function save($new, $old, $post_id, $field)
        {
        }
        /**
         * Add new terms if users created some.
         *
         * @param array $field Field settings.
         * @return int|null Term ID if added successfully, null otherwise.
         */
        protected static function add_term($field)
        {
        }
        /**
         * Get raw meta value.
         *
         * @param int   $object_id Object ID.
         * @param array $field     Field parameters.
         * @param array $args      Arguments of {@see rwmb_meta()} helper.
         *
         * @return mixed
         */
        public static function raw_meta($object_id, $field, $args = [])
        {
        }
        /**
         * Get the field value.
         * Return list of post term objects.
         *
         * @param  array $field   Field parameters.
         * @param  array $args    Additional arguments.
         * @param  ?int  $post_id Post ID.
         *
         * @return array List of post term objects.
         */
        public static function get_value($field, $args = [], $post_id = \null)
        {
        }
        /**
         * Format a single value for the helper functions.
         *
         * @param array   $field   Field parameters.
         * @param WP_Term $value   The term object.
         * @param array   $args    Additional arguments. Rarely used. See specific fields for details.
         * @param ?int    $post_id Post ID. null for current post. Optional.
         *
         * @return string
         */
        public static function format_single_value($field, $value, $args, $post_id)
        {
        }
        public static function add_new_form(array $field) : string
        {
        }
        public static function admin_enqueue_scripts()
        {
        }
        protected static function remove_default_meta_box(array $field)
        {
        }
        protected static function get_taxonomy_singular_name(array $field) : string
        {
        }
    }
    /**
     * The select field.
     */
    class RWMB_Select_Field extends \RWMB_Choice_Field
    {
        public static function admin_enqueue_scripts()
        {
        }
        /**
         * Get field HTML.
         *
         * @param mixed $meta  Meta value.
         * @param array $field Field parameters.
         * @return string
         */
        public static function html($meta, $field)
        {
        }
        /**
         * Normalize parameters for field.
         *
         * @param array $field Field parameters.
         * @return array
         */
        public static function normalize($field)
        {
        }
        /**
         * Get the attributes for a field.
         *
         * @param array $field Field parameters.
         * @param mixed $value Meta value.
         *
         * @return array
         */
        public static function get_attributes($field, $value = \null)
        {
        }
        /**
         * Get html for select all|none for multiple select.
         *
         * @param array $field Field parameters.
         * @return string
         */
        public static function get_select_all_html($field)
        {
        }
    }
    /**
     * The beautiful select field using select2 library.
     */
    class RWMB_Select_Advanced_Field extends \RWMB_Select_Field
    {
        public static function admin_enqueue_scripts()
        {
        }
        /**
         * Normalize parameters for field.
         *
         * @param array $field Field parameters.
         * @return array
         */
        public static function normalize($field)
        {
        }
        /**
         * Get the attributes for a field.
         *
         * @param array $field Field parameters.
         * @param mixed $value Meta value.
         * @return array
         */
        public static function get_attributes($field, $value = \null)
        {
        }
    }
    /**
     * The Button group.
     */
    class RWMB_Button_Group_Field extends \RWMB_Choice_Field
    {
        public static function admin_enqueue_scripts()
        {
        }
        /**
         * Get field HTML.
         *
         * @param mixed $meta  Meta value.
         * @param array $field Field parameters.
         * @return string
         */
        public static function html($meta, $field)
        {
        }
        /**
         * Normalize parameters for field.
         *
         * @param array $field Field parameters.
         *
         * @return array
         */
        public static function normalize($field)
        {
        }
        /**
         * Get the attributes for a field.
         *
         * @param array $field Field parameters.
         * @param mixed $value Meta value.
         *
         * @return array
         */
        public static function get_attributes($field, $value = \null)
        {
        }
    }
    /**
     * The icon field.
     */
    class RWMB_Icon_Field extends \RWMB_Select_Advanced_Field
    {
        const CACHE_GROUP = 'meta-box-icon-field';
        public static function admin_enqueue_scripts()
        {
        }
        /**
         * Normalize field settings.
         *
         * @param array $field Field settings.
         * @return array
         */
        public static function normalize($field)
        {
        }
        /**
         * Format value for the helper functions.
         *
         * @param array        $field   Field parameters.
         * @param string|array $value   The field meta value.
         * @param array        $args    Additional arguments. Rarely used. See specific fields for details.
         * @param int|null     $post_id Post ID. null for current post. Optional.
         *
         * @return string
         */
        public static function format_single_value($field, $value, $args, $post_id)
        {
        }
    }
    /**
     * The file upload file which allows users to upload files via the default HTML <input type="file">.
     */
    class RWMB_File_Field extends \RWMB_Field
    {
        public static function admin_enqueue_scripts()
        {
        }
        public static function add_actions()
        {
        }
        public static function post_edit_form_tag()
        {
        }
        public static function ajax_delete_file()
        {
        }
        /**
         * Recursively search needle in haystack
         */
        protected static function in_array_r($needle, $haystack, $strict = \false) : bool
        {
        }
        /**
         * Get field HTML.
         *
         * @param mixed $meta  Meta value.
         * @param array $field Field parameters.
         *
         * @return string
         */
        public static function html($meta, $field)
        {
        }
        /**
         * Get HTML for uploaded files.
         *
         * @param array $files List of uploaded files.
         * @param array $field Field parameters.
         * @return string
         */
        protected static function get_uploaded_files($files, $field)
        {
        }
        /**
         * Get HTML for uploaded file.
         *
         * @param int   $file  Attachment (file) ID.
         * @param int   $index File index.
         * @param array $field Field data.
         * @return string
         */
        protected static function file_html($file, $index, $field)
        {
        }
        protected static function file_info_custom_dir(string $file, array $field) : array
        {
        }
        /**
         * Get meta values to save.
         *
         * @param mixed $new     The submitted meta value.
         * @param mixed $old     The existing meta value.
         * @param int   $post_id The post ID.
         * @param array $field   The field parameters.
         *
         * @return array|mixed
         */
        public static function value($new, $old, $post_id, $field)
        {
        }
        /**
         * Get meta values to save for cloneable fields.
         *
         * @param array $new         The submitted meta value.
         * @param array $old         The existing meta value.
         * @param int   $object_id   The object ID.
         * @param array $field       The field settings.
         * @param array $data_source Data source. Either $_POST or custom array. Used in group to get uploaded files.
         *
         * @return mixed
         */
        public static function clone_value($new, $old, $object_id, $field, $data_source = \null)
        {
        }
        /**
         * Handle file upload.
         * Consider upload to Media Library or custom folder.
         *
         * @param string $file_id File ID in $_FILES when uploading.
         * @param int    $post_id Post ID.
         * @param array  $field   Field settings.
         *
         * @return \WP_Error|int|string WP_Error if has error, attachment ID if upload in Media Library, URL to file if upload to custom folder.
         */
        protected static function handle_upload($file_id, $post_id, $field)
        {
        }
        /**
         * Transform $_FILES from $_FILES['field']['key']['index'] to $_FILES['field_index']['key'].
         *
         * @param string $input_name The field input name.
         *
         * @return int The number of uploaded files.
         */
        protected static function transform($input_name) : int
        {
        }
        /**
         * Normalize parameters for field.
         *
         * @param array $field Field parameters.
         * @return array
         */
        public static function normalize($field)
        {
        }
        /**
         * Get the field value. Return meaningful info of the files.
         *
         * @param  array    $field   Field parameters.
         * @param  array    $args    Not used for this field.
         * @param  int|null $post_id Post ID. null for current post. Optional.
         *
         * @return mixed Full info of uploaded files
         */
        public static function get_value($field, $args = [], $post_id = \null)
        {
        }
        /**
         * Get uploaded files information.
         *
         * @param array $field Field parameters.
         * @param array $files Files IDs.
         * @param array $args  Additional arguments (for image size).
         * @return array
         */
        public static function files_info($field, $files, $args)
        {
        }
        /**
         * Get uploaded file information.
         *
         * @param int   $file  Attachment file ID (post ID). Required.
         * @param array $args  Array of arguments (for size).
         * @param array $field Field settings.
         *
         * @return array|bool False if file not found. Array of (id, name, path, url) on success.
         */
        public static function file_info($file, $args = [], $field = [])
        {
        }
        /**
         * Format a single value for the helper functions. Sub-fields should overwrite this method if necessary.
         *
         * @param array    $field   Field parameters.
         * @param array    $value   The value.
         * @param array    $args    Additional arguments. Rarely used. See specific fields for details.
         * @param int|null $post_id Post ID. null for current post. Optional.
         *
         * @return string
         */
        public static function format_single_value($field, $value, $args, $post_id)
        {
        }
        /**
         * Handle upload for files in custom directory.
         *
         * @param string $file_id File ID in $_FILES when uploading.
         * @param array  $field   Field settings.
         *
         * @return string URL to uploaded file.
         */
        public static function handle_upload_custom_dir($file_id, $field)
        {
        }
        public static function convert_path_to_url(string $path) : string
        {
        }
    }
    /**
     * Media field class which users WordPress media popup to upload and select files.
     */
    class RWMB_Media_Field extends \RWMB_File_Field
    {
        public static function admin_enqueue_scripts()
        {
        }
        public static function add_actions()
        {
        }
        /**
         * Get meta value.
         *
         * @param int   $post_id Post ID.
         * @param bool  $saved   Whether the meta box is saved at least once.
         * @param array $field   Field parameters.
         *
         * @return mixed
         */
        public static function meta($post_id, $saved, $field)
        {
        }
        /**
         * Get field HTML.
         *
         * @param mixed $meta  Meta value.
         * @param array $field Field parameters.
         *
         * @return string
         */
        public static function html($meta, $field)
        {
        }
        /**
         * Normalize parameters for field.
         *
         * @param array $field Field parameters.
         *
         * @return array
         */
        public static function normalize($field)
        {
        }
        /**
         * Get the attributes for a field.
         *
         * @param array $field Field parameters.
         * @param mixed $value Meta value.
         *
         * @return array
         */
        public static function get_attributes($field, $value = \null)
        {
        }
        protected static function get_mime_extensions() : array
        {
        }
        /**
         * Get meta values to save.
         *
         * @param mixed $new     The submitted meta value.
         * @param mixed $old     The existing meta value.
         * @param int   $post_id The post ID.
         * @param array $field   The field parameters.
         *
         * @return array
         */
        public static function value($new, $old, $post_id, $field)
        {
        }
        /**
         * Save meta value.
         *
         * @param mixed $new     The submitted meta value.
         * @param mixed $old     The existing meta value.
         * @param int   $post_id The post ID.
         * @param array $field   The field parameters.
         */
        public static function save($new, $old, $post_id, $field)
        {
        }
        /**
         * Template for media item.
         */
        public static function print_templates()
        {
        }
    }
    /**
     * The advanced image upload field which uses WordPress media popup to upload and select images.
     */
    class RWMB_Image_Advanced_Field extends \RWMB_Media_Field
    {
        public static function admin_enqueue_scripts()
        {
        }
        /**
         * Normalize parameters for field.
         *
         * @param array $field Field parameters.
         *
         * @return array
         */
        public static function normalize($field)
        {
        }
        /**
         * Get the field value.
         *
         * @param array $field   Field parameters.
         * @param array $args    Additional arguments.
         * @param ?int  $post_id Post ID.
         * @return mixed
         */
        public static function get_value($field, $args = [], $post_id = \null)
        {
        }
        /**
         * Get uploaded file information.
         *
         * @param int   $file  Attachment image ID (post ID). Required.
         * @param array $args  Array of arguments (for size).
         * @param array $field Field settings.
         *
         * @return array|bool False if file not found. Array of image info on success.
         */
        public static function file_info($file, $args = [], $field = [])
        {
        }
        /**
         * Format a single value for the helper functions. Sub-fields should overwrite this method if necessary.
         *
         * @param array    $field   Field parameters.
         * @param array    $value   The value.
         * @param array    $args    Additional arguments. Rarely used. See specific fields for details.
         * @param int|null $post_id Post ID. null for current post. Optional.
         *
         * @return string
         */
        public static function format_single_value($field, $value, $args, $post_id)
        {
        }
        /**
         * Template for media item.
         */
        public static function print_templates()
        {
        }
    }
    /**
     * The advanced image upload field which uses WordPress media popup to upload and select images.
     */
    class RWMB_Single_Image_Field extends \RWMB_Image_Advanced_Field
    {
        /**
         * Normalize parameters for field.
         *
         * @param array $field Field parameters.
         *
         * @return array
         */
        public static function normalize($field)
        {
        }
        /**
         * Get meta values to save.
         *
         * @param mixed $new     The submitted meta value.
         * @param mixed $old     The existing meta value.
         * @param int   $post_id The post ID.
         * @param array $field   The field parameters.
         *
         * @return array|mixed
         */
        public static function value($new, $old, $post_id, $field)
        {
        }
        /**
         * Get the field value. Return meaningful info of the files.
         *
         * @param  array    $field   Field parameters.
         * @param  array    $args    Not used for this field.
         * @param  int|null $post_id Post ID. null for current post. Optional.
         *
         * @return mixed Full info of uploaded files
         */
        public static function get_value($field, $args = [], $post_id = \null)
        {
        }
    }
    /**
     * The input list field which displays choices in a list of inputs.
     */
    class RWMB_Input_List_Field extends \RWMB_Choice_Field
    {
        public static function admin_enqueue_scripts()
        {
        }
        /**
         * Get field HTML.
         *
         * @param mixed $meta  Meta value.
         * @param array $field Field parameters.
         * @return string
         */
        public static function html($meta, $field)
        {
        }
        /**
         * Normalize parameters for field.
         *
         * @param array $field Field parameters.
         * @return array
         */
        public static function normalize($field)
        {
        }
        /**
         * Get the attributes for a field.
         *
         * @param array $field Field parameters.
         * @param mixed $value Meta value.
         *
         * @return array
         */
        public static function get_attributes($field, $value = \null)
        {
        }
        /**
         * Get html for select all|none for multiple checkbox.
         *
         * @param array $field Field parameters.
         * @return string
         */
        public static function get_select_all_html($field)
        {
        }
    }
    /**
     * The radio field.
     */
    class RWMB_Radio_Field extends \RWMB_Input_List_Field
    {
        public static function normalize($field)
        {
        }
    }
    /**
     * The time picker field.
     */
    class RWMB_Time_Field extends \RWMB_Datetime_Field
    {
        public static function admin_enqueue_scripts()
        {
        }
        /**
         * Normalize parameters for field.
         *
         * @param array $field Field parameters.
         * @return array
         */
        public static function normalize($field)
        {
        }
        /**
         * Returns a date() compatible format string from the JavaScript format.
         * @link http://www.php.net/manual/en/function.date.php
         */
        protected static function get_php_format(array $js_options) : string
        {
        }
    }
    /**
     * The number field which uses HTML <input type="number">.
     */
    class RWMB_Number_Field extends \RWMB_Input_Field
    {
        /**
         * Normalize parameters for field.
         *
         * @param array $field Field parameters.
         *
         * @return array
         */
        public static function normalize($field)
        {
        }
        /**
         * Get the attributes for a field.
         *
         * @param array $field Field parameters.
         * @param mixed $value Meta value.
         *
         * @return array
         */
        public static function get_attributes($field, $value = \null)
        {
        }
    }
    /**
     * The file input field which allows users to enter a file URL or select it from the Media Library.
     */
    class RWMB_File_Input_Field extends \RWMB_Input_Field
    {
        /**
         * Enqueue scripts and styles.
         */
        public static function admin_enqueue_scripts()
        {
        }
        /**
         * Get field HTML.
         *
         * @param mixed $meta  Meta value.
         * @param array $field Field parameters.
         *
         * @return string
         */
        public static function html($meta, $field)
        {
        }
        /**
         * Get the attributes for a field.
         *
         * @param array $field Field parameters.
         * @param mixed $value Meta value.
         * @return array
         */
        public static function get_attributes($field, $value = \null)
        {
        }
    }
    /**
     * The image upload field which allows users to drag and drop images.
     */
    class RWMB_Image_Upload_Field extends \RWMB_Image_Advanced_Field
    {
        public static function admin_enqueue_scripts()
        {
        }
        /**
         * Normalize parameters for field.
         *
         * @param array $field Field parameters.
         *
         * @return array
         */
        public static function normalize($field)
        {
        }
        /**
         * Template for media item.
         */
        public static function print_templates()
        {
        }
    }
    /**
     * The checkbox list field which shows a list of choices and allow users to select multiple options.
     */
    class RWMB_Checkbox_List_Field extends \RWMB_Input_List_Field
    {
        /**
         * Normalize parameters for field.
         *
         * @param array $field Field parameters.
         * @return array
         */
        public static function normalize($field)
        {
        }
    }
    /**
     * The post field which allows users to select existing posts.
     */
    class RWMB_Post_Field extends \RWMB_Object_Choice_Field
    {
        public static function add_actions()
        {
        }
        public static function ajax_get_posts()
        {
        }
        /**
         * Normalize parameters for field.
         *
         * @param array $field Field parameters.
         * @return array
         */
        public static function normalize($field)
        {
        }
        public static function query($meta, array $field) : array
        {
        }
        /**
         * Only search posts by title.
         * WordPress searches by either title or content which is confused when users can't find their posts.
         *
         * @link https://developer.wordpress.org/reference/hooks/posts_search/
         */
        public static function search_by_title($search, $wp_query)
        {
        }
        /**
         * Get meta value.
         * If field is cloneable, value is saved as a single entry in DB.
         * Otherwise value is saved as multiple entries (for backward compatibility).
         *
         * @see "save" method for better understanding
         *
         * @param int   $post_id Post ID.
         * @param bool  $saved   Is the meta box saved.
         * @param array $field   Field parameters.
         *
         * @return mixed
         */
        public static function meta($post_id, $saved, $field)
        {
        }
        /**
         * Format a single value for the helper functions. Sub-fields should overwrite this method if necessary.
         *
         * @param array $field   Field parameters.
         * @param int   $value   The value.
         * @param array $args    Additional arguments. Rarely used. See specific fields for details.
         * @param ?int  $post_id Post ID. null for current post. Optional.
         *
         * @return string
         */
        public static function format_single_value($field, $value, $args, $post_id)
        {
        }
        public static function add_new_form(array $field) : string
        {
        }
    }
    /**
     * The Google Maps field.
     */
    class RWMB_Map_Field extends \RWMB_Field
    {
        public static function admin_enqueue_scripts()
        {
        }
        /**
         * Get field HTML.
         *
         * @param mixed $meta  Meta value.
         * @param array $field Field parameters.
         *
         * @return string
         */
        public static function html($meta, $field)
        {
        }
        /**
         * Normalize parameters for field.
         *
         * @param array $field Field parameters.
         *
         * @return array
         */
        public static function normalize($field)
        {
        }
        /**
         * Get the field value.
         * The difference between this function and 'meta' function is 'meta' function always returns the escaped value
         * of the field saved in the database, while this function returns more meaningful value of the field.
         *
         * @param  array    $field   Field parameters.
         * @param  array    $args    Not used for this field.
         * @param  int|null $post_id Post ID. null for current post. Optional.
         *
         * @return mixed Array(latitude, longitude, zoom)
         */
        public static function get_value($field, $args = [], $post_id = \null)
        {
        }
        /**
         * Format value before render map
         * @param mixed $field
         * @param mixed $value
         * @param mixed $args
         * @param mixed $post_id
         * @return string
         */
        public static function format_single_value($field, $value, $args, $post_id) : string
        {
        }
        /**
         * Render a map in the frontend.
         *
         * @param string $location The "latitude,longitude[,zoom]" location.
         * @param array  $args     Additional arguments for the map.
         *
         * @return string
         */
        public static function render_map($location, $args = [])
        {
        }
    }
    /**
     * The divider field which displays a simple horizontal line.
     */
    class RWMB_Divider_Field extends \RWMB_Field
    {
        public static function admin_enqueue_scripts()
        {
        }
        protected static function begin_html(array $field) : string
        {
        }
        public static function end_html(array $field) : string
        {
        }
    }
    /**
     * The heading field which displays a simple heading text.
     */
    class RWMB_Heading_Field extends \RWMB_Field
    {
        public static function admin_enqueue_scripts()
        {
        }
        protected static function begin_html(array $field) : string
        {
        }
        protected static function end_html(array $field) : string
        {
        }
    }
    /**
     * This class implements common methods used in fields which have multiple values
     * like checkbox list, autocomplete, etc.
     *
     * The difference when handling actions for these fields are the way they get/set
     * meta value. Briefly:
     * - If field is cloneable, value is saved as a single entry in the database
     * - Otherwise value is saved as multiple entries
     */
    abstract class RWMB_Multiple_Values_Field extends \RWMB_Field
    {
        /**
         * Normalize parameters for field.
         *
         * @param array $field Field parameters.
         *
         * @return array
         */
        public static function normalize($field)
        {
        }
        /**
         * Format a single value for the helper functions. Sub-fields should overwrite this method if necessary.
         *
         * @param array    $field   Field parameters.
         * @param string   $value   The value.
         * @param array    $args    Additional arguments. Rarely used. See specific fields for details.
         * @param int|null $post_id Post ID. null for current post. Optional.
         *
         * @return string
         */
        public static function format_single_value($field, $value, $args, $post_id)
        {
        }
    }
    /**
     * The text list field which allows users to enter multiple texts.
     */
    class RWMB_Text_List_Field extends \RWMB_Multiple_Values_Field
    {
        public static function admin_enqueue_scripts()
        {
        }
        /**
         * Get field HTML.
         *
         * @param mixed $meta  Meta value.
         * @param array $field Field parameters.
         *
         * @return string
         */
        public static function html($meta, $field)
        {
        }
        /**
         * Normalize parameters for field.
         *
         * @param array $field Field parameters.
         *
         * @return array
         */
        public static function normalize($field)
        {
        }
        /**
         * Set value of meta before saving into database.
         * Do not save if all inputs has no value.
         *
         * @param mixed $new     The submitted meta value.
         * @param mixed $old     The existing meta value.
         * @param int   $post_id The post ID.
         * @param array $field   The field parameters.
         *
         * @return mixed
         */
        public static function value($new, $old, $post_id, $field)
        {
        }
        /**
         * Format value for the helper functions.
         *
         * @param array        $field   Field parameters.
         * @param string|array $value   The field meta value.
         * @param array        $args    Additional arguments. Rarely used. See specific fields for details.
         * @param int|null     $post_id Post ID. null for current post. Optional.
         *
         * @return string
         */
        public static function format_value($field, $value, $args, $post_id)
        {
        }
        /**
         * Format a single value for the helper functions. Sub-fields should overwrite this method if necessary.
         *
         * @param array    $field   Field parameters.
         * @param array    $value   The value.
         * @param array    $args    Additional arguments. Rarely used. See specific fields for details.
         * @param int|null $post_id Post ID. null for current post. Optional.
         *
         * @return string
         */
        public static function format_single_value($field, $value, $args, $post_id)
        {
        }
    }
    /**
     * The user select field.
     */
    class RWMB_User_Field extends \RWMB_Object_Choice_Field
    {
        public static function add_actions()
        {
        }
        public static function ajax_get_users()
        {
        }
        /**
         * Update object cache to make sure query method below always get the fresh list of users.
         * Unlike posts and terms, WordPress doesn't set 'last_changed' for users.
         * So we have to do it ourselves.
         *
         * @see clean_post_cache()
         */
        public static function update_cache()
        {
        }
        /**
         * Normalize parameters for field.
         *
         * @param array $field Field parameters.
         *
         * @return array
         */
        public static function normalize($field)
        {
        }
        public static function query($meta, array $field) : array
        {
        }
        /**
         * Format a single value for the helper functions. Sub-fields should overwrite this method if necessary.
         *
         * @param array    $field   Field parameters.
         * @param int      $value   User ID.
         * @param array    $args    Additional arguments. Rarely used. See specific fields for details.
         * @param int|null $post_id Post ID. null for current post. Optional.
         *
         * @return string
         */
        public static function format_single_value($field, $value, $args, $post_id)
        {
        }
        public static function add_new_form(array $field) : string
        {
        }
    }
    /**
     * The image select field which behaves similar to the radio field but uses images as options.
     */
    class RWMB_Image_Select_Field extends \RWMB_Field
    {
        public static function admin_enqueue_scripts()
        {
        }
        /**
         * Get field HTML.
         *
         * @param mixed $meta  Meta value.
         * @param array $field Field parameters.
         * @return string
         */
        public static function html($meta, $field)
        {
        }
        /**
         * Normalize parameters for field.
         *
         * @param array $field Field parameters.
         * @return array
         */
        public static function normalize($field)
        {
        }
        /**
         * Get the attributes for a field.
         *
         * @param array $field Field parameters.
         * @param mixed $value Meta value.
         * @return array
         */
        public static function get_attributes($field, $value = \null)
        {
        }
        /**
         * Format a single value for the helper functions. Sub-fields should overwrite this method if necessary.
         *
         * @param array    $field   Field parameters.
         * @param string   $value   The value.
         * @param array    $args    Additional arguments. Rarely used. See specific fields for details.
         * @param int|null $post_id Post ID. null for current post. Optional.
         *
         * @return string
         */
        public static function format_single_value($field, $value, $args, $post_id)
        {
        }
    }
    /**
     * The background field.
     */
    class RWMB_Background_Field extends \RWMB_Field
    {
        public static function admin_enqueue_scripts()
        {
        }
        /**
         * Get field HTML.
         *
         * @param mixed $meta  Meta value.
         * @param array $field Field settings.
         *
         * @return string
         */
        public static function html($meta, $field)
        {
        }
        /**
         * Format a single value for the helper functions. Sub-fields should overwrite this method if necessary.
         *
         * @param array    $field   Field parameters.
         * @param array    $value   The value.
         * @param array    $args    Additional arguments. Rarely used. See specific fields for details.
         * @param int|null $post_id Post ID. null for current post. Optional.
         *
         * @return string
         */
        public static function format_single_value($field, $value, $args, $post_id)
        {
        }
    }
    /**
     * The key-value field which allows users to add pairs of keys and values.
     */
    class RWMB_Key_Value_Field extends \RWMB_Input_Field
    {
        public static function admin_enqueue_scripts()
        {
        }
        /**
         * Get field HTML.
         *
         * @param mixed $meta  Meta value.
         * @param array $field Field parameters.
         *
         * @return string
         */
        public static function html($meta, $field)
        {
        }
        protected static function begin_html(array $field) : string
        {
        }
        protected static function input_description(array $field) : string
        {
        }
        /**
         * Sanitize field value.
         *
         * @param mixed $new     The submitted meta value.
         * @param mixed $old     The existing meta value.
         * @param int   $post_id The post ID.
         * @param array $field   The field parameters.
         *
         * @return array
         */
        public static function value($new, $old, $post_id, $field)
        {
        }
        /**
         * Normalize parameters for field.
         *
         * @param array $field Field parameters.
         *
         * @return array
         */
        public static function normalize($field)
        {
        }
        /**
         * Format value for the helper functions.
         *
         * @param array        $field   Field parameters.
         * @param string|array $value   The field meta value.
         * @param array        $args    Additional arguments. Rarely used. See specific fields for details.
         * @param int|null     $post_id Post ID. null for current post. Optional.
         *
         * @return string
         */
        public static function format_clone_value($field, $value, $args, $post_id)
        {
        }
    }
    /**
     * The secured password field.
     */
    class RWMB_Password_Field extends \RWMB_Input_Field
    {
        /**
         * Store secured password in the database.
         *
         * @param mixed $new     The submitted meta value.
         * @param mixed $old     The existing meta value.
         * @param int   $post_id The post ID.
         * @param array $field   The field parameters.
         * @return string
         */
        public static function value($new, $old, $post_id, $field)
        {
        }
    }
    /**
     * The date picker field, which uses built-in jQueryUI date picker widget.
     */
    class RWMB_Date_Field extends \RWMB_Datetime_Field
    {
        public static function admin_enqueue_scripts()
        {
        }
        /**
         * Returns a date() compatible format string from the JavaScript format.
         * @link http://www.php.net/manual/en/function.date.php
         */
        protected static function get_php_format(array $js_options) : string
        {
        }
    }
    /**
     * The file upload field which allows users to drag and drop files to upload.
     */
    class RWMB_File_Upload_Field extends \RWMB_Media_Field
    {
        public static function admin_enqueue_scripts()
        {
        }
        /**
         * Normalize parameters for field.
         *
         * @param array $field Field parameters.
         *
         * @return array
         */
        public static function normalize($field)
        {
        }
        /**
         * Template for media item.
         */
        public static function print_templates()
        {
        }
    }
    /**
     * The color field which uses WordPress color picker to select a color.
     */
    class RWMB_Color_Field extends \RWMB_Input_Field
    {
        public static function admin_enqueue_scripts()
        {
        }
        /**
         * Normalize parameters for field.
         *
         * @param array $field Field parameters.
         *
         * @return array
         */
        public static function normalize($field)
        {
        }
        /**
         * Get the attributes for a field.
         *
         * @param array $field Field parameters.
         * @param mixed $value Meta value.
         *
         * @return array
         */
        public static function get_attributes($field, $value = \null)
        {
        }
        /**
         * Format a single value for the helper functions. Sub-fields should overwrite this method if necessary.
         *
         * @param array    $field   Field parameters.
         * @param string   $value   The value.
         * @param array    $args    Additional arguments. Rarely used. See specific fields for details.
         * @param int|null $post_id Post ID. null for current post. Optional.
         *
         * @return string
         */
        public static function format_single_value($field, $value, $args, $post_id)
        {
        }
    }
    /**
     * Video field which uses WordPress media popup to upload and select video.
     */
    class RWMB_Video_Field extends \RWMB_Media_Field
    {
        public static function admin_enqueue_scripts()
        {
        }
        /**
         * Normalize parameters for field.
         *
         * @param array $field Field parameters.
         *
         * @return array
         */
        public static function normalize($field)
        {
        }
        /**
         * Get uploaded file information.
         *
         * @param int   $file_id Attachment image ID (post ID). Required.
         * @param array $args    Array of arguments (for size).
         * @param array $field   Field settings.
         *
         * @return array|bool False if file not found. Array of image info on success.
         */
        public static function file_info($file_id, $args = [], $field = [])
        {
        }
        /**
         * Format value for a clone.
         *
         * @param array        $field   Field parameters.
         * @param string|array $value   The field meta value.
         * @param array        $args    Additional arguments. Rarely used. See specific fields for details.
         * @param int|null     $post_id Post ID. null for current post. Optional.
         *
         * @return string
         */
        public static function format_clone_value($field, $value, $args, $post_id)
        {
        }
        /**
         * Template for media item.
         */
        public static function print_templates()
        {
        }
    }
    /**
     * The Switch field.
     */
    class RWMB_Switch_Field extends \RWMB_Input_Field
    {
        public static function admin_enqueue_scripts()
        {
        }
        /**
         * Get field HTML.
         *
         * @param mixed $meta  Meta value.
         * @param array $field Field parameters.
         *
         * @return string
         */
        public static function html($meta, $field)
        {
        }
        /**
         * Normalize parameters for field.
         *
         * @param array $field Field parameters.
         *
         * @return array
         */
        public static function normalize($field)
        {
        }
        /**
         * Get the attributes for a field.
         *
         * @param array $field The field parameters.
         * @param mixed $value The attribute value.
         *
         * @return array
         */
        public static function get_attributes($field, $value = \null)
        {
        }
        /**
         * Format a single value for the helper functions. Sub-fields should overwrite this method if necessary.
         *
         * @param array    $field   Field parameters.
         * @param string   $value   The value.
         * @param array    $args    Additional arguments. Rarely used. See specific fields for details.
         * @param int|null $post_id Post ID. null for current post. Optional.
         *
         * @return string
         */
        public static function format_single_value($field, $value, $args, $post_id)
        {
        }
    }
    /**
     * The textarea field.
     */
    class RWMB_Textarea_Field extends \RWMB_Field
    {
        /**
         * Get field HTML.
         *
         * @param mixed $meta Meta value.
         * @param array $field Field parameters.
         *
         * @return string
         */
        public static function html($meta, $field)
        {
        }
        /**
         * Normalize parameters for field.
         *
         * @param array $field Field parameters.
         * @return array
         */
        public static function normalize($field)
        {
        }
        /**
         * Get the attributes for a field.
         *
         * @param array $field Field parameters.
         * @param mixed $value Meta value.
         *
         * @return array
         */
        public static function get_attributes($field, $value = \null)
        {
        }
    }
    /**
     * The checkbox field.
     */
    class RWMB_Checkbox_Field extends \RWMB_Input_Field
    {
        /**
         * Get field HTML.
         *
         * @param mixed $meta  Meta value.
         * @param array $field Field parameters.
         * @return string
         */
        public static function html($meta, $field)
        {
        }
        protected static function input_description(array $field) : string
        {
        }
        /**
         * Format a single value for the helper functions. Sub-fields should overwrite this method if necessary.
         *
         * @param array    $field   Field parameters.
         * @param string   $value   The value.
         * @param array    $args    Additional arguments. Rarely used. See specific fields for details.
         * @param int|null $post_id Post ID. null for current post. Optional.
         *
         * @return string
         */
        public static function format_single_value($field, $value, $args, $post_id)
        {
        }
    }
    /**
     * The HTML5 range field.
     */
    class RWMB_Range_Field extends \RWMB_Number_Field
    {
        /**
         * Get field HTML.
         *
         * @param mixed $meta  Meta value.
         * @param array $field Field parameters.
         * @return string
         */
        public static function html($meta, $field)
        {
        }
        public static function admin_enqueue_scripts()
        {
        }
        /**
         * Normalize parameters for field.
         *
         * @param array $field Field parameters.
         * @return array
         */
        public static function normalize($field)
        {
        }
        /**
         * Ensure number in range.
         *
         * @param mixed $new     The submitted meta value.
         * @param mixed $old     The existing meta value.
         * @param int   $post_id The post ID.
         * @param array $field   The field parameters.
         *
         * @return int
         */
        public static function value($new, $old, $post_id, $field)
        {
        }
    }
    /**
     * The select tree field.
     */
    class RWMB_Select_Tree_Field extends \RWMB_Select_Advanced_Field
    {
        /**
         * Get field HTML.
         *
         * @param mixed $meta  Meta value.
         * @param array $field Field parameters.
         * @return string
         */
        public static function html($meta, $field)
        {
        }
        public static function admin_enqueue_scripts()
        {
        }
        /**
         * Normalize parameters for field.
         *
         * @param array $field Field parameters.
         * @return array
         */
        public static function normalize($field)
        {
        }
        /**
         * Get the attributes for a field.
         *
         * @param array $field Field parameters.
         * @param mixed $value Meta value.
         *
         * @return array
         */
        public static function get_attributes($field, $value = \null)
        {
        }
    }
    /**
     * The autocomplete field.
     */
    class RWMB_Autocomplete_Field extends \RWMB_Multiple_Values_Field
    {
        public static function admin_enqueue_scripts()
        {
        }
        /**
         * Get field HTML.
         *
         * @param mixed $meta  Meta value.
         * @param array $field Field parameters.
         * @return string
         */
        public static function html($meta, $field)
        {
        }
    }
    /**
     * The oEmbed field which allows users to enter oEmbed URLs.
     */
    class RWMB_OEmbed_Field extends \RWMB_Input_Field
    {
        /**
         * Normalize parameters for field.
         *
         * @param array $field Field parameters.
         * @return array
         */
        public static function normalize($field)
        {
        }
        public static function admin_enqueue_scripts()
        {
        }
        public static function add_actions()
        {
        }
        public static function ajax_get_embed()
        {
        }
        /**
         * Get embed html from url.
         *
         * @param string $url           URL.
         * @param string $not_available Not available string displayed to users.
         * @return string
         */
        public static function get_embed($url, $not_available = '')
        {
        }
        /**
         * Get field HTML.
         *
         * @param mixed $meta  Meta value.
         * @param array $field Field parameters.
         * @return string
         */
        public static function html($meta, $field)
        {
        }
        /**
         * Get the attributes for a field.
         *
         * @param array $field Field parameters.
         * @param mixed $value Meta value.
         *
         * @return array
         */
        public static function get_attributes($field, $value = \null)
        {
        }
        /**
         * Format a single value for the helper functions. Sub-fields should overwrite this method if necessary.
         *
         * @param array    $field   Field parameters.
         * @param string   $value   The value.
         * @param array    $args    Additional arguments. Rarely used. See specific fields for details.
         * @param int|null $post_id Post ID. null for current post. Optional.
         *
         * @return string
         */
        public static function format_single_value($field, $value, $args, $post_id)
        {
        }
    }
    /**
     * The Open Street Map field.
     */
    class RWMB_OSM_Field extends \RWMB_Field
    {
        public static function admin_enqueue_scripts()
        {
        }
        /**
         * Get field HTML.
         *
         * @param mixed $meta  Meta value.
         * @param array $field Field parameters.
         *
         * @return string
         */
        public static function html($meta, $field)
        {
        }
        /**
         * Normalize parameters for field.
         *
         * @param array $field Field parameters.
         *
         * @return array
         */
        public static function normalize($field)
        {
        }
        /**
         * Get the field value.
         * The difference between this function and 'meta' function is 'meta' function always returns the escaped value
         * of the field saved in the database, while this function returns more meaningful value of the field.
         *
         * @param  array    $field   Field parameters.
         * @param  array    $args    Not used for this field.
         * @param  int|null $post_id Post ID. null for current post. Optional.
         *
         * @return mixed Array(latitude, longitude, zoom)
         */
        public static function get_value($field, $args = [], $post_id = \null)
        {
        }
        /**
         * Format value before render map
         * @param mixed $field
         * @param mixed $value
         * @param mixed $args
         * @param mixed $post_id
         * @return string
         */
        public static function format_single_value($field, $value, $args, $post_id) : string
        {
        }
        /**
         * Render a map in the frontend.
         *
         * @param string|array $location The "latitude,longitude[,zoom]" location.
         * @param array  $args     Additional arguments for the map.
         *
         * @return string
         */
        public static function render_map($location, $args = [])
        {
        }
    }
    /**
     * Taxonomy advanced field which saves terms' IDs in the post meta in CSV format.
     */
    class RWMB_Taxonomy_Advanced_Field extends \RWMB_Taxonomy_Field
    {
        /**
         * Save terms in form of comma-separated IDs.
         *
         * @param mixed $new     The submitted meta value.
         * @param mixed $old     The existing meta value.
         * @param int   $post_id The post ID.
         * @param array $field   The field parameters.
         *
         * @return string
         */
        public static function value($new, $old, $post_id, $field)
        {
        }
        /**
         * Save meta value.
         *
         * @param mixed $new     The submitted meta value.
         * @param mixed $old     The existing meta value.
         * @param int   $post_id The post ID.
         * @param array $field   The field parameters.
         */
        public static function save($new, $old, $post_id, $field)
        {
        }
        /**
         * Get raw meta value.
         *
         * @param int   $object_id Object ID.
         * @param array $field     Field parameters.
         * @param array $args      Arguments of {@see rwmb_meta()} helper.
         *
         * @return mixed
         */
        public static function raw_meta($object_id, $field, $args = [])
        {
        }
        /**
         * Get the field value.
         * Return list of post term objects.
         *
         * @param  array    $field   Field parameters.
         * @param  array    $args    Additional arguments.
         * @param  int|null $post_id Post ID. null for current post. Optional.
         *
         * @return array List of post term objects.
         */
        public static function get_value($field, $args = [], $post_id = \null)
        {
        }
        /**
         * Get terms information.
         *
         * @param array  $field    Field parameters.
         * @param string $term_ids Term IDs, in CSV format.
         * @param array  $args     Additional arguments (for image size).
         *
         * @return array
         */
        public static function terms_info($field, $term_ids, $args)
        {
        }
    }
    /**
     * The text fieldset field, which allows users to enter content for a list of text fields.
     */
    class RWMB_Fieldset_Text_Field extends \RWMB_Input_Field
    {
        public static function admin_enqueue_scripts()
        {
        }
        /**
         * Get field HTML.
         *
         * @param mixed $meta  Meta value.
         * @param array $field Field parameters.
         *
         * @return string
         */
        public static function html($meta, $field)
        {
        }
        protected static function input_description(array $field) : string
        {
        }
        protected static function label_description(array $field) : string
        {
        }
        /**
         * Normalize parameters for field.
         *
         * @param array $field Field parameters.
         *
         * @return array
         */
        public static function normalize($field)
        {
        }
        /**
         * Format value for the helper functions.
         *
         * @param array        $field   Field parameters.
         * @param string|array $value   The field meta value.
         * @param array        $args    Additional arguments. Rarely used. See specific fields for details.
         * @param int|null     $post_id Post ID. null for current post. Optional.
         *
         * @return string
         */
        public static function format_value($field, $value, $args, $post_id)
        {
        }
        /**
         * Format a single value for the helper functions. Sub-fields should overwrite this method if necessary.
         *
         * @param array    $field   Field parameters.
         * @param array    $value   The value.
         * @param array    $args    Additional arguments. Rarely used. See specific fields for details.
         * @param int|null $post_id Post ID. null for current post. Optional.
         *
         * @return string
         */
        public static function format_single_value($field, $value, $args, $post_id)
        {
        }
        /**
         * Since we're using an array of text fields, we need to check if all of them are empty.
         * Otherwise, there is no way to know if the field is empty or not.
         */
        public static function value($new, $old, $post_id, $field)
        {
        }
    }
    /**
     * The sidebar select field.
     */
    class RWMB_Sidebar_Field extends \RWMB_Object_Choice_Field
    {
        public static function normalize($field)
        {
        }
        public static function query($meta, array $field) : array
        {
        }
        /**
         * Format a single value for the helper functions. Sub-fields should overwrite this method if necessary.
         *
         * @param array    $field   Field parameters.
         * @param string   $value   The value.
         * @param array    $args    Additional arguments. Rarely used. See specific fields for details.
         * @param int|null $post_id Post ID. null for current post. Optional.
         *
         * @return string
         */
        public static function format_single_value($field, $value, $args, $post_id)
        {
        }
    }
    /**
     * The image field which uploads images via HTML <input type="file">.
     */
    class RWMB_Image_Field extends \RWMB_File_Field
    {
        public static function admin_enqueue_scripts()
        {
        }
        /**
         * Get HTML for uploaded file.
         *
         * @param int   $file  Attachment (file) ID.
         * @param int   $index File index.
         * @param array $field Field data.
         *
         * @return string
         */
        protected static function file_html($file, $index, $field)
        {
        }
        /**
         * Normalize field settings.
         *
         * @param array $field Field settings.
         *
         * @return array
         */
        public static function normalize($field)
        {
        }
        /**
         * Format a single value for the helper functions. Sub-fields should overwrite this method if necessary.
         *
         * @param array    $field   Field parameters.
         * @param array    $value   The value.
         * @param array    $args    Additional arguments. Rarely used. See specific fields for details.
         * @param int|null $post_id Post ID. null for current post. Optional.
         *
         * @return string
         */
        public static function format_single_value($field, $value, $args, $post_id)
        {
        }
        /**
         * Get uploaded file information.
         *
         * @param int   $file  Attachment image ID (post ID). Required.
         * @param array $args  Array of arguments (for size).
         * @param array $field Field settings.
         *
         * @return array|bool False if file not found. Array of image info on success.
         */
        public static function file_info($file, $args = [], $field = [])
        {
        }
        /**
         * Get image meta data.
         *
         * @param  int $attachment_id Attachment ID.
         * @return array
         */
        protected static function get_image_meta_data($attachment_id)
        {
        }
    }
    /**
     * The button field. Simply displays a HTML button which might be used for JavaScript actions.
     */
    class RWMB_Button_Field extends \RWMB_Field
    {
        /**
         * Get field HTML.
         *
         * @param mixed $meta  Meta value.
         * @param array $field The field parameters.
         * @return string
         */
        public static function html($meta, $field)
        {
        }
        /**
         * Normalize parameters for field.
         *
         * @param array $field The field parameters.
         * @return array
         */
        public static function normalize($field)
        {
        }
        /**
         * Get the attributes for a field.
         *
         * @param array $field The field parameters.
         * @param mixed $value The attribute value.
         * @return array
         */
        public static function get_attributes($field, $value = \null)
        {
        }
    }
    /**
     * The custom HTML field which allows users to output any kind of content to the meta box.
     */
    class RWMB_Custom_Html_Field extends \RWMB_Field
    {
        /**
         * Get field HTML.
         *
         * @param mixed $meta  Meta value.
         * @param array $field Field parameters.
         *
         * @return string
         */
        public static function html($meta, $field)
        {
        }
    }
    /**
     * Storage registry class
     */
    class RWMB_Storage_Registry
    {
        protected $storages = [];
        /**
         * Get storage instance.
         *
         * @param string $class_name Storage class name.
         * @return RWMB_Storage_Interface
         */
        public function get($class_name)
        {
        }
    }
    /**
     * Validation module.
     */
    class RWMB_Validation
    {
        public function __construct()
        {
        }
        /**
         * Output validation rules of each meta box.
         * The rules are outputted in [data-validation] attribute of an hidden <script> and will be converted into JSON by JS.
         */
        public function rules(\RW_Meta_Box $meta_box)
        {
        }
        public function enqueue()
        {
        }
    }
}
namespace MetaBox\Support {
    class Arr
    {
        /**
         * New array map function that accepts more params than just values.
         * Params: array|item, callback, other params.
         */
        public static function map()
        {
        }
        /**
         * Convert a comma separated string to array.
         *
         * @param array|string $csv Comma separated string.
         */
        public static function from_csv($csv) : array
        {
        }
        /**
         * Change array key.
         *
         * @param  array  $array Input array.
         * @param  string $from  From key.
         * @param  string $to    To key.
         */
        public static function change_key(&$array, $from, $to)
        {
        }
        /**
         * Ensure a variable is an array.
         */
        public static function ensure($input) : array
        {
        }
        /**
         * Flatten an array.
         * @link https://stackoverflow.com/a/1320156/371240
         */
        public static function flatten(array $array) : array
        {
        }
        /**
         * Convert flatten collection (with dot notation) to multiple dimensional array
         *
         * @param  array $collection Collection to be flatten.
         * @return array
         */
        public static function unflatten($collection)
        {
        }
        /**
         * Set array element value with dot notation.
         */
        public static function set(&$array, $key, $value)
        {
        }
        /**
         * Get array element value with dot notation.
         */
        public static function get($array, $key, $default = null)
        {
        }
        public static function to_depth($input, $depth)
        {
        }
        public static function depth(array $array)
        {
        }
        public static function remove_first(&$array, $query)
        {
        }
    }
}
namespace {
    /**
     * No longer needed. Keep it here for backward compatibility.
     */
    class RWMB_Helpers_Array extends \MetaBox\Support\Arr
    {
    }
    /**
     * Field helper functions.
     */
    class RWMB_Helpers_Field
    {
        /**
         * Localize a script only once.
         * @link https://github.com/rilwis/meta-box/issues/850
         */
        public static function localize_script_once(string $handle, string $name, array $data)
        {
        }
        public static function add_inline_script_once(string $handle, string $text)
        {
        }
        public static function get_class($field) : string
        {
        }
    }
    /**
     * Helper functions for checking values.
     *
     * @package Meta Box
     */
    /**
     * Helper class for checking values.
     *
     * @package Meta Box
     */
    class RWMB_Helpers_Value
    {
        /**
         * Check if a value is valid for field (not empty "WordPress way"), e.g. equals to empty string or array.
         *
         * @param mixed $value Input value.
         * @return bool
         */
        public static function is_valid_for_field($value)
        {
        }
        /**
         * Check if a value is valid for attribute.
         *
         * @param mixed $value Input value.
         * @return bool
         */
        public static function is_valid_for_attribute($value)
        {
        }
    }
    /**
     * String helper functions.
     */
    class RWMB_Helpers_String
    {
        public static function title_case(string $text) : string
        {
        }
    }
    class RWMB_Core
    {
        public function init()
        {
        }
        public function load_textdomain()
        {
        }
        public function plugin_links(array $links) : array
        {
        }
        public function register_meta_boxes()
        {
        }
        /**
         * WordPress will prevent post data saving if a page template has been selected that does not exist.
         * This is especially a problem when switching themes, and old page templates are in the post data.
         * Unset the page template if the page does not exist to allow the post to save.
         */
        public function fix_page_template(\WP_Post $post)
        {
        }
        /**
         * Get registered meta boxes via a filter.
         * @deprecated No longer used. Keep for backward-compatibility with extensions.
         */
        public static function get_meta_boxes() : array
        {
        }
        public function add_context_hooks()
        {
        }
        public function render_meta_boxes_for_context($post)
        {
        }
    }
    /**
     * Autoload plugin classes.
     */
    class RWMB_Autoloader
    {
        protected $dirs = [];
        /**
         * Adds a base directory for a class name prefix and/or suffix.
         *
         * @param string $dir    A base directory for class files.
         * @param string $prefix The class name prefix.
         * @param string $suffix The class name suffix.
         */
        public function add(string $dir, string $prefix, string $suffix = '')
        {
        }
        public function register()
        {
        }
        public function autoload(string $class_name)
        {
        }
    }
}
namespace Composer\Autoload {
    class ComposerStaticInit5a753014a74b69f166bd9adf666c7be8
    {
        public static $prefixLengthsPsr4 = array('M' => array('MetaBox\\Support\\' => 16, 'MetaBox\\' => 8));
        public static $prefixDirsPsr4 = array('MetaBox\\Support\\' => array(0 => __DIR__ . '/..' . '/meta-box/support'), 'MetaBox\\' => array(0 => __DIR__ . '/../..' . '/src'));
        public static $classMap = array('Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php');
        public static function getInitializer(\Composer\Autoload\ClassLoader $loader)
        {
        }
    }
}
namespace {
    // autoload_real.php @generated by Composer
    class ComposerAutoloaderInit5a753014a74b69f166bd9adf666c7be8
    {
        public static function loadClassLoader($class)
        {
        }
        /**
         * @return \Composer\Autoload\ClassLoader
         */
        public static function getLoader()
        {
        }
    }
}
namespace MetaBox\Support {
    class Data
    {
        public static function get_post_types()
        {
        }
        public static function get_taxonomies()
        {
        }
        public static function get_dashicons()
        {
        }
    }
}
namespace MetaBox\Updater {
    class Settings
    {
        public function __construct(\MetaBox\Updater\Checker $checker, \MetaBox\Updater\Option $option)
        {
        }
        public function init()
        {
        }
        public function enable_menu()
        {
        }
        public function add_settings_page()
        {
        }
        public function render()
        {
        }
        public function save()
        {
        }
    }
    /**
     * This class handles getting and saving the updater option.
     */
    class Option
    {
        /**
         * Get an option.
         *
         * @param ?string $name    Option name. Pass null to return the option array.
         * @param mixed   $default Default value.
         *
         * @return mixed Option value or option array.
         */
        public function get($name = null, $default = null)
        {
        }
        public function get_api_key() : string
        {
        }
        public function get_license_status() : string
        {
        }
        /**
         * Update the option array.
         *
         * @param array $option Option value.
         */
        public function update($option)
        {
        }
        public function is_network_activated() : bool
        {
        }
    }
    class Checker
    {
        public function __construct(\MetaBox\Updater\Option $option)
        {
        }
        public function init()
        {
        }
        public function enable_update()
        {
        }
        public function has_extensions()
        {
        }
        public function get_extensions()
        {
        }
        /**
         * Check plugin for updates
         *
         * @param object $data The plugin update data.
         *
         * @return mixed
         */
        public function check_updates($data)
        {
        }
        /**
         * Get plugin information
         *
         * @param object $data   The plugin update data.
         * @param string $action Request action.
         * @param object $args   Extra parameters.
         *
         * @return mixed
         */
        public function get_info($data, $action, $args)
        {
        }
        public function request($endpoint, $args = [])
        {
        }
    }
    /**
     * This class notifies users to enter or update license key.
     */
    class Notification
    {
        public function __construct(\MetaBox\Updater\Checker $checker, \MetaBox\Updater\Option $option)
        {
        }
        /**
         * Add hooks to show admin notice.
         */
        public function init()
        {
        }
        public function notify()
        {
        }
        /**
         * Show update message on Plugins page.
         *
         * @param  array  $plugin_data Plugin data.
         * @param  object $response    Available plugin update data.
         */
        public function show_update_message($plugin_data, $response)
        {
        }
        public function plugin_links(array $links) : array
        {
        }
    }
}
namespace MetaBox\Integrations {
    class Bricks
    {
        public function __construct()
        {
        }
        public function i18n(array $i18n) : array
        {
        }
    }
    class Oxygen
    {
        public function __construct()
        {
        }
        public function add_metabox_category()
        {
        }
    }
    class Block
    {
        public function __construct()
        {
        }
        public function register_block_category($categories)
        {
        }
    }
    class Elementor
    {
        public function __construct()
        {
        }
        public function add_metabox_category()
        {
        }
    }
}
namespace {
    /**
     * Get post meta.
     *
     * @param string   $key     Meta key. Required.
     * @param array    $args    Array of arguments. Optional.
     * @param int|null $post_id Post ID. null for current post. Optional.
     *
     * @return mixed
     */
    function rwmb_meta($key, $args = [], $post_id = \null)
    {
    }
    /**
     * Set meta value.
     *
     * @param int    $object_id Object ID. Required.
     * @param string $key       Meta key. Required.
     * @param mixed  $value     Meta value. Required.
     * @param array  $args      Array of arguments. Optional.
     */
    function rwmb_set_meta($object_id, $key, $value, $args = [])
    {
    }
    /**
     * Get field settings.
     *
     * @param string   $key       Meta key. Required.
     * @param array    $args      Array of arguments. Optional.
     * @param int|null $object_id Object ID. null for current post. Optional.
     *
     * @return array
     */
    function rwmb_get_field_settings($key, $args = [], $object_id = \null)
    {
    }
    /**
     * Get post meta.
     *
     * @param string   $key     Meta key. Required.
     * @param array    $args    Array of arguments. Optional.
     * @param int|null $post_id Post ID. null for current post. Optional.
     *
     * @return mixed
     */
    function rwmb_meta_legacy($key, $args = [], $post_id = \null)
    {
    }
    /**
     * Get value of custom field.
     * This is used to replace old version of rwmb_meta key.
     *
     * @param  string   $field_id Field ID. Required.
     * @param  array    $args     Additional arguments. Rarely used. See specific fields for details.
     * @param  int|null $post_id  Post ID. null for current post. Optional.
     *
     * @return mixed false if field doesn't exist. Field value otherwise.
     */
    function rwmb_get_value($field_id, $args = [], $post_id = \null)
    {
    }
    /**
     * Display the value of a field
     *
     * @param  string   $field_id Field ID. Required.
     * @param  array    $args     Additional arguments. Rarely used. See specific fields for details.
     * @param  int|null $post_id  Post ID. null for current post. Optional.
     * @param  bool     $echo     Display field meta value? Default `true` which works in almost all cases. We use `false` for  the [rwmb_meta] shortcode.
     *
     * @return string
     */
    function rwmb_the_value($field_id, $args = [], $post_id = \null, $echo = \true)
    {
    }
    /**
     * Get defined meta fields for object.
     *
     * @param int|string $type_or_id  Object ID or post type / taxonomy (for terms) / user (for users).
     * @param string     $object_type Object type. Use post, term.
     *
     * @return array
     */
    function rwmb_get_object_fields($type_or_id, $object_type = 'post')
    {
    }
    /**
     * Check if a meta box supports an object.
     *
     * @param  object $meta_box    Meta Box object.
     * @param  int    $key         Not used.
     * @param  array  $object_data Object data (type and ID).
     */
    function rwmb_check_meta_box_supports(&$meta_box, $key, $object_data)
    {
    }
    /**
     * Get the registry by type.
     * Always return the same instance of the registry.
     *
     * @param string $type Registry type.
     *
     * @return object
     */
    function rwmb_get_registry($type)
    {
    }
    /**
     * Get storage instance.
     *
     * @param string      $object_type Object type. Use post or term.
     * @param RW_Meta_Box $meta_box    Meta box object. Optional.
     * @return RWMB_Storage_Interface
     */
    function rwmb_get_storage($object_type, $meta_box = \null)
    {
    }
    /**
     * Get request object.
     *
     * @return RWMB_Request
     */
    function rwmb_request()
    {
    }
}
