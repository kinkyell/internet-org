<?php

class IORG_CEI_Importer {

	private $content;
	private $parser;
	private $original_site;
	private $target_site;
	private $mlp_content_relations;
	private $output;

	public function run( $content, $request ) {

		global $wpdb;

		$this->mlp_content_relations = new Mlp_Content_Relations(
			$wpdb,
			new Mlp_Site_Relations( $wpdb, 'mlp_site_relations' ),
			new Mlp_Db_Table_Name( $wpdb->base_prefix . 'multilingual_linked',  new Mlp_Db_Table_List( $wpdb ) )
		);

		$this->original_site = $request['site'];
		$this->switch_to_original_site();

		$theme = wp_get_theme();
		require_once $theme->get_template_directory() . '/functions.php';

		$this->parser  = new IORG_CEI_Shortcode_Parser;
		$this->content = simplexml_load_string( $content );

		$this->set_target_site( $this->content['locale'] );

		$posts = array();
		$menus = array();

		foreach ( $this->content->{'wp-obj'} as $key => $obj ) {

			if ( $obj['wp_type'] == 'post' ||  $obj['wp_type'] == 'page' ) {

				$posts[] = array(
					'original_id' => (string) $obj['wp_post_id'],
					'type'		  => (string) $obj['wp_type'],
					'title'		  => (string) $obj['wp_post_title'],
					'content'	  => (string) $this->strip_xml_tag( $obj->asXML(), 'wp-obj' ),
				);

			} else if ( $obj['wp_type'] == 'menu' ) {

				$menu_items = array();

				foreach ( $obj->children() as $item ) {
					$menu_items[] = array(
						'item_id' 	=> (string) $item['wp_item_id'],
						'object_id' => (string) $item->{'wp-menu-item-object-id'},
						'object'	=> (string) $item->{'wp-menu-item-object'},
						'type'		=> (string) $item->{'wp-menu-item-type'},
						'title'		=> (string) $item->{'wp-menu-item-title'},
						'url'		=> (string) $item->{'wp-menu-item-url'},
						'classes'	=> (string) $item->{'wp-menu-item-classes'},
						'order'		=> (string) $item->{'wp-menu-item-order'},
						'parent'	=> (string) $item->{'wp-menu-item-parent'},
					);
				}

				$menus[] = array(
					'original_id' 		=> (string) $obj['wp_menu_id'],
					'original_slug' 	=> (string) $obj['wp_menu_slug'],
					'original_name' 	=> (string) $obj['wp_menu_name'],
					'original_location' => (string) $obj['wp_menu_location'],
					'items'				=> $menu_items,
				);

			}
		}

		$this->posts( $posts );
		$this->menus( $menus );

		return $this->output;
	}

	private function posts( $posts ) {

		foreach ( $posts as $post ) {

			$this->switch_to_original_site();

			$current_post = get_post( $post['original_id'],  ARRAY_A );
			$id 		  = $this->get_related_post_id( $post['original_id'] );

			$this->switch_to_target_site();

			$current_post['ID'] 		  = (int) $id;
			$current_post['post_title']   = $post['title'];
			$current_post['post_content'] = $this->parser->from_xml( $post['content'] );

			unset(
				$current_post['post_content_filtered'],
				$current_post['post_excerpt'],
				$current_post['guid'],
				$current_post['post_parent'],
				$current_post['menu_order']
			);

			$post_id = wp_insert_post( $current_post );

			if ( is_wp_error( $post_id ) ) {
			    $this->output['posts'][] = array(
			    	'description' => 'Error importing Post/Page ' . $post['original_id'] . ': ' . $post_id->get_error_message(),
			    	'type'	  	  => 'error',
			    );
			    continue;
			}

		    $this->output['posts'][] = array(
		    	'description' => 'Success importing Post/Page ' . $post['original_id'] . ' into ' . $post_id,
		    	'type'	  	  => 'success',
		    );

			$this->switch_to_original_site();

			if ( $id == 0 ) {
				$this->mlp_content_relations->set_relation(
					$this->original_site,
					$this->target_site,
					$post['original_id'],
					$post_id,
					$post['type']
				);
			}

		}
	}

	private function menus( $menus ) {

		$this->switch_to_target_site();

		$locations = get_theme_mod( 'nav_menu_locations' );

		foreach ( $menus as $menu ) {

			$this->switch_to_target_site();

			$menu_exists = wp_get_nav_menu_object( $menu['original_name'] );

			if ( $menu_exists ) {
				wp_delete_nav_menu( $menu['original_name'] );
			}

			$menu_id = wp_create_nav_menu( $menu['original_name'] );

			if ( is_wp_error( $menu_id ) ) {
			    $this->output['menus'][] = array(
			    	'description' => 'Error importing Menu ' . $menu['original_name'] . ': ' . $menu_id->get_error_message(),
			    	'type'	  	  => 'error',
			    );
			    continue;
			}

		    $this->output['menus'][] = array(
		    	'description' => 'Success importing Menu ' . $menu['original_name'],
		    	'type'	  	  => 'success',
		    );

	        if ( isset( $locations[ $menu['original_location'] ] ) ) {
	        	$locations[ $menu['original_location'] ] = $menu_id;
	        }

			foreach ( $menu['items'] as $item ) {

				$this->switch_to_original_site();

				$args = array(
					'menu-item-object-id' => $this->get_related_post_id( $item['object_id'] ),
					'menu-item-object' 	  => $item['object'],
					'menu-item-type' 	  => $item['type'],
					'menu-item-title' 	  => $item['title'],
					'menu-item-url' 	  => $this->correct_site_urls( $item['url'] ),
					'menu-item-classes'   => $item['classes'],
					'menu-item-order' 	  => $item['order'],
					'menu-item-parent-id' => $this->get_related_post_id( $item['parent'] ),
					'menu-item-status'	  => 'publish',
		        );

		        $this->switch_to_target_site();

				wp_update_nav_menu_item( $menu_id, 0, $args );
			}
		}

		set_theme_mod( 'nav_menu_locations', $locations );
	}

	private function get_related_post_id( $id ) {

		$ids = mlp_get_linked_elements( $id );

		if ( isset( $ids[ $this->target_site ] ) ) {
			return $ids[ $this->target_site ];
		}

		return 0;
	}

	private function correct_site_urls( $url ) {

		$protocols 			  = array( 'http://', 'https://' );
		$site_url  			  = str_replace( $protocols, '', get_site_url() );
		$original_site_prefix = mlp_get_blog_language( $this->original_site );
		$target_site_prefix   = mlp_get_blog_language( $this->target_site );

		return str_replace( $site_url . $original_site_prefix . '/', $site_url . $target_site_prefix . '/', $url );
	}

	private function strip_xml_tag( $string, $tag_name ) {
		$string = preg_replace( '/<('. $tag_name .') [^>]*>/', '', $string );
		$string = str_replace( '<'. $tag_name .'>', '', $string );
		$string = str_replace( '</'. $tag_name .'>', '', $string );
		return $string;
	}

	private function switch_to_original_site() {
		switch_to_blog( $this->original_site );
	}

	private function switch_to_target_site() {
		switch_to_blog( $this->target_site );
	}

	private function set_target_site( $locale ) {

		$languages 	     = mlp_get_available_languages( true );
		$target_language = substr( $locale, 0, 2 );

		foreach( $languages as $site => $language ) {
		    if ( stripos( $language, $target_language ) === 0 ) {
				$this->target_site = $site;
				break;
		    }
		}

	}

}
