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
	 * @todo This is static placeholder for now at request of FED.
	 * @todo Remove static and use bbl_get_switcher_links when Babble is actualy integrated.
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

		$selected_lang = null;

		// Select list of items, hidden from the user but recreated with css/js.
		echo '<select onchange="document.location.href=this.options[this.selectedIndex].value;">';
		foreach ( $list as $item ) {
			// Skip languages for which there is no translation.
			if ( in_array( 'bbl-add', $item['classes'] ) ) {
				continue;
			}
			if ( $item['active'] ) {
				$selected_lang = $item;
			}
			if ( $item['href'] ) {
				echo '<option value="' . esc_url( $item['href'] ) . '">' . esc_html( $item['lang']->display_name ) . '</option>';
			}
		}
		echo '</select>';

		// Display the current language to the user.
		echo '<div class="langSelect-label">';
		echo esc_html( ! empty( $selected_lang ) ? $selected_lang['lang']->display_name : '' );
		echo '</div>';

		// Display list of available languages to the user (recreated select).
		echo '<div class="langSelect-menu" style="height: auto;">';
		foreach ( $list as $item ) {
			if ( ! empty( $item ) ) {
				echo '<div class="langSelect-menu-item"><span>' . esc_html( $item['lang']->display_name ) . '</span></div>';
			}
		}
		echo '</div>';
	}
endif;

if ( ! function_exists( 'internetorg_add_translatable_posttypes' ) ) :
	/**
	 * Add our CPTs to the list of translatable post types.
	 *
	 * @param array $posttypes Current list of translatable post types.
	 *
	 * @return array New list of translatable post types.
	 */
	function internetorg_add_translatable_posttypes( $posttypes ) {
		if ( ! is_array( $posttypes ) ) {
			$posttypes = array();
		}

		$cptlist = array(
			'io_press',
			'io_story',
			'io_campaign',
			'io_freesvc',
			'io_ctntwdgt',
		);

		return array_merge( $posttypes, $cptlist );
	}

	// Hook on to the filter so our CPTs get listed.
	add_filter( 'bogo_localizable_post_types', 'internetorg_add_translatable_posttypes' );
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

function internetorg_video_metaboxes() {

	//bail early as this should go to admin only
	if ( ! is_admin() ) {
		return;
	}

	new Babble_Translatable_Fieldmanager(
		'Fieldmanager_TextField',
		array(
			'name'  => 'video-duration',
			'label' => __( 'Video Duration', 'internetorg' ),
		),
		array(
			'add_meta_box' => array(
				'Video Duration',
				array( 'io_video' ),
			)
		)
	);

	new Babble_Translatable_Fieldmanager(
		'Fieldmanager_Link',
		array(
			'name'  => 'video-url',
			'label' => __( 'Video URL', 'internetorg' ),
		),
		array(
			'add_meta_box' => array(
				'Video URL',
				array( 'io_video' ),
			)
		)
	);
}

add_action( 'init', 'internetorg_video_metaboxes' );

function internetorg_page_metaboxes() {

	//bail early as this should go to admin only
	if ( ! is_admin() ) {
		return;
	}

	new Babble_Translatable_Fieldmanager(
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
				array( 'page' ),
				'internetorg_page_home_after_title',
				'high',
			)
		)
	);

	new Babble_Translatable_Fieldmanager(
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
				'content'        => new Fieldmanager_RichTextarea( __( 'Description', 'internetorg' ) ),
				'src' => new Fieldmanager_Select(
					__( 'Source', 'internetorg' ),
					array(
						'name'    => 'src',
						'first_empty' => true,
						'options' => array(
							'page' => __( 'Page, Post, or Story' ),
							'custom' => __( 'Custom Link', 'internetorg' ),
						),
					)
				),
				'slug'  => new Fieldmanager_TextField(
					__( 'Section Slug', 'internetorg' ),
					array(
						'display_if' => array(
							'src' => 'src',
							'value' => 'custom',
						),
					)
				),
				'url-src' => new Fieldmanager_Select(
					__( 'URL Source', 'internetorg' ),
					array(
						'datasource' => new Fieldmanager_Datasource_Post(
							array(
								'query_args' => array(
									'post_type' => array(
										'io_story',
										'post',
										'page',
									),
									'posts_per_page' => 50,
								),
								'use_ajax' => false,
							)
						),
						'display_if' => array(
							'src' => 'src',
							'value' => 'page',
						),
					)
				),
				'theme' => new Fieldmanager_Select(
					array(
						'label' => 'Select a Theme',
						'options' => array(
							'approach' => __( 'Approach', 'internetorg' ),
							'mission' => __( 'Mission', 'internetorg' ),
							'impact' => __( 'Impact', 'internetorg' ),
						),
					)
				),
				'image' => new Fieldmanager_Media(
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
							'title' => new Fieldmanager_TextField(
								__( 'CTA Title', 'internetorg' )
							),
							'text'  => new Fieldmanager_RichTextarea(
								__( 'Content', 'internetorg' )
							),
							'cta_src' => new Fieldmanager_Select(
								__( 'Link Source', 'internetorg' ),
								array(
									'name'    => 'cta_src',
									'first_empty' => true,
									'options' => array(
										'page' => __( 'Page, Post, or Story' ),
										'custom' => __( 'Custom Link', 'internetorg' ),
									),
								)
							),
							'link' => new Fieldmanager_TextField(
								__( 'Link', 'internetorg' ),
								array(
									'display_if' => array(
										'src' => 'cta_src',
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
												'post_type' => array(
													'io_story',
													'post',
													'page',
												),
												'posts_per_page' => 50,
											),
											'use_ajax' => false,
										)
									),
									'display_if' => array(
										'src' => 'cta_src',
										'value' => 'page',
									),
								)
							),
							'image' => new Fieldmanager_Media(
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
			)
		)
	);

	new Babble_Translatable_Fieldmanager(
		'Fieldmanager_Autocomplete',
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
		),
		array(
			'add_meta_box' => array(
				__( 'Next Page', 'internetorg' ),
				array(
					'page',
				),
			)
		)
	);

}

add_action( 'init', 'internetorg_page_metaboxes' );
