<?php defined( 'WPINC' ) || die; ?>

<div class="wrap">
	<h1><?= $heading; ?></h1>

	<h2 class="pollux-tabs nav-tab-wrapper">
		<a class="nav-tab" href="#general"><?= __( 'General', 'pollux' ); ?></a>
		<a class="nav-tab" href="#taxonomies"><?= __( 'Taxonomies', 'pollux' ); ?></a>
		<a class="nav-tab" href="#post_types"><?= __( 'Post Types', 'pollux' ); ?></a>
		<a class="nav-tab" href="#metaboxes"><?= __( 'Meta Boxes', 'pollux' ); ?></a>
		<a class="nav-tab" href="#documentation"><?= __( 'Documentation', 'pollux' ); ?></a>
	</h2>

	<form class="pollux-config" method="post" action="options.php" enctype="multipart/form-data">
		<?php settings_fields( $id ); ?>

		<input type="hidden" id="pollux-active-tab" name="_active_tab">

		<table class="form-table" id="general">
			<tbody>
				<tr>
					<td>
						<fieldset>
							<label for="disable_posts">
								<input type="checkbox" id="disable_posts" name="pollux_config[disable_posts]" value="1" <?php checked( $config->disable_posts ); ?>>
								<?= __( 'Disable Posts', 'pollux' ); ?>
							</label>
							<br>
							<label for="enable_archive_page">
								<input type="checkbox" id="enable_archive_page" name="pollux_config[enable_archive_page]" value="1" <?php checked( $config->enable_archive_page ); ?>>
								<?= __( 'Enable Archive Page', 'pollux' ); ?>
							</label>
							<br>
							<label for="remove_dashboard_widgets">
								<input type="checkbox" id="remove_dashboard_widgets" name="pollux_config[remove_dashboard_widgets]" value="1" <?php checked( $config->remove_dashboard_widgets ); ?>>
								<?= __( 'Remove Dashboard Widgets', 'pollux' ); ?>
							</label>
							<br>
							<label for="remove_wordpress_footer">
								<input type="checkbox" id="remove_wordpress_footer" name="pollux_config[remove_wordpress_footer]" value="1" <?php checked( $config->remove_wordpress_footer ); ?>>
								<?= __( 'Remove the WordPress Admin Footer', 'pollux' ); ?>
							</label>
							<br>
							<label for="remove_wordpress_menu">
								<input type="checkbox" id="remove_wordpress_menu" name="pollux_config[remove_wordpress_menu]" value="1" <?php checked( $config->remove_wordpress_menu ); ?>>
								<?= __( 'Remove the WordPress Menu From the Admin Bar', 'pollux' ); ?>
							</label>
						</fieldset>
					</td>
				</tr>
			</tbody>
		</table>

		<table class="form-table" id="taxonomies">
			<tbody>
				<tr>
					<th scope="row">
						<label for="pollux_taxonomies"><?= __( 'Taxonomies', 'pollux' ); ?></label>
					</th>
					<td>
						<textarea id="pollux_taxonomies" name="pollux_config[taxonomies]" rows="10" cols="50" class="large-text code pollux-code" placeholder=""><?= $config->taxonomies; ?></textarea>
						<p class="description"></p>
					</td>
				</tr>
			</tbody>
		</table>

		<table class="form-table" id="post_types">
			<tbody>
				<tr>
					<th scope="row">
						<label for="pollux_post_types"><?= __( 'Post Types', 'pollux' ); ?></label>
					</th>
					<td>
						<textarea id="pollux_post_types" name="pollux_config[post_types]" rows="10" cols="50" class="large-text code pollux-code" placeholder=""><?= $config->post_types; ?></textarea>
						<p class="description"></p>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="pollux_columns"><?= __( 'Post Type Columns', 'pollux' ); ?></label>
					</th>
					<td>
						<textarea id="pollux_columns" name="pollux_config[columns]" rows="10" cols="50" class="large-text code pollux-code" placeholder=""><?= $config->columns; ?></textarea>
						<p class="description"></p>
					</td>
				</tr>
			</tbody>
		</table>

		<table class="form-table" id="metaboxes">
			<tbody>
				<tr>
					<th scope="row">
						<label for="pollux_archives"><?= __( 'Archive Meta Boxes', 'pollux' ); ?></label>
					</th>
					<td>
						<?php
							$readonly = !$config->enable_archive_page || !$has_meta_box
								? ' readonly'
								: '';

							$data_disabled = !$has_meta_box
								? __( 'This field requires the Meta Box plugin.', 'pollux' )
								: __( 'Archive Page is not enabled.', 'pollux' );
						?>
						<textarea id="pollux_archives" name="pollux_config[archives]" rows="10" cols="50" class="large-text code pollux-code" placeholder="" data-disabled="<?= $data_disabled; ?>"<?= $readonly; ?>><?= $config->archives; ?></textarea>
						<p class="description"></p>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="pollux_metaboxes"><?= __( 'Post Type Meta Boxes', 'pollux' ); ?></label>
					</th>
					<td>
						<textarea id="pollux_metaboxes" name="pollux_config[metaboxes]" rows="10" cols="50" class="large-text code pollux-code" placeholder="" data-disabled="<?= __( 'This field requires the Meta Box plugin.', 'pollux' ); ?>" <?= !$has_meta_box ? 'readonly' : ''; ?>><?= $config->metaboxes; ?></textarea>
						<p class="description"></p>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="pollux_settings"><?= __( 'Site Settings Meta Boxes', 'pollux' ); ?></label>
					</th>
					<td>
						<textarea id="pollux_settings" name="pollux_config[settings]" rows="10" cols="50" class="large-text code pollux-code" placeholder="" data-disabled="<?= __( 'This field requires the Meta Box plugin.', 'pollux' ); ?>" <?= !$has_meta_box ? 'readonly' : ''; ?>><?= $config->settings; ?></textarea>
						<p class="description"></p>
					</td>
				</tr>
			</tbody>
		</table>

		<div class="form-table" id="documentation">
			<div class="pollux-card postbox">
				<div class="pollux-card-header">
					<h3>Adding Post Types</h3>
					<button type="button" class="handlediv" aria-expanded="true">
						<span class="screen-reader-text"><?= __( 'Toggle documentation panel', 'pollux' ); ?></span>
						<span class="toggle-indicator" aria-hidden="true"></span>
					</button>
				</div>
				<div class="inside">
					<h3>Example configuration for the "Post Types" field</h3>
					<p>You may copy and paste the following into the <code>Post Types</code> field in the Pollux settings.</p>
					<pre><code class="pollux-code-example cm-s-pollux">gallery:

  # [required] This is the Singular name of the Post Type, used to generate correct labels
  single: Gallery

  # [required] This is the Plural name of the Post Type, used to generate correct labels
  plural: Galleries

  # [optional] By default, the menu name label is the same as the "plural" value.
  menu_name: My Galleries

  # [optional] These are the columns you want to show in the Post Type admin page table,
  # you must also add their corresponding labels in the "Post Type Columns".
  columns:
    - title
    - slug
    - media
    - thumbnail
    - date

  # [optional] Default value is false
  # https://codex.wordpress.org/Function_Reference/register_post_type#has_archive
  has_archive: false

  # [optional] Default value is true
  # https://codex.wordpress.org/Function_Reference/register_post_type#map_meta_cap
  map_meta_cap: true

  # [optional] Defaults to the posts icon.
  # See all available Built-in icons here: https://developer.wordpress.org/resource/dashicons/
  # https://codex.wordpress.org/Function_Reference/register_post_type#menu_icon
  menu_icon: dashicons-images-alt2

  # [optional] Default value is 20 (below the Pages menu item)
  # https://codex.wordpress.org/Function_Reference/register_post_type#menu_position
  menu_position: 20

  # [optional] Default value is true
  # https://codex.wordpress.org/Function_Reference/register_post_type#public
  public: false

  # [optional] Default value is true
  # https://codex.wordpress.org/Function_Reference/register_post_type#show_in_menu
  show_in_menu: true

  # [optional] Default value is true
  # https://codex.wordpress.org/Function_Reference/register_post_type#show_ui
  show_ui: true

  # [optional] Default values are title and editor
  # https://codex.wordpress.org/Function_Reference/register_post_type#supports
  supports:
    - title
    - editor
    - thumbnail</code></pre>
				<p>For all other fields and their defaults, please see the available parameters at the official <a href="https://codex.wordpress.org/Function_Reference/register_post_type">WordPress Codex</a>.</p>
				</div>
			</div>
			<div class="pollux-card postbox">
				<div class="pollux-card-header">
					<h3>Adding Post Type Columns</h3>
					<button type="button" class="handlediv" aria-expanded="true">
						<span class="screen-reader-text"><?= __( 'Toggle documentation panel', 'pollux' ); ?></span>
						<span class="toggle-indicator" aria-hidden="true"></span>
					</button>
				</div>
				<div class="inside">
					<p>The <code>Post Type Columns</code> field in the Pollux settings contains the column labels that are shown in the Post Type admin page table.</p>
					<p>Built-in columns include:</p>
					<ul>
						<li>author</li>
						<li>categories</li>
						<li>comments</li>
						<li>date</li>
						<li>media</li>
						<li>slug</li>
						<li>thumbnail</li>
						<li>title</li>
					</ul>
					<p>The <code>slug</code> column displays the slug of the post_type.</p>
					<p>The <code>media</code> column displays a count of images uploaded in a meta-box with an ID of "media".</p>
					<p>The <code>thumbnail</code> column display a thumbnail of the featured image if set.</p>
					<p>All other built-in columns should be self-explanatory.</p>
					<h3>How to add custom columns</h3>
					<ol>
						<li>First you must add the column to the Post Type <code>columns</code> key in the "Post Types" field:</li>
					</ol>
					<pre><code class="pollux-code-example cm-s-pollux">example_cpt:
  single: Example
  plural: Examples
  columns:
    - title
    - my_awesome_column
    - date</code></pre>
					<ol start="2">
						<li>Next you must add the custom column label in the "Post Type Columns" field:</li>
					</ol>
					<pre><code class="pollux-code-example cm-s-pollux">my_awesome_column: Awesome!</code></pre>
					<ol start="3">
						<li>Finally, you must create a special file called <code>pollux-hooks.php</code> and place it either in your root web directory, or in the wp-content directory. Once you have created the file, add a custom filter in it to populate the custom column with a value:</li>
					</ol>
					<pre><code class="pollux-code-example cm-s-pollux">/**
 * @return string|int
 */
