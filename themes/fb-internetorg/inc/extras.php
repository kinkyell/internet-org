<?php
/**
 * Custom functions that act independently of the theme templates.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Internet.org
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 *
 * @return array
 */
function internetorg_body_classes( $classes ) {
	// Adds a class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	return $classes;
}

add_filter( 'body_class', 'internetorg_body_classes' );

if ( ! function_exists( 'internetorg_change_excerpt_length' ) ) :
	/**
	 * Change the default length of the excerpts to 25 words (from default 55).
	 *
	 * @param int $length Original length.
	 *
	 * @return int New length.
	 */
	function internetorg_change_excerpt_length( $length ) {
		return 25;
	}

	add_action( 'excerpt_length', 'internetorg_change_excerpt_length' );
endif;

if ( ! function_exists( 'internetorg_change_excerpt_more' ) ) :
	/**
	 * Change the default "more" indicator.
	 *
	 * @param string $more Current more indicator.
	 *
	 * @return string New more indicator.
	 */
	function internetorg_change_excerpt_more( $more ) {
		return '&hellip;';
	}

	add_action( 'excerpt_more', 'internetorg_change_excerpt_more' );
endif;

/**
 * Improves the caption shortcode with HTML5 figure & figcaption; microdata & wai-aria attributes.
 *
 * @link http://joostkiens.com/improving-wp-caption-shortcode/
 *
 * @param  string $val     The caption output. Default empty.
 * @param  array  $attr    Attributes of the caption shortcode.
 * @param  string $content The image element, possibly wrapped in a hyperlink.
 *
 * @return string          Shortcode output
 */
function internetorg_img_caption_shortcode_filter( $val, $attr, $content = null ) {

	$cleanedAttributes = shortcode_atts(
		array(
			'id'      => '',
			'align'   => 'aligncenter',
			'width'   => '',
			'caption' => '',
		),
		$attr
	);

	$id      = $cleanedAttributes['id'];
	$width   = $cleanedAttributes['width'];
	$caption = $cleanedAttributes['caption'];
	$align   = $cleanedAttributes['align'];

	// No caption, no dice... But why width?
	if ( 1 > (int) $width || empty( $caption ) ) {
		return $val;
	}

	// Add itemprop="contentURL" to image - Ugly hack.
	$content = str_replace( '<img', '<img itemprop="contentURL"', $content );

	return '<figure id="' . esc_attr( $id ) . '" aria-describedby="figcaption_' . esc_attr( $id ) . '" class="wp-caption '
	       . esc_attr( $align ) . '" itemscope itemtype="http://schema.org/ImageObject" style="width: '
	       . ( 0 + (int) $width ) . 'px">' . do_shortcode( $content ) . '<figcaption id="figcaption_' . esc_attr( $id )
	       . '" class="wp-caption-text" itemprop="description">' . esc_html( $caption ) . '</figcaption></figure>';
}

add_filter( 'img_caption_shortcode', 'internetorg_img_caption_shortcode_filter', 10, 3 );
