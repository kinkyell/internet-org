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
		$list = bbl_get_switcher_links();

		echo '<select onchange="document.location.href=this.options[this.selectedIndex].value;">';

		foreach ( $list as $item ) :

			// this will skip any languages for which there is no translation
			if ( in_array( 'bbl-add',$item['classes'] ) ) {
				continue;
			}

			if ( $item['active'] ) {
				$selected = 'selected="selected" ';
			} else {
				$selected = '';
			}

			if ( $item[ 'href'] ) {
				echo '<option ' . $selected . 'class="' . esc_attr( $item[ 'class' ] ) . '" value="' . esc_url( $item[ 'href' ] ) . '">' . esc_html( $item[ 'lang' ]->display_name ) . '</option>';
			}
		endforeach;

		echo '</select>';
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
		if ( empty( $_POST[$nonce_key] ) ) {
			return false;
		}

		if ( ! wp_verify_nonce( $_POST[$nonce_key], $form_name ) ) {
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
	 * Save meta for content, farms out labor when necessary
	 *
	 * @param int $post_id id of post being saved
	 * @return void
	 */
	function iorg_save_meta_for_content( $post_id ) {

		$post = get_post( $post_id );

		if ( 'post' != $post->post_type ) {
			return;
		}

		$nonce_field = null;
		$form_name   = null;
		switch ( $post->post_type ) {
			case 'post':
				$nonce_field = 'some_nonce_field';
				$form_name   = 'some_form_name';
				break;
			case 'page':
				$nonce_field = 'some_nonce_field';
				$form_name   = 'some_form_name';
				break;
			case 'iorg_story':
				$nonce_field = 'some_nonce_field';
				$form_name   = 'some_form_name';
				break;
			case 'iorg_campaign':
				$nonce_field = 'some_nonce_field';
				$form_name   = 'some_form_name';
				break;
			case 'iorg_press':
				$nonce_field = 'some_nonce_field';
				$form_name   = 'some_form_name';
				break;
			case 'iorg_freeservice':
				$nonce_field = 'some_nonce_field';
				$form_name   = 'some_form_name';
				break;
			default:
				// no-op
				break;
		}

		if ( ! iorg_save_meta_common_verified( $nonce_field, $form_name, $post->ID ) ) {
			return;
		}

		// do process data for this post type
		switch ( $post->post_type ) {
			case 'post':
				iorg_save_meta_for_post();
				break;
			case 'page':
				iorg_save_meta_for_page();
				break;
			case 'iorg_story':
				iorg_save_meta_for_story();
				break;
			case 'iorg_campaign':
				iorg_save_meta_for_campaign();
				break;
			case 'iorg_press':
				iorg_save_meta_for_press();
				break;
			case 'iorg_freeservice':
				iorg_save_meta_for_freeservices();
				break;
			default:
				// no-op
				break;
		}

	}
	add_action( 'save_post', 'iorg_save_meta_for_content' );
endif;

if ( ! function_exists( 'iorg_save_meta_for_post' ) ) :
	function iorg_save_meta_for_post() {

	}
endif;

if ( ! function_exists( 'iorg_save_meta_for_page' ) ) :
	function iorg_save_meta_for_page() {

	}
endif;

if ( ! function_exists( 'iorg_save_meta_for_story' ) ) :
	function iorg_save_meta_for_story() {

	}
endif;

if ( ! function_exists( 'iorg_save_meta_for_campaign' ) ) :
	function iorg_save_meta_for_campaign() {

	}
endif;

if ( ! function_exists( 'iorg_save_meta_for_press' ) ) :
	function iorg_save_meta_for_press() {

	}
endif;

if ( ! function_exists( 'iorg_save_meta_for_freeservice' ) ) :
	function iorg_save_meta_for_freeservice() {

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

	}
	add_filter( 'bbl_translated_meta_fields', 'iorg_translated_meta_fields', 10, 2 );
endif;

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
		$sync_not = array();

		if ( in_array( $meta_key, $sync_not ) ) {
			return false;
		}

		return $sync;
	}
	add_filter( 'bbl_sync_meta_key', 'iorg_bbl_sync_meta_key', 10, 2 );
endif;
