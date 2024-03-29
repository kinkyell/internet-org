<?php

/**
 * Class to handle the exporting of content.
 */
class IORG_CEI_Exporter {

	/**
	 * Stores the request array (generally $_POST)
	 * @var array
	 */
	private $request;

	/**
	 * Stores instance of Shortcode Parser class
	 * @var IORG_CEI_Shortcode_Parser
	 */
	private $parser;

	/**
	 * Stores instance of PO String class
	 * @var IORG_CEI_PO_Strings
	 */
	private $po_strings;

	/**
	 * Execute the exporter
	 * @param  array $request
	 */
	public function run( $request ) {

		$this->request = $request;

		if ( isset( $this->request['site'] ) ) {
			switch_to_blog( $this->request['site'] );
			$theme = wp_get_theme();
			require_once $theme->get_template_directory() . '/functions.php';
		}

		$this->parser 	  = new IORG_CEI_Shortcode_Parser;
		$this->po_strings = new IORG_CEI_PO_Strings;

		if ( !empty( $this->request['ids'] ) ) {
			$ids = explode( ',', $this->request['ids'] );
			$this->ids( $ids );
		} else {
			$this->query();
		}

		if ( isset( $this->request['include_menus'] ) && $this->request['include_menus'] == 'yes' ) {
			$this->menus();
		}

		if ( !empty( $this->request['po_strings'] ) ) {
			$this->po_strings->set( $this->request['po_strings'] );
		}

		if ( isset( $this->request['include_po'] ) && $this->request['include_po'] == 'yes' ) {
			$this->po();
		}
	}

	/**
	 * Gets post/pages by id's
	 * @param  array $ids
	 */
	private function ids( $ids ) {

		$args = array(
			'post__in'  	 => $ids,
			'post_type' 	 => 'any',
			'posts_per_page' => count( $ids ),
		);

		$posts = get_posts( $args );

		$this->output( $posts );
	}

	/**
	 * Gets post/pages using a query
	 * depending on $request
	 */
	private function query() {

		$args = array(
			'post_type' 	   => 'any',
			'posts_per_page'   => 200,
			'orderby'		   => 'post_type',
			'suppress_filters' => false
		);

		$fields = array(
			'type' 		 => 'post_type',
			'status' 	 => 'post_status',
			'author' 	 => 'author',
		);

		foreach ( $fields as $field => $arg_name ) {
			$args = $this->set_arg_from_request( $field, $arg_name, $args );
		}

		if ( isset( $this->request['start_date'] ) && isset( $this->request['end_date'] ) ) {
			$args['date_query'] = array(
				array(
					'after'     => $this->request['start_date'],
					'before'    => $this->request['end_date'],
					'inclusive' => true,
				),
			);
		}

		$posts = get_posts( $args );

		$this->output( $posts );
	}

	/**
	 * Sets array[element] based on request[key]
	 * @param string $name
	 * @param string $arg_name
	 * @param array $args
	 */
	private function set_arg_from_request( $name, $arg_name, $args ) {
		if ( isset( $this->request[$name] ) && $this->request[$name] != 0 ) {
			$args[$arg_name] = $this->request[$name];
		}
		return $args;
	}

	/**
	 * Outputs Menus as XML
	 */
	private function menus() {
		$menus 			 = get_terms( 'nav_menu', array( 'hide_empty' => true ) );
		$theme_locations = get_nav_menu_locations();

		foreach ( $menus as $menu ) {

			$menu_location = array_search( $menu->term_id, $theme_locations );
			$menu_items    = wp_get_nav_menu_items( $menu->term_id );

			echo '<wp-obj wp_menu_id="' . esc_attr( $menu->term_id ) . '" wp_type="' . 'menu' .'" wp_menu_slug="' . esc_attr( $menu->slug ) .'" wp_menu_name="' . esc_attr( $menu->name ) .'" wp_menu_location="' . esc_attr( $menu_location ) .'">';
				foreach ( $menu_items as $item ) {
					echo '<wp-menu-item wp_item_id="'. esc_attr( $item->ID ) .'">';
					echo '<wp-menu-item-object-id>'	 	. $this->filter( $item->object_id ) 				.'</wp-menu-item-object-id>';	// WPCS: XSS ok.
					echo '<wp-menu-item-object>'		. $this->filter( $item->object ) 					.'</wp-menu-item-object>';		// WPCS: XSS ok.
					echo '<wp-menu-item-type>'			. $this->filter( $item->type ) 						.'</wp-menu-item-type>';		// WPCS: XSS ok.
					echo '<wp-menu-item-title>'			. $this->filter( $item->title ) 					.'</wp-menu-item-title>';		// WPCS: XSS ok.
					echo '<wp-menu-item-url>'			. $this->filter( $item->url ) 						.'</wp-menu-item-url>';			// WPCS: XSS ok.
					echo '<wp-menu-item-classes>'		. $this->filter( implode( '|', $item->classes ) )	.'</wp-menu-item-classes>';		// WPCS: XSS ok.
					echo '<wp-menu-item-order>'			. $this->filter( $item->menu_order ) 				.'</wp-menu-item-order>';		// WPCS: XSS ok.
					echo '<wp-menu-item-parent>'		. $this->filter( $item->menu_item_parent ) 			.'</wp-menu-item-parent>';		// WPCS: XSS ok.
					echo '</wp-menu-item>';
				}
			echo '</wp-obj>';
		}
	}

