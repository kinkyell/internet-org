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
		add_action( 'fm_post_page', 'internetorg_create_fields_internetorg_page_home' );
		add_action( 'edit_form_after_title', 'internetorg_page_home_after_title_fields' );
		add_action( 'fm_post_page', 'internetorg_create_after_title_fields_internetorg_page_home' );

		return;
	}
}
add_action( 'init', 'internetorg_custom_fields_init' );

if ( ! function_exists( 'internetorg_create_fields_internetorg_page_home' ) ) {
	/**
	 * Create the custom fields for the Homepage.
	 *
	 * @return void
	 */
	function internetorg_create_fields_internetorg_page_home() {

		$next_post = new Fieldmanager_Autocomplete(
			array(
				'name'           => 'next_page',
				'show_edit_link' => true,
				'datasource'     => new Fieldmanager_Datasource_Post(
					array(
						'query_args' => array(
							'post_type' => 'page',
						),
					)
				),
			)
		);

		$datasource_post = new Fieldmanager_Datasource_Post( array(
			'query_args' => array( 'post_type' => array( 'io_story', 'post', 'page'), 'posts_per_page' => -1 ),
			'use_ajax' => false
		) );

		$next_post->add_meta_box( 'Next Page', 'page' );

		$fm = new Fieldmanager_Group(
			array(
				'name'           => 'home-content-section',
				'label'          => __( 'Section', 'internetorg' ),
				'label_macro'    => __( 'Section: %s', 'internetorg' ),
				'add_more_label' => __( 'Add another Content Area', 'internetorg' ),
				'collapsed'      => false,
				'collapsible'    => true,
				'sortable'       => true,
				'limit'          => 0,
				'children'       => array(
					'title'          => new Fieldmanager_TextField( __( 'Section Title', 'internetorg' ) ),
					'name'           => new Fieldmanager_TextField( __( 'Section Name', 'internetorg' ) ),
					'content'        => new Fieldmanager_RichTextarea( __( 'Description', 'internetorg' ) ),
					'src' => new Fieldmanager_Radios( __( 'Source', 'internetorg' ), array(
						'name'    => 'src',
						'default_value' => 'page',
						'options' => array(
							'page' => __('Page, Post, or Story'),
							'custom' => __( 'Custom Link', 'internetorg' )
							),
						)
					),
					'slug'  => new Fieldmanager_TextField( __( 'Section Slug', 'internetorg'),
						array(
							'display_if' => array(
								'src' => 'src',
								'value' => 'custom'
							),
						)
					),
					'url-src' => new Fieldmanager_Select( __( 'URL Source', 'internetorg' ),
						array(
							'datasource' => $datasource_post,
							'display_if' => array(
								'src' => 'src',
								'value' => 'page'
							)
						)
					),
					'theme' => new Fieldmanager_Select( array(
						'label' => 'Select a Theme',
						'options' => array(
							'approach' => __( 'Approach', 'internetorg' ),
							'mission' => __( 'Mission', 'internetorg' ),
							'impact' => __( 'Impact', 'internetorg' )
						)
					) ),
					'image'          => new Fieldmanager_Media( __( 'Background Image', 'internetorg' ) ),
					'call-to-action' => new Fieldmanager_Group(
						array(
							'label'          => __( 'Call to action', 'internetorg' ),
							'label_macro'    => __( 'Call to action: %s', 'internetorg' ),
							'add_more_label' => __( 'Add another CTA', 'internetorg' ),
							'limit'          => 5,
							'collapsible'    => true,
							'children'       => array(
								'title' => new Fieldmanager_TextField( __( 'CTA Title', 'internetorg' ) ),
								'text'  => new Fieldmanager_RichTextarea( __( 'Content', 'internetorg' ) ),
								'cta_src' => new Fieldmanager_Radios( __( 'Link Source', 'internetorg' ), array(
										'name'    => 'cta_src',
										'default_value' => 'page',
										'options' => array(
											'page' => __('Page, Post, or Story'),
											'custom' => __( 'Custom Link', 'internetorg' )
										),
									)
								),
								'link'  => new Fieldmanager_TextField( __( 'Link', 'internetorg' ),
									array(
										'display_if' => array(
											'src' => 'cta_src',
											'value' => 'custom'
										),
									)
								),
								'link_src' => new Fieldmanager_Select( __( 'URL Source', 'internetorg' ),
									array(
										'datasource' => $datasource_post,
										'display_if' => array(
											'src' => 'cta_src',
											'value' => 'page'
										)
									)
								),
								'image' => new Fieldmanager_Media( __( 'Image', 'internetorg' ) ),
							),
						)
					),
				),
			)
		);
		$fm->add_meta_box( __( 'Content Areas', 'internetorg' ), array( 'page' ) );
	}
}

if ( ! function_exists( 'internetorg_page_home_after_title_fields' ) ) {
	/**
	 * Adds fields directly below the title of the post title on the edit screen.
	 *
	 * @global \WP_Post $post          The WP_Post object to which to add a meta box to.
	 * @global array    $wp_meta_boxes The array of metaboxes.
	 *
	 * @return void
	 */
	function internetorg_page_home_after_title_fields() {
		// Get the global vars we need to work with.
		global $post, $wp_meta_boxes;

		// Render the FM meta box in 'internetorg_home_after_title' context.
		do_meta_boxes( get_current_screen(), 'internetorg_page_home_after_title', $post );

		// Unset 'internetorg_home_after_title' context from the post's meta boxes.
		unset( $wp_meta_boxes['post']['internetorg_page_home_after_title'] );
	}
}

if ( ! function_exists( 'internetorg_create_after_title_fields_internetorg_page_home' ) ) {
	/**
	 * Create custom fields for the "Home" Page that will appear after the title.
	 *
	 * @see internetorg_page_home_after_title_fields
	 *
	 * @return void
	 */
	function internetorg_create_after_title_fields_internetorg_page_home() {

		$fm = new Fieldmanager_TextArea(
			array(
				'name'       => 'page_subtitle',
				'label'      => __( 'Subtitle', 'internetorg' ),
				'attributes' => array(
					'rows' => 3,
					'cols' => 30,
				),
			)
		);

		$fm->add_meta_box(
			__( 'Additional page configuration', 'internetorg' ),
			array( 'page' ),
			'internetorg_page_home_after_title',
			'high'
		);

		$intro = new Fieldmanager_Group(
			array(
				'name'     => 'page_intro_block',
				'children' => array(
					'intro_title'   => new Fieldmanager_TextField(
						array(
							'label' => __( 'Intro Title', 'internetorg' ),
						)
					),
					'intro_content' => new Fieldmanager_TextArea(
						array(
							'label'      => __( 'Intro Copy', 'internetorg' ),
							'attributes' => array(
								'rows' => 3,
								'cols' => 30,
							),
						)
					),
				),
			)
		);

		$intro->add_meta_box(
			__( 'Page Intro', 'internetorg' ),
			array( 'page' ),
			'internetorg_page_home_after_title',
			'high'
		);
	}
}

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