add_filter( 'pollux/post_type/column/my_awesome_column', function( $value, $post_id ) {
	return PostMeta::get( 'awesome_value', [
		'fallback' => $value,
	]);
});</code></pre>
				</div>
			</div>
			<div class="pollux-card postbox">
				<div class="pollux-card-header">
					<h3>Adding Taxonomies</h3>
					<button type="button" class="handlediv" aria-expanded="true">
						<span class="screen-reader-text"><?= __( 'Toggle documentation panel', 'pollux' ); ?></span>
						<span class="toggle-indicator" aria-hidden="true"></span>
					</button>
				</div>
				<div class="inside">
					<h3>Example configuration for the "Taxonomies" field</h3>
					<p>You may copy and paste the following into the <code>Taxonomies</code> field in the Pollux settings.</p>
					<pre><code class="pollux-code-example cm-s-pollux">project_type:

  # [required] The names of the post types to associate the taxonomy with
  post_types:
    - gallery
    - post

  # [required] This is the Singular name of the Post Type, used to generate correct labels
  single: Project

  # [required] This is the Plural name of the Post Type, used to generate correct labels
  plural: Projects

  # [optional] By default, the menu name label is the same as the "plural" value.
  menu_name: My Projects

  # [optional] Default value is true
  # https://codex.wordpress.org/Function_Reference/register_taxonomy#hierarchical
  hierarchical: true

  # [optional] Default value is true
  # https://codex.wordpress.org/Function_Reference/register_taxonomy#show_admin_column
  show_admin_column: true</code></pre>
					<p>For all other fields and their defaults, please see the available parameters at the official <a href="https://codex.wordpress.org/Function_Reference/register_taxonomy">WordPress Codex</a>.</p>
				</div>
			</div>
			<div class="pollux-card postbox">
				<div class="pollux-card-header">
					<h3>Adding Meta Boxes</h3>
					<button type="button" class="handlediv" aria-expanded="true">
						<span class="screen-reader-text"><?= __( 'Toggle documentation panel', 'pollux' ); ?></span>
						<span class="toggle-indicator" aria-hidden="true"></span>
					</button>
				</div>
				<div class="inside">
					<p>The way you enter Meta-boxes in Pollux is almost identical to the meta-box arrays you create with the Meta Box plugin except for the following few changes which you must be aware of:</p>
					<ol>
						<li>
							<p>In the Meta Box plugin you enter an "ID" key inside the meta-box arrays and in each meta-box field array. In Pollux, you enter the "ID" as the array key for each meta-box and meta-box field.</p>
						</li>
						<li>
							<p>Unlike the Meta Box plugin, all meta-boxes must have a unique ID, and all meta-box fields within a meta-box must also have a unique ID.</p>
						</li>
						<li>
							<p>Please see the <strong>Meta Box Conditions</strong> section for information on how to use the "condition" and "depends" keys</p>
						</li>
					</ol>
					<p>Please see the <strong>Getting Started</strong> section for more information about available options for both meta-boxes and meta-box fields.</p>
					<h3>Example configuration:</h3>
					<p>You may copy and paste the following into the <code>Post Type Meta Boxes</code> field in the Pollux settings.</p>
					<pre><code class="pollux-code-example cm-s-pollux"># This key is the meta-box ID
