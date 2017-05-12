<?php

namespace GeminiLabs\Pollux;

use BadMethodCallException;
use Exception;
use GeminiLabs\Pollux\Application;

/**
 * @property array $all
 * @method void addError( mixed $messages, bool $dismissible = true )
 * @method void addInfo( mixed $messages, bool $dismissible = true )
 * @method void addSuccess( mixed $messages, bool $dismissible = true )
 * @method void addWarning( mixed $messages, bool $dismissible = true )
 */
class Notice
{
	/**
	 * @var Application
	 */
	protected $app;

	public function __construct( Application $app )
	{
		$this->app = $app;
	}

	public function __call( $name, $args )
	{
		$method = strtolower( $name );
		$status = substr( $method, 3 );
		if( 'add' == substr( $method, 0, 3 ) && in_array( $status, ['error', 'info', 'success', 'warning'] )) {
			return call_user_func_array( [$this, 'addNotice'], array_merge( [$status], $args ));
		}
		throw new BadMethodCallException( sprintf( 'Not a valid method: %s', $name ));
	}

	public function __get( $property )
	{
		if( $property == 'all' ) {
			return $this->app->notices;
		}
		throw new Exception( sprintf( 'Not a valid property: %s', $property ));
	}

	/**
	 * @return string
	 */
	public function activateButton( array $plugin )
	{
		$actionUrl = self_admin_url( sprintf( 'options-general.php?page=%s&action=activate&plugin=%s', $this->app->id, $plugin['plugin'] ));
		return $this->button( sprintf( '%s %s', __( 'Activate', 'pollux' ), $plugin['name'] ), [
			'data-name' => $plugin['name'],
			'data-plugin' => $plugin['plugin'],
			'data-slug' => $plugin['slug'],
			'href' => wp_nonce_url( $actionUrl, sprintf( 'activate-plugin_%s', $plugin['plugin'] )),
		]);
	}

	/**
	 * @param string $title
	 * @return string
	 */
	public function button( $title, array $atts = [] )
	{
		$atts = wp_parse_args( $atts, [
			'class' => '',
			'href' => '',
		]);
		$atts['class'] = trim( $atts['class'] . ' button button-small' );
		$attributes = array_reduce( array_keys( $atts ), function( $carry, $key ) use( $atts ) {
			return $carry . sprintf( ' %s="%s"', $key, $atts[$key] );
		});
		return sprintf( '<a%s>%s</a>', $attributes, $title );
	}

	/**
	 * @return string
	 */
	public function generate( array $notice, $unset = true )
	{
		if( $unset ) {
			$index = array_search( $notice, $this->app->notices );
			if( $index !== false ) {
				unset( $this->app->notices[$index] );
			}
		}
		return $this->buildNotice( $notice );
	}

	/**
	 * @return string
	 */
	public function installButton( array $plugin )
	{
		$actionUrl = self_admin_url( sprintf( 'update.php?action=install-plugin&plugin=%s', $plugin['slug'] ));
		return $this->button( sprintf( '%s %s', __( 'Install', 'pollux' ), $plugin['name'] ), [
			'data-name' => $plugin['name'],
			'data-plugin' => $plugin['plugin'],
			'data-slug' => $plugin['slug'],
			'href' => wp_nonce_url( $actionUrl, sprintf( 'install-plugin_%s', $plugin['slug'] )),
		]);
	}

	/**
	 * @return string
	 */
	public function updateButton( array $plugin )
	{
		$actionUrl = self_admin_url( sprintf( 'update.php?action=upgrade-plugin&plugin=%s', $plugin['plugin'] ));
		return $this->button( sprintf( '%s %s', __( 'Update', 'pollux' ), $plugin['name'] ), [
			'data-name' => $plugin['name'],
			'data-plugin' => $plugin['plugin'],
			'data-slug' => $plugin['slug'],
			'href' => wp_nonce_url( $actionUrl, sprintf( 'upgrade-plugin_%s', $plugin['plugin'] )),
		]);
	}

	/**
	 * @param string $string
	 * @return string
	 */
	public function title( $string )
	{
		return sprintf( '<strong>%s</strong>', $string );
	}

	/**
	 * @param string $type
	 * @param string|array $messages
	 * @param bool $dismissible
	 * @return void
	 */
	protected function addNotice( $type, $messages, $dismissible = true )
	{
		$this->app->notices[] = [
			'dismissible' => $dismissible,
			'message' => $this->buildMessage( array_filter( (array) $messages )),
			'type' => $type,
		];
		$this->app->notices = array_unique( $this->app->notices, SORT_REGULAR );
	}

	/**
	 * @return string
	 */
	protected function buildMessage( array $messages )
	{
		foreach( $messages as $key => &$message ) {
			if( !is_wp_error( $message ))continue;
			$message = $message->get_error_message();
		}
		return wpautop( implode( PHP_EOL . PHP_EOL, $messages ));
	}

	/**
	 * @return string
	 */
	protected function buildNotice( array $notice )
	{
		$class = sprintf( 'notice notice-%s', $notice['type'] );
		if( $notice['dismissible'] ) {
			$class .= ' is-dismissible';
		}
		return sprintf( '<div class="pollux-notice %s">%s</div>', $class, $notice['message'] );
	}
}
