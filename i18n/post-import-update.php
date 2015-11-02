<?php
/**
 * Utility script to assist with importing translated content.
 *
 * PREPARATION:
 * 1. Ensure you have all the language packs from production installed in wp-content/languages/.
 * 2. Ensure you have activated all the languages in Babble's Available Languages setting page.
 * 3. Make sure you've set the correct post type for each <item> in the XML (page_fr for example) before import.
 * 4. Import the translated content XML files with WordPress Importer in WP-Admin.
 * 5. Run this script.
 */

/**
 * Load WP Core so we have access to the APIs we need.
 *
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
	/**
	 * The post_types we'll be operating on.
	 *
	 * @var array $post_types
	 */
	$post_types = array(
		'post',
		'page',
		'io_ctntwdgt',
		'io_story',
	);
} else {
	/** Debug and testing post type(s). */
	$post_types = array(
		'page',
	);
}

foreach ( $post_types as $processing_post_type ) {
	/**
	 * Arguments for the WP_Query that we will use to set up "bbl_job" posts, so we don't have to manually do in WP-Admin.
	 *
	 * @var array $bbl_jobs_args
	 */
	$bbl_jobs_args = array(
		'post_type' => $processing_post_type,
	);

	/**
	 * Create a new query.
	 *
	 * @var WP_Query $bbl_jobs_query
	 */
	$bbl_jobs_query = new WP_Query( $bbl_jobs_args );

	/**
	 * Bail early.
	 */
	if ( ! $bbl_jobs_query->have_posts() ) {
		continue;
	}

	if ( empty( $debug_script ) ) {
		/**
		 * Get the list of active languages according to Babble.
		 *
		 * @var stdClass[] $langs
		 */
		$langs = bbl_get_active_langs();

		/**
		 * An array of the 'code' fields from the stdClass "language objects".
		 *
		 * @var array $lang_codes
		 */
		$lang_codes = wp_list_pluck( $langs, 'code' );
	} else {
		/** testing lang_codes set to fr */
		$lang_codes['fr'] = 'fr';
	}

	/**
	 * Bail early.
	 */
	if ( empty( $lang_codes ) ) {
		continue;
	}

	/**
	 * We have posts, let's generate the bbl_jobs.
	 */
	while ( $bbl_jobs_query->have_posts() ) {

		/** Sets up $post object for loop. */
		$bbl_jobs_query->the_post();

		/**
		 * Create a Babble_Jobs object so we can utilize it's create_post_jobs method.
		 */
		$babble_jobs = new Babble_Jobs();

		/**
		 * An array of Translation Job post IDs.
		 *
		 * @var array $jobs
		 */
		$jobs = $babble_jobs->create_post_jobs( $post->ID, $lang_codes );

		/**
		 * The post_id of the original English version of this posttype_languagecode post.
		 *
		 * @var int $original_post_id
		 */
		$original_post_id = $post->ID;

		/**
		 * Get the post_translation term assigned to the original English post. We need that to associate our translated
		 * version with the original.
		 *
		 * @var WP_Term[] $post_translation_term
		 */
		$post_translation_terms = wp_get_post_terms( $original_post_id, 'post_translation' );

		/**
		 * Array of term_ids for the post_translation taxonomy that was assigned to the original English post.
		 *
		 * @var array $post_translation_term_ids
		 */
		$post_translation_term_ids = wp_list_pluck( $post_translation_terms, 'term_id' );

		/**
		 * An array of WP_Post objects of post_type bbl_job that correspond to the original post_id for the
		 * currently processing language code.
		 *
		 * @var WP_Post[] $objects
		 */
		$bbl_job_objects = internetorg_get_object_jobs( $original_post_id, 'post', $processing_post_type );

		/**
		 * Now we need to associate the posttype_languagecode post type from the XML with the corresponding post_transid_
		 * taxonomy that was generated when we created the bbl_job posts.
		 *
		 * Get the correct post_transid_ from the original Engrish version of the post. The XML that was supplied back to us
		 * contains the original guid, we can get the original Engrish post_id from that guid.
		 *
		 * We also need to serialize data from the posttype_languagecode for use as meta on the bbl_job, as we saw in testing,
		 * the bbl_job meta is what is actually editable in the Babble translation UI.
		 *
		 * For example, the bbl_post_15 meta_key that we examined for a bbl_job translation of original English post 15, had the
		 * following data serialized in post_meta :
		 *
		 *     bbl_post_15 = Array (
		 *         [post_title] => Our Impact French
		 *         [post_name] => impact
		 *         [post_content] => French our impact content
		 *         [ID] => 0
		 *         [filter] => db
		 *     )
		 *
		 */

		foreach ( $lang_codes as $lang_code => $full_lang_code ) {

			/** Skip English. */
			if ( 'en' === $lang_code ) {
				continue;
			}

			/**
			 * The bbl_job to set meta on.
			 *
			 * @var WP_Post $bbl_job
			 */
			$bbl_job = $bbl_job_objects[ $lang_code ];

			if ( empty( $bbl_job ) ) {
				continue;
			}

			/**
			 * The post_id of the corresponding shadow post type.
			 *
			 * @var int $shadow_post_id
			 */
			$shadow_post_id = absint( internetorg_get_post_id_sql( $post->post_name, $post->post_type . '_' . $lang_code ) );

			if ( empty( $shadow_post_id ) ) {
				continue;
			}

			/**
			 * The corresponding shadow post type.
			 *
			 * @var WP_Post $shadow_post
			 */
			$shadow_post = get_post( $shadow_post_id );

			if ( empty( $shadow_post ) ) {
				continue;
			}

			/**
			 * Set the post_translation term from the original English version on the current posttype_languagecode post.
			 *
			 * @var array|WP_Error|string $set_object_terms
			 */
			$set_object_terms = wp_set_object_terms( $shadow_post->ID, $post_translation_term_ids, 'post_translation' );

			/**
			 * Translated post details for storing in the meta of the bbl_job.
			 *
			 * The add_post_meta function will serialize for us.
			 *
			 * @see add_post_meta
			 *
			 * @var array $bbl_post_meta_value
			 */
			$bbl_post_meta_value = array(
				'post_title'   => $shadow_post->post_title,
				'post_name'    => $shadow_post->post_name,
				'post_content' => $shadow_post->post_content,
				'id'           => $shadow_post->ID,
				'filter'       => 'db',
			);

			/** Unique meta key. */
			if ( ! add_post_meta( $bbl_job->ID, 'bbl_post_' . $original_post_id, $bbl_post_meta_value, true ) ) {
				update_post_meta( $bbl_job->ID, 'bbl_post_' . $original_post_id, $bbl_post_meta_value );
			}

			/**
			 * Array of post meta.
			 *
			 * @var array $post_data
			 */
			$post_data = get_post_meta( $post->ID );

			foreach ( $post_data as $meta_key => $meta_value ) {

				/** Meta value is sometimes array when it should be string, set single to true. */
				$meta_value = get_post_meta( $post->ID, $meta_key, true );

				/** The custom fields registered for babble UI. */
				add_post_meta( $bbl_job->ID, 'bbl_job_meta', $meta_key );

				/** The custom field contents for babble UI. */
				add_post_meta( $bbl_job->ID, 'bbl_meta_' . $meta_key, $meta_value );
			}

			/**
			 * Fields to pass to wp_update_post to set the post_status to complete for the bbl_job.
			 *
			 * @var array $post_status_update
			 */
			$post_status_update = array(
				'ID'          => $bbl_job->ID,
				'post_status' => 'complete',
			);

			/** Update the post_status */
			wp_update_post( $post_status_update );
		}
	}
}