front_page:

  # This key defines which post types to attach this meta-box to.
  # Value can either be a single post type, or a list of post types.
  post_types: page

  # This key is the Meta Box title
  title: Front Page Options

  # This key defines one or more Meta Box conditions
  condition:
    is_front_page: true

  # This key defines the meta-box fields
  fields:

    # This key is the meta-box field ID
    media:

      # This key is the meta-box field type.
      # https://metabox.io/docs/define-fields/#section-list-of-supported-field-type
      type: image_advanced

gallery_options:
  post_types: page
  title: Gallery Options
  condition:
    is_page_template: template-gallery.php
  fields:
    gallery:
      type: post
      name: Gallery
      placeholder: Select a gallery
      field_type: select
      post_type: gallery
    images_per_page:
      # This key sets the visibility depending on whether or not another field has a value
      depends: pagination
      type: number
      name: Images Per Page
      size: 4
    lazyload:
      depends: gallery
      type: checkbox
      name: Lazyload Images?
    pagination:
      depends: gallery
      type: checkbox
      name: Use Pagination?
gallery_media:
  post_types:
    - gallery
  title: Gallery Media
  fields:
    media:
      type: image_advanced
</code></pre>
				</div>
			</div>
			<div class="pollux-card postbox">
				<div class="pollux-card-header">
					<h3>Meta Box Conditions</h3>
					<button type="button" class="handlediv" aria-expanded="true">
						<span class="screen-reader-text"><?= __( 'Toggle documentation panel', 'pollux' ); ?></span>
						<span class="toggle-indicator" aria-hidden="true"></span>
					</button>
				</div>
				<div class="inside">
					<p>Both meta-boxes, and specific meta-box fields can be set to show only if a specific condition is met.</p>
					<p>The <code>condition</code> key can be added to both a meta-box and a meta-box field.</p>
					<pre><code class="pollux-code-example cm-s-pollux">condition:

  # This condition checks if a PHP Class has been defined.
  # Value to check must be the name of the PHP Class, including the namespace if any.
  class_exists: GeminiLabs/Pollux/Application

  # This condition checks if a PHP constant exists.
  # Value to check must be the name of the PHP constant.
  defined: WP_ENV

  # This condition checks if a PHP function has been defined.
  # Value to check must be the name of the PHP function you are checking for.
  function_exists: get_current_screen

  # This condition checks if a WordPress plugin is active.
  # This is similar to "is_plugin_active()" except it also checks for mu-plugins.
  # Value to check must be the name of the plugin sub-directory/file.
  is_plugin_active: pollux/pollux.php

  # This condition checks if a WordPress plugin is inactive or not installed.
  # Value to check must be the name of the plugin sub-directory/file.
  is_plugin_inactive: pollux/pollux.php

  # The following conditions only apply in meta-boxes assigned to the "page" post_type:

  # This condition checks if the current page has been assigned to the Front page in the WordPress Reading Settings.
  # Value to check must be either true or false .
  is_front_page: true

  # This condition checks if the current page has been assigned to the Posts page in the WordPress Reading Settings.
  # Value to check must be either true or false.
  is_home: true

  # This condition checks if the current page has been assigned a page template.
  # Value to check must be the name of the theme template PHP file.
  is_page_template: template-contact.php</code></pre>
					<p>You can also make meta-box field visibility dependant on whether or not another field has a value.</p>
					<p>The <code>depends</code> key can only be added to a meta-box field, this key checks if the referenced field has a value (and optionally checks for a specific value).</p>
					<pre><code class="pollux-code-example cm-s-pollux"># This checks that another field has a value set.
