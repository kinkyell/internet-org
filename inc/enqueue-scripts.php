<?php
/**
 * Enqueue scripts file.
 *
 * @package Nerdery
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue scripts and styles.
 *
 * @action   wp_enqueue_scripts
 * @priority default (10)
 */
function internetorg_enqueue_scripts() {

	wp_enqueue_style( 'internetorg-style', get_stylesheet_uri() );

	/** @var string $domain_name The domain name of the current website for use with basis font @link http://fontdeck.com/support/tutorial */
	$domain_name = parse_url( get_home_url(), PHP_URL_HOST );

	wp_enqueue_style(
		'internetorg-basis-font',
		'//f.fontdeck.com/s/css/Z/ywsIcMc9pMM4qHGxsWmexRb9Q/' . $domain_name . '/59155.css',
		array(),
		null,
		'all'
	);

	wp_enqueue_style(
		'internetorg-lava-font',
		'//fonts.typotheque.com/WF-026395-008647.css',
		array(),
		null,
		'all'
	);

	wp_enqueue_style(
		'internetorg-screen',
		get_stylesheet_directory_uri() . '/_static/web/assets/styles/screen.css',
		array(),
		false,
		'screen, projection'
	);

	wp_enqueue_script(
		'requirejs',
		get_stylesheet_directory_uri() . '/_static/web/assets/vendor/requirejs/require.js',
		array( 'jquery' ),
		false,
		true
	);

	wp_enqueue_script(
		'requireconfig',
		get_stylesheet_directory_uri() . '/_static/web/assets/scripts/config.js',
		array( 'requirejs' ),
		false,
		true
	);

	return;
}

add_action( 'wp_enqueue_scripts', 'internetorg_enqueue_scripts' );
