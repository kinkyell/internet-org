<?php

/**
 * This file is for language related functions.
 *
 * @package Internet.org
 * @author  arichard <arichard@nerdery.com>
 */

if ( ! function_exists( 'internetorg_language_switcher' ) ) :
	/**
	 * Builds the language switcher needed for the FE of the site.
	 *
	 * @return void
	 */
	function internetorg_language_switcher() {

		if ( ! function_exists( 'bbl_get_switcher_links' ) ) {

			?>

			<div class="mainMenu-panel-lang">
				<div class="langSelect langSelect_inActive">
					<div class="langSelect-label"><?php esc_html_e( 'English', 'internetorg' ); ?></div>
				</div>
			</div>

			<?php

			return;
		}

		$list = bbl_get_switcher_links();

		// Select list of items, hidden from the user but recreated with css/js.
		echo '<select id="js-LanguageView" class="js-select" onchange="document.location.href=this.options[this.selectedIndex].value;">';
		foreach ( $list as $item ) {
			// Skip languages for which there is no translation.
			if ( in_array( 'bbl-add', $item['classes'] ) ) {
				continue;
			}
			if ( $item['href'] ) {
				echo '<option value="' . esc_url( $item['href'] ) . '" ' . selected( $item['active'], true, false ) . ' data-dir="' . esc_attr( $item['lang']->text_direction ) . '">' . esc_html( $item['lang']->display_name ) . '</option>';
			}
		}
		echo '</select>';
	}
endif;

/**
 * Test the provided locale to determine if it is a right-to-left language.
 *
 * @param string $locale The locale to test. Optional. Defaults to the result of get_locale().
 *
 * @return bool
 */
function internetorg_is_rtl( $locale = '' ) {

	if ( empty( $locale ) ) {
		$locale = get_locale();
	}

	/**
	 * Array of language codes where the language writes right-to-left.
	 *
	 * Lifted verbatim from Automattic/babble Babble_Languages
	 * Based primarily on GlotPress' list of locales and Wikipedia's article on RTL:
	 *
	 * @link https://github.com/Automattic/babble/blob/develop/class-languages.php
	 * @link https://glotpress.trac.wordpress.org/browser/trunk/locales/locales.php
	 * @link https://en.wikipedia.org/wiki/Right-to-left
	 *
	 * @var array
	 */
	$rtl_languages = array(
		'ar',      // Arabic / العربية.
		'arc',     // Aramaic.
		'arq',     // Algerian Arabic / الدارجة الجزايرية.
		'azb',     // South Azerbaijani / گؤنئی آذربایجان.
		'az_TR',   // Azerbaijani (Turkey) / Azərbaycan Türkcəsi.
		'bcc',     // Balochi Southern / بلوچی مکرانی.
		'bqi',     // Bakthiari / بختياري.
		'ckb',     // Sorani Kurdish / کوردی.
		'dv',      // Dhivehi.
		'fa',      // Persian / فارسی.
		'fa_IR',   // Persian / فارسی.
		'fa_AF',   // Persian (Afghanistan) / (افغانستان) فارسی.
		'glk',     // Gilaki / گیلکی.
		'ha',      // Hausa / هَوُسَ.
		'haz',     // Hazaragi / هزاره گی.
		'he',      // Hebrew / עִבְרִית.
		'he_IL',   // Hebrew / עִבְרִית.
		'mzn',     // Mazanderani / مازِرونی.
		'pnb',     // Western Punjabi / پنجابی.
		'ps',      // Pashto / پښتو.
		'sd',      // Sindhi / سنڌي.
		'ug',      // Uyghur / ئۇيغۇرچە.
		'ur',      // Urdu / اردو.
		'yi',      // Yiddish / ייִדיש'.
	);

	if ( ! in_array( $locale, $rtl_languages ) ) {
		return false;
	}

	return true;
}

/**
 * Filter language attributes to append HTML dir, if not already present.
 *
 * @filter   language_attributes
 * @priority default (10)
 *
 * @link     http://www.w3.org/TR/html4/struct/dirlang.html#h-8.2
 * @link     https://developer.wordpress.org/reference/hooks/language_attributes/
 * @link     https://developer.wordpress.org/reference/functions/language_attributes/
 *
 * @param string $language_attributes The language attributes string to be filtered.
 *
 * @return string
 */