depends: field_key_to_check

# If you also need to check that the key has a specific value, append that value after a "|" (pipe) character
depends: field_key_to_check|field_value</code></pre>
					<p>Here is an example meta-box entry that demonstrates the use of the <code>depends</code> option:</p>
					<pre><code class="pollux-code-example cm-s-pollux">seo_settings:
  title: SEO Settings
  fields:
    seo_enabled:
      type: checkbox
      name: Enable SEO?
    seo_title:
      depends: seo_enabled
      type: text
      name: SEO Title
    seo_keywords:
      depends: seo_enabled
      type: text
      name: SEO Keywords
    seo_description:
      depends: seo_enabled
      type: textarea
      name: SEO Description</code></pre>
				</div>
			</div>
			<div class="pollux-card postbox">
				<div class="pollux-card-header">
					<h3>How to Create Your Own Defaults</h3>
					<button type="button" class="handlediv" aria-expanded="true">
						<span class="screen-reader-text"><?= __( 'Toggle documentation panel', 'pollux' ); ?></span>
						<span class="toggle-indicator" aria-hidden="true"></span>
					</button>
				</div>
				<div class="inside">
					<p>This optional file contains all configuration settings in a single file. You may edit it and place in either one directory above your webroot (recommended), or in the same location as your <code>wp-config.php</code>. If you choose the latter, make sure you add a rule to your <code>.htaccess</code> to disable access to the <code>pollux.yml</code> file from the browser.</p>
					<p>If the <code>pollux.yml</code> file exists, Pollux with use it as the new plugin defaults.</p>
					<p>You may also add a <code>disable_config</code> option to completely disable the Pollux Settings admin page UI.</p>
					<h3>Example <code>pollux.yml</code> file</h3>
					<pre><code class="pollux-code-example cm-s-pollux">disable_config: false
