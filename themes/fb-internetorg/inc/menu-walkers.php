<?php
/**
 * This file is used to include custom menu walkers
 *
 * @package Internet.org
 * @author  arichard <arichard@nerdery.com>
 */

/**
 * Walker for main nav menu (Our Approach, etc.).
 */
require_once( get_template_directory() . '/inc/class-main-nav-walker.php' );

/**
 * Walker for main menu sup nav (Careers, Facebook Page, etc.).
 */
require_once( get_template_directory() . '/inc/class-main-subnav-walker.php' );
