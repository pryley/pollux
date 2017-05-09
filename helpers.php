<?php

if( !function_exists( 'pollux_app' )) {
	function pollux_app() {
		return GeminiLabs\Pollux\Application::getInstance();
	}
}