function internetorg_language_attributes( $language_attributes = '' ) {

	if ( false !== stripos( $language_attributes, 'dir=' ) ) {
		return $language_attributes;
	}

	if ( ! internetorg_is_rtl( get_locale() ) ) {
		$language_attributes .= ' dir="ltr"';
	} else {
		$language_attributes .= ' dir="rtl"';
	}

	return $language_attributes;
}

add_filter( 'language_attributes', 'internetorg_language_attributes' );

/**
 * Add Babble/Fieldmanager metaboxes to io_video post_type.
 */
function internetorg_video_metaboxes() {

	// Bail early as this should go to admin only.
	if ( ! is_admin() ) {
		return;
	}

	new Fieldmanager_Context_Storable(
		'Fieldmanager_TextField',
		array(
			'name'  => 'video-duration',
			'label' => __( 'Video Duration', 'internetorg' ),
		),
		array(
			'add_meta_box' => array(
				'Video Duration',
				'io_video',
			),
		)
	);

	new Fieldmanager_Context_Storable(
		'Fieldmanager_Link',
		array(
			'name'  => 'video-url',
			'label' => __( 'Video URL', 'internetorg' ),
		),
		array(
			'add_meta_box' => array(
				'Video URL',
				'io_video',
			),
		)
	);
}

add_action( 'init', 'internetorg_video_metaboxes' );

/**
 * Add Babble/Fieldmanager metaboxes to page post_type.
 */
