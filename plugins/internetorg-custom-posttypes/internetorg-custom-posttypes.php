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
require __DIR__ . '/ContentWidget.php';

add_action( 'init', 'init_internetorg_custom_posttypes_callback' );
