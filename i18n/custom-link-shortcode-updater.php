<?php
/**
 * Utility script to change post_id's used in [io-custom-link] shortcodes in alternate language content.
 * Currently only operates on actual post_content.
 * Was unable to complete the ability to traverse all the possible meta fields.
 * Not sure how critical that is at the moment.
 */

/**
 * Load WP Core so we have access to the APIs we need.
 * This is a terrible practice and only doing it here for brevity to complete a one off utility script.
 * Don't let this get onto production, and don't ever do this in plugins or themes, this is a hack job.
 * There are much better ways to do this when you're not in a hurry creating a little hack utility script.
 */
require_once( '../../../../../wp/wp-load.php' );

/**
 * Babble appears to hook into pre_get_posts and is causing unexpected behavior with this script.
 */
remove_all_filters( 'pre_get_posts' );

/**
 * Force this script to only deal with page post type and fr language code for simplified testing purposes.
 *
 * @var bool $debug_script
 */
$debug_script = false;

if ( empty( $debug_script ) ) {

	/** @var array $original_post_types */
	$original_post_types = array(
		'post',
		'page',
		'io_ctntwdgt',
		'io_story',
	);

	/** @var stdClass[] $langs */
	$langs = bbl_get_active_langs();

	/** @var array $lang_codes */
	$lang_codes = wp_list_pluck( $langs, 'code' );

} else {

	$original_post_types = array(
		'page',
	);

	$langs = bbl_get_lang( 'fr' );

	$lang_codes['fr'] = 'fr';
}

/**
 * Bail early if we have no langs.
 */
if ( empty( $langs ) ) {
	exit;
}

/** @var string $processing_post_type */
foreach ( $original_post_types as $processing_post_type ) {

	/** @var array $processing_posts_args */
	$processing_posts_args = array(
		'post_type'      => $processing_post_type,
		'posts_per_page' => - 1,
	);

	/** @var WP_Query $processing_posts */
	$processing_posts = new WP_Query( $processing_posts_args );

	if ( ! $processing_posts->have_posts() ) {
		continue;
	}

	while ( $processing_posts->have_posts() ) {

		/** Sets up the $post WP_Post object */
		$processing_posts->the_post();

		/**
		 * An array of WP_Post objects, including the original, keyed by language.
		 *
		 * @var WP_Post[] $post_translations
		 */
		$post_translations = bbl_get_post_translations( $post );

		/**
		 * An array of WP_Post objects of type bbl_job, keyed by language.
		 *
		 * @var WP_Post[] $bbl_jobs
		 */
		$bbl_jobs = Babble::get( 'jobs' )->get_completed_post_jobs( $post );

		foreach ( $post_translations as $lang => $translated_post ) {

			/** force testing of french only in debug mode. */
			if ( ! empty( $debug_script ) && 'fr' !== $lang ) {
				continue;
			}

			/** not interested in processing the original english version */
			if ( 'en' === $lang || 'en-US' === $lang || 'en_US' === $lang ) {
				continue;
			}

			/** @var WP_Post $bbl_job_post The bbl_job that corresponds to this shadow post */
			$bbl_job_post = ( ! empty( $bbl_jobs[ $lang ] ) ) ? $bbl_jobs[ $lang ] : '';

			/** Check the content for 'io-custom-link' shortcode. */
			if ( has_shortcode( $translated_post->post_content, 'io-custom-link' ) ) {
				/**
				 * Perform search/replace on content and update the post...
				 */
				$translated_post = internetorg_process_content_shortcodes(
					$translated_post,
					$post,
					$bbl_job_post
				);
			}
		}
	}
}

/**
 * Process the shortcodes found in the post content.
 *
 * @param WP_Post $translated_post The shadow post object.
 * @param WP_Post $original_post   The original english post object.
 * @param WP_Post $bbl_job_post    The bbl_job that corresponds to the shadow post object.
 *
 * @return WP_Post
 */