function internetorg_page_metaboxes() {

	// Bail early as this should go to admin only.
	if ( ! is_admin() ) {
		return;
	}

	/**
	 * We're on an admin screen with the post_id available.
	 */
	if ( ! empty( $_GET['post'] ) ) {
		$post_id = absint( $_GET['post'] );
		if ( 'bbl_job' === get_post_type( $post_id ) ) {
			$language_object = wp_get_post_terms( $post_id, 'bbl_job_language' );
			$language_object = $language_object[0];
			$language        = $language_object->slug;
		}
	}

	if ( empty( $language ) ) {
		$language = 'en_US';
	}

	new Fieldmanager_Context_Storable(
		'Fieldmanager_Group',
		array(
			'name'     => 'page_intro_block',
			'children' => array(
				'intro_title'   => new Fieldmanager_Textfield(
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
		),
		array(
			'add_meta_box' => array(
				__( 'Page Intro', 'internetorg' ),
				'page',
				'internetorg_page_home_after_title',
				'high',
			),
		)
	);

	new Fieldmanager_Context_Storable(
		'Fieldmanager_TextArea',
		array(
			'name'       => 'page_subtitle',
			'label'      => __( 'Subtitle', 'internetorg' ),
			'attributes' => array(
				'rows' => 3,
				'cols' => 30,
			),
		),
		array(
			'add_meta_box' => array(
				__( 'Additional page configuration', 'internetorg' ),
				'page',
				'internetorg_page_home_after_title',
				'high',
			),
		)
	);

	new Fieldmanager_Context_Storable(
		'Fieldmanager_Group',
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
				'content'        => new Babble_Fieldmanager_RichTextarea( __( 'Description', 'internetorg' ) ),
				'src'            => new Fieldmanager_Select(
					__( 'Source', 'internetorg' ),
					array(
						'name'        => 'src',
						'first_empty' => true,
						'options'     => array(
							'page'   => __( 'Page, Post, or Story' ),
							'custom' => __( 'Custom Link', 'internetorg' ),
						),
					)
				),
				'slug'           => new Fieldmanager_TextField(
					__( 'Section Slug', 'internetorg' ),
					array(
						'display_if' => array(
							'src'   => 'src',
							'value' => 'custom',
						),
					)
				),
				'url-src'        => new Fieldmanager_Select(
					__( 'URL Source', 'internetorg' ),
					array(
						'datasource' => new Fieldmanager_Datasource_Post(
							array(
								'query_args' => array(
									'post_type' => internetorg_get_multiple_shadow_post_types(
										array(
											'page',
											'post',
											'io_story',
										),
										$language
									),
									'posts_per_page' => 100,
								),
								'use_ajax'   => false,
							)
						),
						'display_if' => array(
							'src'   => 'src',
							'value' => 'page',
						),
					)
				),
				'theme'          => new Fieldmanager_Select(
					array(
						'label'   => 'Select a Theme',
						'options' => array(
							'approach' => __( 'Approach', 'internetorg' ),
							'mission'  => __( 'Mission', 'internetorg' ),
							'impact'   => __( 'Impact', 'internetorg' ),
						),
					)
				),
				'image'          => new Fieldmanager_Media(
					__( 'Background Image', 'internetorg' )
				),
				'call-to-action' => new Fieldmanager_Group(
					array(
						'label'          => __( 'Call to action', 'internetorg' ),
						'label_macro'    => __( 'Call to action: %s', 'internetorg' ),
						'add_more_label' => __( 'Add another CTA', 'internetorg' ),
						'limit'          => 5,
						'collapsed'      => false,
						'collapsible'    => true,
						'sortable'       => true,
						'children'       => array(
							'title'    => new Fieldmanager_TextField(
								__( 'CTA Title', 'internetorg' )
							),
							'text'     => new Babble_Fieldmanager_RichTextarea(
								__( 'Content', 'internetorg' )
							),
							'cta_src'  => new Fieldmanager_Select(
								__( 'Link Source', 'internetorg' ),
								array(
									'name'        => 'cta_src',
									'first_empty' => true,
									'options'     => array(
										'page'   => __( 'Page, Post, or Story' ),
										'custom' => __( 'Custom Link', 'internetorg' ),
									),
								)
							),
							'link'     => new Fieldmanager_TextField(
								__( 'Link', 'internetorg' ),
								array(
									'display_if' => array(
										'src'   => 'cta_src',
										'value' => 'custom',
									),
								)
							),
							'link_src' => new Fieldmanager_Select(
								__( 'URL Source', 'internetorg' ),
								array(
									'datasource' => new Fieldmanager_Datasource_Post(
										array(
											'query_args' => array(
												'post_type' => internetorg_get_multiple_shadow_post_types(
													array(
														'page',
														'post',
														'io_story',
													),
													$language
												),
												'posts_per_page' => 100,
											),
											'use_ajax'   => false,
										)
									),
									'display_if' => array(
										'src'   => 'cta_src',
										'value' => 'page',
									),
								)
							),
							'image'    => new Fieldmanager_Media(
								__( 'Image', 'internetorg' )
							),
						),
					)
				),
			),
		),
		array(
			'add_meta_box' => array(
				__( 'Content Areas', 'internetorg' ),
				array(
					'page',
				),
			),
		)
	);

	new Fieldmanager_Context_Storable(
		'Fieldmanager_Autocomplete',
		array(
			'name'           => 'next_page',
			'show_edit_link' => true,
			'datasource'     => new Fieldmanager_Datasource_Post(
				array(
					'query_args' => array(
						'post_type' => internetorg_get_shadow_post_types_for_ajax( 'page' ),
						'posts_per_page' => 100,
					),
				)
			),
		),
		array(
			'add_meta_box' => array(
				__( 'Next Page', 'internetorg' ),
				array(
					'page',
				),
			),
		)
	);

}

add_action( 'init', 'internetorg_page_metaboxes' );

/**
 * Add Babble/Fieldmanager metaboxes to io_ctntwdgt post_type.
 */
function internetorg_content_widget_metaboxes() {

	// Bail early as this should go to admin only.
	if ( ! is_admin() ) {
		return;
	}

	new Fieldmanager_Context_Storable(
		'Fieldmanager_Group',
		array(
			'name'        => 'widget-data',
			'label'       => __( 'Call to Action', 'internetorg' ),
			'label_macro' => __( 'Call to Action: %s', 'internetorg' ),
			'collapsed'   => false,
			'sortable'    => false,
			'limit'       => 0,
			'children'    => array(
				'label' => new Fieldmanager_TextField( __( 'Button Label', 'internetorg' ) ),
				'url'   => new Fieldmanager_Link( __( 'URL', 'internetorg' ) ),
				'image' => new Fieldmanager_Media( __( 'File', 'internetorg' ) ),
			),
		),
		array(
			'add_meta_box' => array(
				__( 'Call to Action', 'internetorg' ),
				array(
					'io_ctntwdgt',
				),
			),
		)
	);
}

add_action( 'init', 'internetorg_content_widget_metaboxes' );

/**
 * Add Babble/Fieldmanager metaboxes to io_story post_type.
 */
