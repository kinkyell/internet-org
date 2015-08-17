<?php
/*
Plugin Name: Internet.org Custom Post Types
Description: Creates custom post types for the Internet.org website
Version:     1.0
Author:      The Nerdery
Author URI:  https://nerdery.com/
License:     GPL v2 or later
*/

// load our class
require __DIR__ . '/functions.php';
require __DIR__ . '/CustomPostTypes.php';
require __DIR__ . '/PostTypeBase.php';
require __DIR__ . '/FreeService.php';
require __DIR__ . '/Campaign.php';
require __DIR__ . '/Story.php';
require __DIR__ . '/Press.php';
require __DIR__ . '/ContentWidget.php';

// function_exists( 'add_action' ) or exit( 'No direct access' );

add_action( 'init', 'init_internetorg_custom_posttypes_callback' );

register_activation_hook( __FILE__, 'activate_internetorg_custom_posttypes_callback' );
register_deactivation_hook( __FILE__, 'deactivate_internetorg_custom_posttypes_callback' );

