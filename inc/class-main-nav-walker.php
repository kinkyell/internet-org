<?php
/**
 * This class extends the default nav menu walker for the primary nav piece
 * of the main menu
 *
 * @package Internet.org
 * @author arichard <arichard@nerdery.com>
 */

/**
 * Class IOrg_Main_Nav_Walker used to use our own html with the menu
 *
 * @package Internet.org
 * @author arichard <arichard@nerdery.com>
 */
class IOrg_Main_Nav_Walker extends Walker_Nav_Menu
{
	/**
	 *
	 *
	 * @param string $output html to be output - passed by reference
	 * @param int $depth
	 * @return void
	 */
	public function start_lvl( &$output, $depth ) {
		$indent = ( $depth > 0  ? str_repeat( "\t", $depth ) : '' );
		$output .= "\n" . $indent . '<ul>' . "\n";
	}

	/**
	 *
	 *
	 * @param string $output html to be output - passed by reference
	 * @param object $item
	 * @param int $depth
	 * @param array $args
	 * @return void
	 */
	public function start_el( &$output, $item, $depth, $args ) {
		global $wp_query;
		$indent = ( $depth > 0 ? str_repeat( "\t", $depth ) : '' );

		$classes = empty( $item->classes ) ? array() : (array) $item->classes;
		$class_names = esc_attr( implode( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) ) );


		$output .= $indent . '<li id="main-nav-menu-item-'. $item->ID . '">';

		// link attributes
		$attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) . '"' : '';
		$attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target )     . '"' : '';
		$attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn )        . '"' : '';
		$attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url )        . '"' : '';
		$attributes .= ' ';
		$attributes .= ' class="menu-link ' . ( $depth > 0 ? 'sub-menu-link' : 'main-menu-link' ) . ' topicLink-link js-stateLink"';
		$attributes .= ' data-type="panel"';
		$attributes .= ' data-title="' . apply_filters( 'the_title', $item->title, $item->ID ) . '"';
		$attributes .= ' data-image="http://placehold.it/400x800?text=' . substr( $item->title, 4 ) . '"';
		$attributes .= ' data-theme="' . substr( $item->title, 4 ) . '"';

		$item_output = sprintf( '%1$s<a%2$s>%3$s%4$s%5$s</a>%6$s',
			$args['before'],
			$attributes,
			$args['link_before'],
			apply_filters( 'the_title', $item->title, $item->ID ),
			$args['link_after'],
			$args['after']
		);

		$customDiv = '<div class="topicLink topicLink_themeMission ' . $class_names . ' js-menuView-slider" style="opacity: 1; transform: matrix(1, 0, 0, 1, 0, 0);">';
		$item_output = $customDiv . $item_output . '</div>';

		// build html
		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}

}