<?php
/**
 * This class extends the default nav menu walker for the primary nav piece of the main menu.
 *
 * @package Internet.org
 * @author  arichard <arichard@nerdery.com>
 */

/**
 * Class Internetorg_Main_SubNav_Walker used to use our own html with the menu.
 *
 * @package Internet.org
 * @author  arichard <arichard@nerdery.com>
 */
class Internetorg_Main_SubNav_Walker extends Walker_Nav_Menu {
	/**
	 * Display content to start the menu.
	 *
	 * @param string $output Markup to be output - passed by reference.
	 * @param int    $depth  Nesting depth of this menu.
	 * @param array  $args   Configuration args for this menu.
	 *
	 * @return void
	 */
	public function start_lvl( &$output, $depth = 0, $args = array() ) {
		$indent = ( $depth > 0 ? str_repeat( "\t", $depth ) : '' );
		$output .= "\n" . $indent . '<ul class="borderBlocks borderBlocks_2up>' . "\n";
	}

	/**
	 * Build the starting element for this menu item.
	 *
	 * @param string $output Markup to be output - passed by reference.
	 * @param object $item   Current item data.
	 * @param int    $depth  What level is this item.
	 * @param array  $args   Arguments used to configure this item.
	 * @param int    $id     Menu item id.
	 *
	 * @return void
	 */
	public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {

		$indent = ( $depth > 0 ? str_repeat( "\t", $depth ) : '' );

		$output .= $indent . '<li>';

		// Link attributes.
		$attributes = ! empty( $item->attr_title ) ? ' title="' . esc_attr( $item->attr_title ) . '"' : '';
		$attributes .= ! empty( $item->target ) ? ' target="' . esc_attr( $item->target ) . '"' : '';
		$attributes .= ! empty( $item->xfn ) ? ' rel="' . esc_attr( $item->xfn ) . '"' : '';
		$attributes .= ! empty( $item->url ) ? ' href="' . esc_attr( $item->url ) . '"' : '';
		$attributes .= ' ';

		if ( internetorg_is_internal_url( $item->url ) ) {
			$attributes .= ' class="auxLink js-stateLink" data-type="titled" data-title="' . esc_attr( $item->title ) . '"';
		} else {
			$attributes .= ' class="auxLink"';
		}

		if ( is_array( $args ) && ! empty( $args ) ) {
			$args = (object) $args;
		}

		$item_output = sprintf(
			'%1$s<a%2$s><span>%3$s%4$s%5$s</span></a>%6$s',
			$args->before,
			$attributes,
			$args->link_before,
			esc_attr( apply_filters( 'the_title', $item->title, $item->ID ) ),
			$args->link_after,
			$args->after
		);

		// Build the markup HTML.
		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}
}