function internetorg_story_metaboxes() {
	if ( ! is_admin() ) {
		return;
	}

	new Fieldmanager_Context_Storable(
		'Fieldmanager_TextArea',
		array(
			'name'       => 'page_subtitle',
			'label'      => __( 'Subtitle', 'internetorg' ),
			'attributes' => array(
				'rows' => 3,
				'cols' => 30,
			),
		),
		array(
			'add_meta_box' => array(
				__( 'Additional page configuration', 'internetorg' ),
				array(
					'io_story'
				),
				'internetorg_page_home_after_title',
				'high',
			),
		)
	);

}

add_action( 'init', 'internetorg_story_metaboxes' );

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

/**
 * Retrieve a list of the active language codes from babble.
 *
 * @return array
 */
function internetorg_get_active_lang_codes() {

	if ( ! function_exists( 'bbl_get_active_langs' ) ) {
		return array();
	}

	$active_langs = bbl_get_active_langs();

	if ( empty( $active_langs ) ) {
		return array();
	}

	return wp_list_pluck( $active_langs, 'code' );
}

/**
 * Retrieve a list of Babble's "shadow" post_types for a given post_type and a language.
 *
 * Useful for Fieldmanager_Datasource_Post in conjunction with Fieldmanager_Select.
 * Don't use this with Fieldmanager_Autocomplete, $language doesn't appear to be available when FM does AJAX.
 * A shadow post type is essentially a post_type appended with language code, posttype_languagecode.
 * If bbl_get_post_type_in_lang function is not available, will return a single element array of the given post_type.
 * If there are no shadow post types for the language, will return a single element array of the given post_type.
 * If there are shadow post types, returns an array of the given original and the corresponding shadow post_type.
 *
 * @used-by internetorg_get_multiple_shadow_post_types
 *
 * @param string $post_type The post_type to get shadow_post_types for. Optional. Defaults to 'page'.
 * @param string $language  The language code.
 *
 * @return array An array of post_types.
 */
function internetorg_get_shadow_post_types_by_lang( $post_type = 'page', $language = 'en_US' ) {

	if ( ! function_exists( 'bbl_get_post_type_in_lang' ) ) {
		return array( $post_type );
	}

	if ( bbl_get_default_lang_code() === $language ) {
		return array( $post_type );
	}

	$bbl_shadow_post_types = bbl_get_post_type_in_lang( $post_type, $language );

	if ( empty( $bbl_shadow_post_types ) ) {
		return array( $post_type );
	}

	return array(
		$post_type,
		$bbl_shadow_post_types,
	);
}

/**
 * Retrieve a list of Babble's "shadow" post_types for a given array of original post_types and a language.
 *
 * Useful for Fieldmanager_Datasource_Post in conjunction with Fieldmanager_Select.
 * Don't use this with Fieldmanager_Autocomplete, $language doesn't appear to be available when FM does AJAX.
 * A shadow post type is essentially a post_type appended with language code, posttype_languagecode.
 *
 * @uses internetorg_get_shadow_post_types_by_lang
 *
 * @param array  $post_types An array of post_types to get shadow_post_types for. Optional. Defaults to array( 'page' ).
 * @param string $language   The language code.
 *
 * @return array An array of post_types.
 */
function internetorg_get_multiple_shadow_post_types( $post_types = array( 'page' ), $language = 'en_US' ) {

	foreach ( $post_types as $post_type ) {
		$types[] = array_values( internetorg_get_shadow_post_types_by_lang( $post_type, $language ) );
	}

	if ( empty( $types ) ) {
		return $post_types;
	}

	foreach ( $types as $outer_key => $type_array ) {
		foreach ( $type_array as $inner_key => $type ) {
			array_push( $post_types, $type );
		}
	}

	return array_unique( $post_types );
}

/**
 * Retrieve a list of all of Babble's "shadow" post_types for all languages for a given original post_type.
 *
 * Useful for Fieldmanager_Datasource_Post using AJAX, for example in conjunction with Fieldmanager_Autocomplete.
 * Don't use this with Fieldmanager_Select, the select menu gets rather large.
 *
 * @used-by internetorg_get_multiple_shadow_post_types_for_ajax
 *
 * @param string $post_type A post_type to get all shadow_post_types for. Optional. Defaults to 'page'.
 *
 * @return array An array of post_types.
 */
