<?php

namespace GeminiLabs\Pollux;

use Exception;
use GeminiLabs\Pollux\Config\Config;
use ReflectionClass;

class GateKeeper
{
	/**
	 * [plugin_file_path] => [plugin_name]|[plugin_version]|[plugin_url]
	 */
	const DEPENDENCIES = [
		'meta-box/meta-box.php' => 'Meta Box|4.11|https://wordpress.org/plugins/meta-box/',
	];
	const MIN_PHP_VERSION = '5.6.0';
	const MIN_WORDPRESS_VERSION = '4.7';

	public $errors = [];

	/**
	 * @var Application
	 */
	protected $app;

	/**
	 * @var Notice
	 */
	protected $notice;

	/**
	 * @var string
	 */
	protected $plugin;

	public function __construct( $plugin )
	{
		$this->plugin = $plugin;

		if( $this->canActivate() ) {
			add_action( 'admin_init', array( $this, 'init' ));
		}
		else {
			add_action( 'activated_plugin', array( $this, 'deactivate' ));
			add_action( 'admin_notices',    array( $this, 'deactivate' ));
		}
	}

	public function init()
	{
		$this->app = pollux_app();
		$this->notice = pollux_app()->make( 'Notice' );

		add_action( 'current_screen',                         array( $this, 'activatePlugin' ));
		add_action( 'wp_ajax_pollux/dependency/activate_url', array( $this, 'ajaxActivatePluginLink' ));
		add_action( 'admin_notices',                          array( $this, 'printNotices' ));
		add_action( 'current_screen',                         array( $this, 'setDependencyNotice' ));
	}

	/**
	 * @return void
	 */
	public function activatePlugin()
	{
		if( get_current_screen()->id != sprintf( 'settings_page_%s', $this->app->id )
			|| filter_input( INPUT_GET, 'action' ) != 'activate'
		)return;
		$plugin = filter_input( INPUT_GET, 'plugin' );
		check_admin_referer( 'activate-plugin_' . $plugin );
		$result = activate_plugin( $plugin, null, is_network_admin(), true );
		if( is_wp_error( $result )) {
			wp_die( $result->get_error_message() );
		}
		wp_safe_redirect( wp_get_referer() );
		exit;
	}

	/**
	 * @return void
	 */
	public function ajaxActivatePluginLink()
	{
		check_ajax_referer( 'updates' );
		$plugin = filter_input( INPUT_POST, 'plugin' );
		if( !$this->isPluginDependency( $plugin )) {
			wp_send_json_error();
		}
		$activateUrl = add_query_arg([
			'_wpnonce' => wp_create_nonce( sprintf( 'activate-plugin_%s', $plugin )),
			'action' => 'activate',
			'page' => $this->app->id,
			'plugin' => $plugin,
		], self_admin_url( 'options-general.php' ));
		wp_send_json_success([
			'activate_url' => $activateUrl,
			filter_input( INPUT_POST, 'type' ) => $plugin,
		]);
	}

	/**
	 * @return bool
	 */
	public function canActivate()
	{
		return $this->hasValidPHPVersion() && $this->hasValidWPVersion();
	}

	/**
	 * @return void
	 * @action activated_plugin
	 * @action admin_notices
	 */
	public function deactivate( $plugin )
	{
		if( $plugin == $this->plugin ) {
			$this->redirect();
		}
		deactivate_plugins( $this->plugin );
		$addNotice = $this->hasValidPHPVersion()
			? 'addInvalidWPVersionNotice'
			: 'addInvalidPHPVersionNotice';
		$this->$addNotice();
	}

	/**
	 * @return bool
	 */
	public function hasDependency( $plugin )
	{
		if( !$this->isPluginDependency( $plugin )) {
			return true;
		}
		return $this->isPluginInstalled( $plugin ) && $this->isPluginValid( $plugin );
	}

	/**
	 * @return bool
	 */
	public function hasPendingDependencies()
	{
		foreach( static::DEPENDENCIES as $plugin => $data ) {
			if( !$this->isPluginDependency( $plugin ))continue;
			$this->isPluginActive( $plugin );
			$this->isPluginVersionValid( $plugin );
		}
		return !empty( $this->errors );
	}

