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

add_action( 'init',
	function () {

		//bail early as this should go to admin only
		if ( ! is_admin() ) {
			return;
		}

		new Babble_Translatable_Fieldmanager(
			'Fieldmanager_TextField',
			array(
				'name' => 'demo-field',
			),
			array(
				'add_meta_box' => array(
					'TextField Demo',
					array( 'page' ),
				)
			)
		);

		new Babble_Translatable_Fieldmanager(
			'Fieldmanager_Group',
			array(
				'name'     => 'demo-group',
				'children' => array(
					'field-one' => new Babble_Fieldmanager_TextField( 'First Field' ),
					'field-two' => new Babble_Fieldmanager_TextField( 'Second Field' ),
				),
			),
			array(
				'add_meta_box' => array(
					'Group demo',
					array( 'page' ),
				)
			)
		);

		new Babble_Translatable_Fieldmanager(
			'Fieldmanager_Group',
			array(
				'name'     => 'tabbed_meta_fields',
				'tabbed'   => 'vertical',
				'children' => array(
					'tab-1' => new Babble_Fieldmanager_Group(
						array(
							'label'    => 'First Tab',
							'children' => array(
								'text' => new Babble_Fieldmanager_Textfield( 'Text Field' ),
								'media'    => new Babble_Fieldmanager_Media( 'Media File' ),
							)
						)
					),
					'tab-2' => new Fieldmanager_Group(
						array(
							'label'    => 'Second Tab',
							'children' => array(
								'textarea' => new Babble_Fieldmanager_TextArea( 'TextArea' ),
								'media'    => new Babble_Fieldmanager_Media( 'Media File' ),
							)
						)
					),
				)
			),
			array(
				'add_meta_box' => array(
					'Tabbed Demo',
					array( 'page' ),
				)
			)
		);

		// using Fieldmanager for a slideshow - any number of slides,
		// with any number of related links
		new Babble_Translatable_Fieldmanager(
			'Fieldmanager_Group',
			array(
				'name'           => 'slideshow',
				'limit'          => 0,
				'label'          => __( 'New Slide', 'your-domain' ),
				'label_macro'    => array( __( 'Slide: %s', 'your-domain' ), 'title' ),
				'add_more_label' => __( 'Add another slide', 'your-domain' ),
				'collapsed'      => true,
				'sortable'       => true,
				'children'       => array(
					'title'       => new Babble_Fieldmanager_Textfield( __( 'Slide Title', 'your-domain' ) ),
					'slide'       => new Babble_Fieldmanager_Media( __( 'Slide', 'your-domain' ) ),
					'description' => new Babble_Fieldmanager_RichTextarea( __( 'Description', 'your-domain' ) ),
					'posts'       => new Babble_Fieldmanager_Autocomplete(
						array(
							'label'              => __( 'Related Posts', 'your-domain' ),
							'limit'              => 0,
							'sortable'           => true,
							'one_label_per_item' => false,
							'add_more_label'     => __( 'Add another related link', 'your-domain' ),
							'datasource'         => new Fieldmanager_Datasource_Post(
								array(
										'query_args' => array(
											'post_status' => 'any',
										),
									)
							),
							)
					),
				),
			),
			array(
				'add_meta_box' => array(
					__( 'Slides', 'your-domain' ),
					'page',
				)
			)
		);

		new Babble_Translatable_Fieldmanager(
			'Fieldmanager_RichTextarea',
			array(
				'name' => 'demo-editor',
			),
			array(
				'add_meta_box' => array(
					'Editor Demo',
					array( 'page', ),
				)
			)
		);
	}
);
