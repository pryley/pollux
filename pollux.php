<?php
/**
 * ╔═╗╔═╗╔╦╗╦╔╗╔╦  ╦  ╔═╗╔╗ ╔═╗
 * ║ ╦║╣ ║║║║║║║║  ║  ╠═╣╠╩╗╚═╗
 * ╚═╝╚═╝╩ ╩╩╝╚╝╩  ╩═╝╩ ╩╚═╝╚═╝
 *
 * Plugin Name: Pollux
 * Plugin URI:  https://wordpress.org/plugins/pollux
 * Description: Pollux is a theme-agnostic scaffolding plugin for WordPress.
 * Version:     2.0.0-alpha
 * Author:      Paul Ryley
 * Author URI:  http://geminilabs.io
 * License:     GPL3
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: pollux
 * Domain Path: languages
 * Network:     true
 */

defined( 'WPINC' ) || die;

require_once __DIR__ . '/activate.php';
require_once __DIR__ . '/autoload.php';
require_once __DIR__ . '/helpers.php';

use GeminiLabs\Pollux\Application;
use GeminiLabs\Pollux\Provider;

$app = Application::getInstance();

$app->register( new Provider );

register_activation_hook( __FILE__, array( $app, 'onActivation' ));
register_deactivation_hook( __FILE__, array( $app, 'onDeactivation' ));

$app->init();
