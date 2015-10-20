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

		/**
		 * Custom offsite links should not have js-stateLink applied to them.
		 */
		if ( 'custom' === $item->type && ! internetorg_is_internal_url( $item->url ) ) {
			/**
			 * The classes to apply to the link.
			 *
			 * @var string $class
			 */
			$class = 'auxLink';
		} else {
			$class = 'auxLink js-stateLink';
		}

		/**
		 * Get the title and description of the object if it's a post of any post_type.
		 */
		if ( 'post_type' === $item->type ) {

			/**
			 * Title of the post.
			 *
			 * @var string $data_title
			 */
			$data_title = get_the_title( $item->object_id );

			/**
			 * Subtitle meta or post_excerpt for description on the link.
			 *
			 * @var string $data_desc
			 */
			$data_desc  = internetorg_get_the_subtitle( $item->object_id );

			if ( empty( $data_desc ) ) {
				$data_desc = get_post_field( 'post_excerpt', $item->object_id );
			}
		}

		/**
		 * Retrieve a list of all the 'io_story' post type names, including Babble's shadow post types for 'io_story'.
		 *
		 * @var array $io_story_post_types
		 */
		$io_story_post_types = internetorg_get_shadow_post_types_for_ajax( 'io_story' );

		/**
		 * Retrieve a list of all the 'page' post type names, including Babble's shadow post types for 'page'.
		 *
		 * @var array $page_post_types
		 */
		$page_post_types = internetorg_get_shadow_post_types_for_ajax( 'page' );

		/**
		 * The page and io_story post types (and their Babble shadow post_type equivalents) are the only post_types that
		 * should be paneled. But only if we have a post_thumbnail, and not for blog home or page-contact.php.
		 */
		if ( in_array( $item->object, $io_story_post_types ) || in_array( $item->object, $page_post_types ) ) {

			/**
			 * The page for posts setting is the blog home.
			 *
			 * @var int $blog_home
			 */
			$blog_home = get_option( 'page_for_posts' );

			/**
			 * Page template filename, empty when default template is in use, false if post is not a page.
			 *
			 * @var string|bool $template_slug
			 */
			$template_slug = get_page_template_slug( $item->object_id );

			/**
			 * True or false. Do we have a post_thumbnail or not.
			 *
			 * @var bool $has_post_thumbnail
			 */
			$has_post_thumbnail = has_post_thumbnail( $item->object_id );

			if ( $has_post_thumbnail && $item->object_id !== $blog_home && 'page-contact.php' !== $template_slug ) {

				/**
				 * The data-type attribute for the link, indicates if this is a paneled or titled destination link.
				 * @var string $data_type
				 */
				$data_type = 'panel';

				/**
				 * URL to the post_thumbnail of panel-image size.
				 *
				 * @var string $post_thumbnail
				 */
				$post_thumbnail = internetorg_get_post_thumbnail(
					$item->object_id,
					'panel-image'
				);

				/**
				 * URL to the mobile post_thumbnail of inline-image size.
				 *
				 * @var string $mobile_thumbnail
				 */
				$mobile_thumbnail = internetorg_get_mobile_featured_image(
					$item->object,
					$item->object_id
				);
			}
		}

		/**
		 * Retrieve a list of all the 'post' post type names, including Babble's shadow post types for 'post'.
		 *
		 * @var array $post_post_types
		 */
		$post_post_types = internetorg_get_shadow_post_types_for_ajax( 'post' );

		if ( in_array( $item->object, $post_post_types ) ) {
			/**
			 * Posts have a date attribute for publish date.
			 *
			 * @var string $data_date
			 */
			$data_date = get_the_date( '', $item->object_id );

			/**
			 * Posts have a social attribute.
			 *
			 * @var string $data_social
			 */
			$data_social = 'true';
		}

		// Link attributes.
		$attributes = ! empty( $class ) ? ' class="' . esc_attr( $class ) . '"' : '';
		$attributes .= ! empty( $item->url ) ? ' href="' . esc_url( $item->url ) . '"' : '';
		$attributes .= ! empty( $data_title )
			? ' data-title="' . esc_attr( $data_title ) . '"'
			: ' data-title="' . esc_attr( $item->title ) . '"';
		$attributes .= ! empty( $data_date ) ? ' data-date="' . esc_attr( $data_date ) . '"' : '';
		$attributes .= ! empty( $post_thumbnail ) ? ' data-image="' . esc_url( $post_thumbnail ) . '"' : '';
		$attributes .= ! empty( $mobile_thumbnail ) ? ' data-mobile-image="' . esc_url( $mobile_thumbnail ) . '"' : '';
		$attributes .= ! empty( $data_social ) ? ' data-social="' . esc_attr( $data_social ) . '"' : '';
		$attributes .= ! empty( $data_type ) ? ' data-type="' . esc_attr( $data_type ) . '"' : ' data-type="titled"';
		$attributes .= ! empty( $item->attr_title ) ? ' title="' . esc_attr( $item->attr_title ) . '"' : '';
		$attributes .= ! empty( $item->target ) ? ' target="' . esc_attr( $item->target ) . '"' : '';
		$attributes .= ! empty( $item->xfn ) ? ' rel="' . esc_attr( $item->xfn ) . '"' : '';

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