disable_posts: false
enable_archive_page: false
remove_dashboard_widgets: false
remove_wordpress_footer: false
remove_wordpress_menu: false

archives: []

columns: []

metaboxes:
  contact_options:
    post_types: page
    title: Contact Options
    condition:
      is_page_template: template-contact.php
    fields:
      address:
        type: text
        name: Address
        std: Hill of Tara, Meath, Ireland
        attributes:
          class: large-text
      location:
        type: map
        name: Location
        address_field: address
        std: 53.5788114, -6.613843, 15
        api_key: google_maps
      shortcode:
        type: text
        name: Custom Form
        placeholder: Enter a custom Contact Form shortcode here
        desc: Replace the default contact form by entering a custom contact form shortcode here.
        attributes:
          class: large-text
  gallery_options:
    post_types: page
    title: Gallery Options
    condition:
      is_page_template: template-gallery.php
    fields:
      gallery:
        type: post
        name: Gallery
        placeholder: Select a gallery
        field_type: select
        post_type: gallery
      images_per_page:
        type: number
        name: Images Per Page
        size: 4
      lazyload:
        type: checkbox
        name: Lazyload Images?
      pagination:
        type: checkbox
        name: Use Pagination?
  gallery_media:
    post_types: gallery
    title: Gallery Media
    fields:
      media:
        type: image_advanced

