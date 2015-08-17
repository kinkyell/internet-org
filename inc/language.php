<?php
/**
 * This file is for language related functions
 *
 * @package
 * @author arichard <arichard@nerdery.com>
 */

if ( ! function_exists( 'internetorg_language_switcher' ) ) :
	/**
	 * Builds the language switcher needed for the FE of the site
	 *
	 * @return void
	 */
	function internetorg_language_switcher() {

		$list = bogo_language_switcher_links();

		$optionsList  = array();
		$divList      = array();
		$selectedLang = null;

		foreach ( $list as $item ) {
			if ( $item['href'] ) {
				$optionsList[] = '<option value="' . esc_url( $item['href'] ) . '">' . esc_html( $item['lang']->title ) . '</option>';

				$divList[] = '<div class="langSelect-menu-item"><span>' . esc_html( $item['lang']->title ) . '</span></div>';
			}
		}

		// display the selector, NOTE: we are not caching here because each user
		// will need their own selection
		echo '<select onchange="document.location.href=this.options[this.selectedIndex].value;">';
		echo implode( "\n", $optionsList );
		echo '</select>';
		echo '<div class="langSelect-label">' . esc_html( $selectedLang['lang']->display_name ) . '</div>';
		echo '<div class="langSelect-menu" style="height: auto;">';
		echo implode( "\n", $divList );
		echo '</div>';

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
		'ar',      // Arabic / العربية
		'arc',     // Aramaic
		'arq',     // Algerian Arabic / الدارجة الجزايرية
		'azb',     // South Azerbaijani / گؤنئی آذربایجان
		'az_TR',   // Azerbaijani (Turkey) / Azərbaycan Türkcəsi
		'bcc',     // Balochi Southern / بلوچی مکرانی
		'bqi',     // Bakthiari / بختياري
		'ckb',     // Sorani Kurdish / کوردی
		'dv',      // Dhivehi
		'fa',      // Persian / فارسی
		'fa_IR',   // Persian / فارسی
		'fa_AF',   // Persian (Afghanistan) / (افغانستان) فارسی
		'glk',     // Gilaki / گیلکی
		'ha',      // Hausa / هَوُسَ
		'haz',     // Hazaragi / هزاره گی
		'he',      // Hebrew / עִבְרִית
		'he_IL',   // Hebrew / עִבְרִית
		'mzn',     // Mazanderani / مازِرونی
		'pnb',     // Western Punjabi / پنجابی
		'ps',      // Pashto / پښتو
		'sd',      // Sindhi / سنڌي
		'ug',      // Uyghur / ئۇيغۇرچە
		'ur',      // Urdu / اردو
		'yi',      // Yiddish / ייִדיש'
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
