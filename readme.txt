=== Pollux ===
Contributors: pryley, geminilabs
Donate link: https://www.paypal.me/pryley
Tags: pollux, taxonomies, custom taxonomies, post types, custom post types, settings, meta-box, yaml, scaffolding, castor
Requires at least: 4.7.0
Requires PHP: 5.6
Tested up to: 5.6
Stable tag: 1.5.2
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Pollux allows you to easily add custom Post Types, Taxonomies, Meta Boxes, Global Settings, Archive Page meta, and more...all within mere minutes.

== Description ==

Pollux allows you to easily add custom Post Types, Taxonomies, Meta Boxes, Global Settings, Archive Page meta, and more...all within mere minutes.

Instead of drag-and-drop, all configuration is added using simple [YAML](https://learn-the-web.algonquindesign.ca/topics/markdown-yaml-cheat-sheet/#yaml) markup on the Pollux settings page.

Pollux uses the [Meta Box](https://wordpress.org/plugins/meta-box/) plugin to add custom meta-boxes. If the Meta Box plugin is not installed, Pollux will prompt you to do so on the Pollux settings page.

Please see the [plugin wiki](https://github.com/pryley/pollux/wiki) for complete examples on how to use YAML markup in the plugin. Once you've used it, you'll wonder how you ever managed without it!

Pollux was made to complement themes built on the [Castor framework](https://github.com/pryley/castor-framework) (i.e. [Castor](https://github.com/pryley/castor)), but it can be used with any theme.

== Installation ==

= Minimum plugin requirements =

* PHP 5.6
* WordPress 4.7.0

= Automatic installation =

Log in to your WordPress dashboard, navigate to the Plugins menu and click "Add New".

In the search field type "Pollux" and click Search Plugins. Once you have found the plugin you can view details about it such as the point release, rating and description. You can install it by simply clicking "Install Now".

= Manual installation =

Download the Pollux plugin and uploading it to your server via your favorite FTP application. The WordPress codex contains [instructions on how to do this here](https://codex.wordpress.org/Managing_Plugins#Manual_Plugin_Installation).

== Frequently Asked Questions ==

= How do I...? =

Pollux includes comprehensive documentation. If your answer is not answered in the documentation, please open a new topic on the [support forum](http://wordpress.org/support/plugin/pollux/).

== Screenshots ==

1. A view of the Pollux settings "General" tab

2. A view of the Pollux settings "Taxonomies" tab

3. A view of the Pollux settings "Post Types" tab

4. A view of the Pollux settings "Meta Boxes" tab

5. Pollux includes complete documentation

5. A view of the "Post Archive" page

6. A view of the "Site Settings" page

== Changelog ==

= 1.5.2 (2023-03-10) =

- Fixed compatibility with Meta Box

= 1.5.1 (2020-12-18) =

- PHP 8 support
- WordPress 5.6 support

= 1.5.0 (2019-08-07) =
- Changed minimum required version of Meta Box to v5.0.1
- Fixed compatibility with Meta Box v5

= 1.4.0 (2019-07-02) =
- Added syntax highlighting to documentation examples
- Changed minimum required version of Meta Box to v4.17
- Fixed compatibility with Meta Box

= 1.3.1 (2019-01-28) =
- Updated plugin URL

= 1.3.0 (2018-09-24) =

- Added ability to set required value of a meta box field dependancy (see documentation)

= 1.2.0 (2018-09-23) =

- Added ability to use arrays in condition values
- Added plugin documentation
- Fixed compatibility with some Meta-box addons

= 1.1.4 (2018-07-04) =

- Fixed possible activation check conflict

= 1.1.3 (2018-02-19) =

- Fixed plugin deactivation on unsupported systems

= 1.1.2 (2017-10-04) =

- Fixed archive settings

= 1.1.1 (2017-10-04) =

- Added compatibility with Give WP plugin
- Fixed plugin localization

= 1.1.0 (2017-08-12) =

- [feature] Load pollux-hooks.php if it exists
- An unknown column value is now '&mdash;'
- Column thumbnail image is now the builtin 'thumbnail' size
- Restricted column thumbnail max height/width to 64px
- Show permalink in archive pages
- Fixed a "SiteMeta" helper bug
- Fixed PostMeta::get() to allow `(array) get_query_var('post_type')` as the group.
- Fixed Settings meta-box fields that have multiple values (i.e. checkboxes)
- Fixed taxonomy meta-box fields from incorrectly showing in the instructions meta-box

= 1.0.3 (2017-05-19) =

- Fixed post_type detection on post-new.php
- Recompile config on reset

= 1.0.2 (2017-05-16) =

- Show admin notices for YAML parse errors
- Replaced demo *.yml files in favour of github wiki pages

= 1.0.1 (2017-05-15) =

- [removed] Disabled raw strings until they can be parsed properly without using eval()
- Fixed headings and divider fields from incorrectly showing in the instructions meta-box
- Fixed negative conditions

= 1.0.0 (2017-05-13) =

- Initial plugin release