post_types:
  gallery:
    single: Gallery
    plural: Galleries
    menu_icon: dashicons-images-alt2
    supports:
      - title
    columns:
      - title
      - slug
      - media
      - date
    public: false

taxonomies:
  project_type:
    post_types: gallery
    single: Project
    plural: Projects

settings:
  global:
    title: SEO Settings
    fields:
      seo_enabled:
        type: checkbox
        name: Enable SEO?
      seo_title:
        type: text
        name: SEO Title (prefix)
        depends: seo_enabled
        attributes:
          class: large-text
      seo_description:
        type: textarea
        name: SEO Description
        depends: seo_enabled
        attributes:
          class: large-text
      seo_keywords:
        type: text
        name: SEO Keywords
        depends: seo_enabled
        attributes:
          class: large-text
      robots:
        name: Robots
        desc: &lt;a href="http://www.robotstxt.org/meta.html" target="_blank"&gt;What is this?&lt;/a&gt;
        type: select
        default: index, follow
        options:
          - index, follow
          - index, nofollow
          - noindex, follow
          - noindex, nofollow
  contact:
    title: Contact Details
    fields:
      email:
        type: text
        name: Email
  services:
    title: 3rd Party Services
    fields:
      google_analytics:
        type: text
        name: Google Analytics ID
        desc: &lt;a href="https://www.google.com/analytics/" target="_blank"&gt;What is this?&lt;/a&gt;
        placeholder: UA-XXXXXXXX-Y
      google_maps:
        type: text
        name: Google Maps API key
        desc: &lt;a href="https://developers.google.com/maps/documentation/javascript/get-api-key" target="_blank"&gt;What is this?&lt;/a&gt;
    validation:
      rules:
        google_analytics:
          required: true
          minlength: 5
      messages:
        google_analytics:
          required: A Google Analytics ID is required</code></pre>
				</div>
			</div>
			<div class="pollux-card postbox">
				<div class="pollux-card-header">
					<h3>Meta Box plugin: Using the "getting started" demo example</h3>
					<button type="button" class="handlediv" aria-expanded="true">
						<span class="screen-reader-text"><?= __( 'Toggle documentation panel', 'pollux' ); ?></span>
						<span class="toggle-indicator" aria-hidden="true"></span>
					</button>
				</div>
				<div class="inside">
					<p>The following contains all of the meta box fields found in the <a href="https://metabox.io/docs/getting-started/" rel="nofollow">Meta Box plugin getting started</a> demo file.</p>
					<p>Please see the <strong>Adding Meta Boxes</strong> section for detailed information on adding meta-boxes.</p>
					<p>To use, copy and paste into the <code>Post Type Meta Boxes</code> field in the Pollux plugin settings.</p>
					<pre><code class="pollux-code-example cm-s-pollux">standard:
  title: Standard Fields
  post_types:
    - post
    - page
  context: normal
  priority: high
  autosave: true
  fields:
    text:
      name: Text
      label_description: Some description
      desc: Text description
      type: text
      std: Default text value
      clone: true
    checkbox:
      name: Checkbox
      type: checkbox
      std: 1
    radio:
      name: Radio
      type: radio
      options:
        value1: Label1
        value2: Label2
    select:
      name: Select
      type: select
      options:
        value1: Label1
        value2: Label2
      multiple: false
      std: value2
      placeholder: Select an Item
    hidden:
      type: hidden
      std: Hidden value
    password:
      name: Password
      type: password
    textarea:
      name: Textarea
      desc: Textarea description
      type: textarea
      cols: 20
      rows: 3
  validation:
    rules:
      password:
        required: true
        minlength: 7
    messages:
      password:
        required: Password is required
        minlength: Password must be at least 7 characters
