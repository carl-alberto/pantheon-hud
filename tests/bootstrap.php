<?php

$_tests_dir = getenv( 'WP_TESTS_DIR' );
if ( ! $_tests_dir ) {
	$_tests_dir = '/tmp/wordpress-tests-lib';
}

define( 'PANTHEON_HUD_PHPUNIT_RUNNING', true );

require_once $_tests_dir . '/includes/functions.php';

function _manually_load_plugin() {

	add_filter( 'pre_http_request', function( $response, $request, $url ){
		if ( 'https://api.live.getpantheon.com:8443/sites/self/state' !== $url ) {
			return $response;
		}
		return array(
			'headers'     => array(
				'date'            => 'Thu, 14 Jan 2016 13:46:02 GMT',
				'content-type'    => 'application/json',
				'x-pantheon-host' => 'yggdrasil4ead8a2d.chios.panth.io',
				'server'          => 'TwistedWeb/12.2.0',
				),
			'body'        => file_get_contents( __DIR__ . '/data/sample-response.json' ),
			'response'    => array(
				'code'    => 200,
				'message' => 'OK'
				),
			'cookies'     => array(),
			'filename'    => null,
			);
	}, 10, 3 );

	require dirname( dirname( __FILE__ ) ) . '/pantheon-hud.php';
}
tests_add_filter( 'muplugins_loaded', '_manually_load_plugin' );

require $_tests_dir . '/includes/bootstrap.php';