	/**
	 * @return bool
	 */
	public function hasValidPHPVersion()
	{
		return version_compare( PHP_VERSION, self::MIN_PHP_VERSION, '>=' );
	}

	/**
	 * @return bool
	 */
	public function hasValidWPVersion()
	{
		global $wp_version;
		return version_compare( $wp_version, self::MIN_WORDPRESS_VERSION, '>=' );
	}

	/**
	 * @return bool
	 */
	public function isPluginActive( $plugin )
	{
		return $this->catchError( $plugin, 'inactive',
			is_plugin_active( $plugin ) || array_key_exists( $plugin, $this->getMustUsePlugins() )
		);
	}

	/**
	 * @return bool
	 */
	public function isPluginDependency( $plugin )
	{
		return array_key_exists( $plugin, static::DEPENDENCIES );
	}

	/**
	 * @return bool
	 */
	public function isPluginInstalled( $plugin )
	{
		return $this->catchError( $plugin, 'not_found',
			array_key_exists( $plugin, $this->getAllPlugins() )
		);
	}

	/**
	 * @return bool
	 */
	public function isPluginValid( $plugin )
	{
		return $this->isPluginActive( $plugin ) && $this->isPluginVersionValid( $plugin );
	}

	/**
	 * @return bool
	 */
	public function isPluginVersionValid( $plugin )
	{
		if( !$this->isPluginDependency( $plugin )) {
			return true;
		}
		if( !$this->isPluginInstalled( $plugin )) {
			return false;
		}
		return $this->catchError( $plugin, 'wrong_version', version_compare(
			$this->getPluginRequirements( $plugin, 'version' ),
			$this->getAllPlugins()[$plugin]['Version'],
			'<='
		));
	}

	/**
	 * @return void
	 */
	public function printNotices()
	{
		foreach( $this->notice->all as $notice ) {
			echo $this->notice->generate( $notice );
		}
	}

	/**
	 * @return void|null
	 */
	public function setDependencyNotice()
	{
		if( get_current_screen()->id != 'settings_page_pollux'
			|| $this->app->config->disable_config
			|| !$this->hasPendingDependencies()
		)return;
		$message = sprintf( '<strong>%s:</strong> %s',
			__( 'Pollux requires the latest version of the following plugins', 'pollux' ),
			$this->getDependencyLinks()
		);
		$this->notice->addWarning( [$message, $this->getDependencyActions()] );
	}

	/**
	 * @return void
	 */
	protected function addInvalidPHPVersionNotice()
	{
		$message1 = sprintf( __( 'Pollux requires PHP %s or greater in order to work properly (your server is running PHP %s).', 'pollux' ), self::MIN_PHP_VERSION, PHP_VERSION );
		$message2 = __( 'Please contact your webhosting provider or server administrator to upgrade the version of PHP running on your server, or use a different plugin.', 'pollux' );
		$this->printDeactivationNotice( sprintf( '%s %s',
			$message1,
			$message2
		));
	}

	/**
	 * @return void
	 */
	protected function addInvalidWPVersionNotice()
	{
		$message = sprintf( __( 'Pollux requires WordPress %s or greater in order to work properly.', 'pollux' ), self::MIN_WORDPRESS_VERSION );
		if( current_user_can( 'update_core' )) {
			$message .= PHP_EOL . PHP_EOL . sprintf( '<a href="%s" class="button button-small">%s</a>',
				self_admin_url( 'update-core.php' ),
				__( 'Update WordPress', 'pollux' )
			);
		}
		$this->printDeactivationNotice( $message );
	}

	/**
	 * @param string $plugin
	 * @param string $error
	 * @param bool $isValid
	 * @return bool
	 */
	protected function catchError( $plugin, $error, $isValid )
	{
		if( !$isValid && $this->isPluginDependency( $plugin )) {
			if( !isset( $this->errors[$plugin] )) {
				$this->errors[$plugin] = [];
			}
			$this->errors[$plugin] = array_keys( array_flip(
				array_merge( $this->errors[$plugin], [$error] )
			));
		}
		return $isValid;
	}

	/**
	 * @return array
	 */
	protected function getAllPlugins()
	{
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
		return array_merge( get_plugins(), $this->getMustUsePlugins() );
	}

