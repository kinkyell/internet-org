<?php
/*
Plugin Name: Internet.org Custom Fields
Description: Creates custom fields for the Internet.org website
Version:     1.0
Author:      The Nerdery
Author URI:  https://nerdery.com/
License:     GPL v2 or later
*/

if ( !function_exists( 'internetorg_custom_fields_init' ) ) {
	/**
	 * Initializes the custom fields using "Field Manager" from Alley
	 *
	 * Basic field creation
	 * @see http://fieldmanager.org/docs/contexts/post-context/
	 *
	 * List of field types
	 * @see http://fieldmanager.org/docs/fields/
	 *
	 * Fields being activated
	 * @see internetorg_create_fields_internetorg_page_home
	 * @see internetorg_page_home_after_title_fields
	 * @see internetorg_create_after_title_fields_internetorg_page_home
	 *
	 * @return void
	 */
	function internetorg_custom_fields_init() {
		// The desire is have these fields only on the home page but we cannot
		// limit these custom fields to a specific page upon page creation, we
		// can, however, limit on display with a custom page template for those
		// pages on which we wish to display the content
		add_action( 'fm_post_page', 'internetorg_create_fields_internetorg_page_home' );
		add_action( 'edit_form_after_title', 'internetorg_page_home_after_title_fields' );
		add_action( 'fm_post_page', 'internetorg_create_after_title_fields_internetorg_page_home' );

		return;
	}
}
add_action( 'init', 'internetorg_custom_fields_init' );

// pluggable because pluggable
if ( ! function_exists( 'internetorg_create_fields_internetorg_page_home' ) ) {
	/**
	 * Create the custom fields for the Homepage
	 *
	 * @return void
	 */
	function internetorg_create_fields_internetorg_page_home() {
		$fm = new Fieldmanager_Group( array(
			'name'           => 'home-content-section',
			'label'          => __( 'Section', 'internetorg' ),
			'label_macro'    => __( 'Section: %s', 'internetorg' ),
			'add_more_label' => __( 'Add another Content Area', 'internetorg' ),
			'collapsed'      => false,
			'sortable'       => true,
			'limit'          => 0,
			'children'       => array(
				'title'          => new Fieldmanager_TextField( __( 'Section Title', 'internetorg' ) ),
				'name'           => new Fieldmanager_TextField( __( 'Section Name', 'internetorg' ) ),
				'content'        => new Fieldmanager_RichTextarea( __( 'Description', 'internetorg' ) ),
				'slug'           => new Fieldmanager_TextField( __( 'Section Slug', 'internetorg' ) ),
				'image'          => new Fieldmanager_Media( __( 'Background Image', 'internetorg' ) ),
				'call-to-action' => new Fieldmanager_Group( array(
					'label'          => __( 'Call to action', 'internetorg' ),
					'label_macro'    => __( 'Call to action: %s', 'internetorg' ),
					'add_more_label' => __( 'Add another CTA', 'internetorg' ),
					'limit'          => 2,
					'children'       => array(
						'link'  => new Fieldmanager_TextField( __( 'Link', 'internetorg' ) ),
						'image' => new Fieldmanager_Media( __( 'Image', 'internetorg' ) ),
					),
				) ),
			),
		) );

		$fm->add_meta_box( __( 'Content Areas', 'internetorg' ), array( 'page' ) );
	}
}

// make pluggable
if ( ! function_exists( 'internetorg_page_home_after_title_fields' ) ) {
	/**
	 * Adds fields directly below the title of the post title on the edit scree
	 *
	 * @return void
	 */
	function internetorg_page_home_after_title_fields() {
		// get the global vars we need to work with
		global $post, $wp_meta_boxes;

		// render the FM meta box in 'internetorg_home_after_title' context
		do_meta_boxes( get_current_screen(), 'internetorg_page_home_after_title', $post );

		// unset 'internetorg_home_after_title' context from the post's meta boxes
		unset( $wp_meta_boxes['post']['internetorg_page_home_after_title'] );
	}
}

// pluggable to allow this to be overriden if extended
if ( ! function_exists( 'internetorg_create_after_title_fields_internetorg_page_home' ) ) {
	/**
	 * Create custom fields for the "Home" Page that will appear after the title
	 *
	 * @see internetorg_page_home_after_title_fields
	 *
	 * @return void
	 */
	function internetorg_create_after_title_fields_internetorg_page_home() {
		/*
		 * This is how to specify a group of items in case you need to add more
		 * than one field to this location
		 *
		 * $fm = new Fieldmanager_Group( array(
		 * 	'name'     => 'after_title_fm_fields',
		 * 	'children' => array(
		 * 		'Subtitle' => new Fieldmanager_TextField( __( 'Subtitle', 'internetorg' ) ),
		 * 	),
		 * ) );
		 */

		$fm = new Fieldmanager_TextArea(
			array(
				'name' => 'page_subtitle',
				'label' => __( 'Subtitle', 'internetorg' ),
				'attributes' => array(
					'rows' => 3,
					'cols' => 30,
				),
			)
		);

		// add field to context create in "internetorg_page_home_after_title_fields"
		$fm->add_meta_box( __( 'Additional page configuration', 'internetorg' ), array( 'page' ), 'internetorg_page_home_after_title', 'high' );
	}
}

/**
 * Called when the plugin activates, use to do anything that needs to be done once
 *
 * @return void
 */
function internetorg_cf_on_activate() {
	internetorg_custom_fields_init();

	return;
}
register_activation_hook( __FILE__, 'internetorg_cf_on_activate' );