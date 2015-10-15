<?php
/**
 * Utility script to assist with importing translated content.
 *
 * PREPARATION:
 * 0. Use the feature/babble-fieldmanager-integration-test branch.
 * 1. Ensure you have all the language packs from production installed in wp-content/languages/.
 * 2. Ensure you have activated all the languages in Babble's Available Languages setting page.
 * 3. Make sure you've set the correct post type for each <item> in the XML (page_fr for example) before import.
 * 4. Import the translated content XML files with WordPress Importer in WP-Admin.
 * 5. Run this script.
 *
 * @todo not a whole lot of empty or error checking going on here, might want to work on that, or maybe doesn't matter.
 */

/**
 * Load WP Core so we have access to the APIs we need.
 *
 * This is a terrible practice and only doing it here for brevity to complete a one off utility script.
 * Don't let this get onto production, and don't ever do this in plugins or themes, this is a hack job.
 * There are much better ways to do this when you're not in a hurry creating a little hack utility script.
 */
require_once( '../../../../wp/wp-load.php' );

/**
 * Babble appears to hook into pre_get_posts and is causing unexpected behavior with this script.
 */
remove_all_filters( 'pre_get_posts' );

$debug_script = true;

if ( empty( $debug_script ) ) {
	/**
	 * The post_types we'll be operating on.
	 *
	 * @var array $post_types
	 */
	$post_types = array(
		'post',
		'page',
		'io_campaign',
		'io_ctntwdgt',
		'io_freesvc',
		'io_story',
		'io_video',
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
	 * An array of bbl_job post_ids keyed by original English post_id.
	 *
	 * @var array $babble_job_ids
	 */
	$babble_job_ids = [];

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

		$babble_job_ids[ $post->ID ] = $jobs;
	}

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
	foreach ( $lang_codes as $lang_code => $full_code ) {
		/**
		 * For testing, only use 'fr' for easier to parse results.
		 */
		if ( ! empty( $debug_script ) && 'fr' !== $lang_code ) {
			continue;
		}

		/**
		 * The posttype_languagecode post_type that we are operating on, page_fr for example.
		 *
		 * @var array $lang_args
		 */
		$lang_args = array(
			'post_type' => $processing_post_type . '_' . $lang_code,
		);

		/**
		 * Create a new query for us to work with.
		 *
		 * @var WP_Query $lang_query
		 */
		$lang_query = new WP_Query( $lang_args );

		if ( ! $lang_query->have_posts() ) {
			continue;
		}

		while ( $lang_query->have_posts() ) {

			/** Sets up $post object for loop. */
			$lang_query->the_post();

			/**
			 * The files we get back from translators still have the original guid after import, we can extract the original
			 * post_id from that guid field.
			 */
			$match_res = preg_match( '/.*?(\d+)$/', $post->guid, $matches );

			if ( 1 !== $match_res ) {
				continue;
			}

			/**
			 * The post_id of the original English version of this posttype_languagecode post.
			 *
			 * @var int $original_post_id
			 */
			$original_post_id = $matches[1];

			/**
			 * The bbl_job post_ids that were created for the $original_post_id, we'll need to figure out the correct language based on taxonomy... see wp_terms
			 */
			$bbl_job_post_ids = $babble_job_ids[ $original_post_id ];

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
			 * Set the post_translation term from the original English version on the current posttype_languagecode post.
			 *
			 * @var array|WP_Error|string $set_object_terms
			 */
			$set_object_terms = wp_set_object_terms( $post->ID, $post_translation_term_ids, 'post_translation' );

			/**
			 * An array of WP_Post objects of post_type bbl_job that correspond to the original post_id for the
			 * currently processing language code.
			 *
			 * @var WP_Post[] $objects
			 */
			$bbl_job_objects = $babble_jobs->get_object_jobs( $original_post_id, 'post', $processing_post_type );

			/**
			 * The bbl_job to set meta on.
			 *
			 * @var WP_Post $bbl_job
			 */
			$bbl_job = $bbl_job_objects[ $lang_code ];

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
				'post_title'   => $post->post_title,
				'post_name'    => $post->post_name,
				'post_content' => $post->post_content,
				'id'           => $post->ID,
				'filter'       => 'db',
			);

			/** Unique meta key. */
			if ( ! add_post_meta( $bbl_job->ID, 'bbl_post_' . $original_post_id, $bbl_post_meta_value, true ) ) {
				update_post_meta( $bbl_job->ID, 'bbl_post_' . $original_post_id, $bbl_post_meta_value );
			}

			/**
			 * Array of "regsitered" meta_fields keyed by post type.
			 *
			 * @var array $meta_fields
			 */
			$meta_fields = array(
				'page' => array(
					'page_subtitle',
					'page_intro_block',
					'home-content-section',
					'next_page',
				),
				'io_video' => array(
					'video-duration',
					'video-url',
				),
			);

			foreach ( $meta_fields[ $processing_post_type ] as $meta_field ) {
				/** the custom fields registered for babble UI */
				add_post_meta( $bbl_job->ID, 'bbl_job_meta', $meta_field );
				/** the custom field contents for babble UI */
				add_post_meta( $bbl_job->ID, 'bbl_meta_' . $meta_field, get_post_meta( $post->ID, $meta_field, true ), true );
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