	/**
	 * @return string
	 */
	protected function getDependencyActions()
	{
		$actions = '';
		foreach( $this->errors as $plugin => $errors ) {
			if( in_array( 'not_found', $errors ) && current_user_can( 'install_plugins' )) {
				$actions .= $this->notice->installButton( $this->getPluginRequirements( $plugin ));
			}
			else if( in_array( 'wrong_version', $errors ) && current_user_can( 'update_plugins' )) {
				$actions .= $this->notice->updateButton( $this->getPluginInformation( $plugin ));
			}
			else if( in_array( 'inactive', $errors ) && current_user_can( 'activate_plugins' )) {
				$actions .= $this->notice->activateButton( $this->getPluginInformation( $plugin ));
			}
		}
		return $actions;
	}

	/**
	 * @return string
	 */
	protected function getDependencyLinks()
	{
		return array_reduce( array_keys( $this->errors ), function( $carry, $plugin ) {
			return $carry . $this->getPluginLink( $plugin );
		});
	}

	/**
	 * @return array
	 */
	protected function getMustUsePlugins()
	{
		$plugins = get_mu_plugins();
		if( in_array( 'Bedrock Autoloader', array_column( $plugins, 'Name' ))) {
			$autoloadedPlugins = get_site_option( 'bedrock_autoloader' );
			if( !empty( $autoloadedPlugins['plugins'] )) {
				return array_merge( $plugins, $autoloadedPlugins['plugins'] );
			}
		}
		return $plugins;
	}

	/**
	 * @return array|false
	 */
	protected function getPlugin( $plugin )
	{
		if( $this->isPluginInstalled( $plugin )) {
			return $this->getAllPlugins()[$plugin];
		}
		return false;
	}

	/**
	 * @return array|string
	 */
	protected function getPluginData( $plugin, $data, $key = null )
	{
		if( !is_array( $data )) {
			throw new Exception( sprintf( 'Plugin information not found for: %s', $plugin ));
		}
		$data['plugin'] = $plugin;
		$data['slug'] = $this->getPluginSlug( $plugin );
		$data = array_change_key_case( $data );
		if( is_null( $key )) {
			return $data;
		}
		$key = strtolower( $key );
		return isset( $data[$key] )
			? $data[$key]
			: '';
	}

	/**
	 * @return array|string
	 */
	protected function getPluginInformation( $plugin, $key = null )
	{
		return $this->getPluginData( $plugin, $this->getPlugin( $plugin ), $key );
	}

	/**
	 * @return string
	 */
	protected function getPluginLink( $plugin )
	{
		try {
			$data = $this->getPluginInformation( $plugin );
		}
		catch( Exception $e ) {
			$data = $this->getPluginRequirements( $plugin );
		}
		return sprintf( '<span class="plugin-%s"><a href="%s">%s</a></span>',
			$data['slug'],
			$data['pluginuri'],
			$data['name']
		);
	}

	/**
	 * @return array|string
	 */
	protected function getPluginRequirements( $plugin, $key = null )
	{
		$keys = ['Name', 'Version', 'PluginURI'];
		$requirements = $this->isPluginDependency( $plugin )
			? array_pad( explode( '|', static::DEPENDENCIES[$plugin] ), 3, '' )
			: array_fill( 0, 3, '' );
		return $this->getPluginData( $plugin, array_combine( $keys, $requirements ), $key );
	}

	/**
	 * @return string
	 */
	protected function getPluginSlug( $plugin )
	{
		return substr( $plugin, 0, strrpos( $plugin, '/' ));
	}

	/**
	 * @return void
	 */
	protected function printDeactivationNotice( $message )
	{
		printf( '<div class="notice notice-error is-dismissible"><p><strong>%s</strong></p>%s</div>',
			__( 'The Pollux plugin was deactivated.', 'pollux' ),
			wpautop( $message )
		);
	}

	/**
	 * @return void
	 */
	protected function redirect()
	{
		wp_safe_redirect( self_admin_url( sprintf( 'plugins.php?plugin_status=%s&paged=%s&s=%s',
			filter_input( INPUT_GET, 'plugin_status' ),
			filter_input( INPUT_GET, 'paged' ),
			filter_input( INPUT_GET, 's' )
		)));
		exit;
	}
}
