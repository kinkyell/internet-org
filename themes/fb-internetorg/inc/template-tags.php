<?php
/**
 * Custom template tags for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Internet.org
 */

if ( ! function_exists( 'internetorg_get_post_publish_time_string' ) ) :
	/**
	 * Get formatted data/time string for the current post.
	 *
	 * @return string Formatted time/date post was published.
	 */
	function internetorg_get_post_publish_time_string() {
		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';

		$time_string = sprintf(
			$time_string,
			esc_attr( get_the_date( 'c' ) ),
			esc_html( get_the_date() ),
			esc_attr( get_the_modified_date( 'c' ) ),
			esc_html( get_the_modified_date()
			)
		);

		return $time_string;
	}
endif;

if ( ! function_exists( 'internetorg_posted_on_date' ) ) :
	/**
	 * No frills way to print (translated) the post's publish date.
	 *
	 * @see  internetorg_get_post_publish_time_string
	 *
	 * @note This method echos content directly to the screen.
	 *
	 * @return void
	 */
	function internetorg_posted_on_date() {
		$time_string = internetorg_get_post_publish_time_string();

		printf( esc_html_x( '%s', 'post date', 'internetorg' ), wp_kses_post( $time_string ) );
	}
endif;

if ( ! function_exists( 'internetorg_link_class' ) ) :
	/**
	 * Append special classes to a link depending on the post.
	 *
	 * @return void
	 */
	function internetorg_link_class() {
		$extra_classes = '';

		$show_english_content_dialog = get_post_meta( get_the_ID(), 'show-english-content-dialog', true );

		if ($show_english_content_dialog) {
			$extra_classes .= ' js-englishContentDialog';
		}

		return trim($extra_classes);
	}

endif;

if ( ! function_exists( 'internetorg_entry_footer' ) ) :
	/**
	 * Prints HTML with meta information for the categories, tags and comments.
	 */
	function internetorg_entry_footer() {
		// Hide category and tag text for pages.
		if ( in_array( get_post_type(), internetorg_get_shadow_post_types_for_ajax( 'post' ) ) ) {
			/* translators: used between list items, there is a space after the comma. */
			$categories_list = get_the_category_list( esc_html__( ', ', 'internetorg' ) );
			if ( $categories_list && internetorg_categorized_blog() ) {
				printf(
					'<span class="cat-links">' . esc_html__( 'Posted in %1$s', 'internetorg' ) . '</span>',
					esc_html( $categories_list )
				); // WPCS: XSS OK.
			}

			/* translators: used between list items, there is a space after the comma. */
			$tags_list = get_the_tag_list( '', esc_html__( ', ', 'internetorg' ) );
			if ( $tags_list ) {
				printf(
					'<span class="tags-links">' . esc_html__( 'Tagged %1$s', 'internetorg' ) . '</span>',
					esc_html( $tags_list )
				); // WPCS: XSS OK.
			}
		}

		if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
			echo '<span class="comments-link">';
			comments_popup_link(
				esc_html__( 'Leave a comment', 'internetorg' ),
				esc_html__( '1 Comment', 'internetorg' ),
				esc_html__( '% Comments', 'internetorg' )
			);
			echo '</span>';
		}

		edit_post_link( esc_html__( 'Edit', 'internetorg' ), '<span class="edit-link">', '</span>' );
	}
endif;

if ( ! function_exists( 'internetorg_entry_footer_archive' ) ) :
	/**
	 * Display necessary HTML for post footer suitable for archive listing.
	 *
	 * Less information is displayed than is used on default entry footer, no date, author, comment count, comment link, etc.
	 *
	 * @see internetorg_entry_footer
	 *
	 * @return void
	 */
	function internetorg_entry_footer_archive() {

		// Read more link.
		echo '<div class="feature-cta">';
		printf( esc_html( __( 'Read More &rarr;', 'internetorg' ) ) );
		echo '</div>';

		// Display the edit link if an authorized user is logged in.
		edit_post_link( esc_html__( 'Edit', 'internetorg' ), '<span class="edit-link">', '</span>' );
	}
endif;

/**
 * Returns true if a blog has more than 1 category.
 *
 * @return bool
 */
function internetorg_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'internetorg_categories' ) ) ) {
		// Create an array of all the categories that are attached to posts.
		$all_the_cool_cats = get_categories(
			array(
				'fields'     => 'ids',
				'hide_empty' => 1,
				// We only need to know if there is more than one category.
				'number'     => 2,
			)
		);

		// Count the number of categories that are attached to the posts.
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'internetorg_categories', $all_the_cool_cats );
	}

	if ( $all_the_cool_cats > 1 ) {
		// This blog has more than 1 category so internetorg_categorized_blog should return true.
		return true;
	} else {
		// This blog has only 1 category so internetorg_categorized_blog should return false.
		return false;
	}
}

/**
 * Flush out the transients used in internetorg_categorized_blog.
 */
function internetorg_category_transient_flusher() {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	// Like, beat it. Dig?
	delete_transient( 'internetorg_categories' );
}

add_action( 'edit_category', 'internetorg_category_transient_flusher' );
add_action( 'save_post', 'internetorg_category_transient_flusher' );

/**
 * Print the vip_powered_wpcom function, wrapped with our legal tag markup.
 *
 * Concatenating the string together is a little bit gross, but our JS dev needs a version with no carriage returns.
 *
 * @param string $class The classes to apply to the inner wrapper div. Defaults to "pwdByVip-txt".
 */
function internetorg_vip_powered_wpcom( $class = 'pwdByVip-txt' ) {

	$string = '<small class="pwdByVip"><div class="' . esc_attr( $class ) . '">'
	          . sprintf( esc_html__( 'Facebook &copy; %1$s', 'internetorg' ), date( 'Y' ) ) . ' ' . vip_powered_wpcom()
	          . '</div></small>';

	echo wp_kses_post( $string );
}
