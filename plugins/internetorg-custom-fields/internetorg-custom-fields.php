<?php
/**
 * Internet.org Custom Fields file.
 *
 * @since             1.0.0
 * @package           Internet.org
 *
 * @wordpress-plugin
 * Plugin Name:       Internet.org Custom Fields
 * Description:       Creates custom fields for the Internet.org website
 * Version:           1.0.0
 * Author:            The Nerdery
 * Author URI:        https://nerdery.com/
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

if ( ! function_exists( 'internetorg_custom_fields_init' ) ) {
	/**
	 * Initializes the custom fields using the "Fieldmanager" plugin from Alley Interactive.
	 *
	 * Basic field creation.
	 *
	 * @link http://fieldmanager.org/docs/contexts/post-context/
	 *
	 * List of field types.
	 * @link http://fieldmanager.org/docs/fields/
	 *
	 * Fields being activated.
	 * @see  internetorg_create_fields_internetorg_page_home
	 * @see  internetorg_page_home_after_title_fields
	 * @see  internetorg_create_after_title_fields_internetorg_page_home
	 *
	 * @return void
	 */
	function internetorg_custom_fields_init() {
		/*
		 * The desire is have these fields only on the home page but we cannot limit these custom fields to a specific
		 * page upon page creation, we can, however, limit on display with a custom page template for those pages
		 * on which we wish to display the content.
		 */
		//add_action( 'fm_post_page', 'internetorg_create_fields_internetorg_page_home' );
		add_action( 'edit_form_after_title', 'internetorg_page_home_after_title_fields' );
		//add_action( 'fm_post_page', 'internetorg_create_after_title_fields_internetorg_page_home' );

		return;
	}
}
add_action( 'init', 'internetorg_custom_fields_init' );

/**
 * Called when the plugin activates, use to do anything that needs to be done once.
 *
 * @return void
 */
function internetorg_cf_on_activate() {
	internetorg_custom_fields_init();

	return;
}

register_activation_hook( __FILE__, 'internetorg_cf_on_activate' );