/**
 * Get a post_id by post_name and post_type with SQL.
 *
 * @param $post_name The post_name of the post. Required.
 * @param $post_type The post_type of the post. Required.
 *
 * @global wpdb $wpdb The global WordPress database object. Required.
 *
 * @return null|string
 */
function internetorg_get_post_id_sql( $post_name, $post_type ) {
	global $wpdb;

	$post_id = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_name = %s AND post_type = %s", $post_name, $post_type ) );

	return $post_id;
}

/**
 * Get Babble jobs for a given original post.
 *
 * @param int $id The original object id.
 * @param string $type The type, either post or term.
 * @param string $post_type The original post_type.
 *
 * @return WP_Post[] An array of WP_Post objects of type bbl_job corresponding to the original object id.
 */
function internetorg_get_object_jobs( $id, $type, $post_type ) {

	$jobs = get_posts(
		array(
			'bbl_translate'  => false,
			'post_type'      => 'bbl_job',
			'post_status'    => 'any',
			'meta_key'       => "bbl_job_{$type}",
			'meta_value'     => "{$post_type}|{$id}",
			'posts_per_page' => - 1,
			'post_status'    => 'any',
		)
	);

	if ( empty( $jobs ) ) {
		return array();
	}

	$jobs   = array_map( 'get_post', $jobs );

	$return = array();

	foreach ( $jobs as $job ) {
		if ( $lang = internetorg_get_job_language( $job ) ) {
			$return[ $lang->code ] = $job;
		}
	}

	return $return;
}

/**
 * Get the language of a bbl_job.
 *
 * @param $job
 *
 * @return bool|object
 */
function internetorg_get_job_language( $job ) {
	$job       = get_post( $job );
	$languages = get_the_terms( $job, 'bbl_job_language' );
	if ( empty( $languages ) ) {
		return false;
	}

	return bbl_get_lang( reset( $languages )->name );
}
