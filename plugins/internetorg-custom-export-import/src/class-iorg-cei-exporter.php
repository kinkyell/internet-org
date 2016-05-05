<?php

class IORG_CEI_Exporter {

	private $request;
	private $parser;
	private $po_strings;

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

	private function ids( $ids ) {

		$args = array(
			'post__in'  	 => $ids,
			'post_type' 	 => 'any',
			'posts_per_page' => -1,
		);

		$posts = get_posts( $args );

		$this->output( $posts );
	}

	private function query() {

		$args = array(
			'post_type' 	 => 'any',
			'posts_per_page' => -1,
			'orderby'		 => 'post_type',
		);

		$posts = get_posts( $args );

		$this->output( $posts );
	}

	private function menus() {
		$menus = get_terms( 'nav_menu', array( 'hide_empty' => true ) );
		//wp_update_nav_menu_item
		foreach ( $menus as $menu ) {
			$menu_items = wp_get_nav_menu_items( $menu->term_id );
			echo '<wp-obj wp_menu_id="' . $menu->term_id . '" wp_type="' . 'menu' .'" wp_menu_slug="' . $menu->slug . '">';
				foreach ( $menu_items as $item ) {
					echo '<wp-menu-item wp_item_id="' . $this->filter( $item->ID ) . '">';
					echo '<wp-menu-item-object-id>'. $this->filter( $item->object_id ) .'</wp-menu-item-object-id>';
					echo '<wp-menu-item-object>'. $this->filter( $item->object ) .'</wp-menu-item-object>';
					echo '<wp-menu-item-type>'. $this->filter( $item->type ) .'</wp-menu-item-type>';
					echo '<wp-menu-item-title>'. $this->filter( $item->title ) .'</wp-menu-item-title>';
					echo '<wp-menu-item-url>'. $item->url .'</wp-menu-item-url>';
					echo '<wp-menu-item-classes>'. implode( '|', $item->classes ) .'</wp-menu-item-classes>';
					echo '<wp-menu-item-order>'. $this->filter( $item->menu_order ) .'</wp-menu-item-order>';
					echo '<wp-menu-item-parent>'. $this->filter( $item->menu_item_parent ) .'</wp-menu-item-parent>';
					echo '</wp-menu-item>';
				}
			echo '</wp-obj>';
		}
	}

	private function po() {
		$strings = explode( PHP_EOL, $this->po_strings->get() );

		echo '<wp-obj wp_type="po">';
			foreach ( $strings as $string ) {
				echo '<wp-po-item>';
				echo '<wp-po-item-original>' . $this->filter( $string ) . '</wp-po-item-original>';
				echo '<wp-po-item-translated>' . $this->filter( $string ) . '</wp-po-item-translated>';
				echo '</wp-po-item>';
			}
		echo '</wp-obj>';
	}

	private function output( $posts ) {

		foreach ( $posts as $post ) {
			echo '<wp-obj wp_post_id="' . $post->ID . '" wp_type="' . $post->post_type . '" wp_post_title="' . esc_attr( $post->post_title ) . '">';
			echo $this->parser->to_xml( $this->filter( $post->post_content ) );
			echo '</wp-obj>';
		}
	}

	private function filter( $string ) {

		$string = str_replace( '&nbsp;', '&#160;', $string );

		return $string;
	}

}