advanced:
  title: Advanced Fields
  post_types:
    - post
    - page
  fields:
    heading1:
      type: heading
      name: Heading
      desc: Optional description for this heading
    slider:
      name: Slider
      type: slider
      prefix: $
      suffix: ' USD'
      js_options:
        min: 10
        max: 255
        step: 5
      std: 155
    number:
      name: Number
      type: number
      min: 0
      step: 5
    date:
      name: Date picker
      type: date
      js_options:
        appendText: (yyyy-mm-dd)
        dateFormat: yy-mm-dd
        changeMonth: true
        changeYear: true
        showButtonPanel: true
    datetime:
      name: Datetime picker
      type: datetime
      js_options:
        stepMinute: 15
        showTimepicker: true
    time:
      name: Time picker
      type: time
      js_options:
        stepMinute: 5
        showSecond: true
        stepSecond: 10
    color:
      name: Color picker
      type: color
    checkbox_list:
      name: Checkbox list
      type: checkbox_list
      options:
        value1: Label1
        value2: Label2
    autocomplete:
      name: Autocomplete
      type: autocomplete
      options:
        value1: Label1
        value2: Label2
      size: 30
      clone: false
    email:
      name: Email
      desc: Email description
      type: email
      std: name@email.com
    range:
      name: Range
      desc: Range description
      type: range
      min: 0
      max: 100
      step: 5
      std: 0
    url:
      name: URL
      desc: URL description
      type: url
      std: http://google.com
    oembed:
      name: oEmbed
      desc: oEmbed description
      type: oembed
    select_advanced:
      name: Select
      type: select_advanced
      options:
        value1: Label1
        value2: Label2
      multiple: false
      placeholder: Select an Item
    taxonomy:
      name: Taxonomy
      type: taxonomy
      taxonomy: category
      field_type: checkbox_list
      query_args: {  }
    taxonomy_advanced:
      name: Taxonomy Advanced
      type: taxonomy_advanced
      clone: true
      taxonomy: category
      field_type: select_tree
      query_args: {  }
    pages:
      name: Posts (Pages)
      type: post
      post_type: page
      field_type: select_advanced
      placeholder: Select an Item
      query_args:
        post_status: publish
        posts_per_page: -1
    wysiwyg:
      name: WYSIWYG / Rich Text Editor
      type: wysiwyg
      raw: false
      std: WYSIWYG default value
      options:
        textarea_rows: 4
        teeny: true
        media_buttons: false
    divider1:
      type: divider
    file:
      name: File Upload
      type: file
    file_advanced:
      name: File Advanced Upload
      type: file_advanced
      max_file_uploads: 4
      mime_type: application,audio,video
    imgadv:
      name: Image Advanced Upload (Recommended)
      type: image_advanced
      force_delete: false
      max_file_uploads: 2
      max_status: true
    image_upload:
      name: Image Upload
      type: image_upload
      force_delete: false
      max_file_uploads: 2
      max_status: true
    plupload:
      name: Plupload Image (Alias of Image Upload)
      type: plupload_image
      force_delete: false
      max_file_uploads: 2
      max_status: true
    thickbox:
      name: Thickbox Image Upload
      type: thickbox_image
      force_delete: false
    image:
      name: Image Upload
      type: image
      force_delete: false
      max_file_uploads: 2
    video:
      name: Video
      type: video
      max_file_uploads: 3
      force_delete: false
      max_status: true
    button:
      type: button
      name: My button
    text_list:
      name: Text List
      type: text_list
      options:
        Placeholder 1: Label 1
        Placeholder 2: Label 2
        Placeholder 3: Label 3</code></pre>
				</div>
			</div>
		</div>
		<p class="submit">
			<input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes">
			<a href="<?= $reset_url; ?>" id="reset" class="button pollux-reset"><?= __( 'Reset to Defaults', 'pollux' ); ?></a>
		</p>
	</form>
</div>
