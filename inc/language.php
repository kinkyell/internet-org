<?php
/**
 * This file is for language related functions
 *
 * @package
 * @author arichard <arichard@nerdery.com>
 */

if ( ! function_exists( 'iorg_language_switcher' ) ) :
	/**
	 * Builds the language switcher needed for the FE of the site
	 *
	 * This is a stripped down version of the the Babble's built in language
	 * switcher since we are not relying on any special functionality
	 *
	 * @see Babble_Widget::widget
	 *
	 * @return void
	 */
	function iorg_language_switcher() {

		// check if Babble is installed/active, if not trigger a notice as a
		// developer should already know but shouldn't be hindered by it and a
		// user shouldn't be impacted.
		if ( ! function_exists( 'bbl_get_switcher_links' ) ) {
			error_log( 'Babble plugin not active', LOG_NOTICE );
			return;
		}

		$list = bbl_get_switcher_links();

		$optionsList  = array();
		$divList      = array();
		$selectedLang = null;

		foreach ( $list as $item ) {

			// this will skip any languages for which there is no translation
			if ( in_array( 'bbl-add', $item['classes'] ) ) {
				continue;
			}

			// check if this is the current language
			$selected = false;
			if ( $item['active'] ) {
				$selected = true;
				$selectedLang = $item;
			}

			if ( $item['href'] ) {
				$optionsList[] = '<option ' . ( $selected ? 'selected="selected" ' : '') . 'class="' . esc_attr( $item['class'] ) . '" value="' . esc_url( $item['href'] ) . '">' . esc_html( $item['lang']->display_name ) . '</option>';

				$divList[] = '<div class="langSelect-menu-item' . ( $selected ? 'isSelected' : '' ) . '" tabindex="0"><span>' . esc_html( $item['lang']->display_name ) . '</span></div>';
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


// handle the translation bits
if ( ! function_exists( 'iorg_save_meta_common_verify' ) ) {
	/**
	 * @param string $nonce_key field name of the nonce field
	 * @param string $form_name name of the form
	 * @param int $post_id id of post being saved
	 * @return bool true if verified false if not
	 */
	function iorg_save_meta_common_verified( $nonce_key, $form_name, $post_id ) {
		// if this is an autosave, our form has not been submitted, so we don't want to do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return false;
		}

		if ( empty( $_POST ) ) {
			return false;
		}

		// check / verify nonce
		if ( empty( $_POST[ $nonce_key ] ) ) {
			return false;
		}

		if ( ! wp_verify_nonce( $_POST[ $nonce_key ], $form_name ) ) {
			return false;
		}

				// Check the user can edit this post
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return false;
		}

		return true;
	}
}


if ( ! function_exists( 'iorg_save_meta_for_content' ) ) :
	/**
	 * Save meta for content
	 *
	 * @param int $post_id id of post being saved
	 * @return void
	 */
	function iorg_save_meta_for_content( $post_id ) {
		// If this is an autosave, our form has not been submitted, so we don't want to do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		// makes sure the post id is for a real post
		$post = get_post( $post_id );

		if ( ! current_user_can( 'edit_post', $post->ID ) ) {
			return;
		}

		switch ( $post->post_type ) {
			case 'page':
			case 'bbl_job':
				iorg_save_meta_for_page( $post_id );
				break;
			default:
				return;
				break;
		}
	}
endif;
add_action( 'save_post', 'iorg_save_meta_for_content' );


if ( ! function_exists( 'iorg_save_meta_for_page' ) ) :
	/**
	 * save translated post meta data
	 *
	 * @param int $post_id id of the post being updated
	 * @return void
	 */
	function iorg_save_meta_for_page( $post_id ) {
		// verify nonces
		if ( ! iorg_check_nonce( 'fieldmanager-after_title_fm_fields-nonce' ) ) {
			return;
		}

		if ( ! iorg_check_nonce( 'fieldmanager-home-content-section-nonce' ) ) {
			return;
		}

		// sanitize the data


		// update the meta

	}
endif;


if ( ! function_exists( 'iorg_check_nonce' ) ) :
	function iorg_check_nonce( $key, $method = 'post' ) {
		if ( ! in_array( $method, array( 'post', 'get' ) ) ) {
			$method = 'post';
		}

		if ( empty( ${ '_' . strtoupper( $method ) }[ $key ] ) ) {
			return false;
		}

		$nonce = ${ '_' . strtoupper( $method ) }[ $key ];

		if ( wp_verify_nonce( $nonce, $key ) ) {
			return true;
		}

		return false;
	}
endif;


if ( ! function_exists( 'iorg_translated_meta_fields' ) ) :
	/**
	 * Tell Babble how to handle the meta fields we have
	 *
	 * @param array $fields current list of meta data
	 * @param WP_Post $post current post
	 * @return array list of Babble_Meta_Field_* objects
	 */
	function iorg_translated_meta_fields( array $fields, WP_Post $post) {
		$fields['Subtitle'] = new Babble_Meta_Field_Text( $post, 'Subtitle', 'Subtitle' );

		// hook here to add our Fieldmanager Custom Fields -- instead of relying
		// on the Babble built in types

		return $fields;
	}
endif;
add_filter( 'bbl_translated_meta_fields', 'iorg_translated_meta_fields', 10, 2 );


if ( ! function_exists( 'iorg_bbl_sync_meta_key' ) ) :
	/**
	 * Determine if specified meta should be synced across translations
	 *
	 * @param boolean $sync current sync value
	 * @param string $meta_key name of hte post meta field being checked
	 * @return bool true if meta should be synced otherwise false
	 */
	function iorg_bbl_sync_meta_key( $sync, $meta_key ) {
		// this is the list of items that should not be auto synced across translations
		$sync_not = array(
			'home-content-section',
			'Subtitle',
			// 'after_title_fm_fields',
		);

		if ( in_array( $meta_key, $sync_not ) ) {
			return false;
		}

		return $sync;
	}
endif;
add_filter( 'bbl_sync_meta_key', 'iorg_bbl_sync_meta_key', 99, 2 );
