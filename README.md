# Pollux

[![GitHub version](https://badge.fury.io/gh/geminilabs%2Fpollux.svg)](https://badge.fury.io/gh/geminilabs%2Fpollux)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/geminilabs/pollux/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/geminilabs/pollux/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/geminilabs/pollux/badges/build.png?b=master)](https://scrutinizer-ci.com/g/geminilabs/pollux/build-status/master)
[![License](https://img.shields.io/badge/license-GPL3-blue.svg)](https://github.com/geminilabs/pollux/blob/master/LICENSE)

![Pollux banner](+/assets/banner-1544x500.png)

Pollux is a theme-agnostic scaffolding plugin for the advanced WordPress user. It allows you to easily add custom Post Types, Taxonomies, Meta Boxes, Global Settings, and more.

Pollux uses the [Meta Box](https://wordpress.org/plugins/meta-box/) plugin to add custom meta-boxes. If the Meta Box plugin is not installed, Pollux will prompt you to do so on the Pollux settings page.

Please see the `demo` directory in the pollux plugin for some example YAML configuration. Detailed documentation will be provided in future updates.

Pollux is intended to complement themes built on the [Castor framework](https://github.com/geminilabs/castor-framework) (i.e. [Castor](https://github.com/geminilabs/castor)), but it is not a requirement.

## Minimum plugin requirements:

- PHP 5.6
- WordPress 4.7.0

## Frequently Asked Questions

### How do I add Meta Boxes?

Pollux uses the Meta Box plugin to add custom meta-boxes. Adding meta-boxes and meta-box fields is [the same as you would](https://github.com/rilwis/meta-box/blob/master/demo/demo.php) with Meta Box, except instead of registering meta-box arrays with the 'rwmb_meta_boxes' filter hook, you instead enter the arrays as YAML markup in the Pollux Settings.

Please see the [meta_boxes_demo.yml](demo/meta_boxes_demo.yml) example provided in the pollux plugin "demo" directory for more information.

### How do I make conditional Meta Boxes?

Please see the [meta_box_conditions.yml](demo/post_types_demo.yml) example provided in the pollux plugin "demo" directory.

### How do I add Custom Post Types?

Please see the [post_types_demo.yml](demo/post_types_demo.yml) example provided in the pollux plugin "demo" directory.

### How do I add Custom Post Type Columns?

Please see the [post_type_columns_demo.yml](demo/post_type_columns_demo.yml) example provided in the pollux plugin "demo" directory.

### How do I add Custom Taxonomies?

Please see the [taxonomies_demo.yml](demo/taxonomies_demo.yml) example provided in the pollux plugin "demo" directory.

### How do set my own defaults?

Please see the [pollux.yml](demo/pollux.yml) example provided in the pollux plugin "demo" directory.
