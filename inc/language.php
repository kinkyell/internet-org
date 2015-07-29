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