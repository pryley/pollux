=== Pollux ===
Contributors: geminilabs, pryley
Donate link: https://www.paypal.me/pryley
Tags: pollux, castor, taxonomies, custom taxonomies, post types, custom post types, settings, settings page, meta-box, yaml, scaffolding
Requires at least: 4.7.0
Tested up to: 4.7.4
Stable tag: 1.0.3
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Pollux allows you to easily add custom Post Types, Taxonomies, Meta Boxes, Global Settings, Archive Page meta, and more...all within mere minutes.

== Description ==

Pollux allows you to easily add custom Post Types, Taxonomies, Meta Boxes, Global Settings, Archive Page meta, and more...all within mere minutes.

Instead of drag-and-drop, all configuration is added using simple [YAML](https://learn-the-web.algonquindesign.ca/topics/markdown-yaml-cheat-sheet/#yaml) markup on the Pollux settings page.

Pollux uses the [Meta Box](https://wordpress.org/plugins/meta-box/) plugin to add custom meta-boxes. If the Meta Box plugin is not installed, Pollux will prompt you to do so on the Pollux settings page.

Please see the [plugin wiki](https://github.com/geminilabs/pollux/wiki) for complete examples on how to use YAML markup in the plugin. Once you've used it, you'll wonder how you ever managed without it!

Pollux was made to complement themes built on the [Castor framework](https://github.com/geminilabs/castor-framework) (i.e. [Castor](https://github.com/geminilabs/castor)), but it can be used with any theme.

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

= How do I add Meta Boxes? =

Pollux uses the [Meta Box](https://wordpress.org/plugins/meta-box/) plugin to add custom meta-boxes. Adding meta-boxes and meta-box fields is [the same as you would](https://github.com/rilwis/meta-box/blob/master/demo/demo.php) with Meta Box, except instead of registering meta-box arrays with the 'rwmb_meta_boxes' filter hook, you instead enter the arrays as YAML markup in the Pollux Settings.

Please see the [Adding Meta Boxes](https://github.com/geminilabs/pollux/wiki/Adding-Meta-Boxes) page in the Pollux wiki for detailed information on how to add Meta Boxes with Pollux.

= How do I make conditional Meta Boxes? =

Please see the [Meta Box Conditions](https://github.com/geminilabs/pollux/wiki/Meta-Box-Conditions) page in the Pollux wiki.

= How do I add Custom Post Types? =

Please see the [Adding Post Types](https://github.com/geminilabs/pollux/wiki/Adding-Post-Types) page in the Pollux wiki.

= How do I add Custom Post Type Columns? =

Please see the [Adding Post Type Columns](https://github.com/geminilabs/pollux/wiki/Adding-Post-Type-Columns) page in the Pollux wiki.

= How do I add Custom Taxonomies? =

Please see the [Adding Taxonomies](https://github.com/geminilabs/pollux/wiki/Adding-Taxonomies) page in the Pollux wiki.

= How do set my own defaults? =

Please see the [How to create your own defaults](https://github.com/geminilabs/pollux/wiki/How-to-create-your-own-defaults) page in the Pollux wiki.

== Screenshots ==

1. A view of the Pollux settings "General" tab

2. A view of the Pollux settings "Meta Boxes" tab

3. A view of the Pollux settings "Post Types" tab

4. A view of the Pollux settings "Taxonomies" tab

5. A view of the "Post Archive" page

6. A view of the "Site Settings" page

== Changelog ==

= 1.0.3 (2017-05-19) =

- Fix post_type detection on post-new.php
- Recompile config on reset

= 1.0.2 (2017-05-16) =

- Show admin notices for YAML parse errors
- Replaced demo *.yml files in favour of github wiki pages

= 1.0.1 (2017-05-15) =

- [removed] Disabled raw strings until they can be parsed properly without using eval()
- Fix headings and divider fields from incorrectly showing in the instructions meta-box
- Fix negative conditions

= 1.0.0 (2017-05-13) =

- Initial plugin release
