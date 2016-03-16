<?php

/**
 * Class to handle adding our links to the admin bar.
 *
 * @package Babble
 * @since 0.2
 */
class Babble_Admin_bar {

	function __construct() {
		add_action( 'admin_bar_menu', array( $this, 'admin_bar_menu' ), 100 );
	}

	/**
	 * Hooks the WP admin_bar_menu action
	 *
	 * @param object $wp_admin_bar The WP Admin Bar, passed by reference
	 * @return void
	 **/
	public function admin_bar_menu( $wp_admin_bar ) {
		$links = bbl_get_switcher_links( 'bbl-admin-bar' );

		$current_lang = bbl_get_current_lang();

		// Remove the current language
		unset( $links[ $current_lang->code ] );

		$parent_id = "bbl-admin-bar-{$current_lang->url_prefix}";
		$wp_admin_bar->add_menu( array(
			'children' => array(),
			'href' => '#',
			'id' => $parent_id,
			'meta' => array( 'class' => "bbl_lang_{$current_lang->code} bbl_lang" ),
			'title' => $current_lang->display_name,
			'parent' => false,
		) );
		foreach ( $links as & $link ) {
			$link[  'parent' ] = $parent_id;
			$wp_admin_bar->add_menu( $link );
		}
	}

}