function internetorg_get_shadow_post_types_for_ajax( $post_type = 'page' ) {

	if ( ! function_exists( 'bbl_get_shadow_post_types' ) ) {
		return array( $post_type );
	}

	$post_types = bbl_get_shadow_post_types( $post_type );

	if ( empty( $post_types ) ) {
		return array( $post_type );
	}

	array_push( $post_types, $post_type );

	return array_unique( $post_types );
}

/**
 * Wrapper function for bbl_get_the_title_in_lang.
 *
 * Will use get_the_title if bbl_get_the_title_in_lang function is not available.
 *
 * @param int|object $post_id   Either a WP Post object, or a post ID.
 * @param string     $lang_code The code for the language the title is requested in.
 * @param bool       $fallback  Whether to provide a fallback title in the default language if the requested language
 *                              is unavailable (defaults to false).
 *
 * @return string|void
 */
function internetorg_get_the_title_in_lang( $post_id = null, $lang_code = null, $fallback = false ) {

	if ( ! function_exists( 'bbl_get_the_title_in_lang' ) ) {
		return get_the_title( $post_id );
	}

	return bbl_get_the_title_in_lang( $post_id, $lang_code, $fallback );
}

/**
 * Wrapper function for bbl_get_the_permalink_in_lang.
 *
 * Will use get_the_permalink if bbl_get_the_permalink_in_lang function is not available.
 *
 * @param int|object $post_id   Either a WP Post object, or a post ID.
 * @param string     $lang_code The code for the language the title is requested in.
 * @param bool       $fallback  Whether to provide a fallback title in the default language if the requested language
 *                              is unavailable (defaults to false).
 *
 * @return false|string|void
 */
function internetorg_get_the_permalink_in_lang( $post_id = null, $lang_code = null, $fallback = false ) {

	if ( ! function_exists( 'bbl_get_the_permalink_in_lang' ) ) {
		return get_the_permalink( $post_id );
	}

	return bbl_get_the_permalink_in_lang( $post_id, $lang_code, $fallback );
}

/**
 * Wrapper function for bbl_get_current_content_lang_code.
 *
 * Will use get_locale if bbl_get_current_content_lang_code function is not available.
 *
 * @return string
 */
function internetorg_get_current_content_lang_code() {

	if ( ! function_exists( 'bbl_get_current_content_lang_code' ) ) {
		return get_locale();
	}

	return bbl_get_current_content_lang_code();
}

/**
 * Wrapper function for bbl_get_base_post_type.
 *
 * Return the base post type (in the default language) for a provided post type.
 * Will return the provided $post_type if bbl_get_base_post_type function is not available.
 *
 * @param string $post_type The name of a post type.
 *
 * @return string The name of the base post type.
 */
function internetorg_get_base_post_type( $post_type ) {

	if ( ! function_exists( 'bbl_get_base_post_type' ) ) {
		return $post_type;
	}

	return bbl_get_base_post_type( $post_type );
}

/**
 * Wrapper function for bbl_get_base_post_types.
 *
 * Will use get_post_types if bbl_get_base_post_types function is not available.
 *
 * @return array
 */
function internetorg_get_base_post_types() {

	if ( ! function_exists( 'bbl_get_base_post_types' ) ) {
		return get_post_types( array(), 'objects' );
	}

	return bbl_get_base_post_types();
}

/**
 * Retrieve a list of Babble's "shadow" post_types for a given array of original post_types.
 *
 * Useful for Shortcake UI.
 *
 * @uses internetorg_get_shadow_post_types_for_ajax
 *
 * @param array $post_types An array of post_types to get shadow_post_types for. Optional. Defaults to array( 'page' ).
 *
 * @return array An array of post_types.
 */
function internetorg_get_multiple_shadow_post_types_for_ajax( $post_types = array( 'page' ) ) {

	foreach ( $post_types as $post_type ) {
		$types[] = array_values( internetorg_get_shadow_post_types_for_ajax( $post_type ) );
	}

	if ( empty( $types ) ) {
		return $post_types;
	}

	foreach ( $types as $outer_key => $type_array ) {
		foreach ( $type_array as $inner_key => $type ) {
			array_push( $post_types, $type );
		}
	}

	return array_unique( $post_types );
}
