<?php
/**
 * ╔═╗╔═╗╔╦╗╦╔╗╔╦  ╦  ╔═╗╔╗ ╔═╗
 * ║ ╦║╣ ║║║║║║║║  ║  ╠═╣╠╩╗╚═╗
 * ╚═╝╚═╝╩ ╩╩╝╚╝╩  ╩═╝╩ ╩╚═╝╚═╝
 *
 * Plugin Name: Pollux
 * Plugin URI:  https://wordpress.org/plugins/pollux
 * Description: Pollux is a theme-agnostic scaffolding plugin for WordPress.
 * Version:     1.1.3
 * Author:      Paul Ryley
 * Author URI:  http://geminilabs.io
 * License:     GPL3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: pollux
 * Domain Path: languages
 */

defined( 'WPINC' ) || die;

require_once __DIR__.'/activate.php';

if( \Pollux_Activate::shouldDeactivate() )return;

require_once __DIR__.'/autoload.php';
require_once __DIR__.'/helpers.php';
require_once __DIR__.'/thirdparty.php';

$app = \GeminiLabs\Pollux\Application::getInstance();

register_activation_hook( __FILE__, array( $app, 'onActivation' ));
register_deactivation_hook( __FILE__, array( $app, 'onDeactivation' ));

$app->register( new \GeminiLabs\Pollux\Provider );
$app->init();
