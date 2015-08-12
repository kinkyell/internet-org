<?php
/**
 * This class extends the default nav menu walker for the primary nav piece
 * of the main menu
 *
 * @package Internet.org
 * @author arichard <arichard@nerdery.com>
 */

/**
 * Class Internetorg_Main_SubNav_Walker used to use our own html with the menu
 *
 * @package Internet.org
 * @author arichard <arichard@nerdery.com>
 */
class Internetorg_Main_SubNav_Walker extends Walker_Nav_Menu
{
	/**
	 * display content to start the menu
	 *
	 * @param string $output html to be output - passed by reference
	 * @param int $depth nesting depth of this menu
	 * @param array $args configuration args for this menu
	 * @return void
	 */
	public function start_lvl( &$output, $depth = 0, $args = array() ) {
		$indent = ( $depth > 0  ? str_repeat( "\t", $depth ) : '' );
		$output .= "\n" . $indent . '<ul class="borderBlocks borderBlocks_2up>' . "\n";
	}

	/**
	 * build the starting element for this menu item
	 *
	 * @param string $output html to be output - passed by reference
	 * @param object $item current item data
	 * @param int $depth what level is this item
	 * @param array $args arguments used to configure this item
	 * @param int $id menu item id
	 * @return void
	 */
	public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		global $wp_query;
		$indent = ( $depth > 0 ? str_repeat( "\t", $depth ) : '' );

		$classes = empty( $item->classes ) ? array() : (array) $item->classes;
		$class_names = esc_attr( implode( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) ) );


		$output .= $indent . '<li>';

		// link attributes
		$attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) . '"' : '';
		$attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target )     . '"' : '';
		$attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn )        . '"' : '';
		$attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url )        . '"' : '';
		$attributes .= ' ';
		$attributes .= ' class="auxLink js-stateLink"';

		$item_output = sprintf( '%1$s<a%2$s>%3$s%4$s%5$s</a>%6$s',
			$args->before,
			$attributes,
			$args->link_before,
			apply_filters( 'the_title', $item->title, $item->ID ),
			$args->link_after,
			$args->after
		);

		// build html
		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}

}