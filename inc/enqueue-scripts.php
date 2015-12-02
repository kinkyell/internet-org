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

	wp_enqueue_style(
		'internetorg-screen',
		get_stylesheet_directory_uri() . '/_static/web/assets/styles/screen.css',
		array(),
		false,
		'screen, projection'
	);

	wp_enqueue_style(
		'internetorg-print',
		get_stylesheet_directory_uri() . '/_static/web/assets/styles/print.css',
		array(),
		false,
		'print'
	);

	wp_enqueue_script(
		'requirejs',
		get_stylesheet_directory_uri() . '/_static/web/assets/vendor/requirejs/require.js',
		array( 'jquery' ),
		false,
		true
	);

	return;
}

add_action( 'wp_enqueue_scripts', 'internetorg_enqueue_scripts', 999 );