function internetorg_process_content_shortcodes( $translated_post, $original_post, $bbl_job_post ) {

	/** @var string $content The content to extract shortcodes from */
	$content = $translated_post->post_content;

	if ( empty( $content ) ) {
		return $translated_post;
	}

	/**
	 * An array of shortcode matches found in $content.
	 * The $matches array is structured like so...
	 *    0 - An array of complete shortcode string matches
	 *    1 - A typically empty array corresponding to extra [ to allow for escaping shortcodes with double [[]]
	 *    2 - An array of the shortcode names
	 *    3 - An array of the shortcode argument strings
	 *    4 - A typically empty array corresponding to self closing / shortcodes
	 *    5 - The content of a shortcode if it wraps some content.
	 *    6 - A typically empty array corresponding to extra ] to allow for escaping shortcodes with double [[]]
	 *
	 * @var array $matches
	 */
	$matches = internetorg_extract_shortcodes( $content );

	if ( empty( $matches ) ) {
		return $translated_post;
	}

	/** @var string $updated_content Copy of content for search/replace */
	$updated_content = $content;

	foreach ( $matches[0] as $key => $value ) {

		/** Bail early. */
		if ( 'io-custom-link' !== $matches[2][ $key ] ) {
			continue;
		}

		/** @var string $shortcode_string The entire shortcode that was matched in the content string */
		$shortcode_string = $matches[0][ $key ];

		/** @var string $shortcode_args The shortcode arguments */
		$shortcode_args = $matches[3][ $key ];

		/** @var array $shortcode_atts Parsed shortcode arguments (atts = attributes) */
		$shortcode_atts = shortcode_parse_atts( $shortcode_args );

		/**
		 * Bail early.
		 * $shortcode_atts[ 'source' ] should contain a URL for an external link, OR a post_id for an internal link.
		 */
		if ( empty( $shortcode_atts['source'] ) || ! is_numeric( $shortcode_atts['source'] ) ) {
			continue;
		}

		/** @var int $destination_post_id The ID of the post that is being linked to */
		$destination_post_id = absint( $shortcode_atts['source'] );

		/**
		 * Bail early.
		 */
		if ( empty( $destination_post_id ) ) {
			continue;
		}

		/** @var WP_Post|bool $alternate_destination_post The translated WP_Post object to link to, else false */
		$alternate_destination_post = bbl_get_post_in_lang(
			$destination_post_id,
			bbl_get_post_lang_code( $translated_post )
		);

		if ( empty( $alternate_destination_post ) ) {
			continue;
		}

		/** @var string $alternate_shortcode_string The full shortcode string updated with translated destination */
		$alternate_shortcode_string = str_replace(
			'source="' . $destination_post_id . '"',
			'source="' . $alternate_destination_post->ID . '"',
			$shortcode_string
		);

		/**
		 * Update the content string with the new link shortcode string.
		 */
		$updated_content = str_replace(
			$shortcode_string,
			$alternate_shortcode_string,
			$updated_content
		);
	}

	/**
	 * Fields to pass to wp_update_post to update the content
	 *
	 * @var array $translated_post_update
	 */
	$translated_post_update = array(
		'ID'           => $translated_post->ID,
		'post_content' => $updated_content,
	);

	/**
	 * Translated post details for storing in the meta of the bbl_job.
	 * The add_post_meta function will serialize for us.
	 *
	 * @see add_post_meta
	 * @var array $bbl_post_meta_value
	 */
	$bbl_post_meta_value = array(
		'post_title'   => $translated_post->post_title,
		'post_name'    => $translated_post->post_name,
		'post_content' => $updated_content,
		'id'           => $translated_post->ID,
		'filter'       => 'db',
	);

	if ( ! empty( $debug_script ) ) {
		return $translated_post;
	}

	/** Update the translated post itself. */
	wp_update_post( $translated_post_update );

	/**
	 * Update the bbl_post_{post_id} meta key.
	 * Babble Jobs have a special meta that stores the post_title, post_name, post_content, post_id, and filter of the
	 * translated post. Make sure we update that, or the data in the Babble UI and the data on the front end won't match
	 * and weirdness ensues.
	 */
	if ( ! add_post_meta( $bbl_job_post->ID, 'bbl_post_' . $original_post->ID, $bbl_post_meta_value, true ) ) {
		update_post_meta( $bbl_job_post->ID, 'bbl_post_' . $original_post->ID, $bbl_post_meta_value );
	}

	return $translated_post;
}

/**
 * Get an array of shortcodes found in the supplied string.
 *
 * @param string $content The string to find shortcodes within.
 *
 * @return array
 */
function internetorg_extract_shortcodes( $content = '' ) {

	/**
	 * Get the shortcode search regular expression pattern.
	 *
	 * @see get_shortcode_regex
	 * @var string $shortcode_regex
	 */
	$shortcode_regex = get_shortcode_regex();

	if ( empty( $shortcode_regex ) ) {
		return array();
	}

	/**
	 * Fill the $matches array with shortcodes found in the content.
	 * Depending on what's in your content, it could look something like this :
	 *    $matches = Array
	 *    (
	 *        [0] => Array
	 *            (
	 *                [0] => [io-custom-link css_class="link" source="20" link_text="詳しくはこちら"]
	 *                [1] => [io-custom-link css_class="link" source="http://example.com/" link_text="詳しくはこちら"]
	 *            )
	 *        [1] => Array
	 *            (
	 *                [0] =>
	 *                [1] =>
	 *            )
	 *        [2] => Array
	 *            (
	 *                [0] => io-custom-link
	 *                [1] => io-custom-link
	 *            )
	 *        [3] => Array
	 *            (
	 *                [0] =>  css_class="link" source="20" link_text="詳しくはこちら"
	 *                [1] =>  css_class="link" source="http://example.com/" link_text="詳しくはこちら"
	 *            )
	 *        [4] => Array
	 *            (
	 *                [0] =>
	 *                [1] =>
	 *            )
	 *        [5] => Array
	 *            (
	 *                [0] =>
	 *                [1] =>
	 *            )
	 *        [6] => Array
	 *            (
	 *                [0] =>
	 *                [1] =>
	 *            )
	 *    )
	 * The $matches array is structured like so...
	 *    0 - An array of complete shortcode string matches
	 *    1 - A typically empty array corresponding to extra [ to allow for escaping shortcodes with double [[]]
	 *    2 - An array of the shortcode names
	 *    3 - An array of the shortcode argument strings
	 *    4 - A typically empty array corresponding to self closing / shortcodes
	 *    5 - The content of a shortcode if it wraps some content.
	 *    6 - A typically empty array corresponding to extra ] to allow for escaping shortcodes with double [[]]
	 *
	 * @var array $matches
	 */
	preg_match_all( '/' . $shortcode_regex . '/s', $content, $matches );

	return $matches;
}
