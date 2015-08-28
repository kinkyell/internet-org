<?php
/**
 * Internet.org Custom Post Types file.
 *
 * @since             1.0.0
 * @package           Internet.org
 *
 * @wordpress-plugin
 * Plugin Name:       Internet.org Custom Post Types
 * Description:       Creates custom post types for the Internet.org website.
 * Version:           1.0.0
 * Author:            The Nerdery
 * Author URI:        https://nerdery.com/
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

// Load our classes.
require __DIR__ . '/functions.php';
require __DIR__ . '/custom-post-types.php';
require __DIR__ . '/post-type-base.php';
require __DIR__ . '/free-service.php';
require __DIR__ . '/campaign.php';
require __DIR__ . '/story.php';
require __DIR__ . '/content-widget.php';

add_action( 'init', 'init_internetorg_custom_posttypes_callback' );
