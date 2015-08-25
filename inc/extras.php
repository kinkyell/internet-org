<?php
/**
 * Custom functions that act independently of the theme templates
 *
 * Eventually, some of the functionality here could be replaced by core features
 *
 * @package Internet.org
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
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

if ( version_compare( $GLOBALS['wp_version'], '4.1', '<' ) ) :
	/**
	 * Filters wp_title to print a neat <title> tag based on what is being viewed.
	 *
	 * @param string $title Default title text for current view.
	 * @param string $sep Optional separator.
	 * @return string The filtered title.
	 */
	function internetorg_wp_title( $title, $sep ) {
		if ( is_feed() ) {
			return $title;
		}

		global $page, $paged;

		// Add the blog name.
		$title .= esc_html( get_bloginfo( 'name', 'display' ) );

		// Add the blog description for the home/front page.
		$site_description = esc_html( get_bloginfo( 'description', 'display' ) );
		if ( $site_description && ( is_home() || is_front_page() ) ) {
			$title .= sprintf( ' %s %s', $sep, $site_description );
		}

		// Add a page number if necessary.
		if ( ( $paged >= 2 || $page >= 2 ) && ! is_404() ) {
			$title .= sprintf( ' %s ' . esc_html__( 'Page %s', 'internetorg' ), $sep, max( $paged, $page ) );
		}

		return $title;
	}
	add_filter( 'wp_title', 'internetorg_wp_title', 10, 2 );

	/**
	 * Title shim for sites older than WordPress 4.1.
	 *
	 * @link https://make.wordpress.org/core/2014/10/29/title-tags-in-4-1/
	 * @todo Remove this function when WordPress 4.3 is released.
	 */
	function internetorg_render_title() {
		?>
		<title><?php wp_title( '|', true, 'right' ); ?></title>
		<?php
	}
	add_action( 'wp_head', 'internetorg_render_title' );
endif;


if ( ! function_exists( 'internetorg_change_excerpt_length' ) ) :
	/**
	 * Change the default length of the excerpts to 25 words (from default 55)
	 *
	 * @param int $length original length
	 * @return int new length
	 */
	function internetorg_change_excerpt_length( $length ) {
		return 25;
	}
	add_action( 'excerpt_length', 'internetorg_change_excerpt_length' );
endif;

if ( ! function_exists( 'internetorg_change_excerpt_more' ) ) :
	/**
	 * Change the default "more" indicator
	 *
	 * @param string $more current more indicator
	 * @return string new more indicator
	 */
	function internetorg_change_excerpt_more( $more ) {
		return '&hellip;';
	}
	add_action( 'excerpt_more', 'internetorg_change_excerpt_more' );
endif;

/**
 * Improves the caption shortcode with HTML5 figure & figcaption; microdata & wai-aria attributes
 *
 * based off this solution http://joostkiens.com/improving-wp-caption-shortcode/
 *
 * @param  string $val     Empty
 * @param  array  $attr    Shortcode attributes
 * @param  string $content Shortcode content
 * @return string          Shortcode output
 */
function jk_img_caption_shortcode_filter($val, $attr, $content = null)
{
	$cleanedAttributes = shortcode_atts( array(
		'id'      => '',
		'align'   => 'aligncenter',
		'width'   => '',
		'caption' => '',
	), $attr );

	$id      = $cleanedAttributes['id'];
	$width   = $cleanedAttributes['width'];
	$caption = $cleanedAttributes['caption'];
	$align   = $cleanedAttributes['align'];

	// No caption, no dice... But why width?
	if ( 1 > (int) $width || empty( $caption ) ) {
		return $val;
	}

	if ( $id ) {
		$id = esc_attr( $id );
	}

	// Add itemprop="contentURL" to image - Ugly hack
	$content = str_replace( '<img', '<img itemprop="contentURL"', $content );

	return '<figure id="' . $id . '" aria-describedby="figcaption_' . $id . '" class="wp-caption ' . esc_attr( $align ) . '" itemscope itemtype="http://schema.org/ImageObject" style="width: ' . ( 0 + (int) $width ) . 'px">' . do_shortcode( $content ) . '<figcaption id="figcaption_'. $id . '" class="wp-caption-text" itemprop="description">' . $caption . '</figcaption></figure>';
}
add_filter( 'img_caption_shortcode', 'jk_img_caption_shortcode_filter', 10, 3 );