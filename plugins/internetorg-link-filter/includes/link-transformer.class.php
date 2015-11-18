<?php
/**
 * File LinkTransformer.class.php
 *
 * @package Internet.org
 */

/**
 * Class LinkTransformer
 *
 * Transform existing link into normalized format specific to
 * the currently selected language
 *
 * @author Ben Koren <bkoren@nerdery.com>
 * @author Edward Pfremmer <epfremme@nerdery.com>
 */
class LinkTransformer {

	/**
	 * Transforms the provided link to point to the proper domain and language.
	 *
	 * @param string $url The URL to be transformed. May or may not already contain a language code.
	 *
	 * @return string
	 */
	public function transform( $url ) {

		// We only transform internal URLs, not external (ex: google.com).
		if ( ! $this->is_internal( $url ) ) {
			return $url;
		}


		$domain    = get_blog_details()->domain;

		$langCode  = bbl_get_current_content_lang_code();
		$urlPrefix = bbl_get_prefix_from_lang_code( $langCode );

		$parsedUrl = parse_url( $url );
		$pathParts = explode( '/', $parsedUrl['path'] );

		// We're only transforming absolute URLs, not relative.
		if ( ! empty( $pathParts[0] ) ) {
			return $url;
		}

		// Strip out slashes at the beginning and end of our array.
		$pathParts = array_values( array_filter( $pathParts ) );

		if ( ! empty( $pathParts ) && $this->is_language_code( $pathParts[0] ) ) {
			$pathParts[0] = $urlPrefix;
		} else {
			array_shift( $pathParts, $urlPrefix );
		}

		$scheme  = isset( $pathParts['scheme'] ) ? $pathParts['scheme'] : 'http';
		$newPath = implode( '/', $pathParts );
		$newPath = sprintf( '%s/%s/%s', $scheme, $domain, $newPath );

		// If the original path had a trailing slash, add it back in.
		if ( '/' === substr( $parsedUrl['path'], - 1 ) ) {
			$newPath = $newPath . '/';
		}

		return $newPath;
	}

	/**
	 * Test if the input string is a potential language code. This tests only that the
	 * string is exactly 2 [a-z] case insensitive characters
	 *
	 * This could be made more accurate by fetching only the supported languages from babble and
	 * testing against that subset, but is sufficient for current requirements.
	 *
	 * @param string $string the string being checked.
	 *
	 * @return bool
	 */
	protected function is_language_code( $string ) {
		return (bool) preg_match( '/^[a-z]{2}$/i', $string );
	}

	/**
	 * Determines whether the given URL is internal. This is used to determine whether the link
	 * should be transformed/localized.
	 *
	 * @param string $url the url being processed.
	 *
	 * @return bool
	 */
	protected function is_internal( $url ) {
		// @TODO: confirm that this works as intended
		return internetorg_is_internal_url( $url );
	}
}