	/**
	 * Outputs PO Strings as XML
	 */
	private function po() {
		$strings = explode( PHP_EOL, $this->po_strings->get() );

		echo '<wp-obj wp_type="po">';
			foreach ( $strings as $string ) {
				echo '<wp-po-item>';
				echo '<wp-po-item-original>' 	. $this->filter( $string ) . '</wp-po-item-original>';		// WPCS: XSS ok.
				echo '<wp-po-item-translated>' 	. $this->filter( $string ) . '</wp-po-item-translated>';	// WPCS: XSS ok.
				echo '</wp-po-item>';
			}
		echo '</wp-obj>';
	}

	/**
	 * Outputs Custom Fields
	 * @param  string $post_id
	 */
	private function custom_fields( $post_id ) {
		$fields = apply_filters( 'iorg_cei_custom_fields_filter', array() );

		foreach ( $fields as $name => $info ) {
			$data = get_post_meta( $post_id, $name, true );

			echo '<wp-custom-field wp_type="'. esc_attr( $name ) . '">';

			if ( isset( $info['parent'] ) ) {
				echo '<' . esc_attr( $info['parent'] ) . '>';
			}

			if ( !empty( $info['structure'] ) ) {
				$this->prepare_parse_for_cf( $data, $info['structure'], $info['tag'] );
			} else {
				echo '<' . esc_attr( $info['tag'] ) . '>';
				echo $this->filter( $data );	// WPCS: XSS ok.
				echo '</' . esc_attr( $info['tag'] ) . '>';
			}

			if ( isset( $info['parent'] ) ) {
				echo '</' . esc_attr( $info['parent'] ) . '>';
			}

			echo '</wp-custom-field>';
		}
	}

	/**
	 * Prepare to parse Custom Fields data
	 * @param  array $data
	 * @param  array $structure
	 */
	private function prepare_parse_for_cf( $data, $structure, $tag ) {

		if ( !is_array( $data ) ) {
			return;
		}

		if ( $this->has_string_keys( $data ) ) {
			echo '<' . esc_attr( $tag ) . '>';
			$this->parse_cf( $data, $structure );
			echo '</' . esc_attr( $tag ) . '>';
		} else {
			foreach ( $data as $entry ) {
				echo '<' . esc_attr( $tag ) . '>';
				$this->parse_cf( $entry, $structure );
				echo '</' . esc_attr( $tag ) . '>';
			}
		}
	}

	/**
	 * Parse Custom Fields data
	 * @param  array $data
	 * @param  array $structure
	 */
	private function parse_cf( $data, $structure ) {
		foreach ( $data as $key => $value ) {
			if ( isset( $structure[$key] ) ) {
				$child = $structure[$key];

				if ( !is_array( $value ) ) {
					echo '<' . esc_attr( $child ) . '>' . $this->filter( $value ) . '</' . esc_attr( $child ) .'>';	// WPCS: XSS ok.
				} else {

					if ( isset( $structure[$key]['parent'] ) ) {
						echo '<' . esc_attr( $structure[$key]['parent'] ) . ' wp_type="'. esc_attr( $key ) . '">';
					}

					$this->prepare_parse_for_cf( $value, $structure[$key]['structure'], $structure[$key]['tag'] );

					if ( isset( $structure[$key]['parent'] ) ) {
						echo '</' . esc_attr( $structure[$key]['parent'] ) . '>';
					}
				}
			}
		}
	}


	/**
	 * Checks if array is assoc
	 * @param  array $array
	 */
	private function has_string_keys( $array ) {
	  return count( array_filter( array_keys( $array ), 'is_string' ) ) > 0;
	}

	/**
	 * Outputs Posts/Pages as XML
	 * @param  array $posts
	 */
	private function output( $posts ) {

		foreach ( $posts as $post ) {
			echo '<wp-obj wp_post_id="' . esc_attr( $post->ID ) . '" wp_type="' . esc_attr( $post->post_type ) . '" wp_post_title="' . esc_attr( $post->post_title ) . '">';
			echo $this->parser->to_xml( $this->filter( $post->post_content ) );	// WPCS: XSS ok.
			echo '<wp-custom-fields>';
			$this->custom_fields( $post->ID );
			echo '<wp-custom-field>';
			echo '<wp-excerpt>';
			echo $this->filter( get_post_field( 'post_excerpt', $post->ID ) );	// WPCS: XSS ok.
			echo '</wp-excerpt>';
			echo '</wp-custom-field>';
			echo '</wp-custom-fields>';
			echo '</wp-obj>';
		}
	}

	/**
	 * Filter content before inserting it into XML
	 * @param  string $string
	 * @return string
	 */

	private function filter( $string ) {
		$string = str_replace( '&quot;', '@quot;@', $string );
		$string = html_entity_decode( $string );
		$string = str_replace( '&', '&amp;', $string );
		$string = str_replace( '@quot;@', '&quot;', $string );

		return $string;
	}

}
