=== Pollux ===
Contributors: geminilabs, pryley
Donate link: https://www.paypal.me/pryley
Tags: pollux, castor, taxonomies, custom taxonomies, post types, custom post types, settings, settings page, meta-box, yaml, scaffolding
Requires at least: 4.7.0
Tested up to: 4.7.4
Stable tag: 1.0.0
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Pollux is a theme-agnostic scaffolding plugin for the advanced WordPress user. It allows you to easily add custom Post Types, Taxonomies, Meta Boxes, Global Settings, and more.

== Description ==

This plugin is geared towards the more advanced WordPress user. Instead of drag-and-drop, all configuration is written using simple [YAML](https://learn-the-web.algonquindesign.ca/topics/markdown-yaml-cheat-sheet/#yaml) markup.

Pollux uses the [Meta Box](https://wordpress.org/plugins/meta-box/) plugin to add custom meta-boxes. If the Meta Box plugin is not installed, Pollux will prompt you to do so on the Pollux settings page.

Please see the `demo` directory in the pollux plugin for some example YAML configuration. Detailed documentation will be provided in the next update.

Pollux is intended to complement themes built on the [Castor framework](https://github.com/geminilabs/castor-framework) (i.e. [Castor](https://github.com/geminilabs/castor)), but it is not a requirement.

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

Pollux uses the Meta Box plugin to add custom meta-boxes. Adding meta-boxes and meta-box fields is [the same as you would](https://github.com/rilwis/meta-box/blob/master/demo/demo.php) with Meta Box, except instead of registering meta-box arrays with the 'rwmb_meta_boxes' filter hook, you instead enter the arrays as YAML markup in the Pollux Settings. Examples have been provided in the pollux plugin "demo" directory.

= How do I add Custom Post Types? =

Please see the "pollux.yml" example provided in the pollux plugin "demo" directory.

= How do I add Custom Taxonomies? =

Please see the "pollux.yml" example provided in the pollux plugin "demo" directory.

== Screenshots ==

1. A view of the Pollux settings "General" tab

2. A view of the Pollux settings "Meta Boxes" tab

3. A view of the Pollux settings "Post Types" tab

4. A view of the Pollux settings "Taxonomies" tab

5. A view of the "Post Archive" page

6. A view of the "Site Settings" page

== Changelog ==

= 1.0.0 (2017-05-13) =

* Initial plugin release
