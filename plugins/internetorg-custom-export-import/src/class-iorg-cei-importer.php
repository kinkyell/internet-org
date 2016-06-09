<?php

/**
 * Class to handle the importing of content.
 */
class IORG_CEI_Importer {

	/**
	 * Store the XML content to import
	 * @var SimpleXMLElement
	 */
	private $content;

	/**
	 * Stores instance of Shortcode Parser class
	 * @var IORG_CEI_Shortcode_Parser
	 */
	private $parser;

	/**
	 * Stores site id where the content came from.
	 * @var string
	 */
	private $original_site;

	/**
	 * Stores site id where content is going too.
	 * @var string
	 */
	private $target_site;

	/**
	 * Stores instance of Mlp_Content_Relations
	 * The MultilingualPress class for handling relationships
	 * between posts.
	 * @var Mlp_Content_Relations
	 */
	private $mlp_content_relations;

	/**
	 * Stores messages to output to the user.
	 * @var array
	 */
	private $output;

	/**
	 * Execute the importer
	 * @param  string $content
	 * @param  array $request
	 * @return array
	 */
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

		libxml_use_internal_errors( true );

		$this->content = simplexml_load_string( $content );

		if ( ! $this->content ) {
			$this->output['xml'] = libxml_get_errors();

			return $this->output;
		}

		$this->set_target_site( $this->content['locale'] );

		$posts = array();
		$menus = array();

		foreach ( $this->content->obj->container->{'wp-obj'} as $key => $obj ) {

			if ( $obj['wp_type'] == 'post' ||  $obj['wp_type'] == 'page' || $obj['wp_type'] == 'io_story' ) {

				$posts[] = array(
					'original_id' => (string) $obj['wp_post_id'],
					'type'		  => (string) $obj['wp_type'],
					'title'		  => (string) $obj['wp_post_title'],
					'content'	  => (string) $this->get_content( $obj ),
					'custom'	  => $this->get_custom_fields( $obj ),
					'excerpt'	  => (string) $this->get_excerpt( $obj ),
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

	/**
	 * Import posts
	 * @param  array $posts
	 */
	private function posts( $posts ) {

		foreach ( $posts as $post ) {

			$this->switch_to_original_site();

			$current_post = get_post( $post['original_id'],  ARRAY_A );
			$id 		  = $this->get_related_post_id( $post['original_id'] );

			$this->switch_to_target_site();

			$current_post['ID'] 		  = (int) $id;
			$current_post['post_title']   = $post['title'];
			$current_post['post_content'] = $this->parser->from_xml( $post['content'] );
			$current_post['post_excerpt'] = $post['excerpt'];

			unset(
				$current_post['post_content_filtered'],
				$current_post['guid'],
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

		    foreach ( $post['custom'] as $key => $cf ) {
		    	update_post_meta( $post_id, $key, $cf );
		    }

			$this->switch_to_original_site();

			if ( $id == 0 ) {
				$this->mlp_content_relations->set_relation(
					$this->original_site,
					$this->target_site,
					$post['original_id'],
					$post_id,
					'post'
				);
			}

		}
	}

	/**
	 * Import Menus
	 * @param  array $menus
	 */
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

	/**
	 * Get custom fields for a post an returns them as an array.
	 * @param  SimpleXMLElement $obj
	 * @return array
	 */
	private function get_custom_fields( $obj ) {

		$custom_fields = apply_filters( 'iorg_cei_custom_fields_filter', array() );
		$sorted_fields = array();
		if ( $obj->{'wp-custom-fields'}->{'wp-custom-field'} ) {

			foreach ( $obj->{'wp-custom-fields'}->{'wp-custom-field'} as $field ) {

				$type = (string) $field['wp_type'];

				if ( isset( $custom_fields[$type] ) ) {

					if ( isset( $custom_fields[$type]['parent'] ) && empty( $custom_fields[$type]['structure'] ) ) {
						$sorted_fields[$type] = (string) $field->{$custom_fields[$type]['parent']}->{$custom_fields[$type]['tag']};
					} else {
						$current 			  = $custom_fields[$type];
						$sorted_fields[$type] = $this->parse_xml_custom_fields( $current, $field );
					}
				}
			}
		}

		return $sorted_fields;
	}

	/**
	 * Parse XMl for Custom Fields
	 * @param  array $current
	 * @param  SimpleXMLElement $field
	 * @return array
	 */
	private function parse_xml_custom_fields( $current, $field ) {

		$sorted_fields = array();
		$count 	 	   = 0;

		foreach ($field->children() as $children) {

		    if ( $children->getName() == $current['tag'] ) {
		    	foreach ($children->children() as $grandchildren) {
		    		$gc_name = (string) $grandchildren->getName();

		    		if ( $gc_key = array_search( $gc_name, $current['structure'] ) ) {

		    			$value = (string) $grandchildren;

		    			if ( $gc_key == 'content' || $gc_key == 'text' ) {
		    				$value = $this->strip_xml_tag( $grandchildren->asXML(), $gc_name );
		    			}

		    			if( $current['repeater'] ) {
							$sorted_fields[$count][$gc_key] = $value;
		    			} else {
		    				$sorted_fields[$gc_key] = $value;
		    			}

		    		} else {

		    			$gc_type = (string) $grandchildren['wp_type'];

						if ( isset( $current['structure'][$gc_type] ) ) {

							$gc_current 			 = $current['structure'][$gc_type];

							if( $current['repeater'] ) {
								$sorted_fields[$count][$gc_type] = $this->parse_xml_custom_fields( $gc_current, $grandchildren );
			    			} else {
			    				$sorted_fields[$gc_key][$gc_type] = $this->parse_xml_custom_fields( $gc_current, $grandchildren );
			    			}
						}
		    		}
		    	}
		    	$count++;
		    }
		}

		return $sorted_fields;
	}

	/**
	 * Get excerpt for a post
	 * @param  SimpleXMLElement $obj
	 * @return string
	 */
	private function get_excerpt( $obj ) {
		$result = $obj->xpath('wp-custom-fields/wp-custom-field/wp-excerpt');
		if ( isset( $result[0] ) ) {
			return (string) $result[0];
		}
		return '';
	}

	/**
	 * Get content for a post while stripping its custom fields
	 * @param  SimpleXMLElement $obj
	 * @return string
	 */
	private function get_content( $obj ) {
		$content = (string) $this->strip_xml_tag( $obj->asXML(), 'wp-obj' );
		return preg_replace( '/<wp-custom-fields>(.*?)<\/wp-custom-fields>/s', '', $content );
	}

	/**
	 * Returns the id of post for the target site.
	 * @param  string|int $id
	 * @return string|int
	 */
	private function get_related_post_id( $id ) {

		$ids = mlp_get_linked_elements( $id );

		if ( isset( $ids[ $this->target_site ] ) ) {
			return $ids[ $this->target_site ];
		}

		return 0;
	}

	/**
	 * Correct orginial site to target site
	 * e.g. domain.com/en/my-post becomes domain.com/fr/my-post
	 * @param  string $url
	 * @return string
	 */
	private function correct_site_urls( $url ) {

		$protocols 			  = array( 'http://', 'https://' );
		$site_url  			  = str_replace( $protocols, '', get_site_url() );
		$original_site_prefix = mlp_get_blog_language( $this->original_site );
		$target_site_prefix   = mlp_get_blog_language( $this->target_site );

		return str_replace( $site_url . $original_site_prefix . '/', $site_url . $target_site_prefix . '/', $url );
	}

	/**
	 * Strips opening and closing tag of a string.
	 * @param  string $string
	 * @param  string $tag_name
	 * @return string
	 */
	private function strip_xml_tag( $string, $tag_name ) {
		$string = preg_replace( '/<('. $tag_name .') [^>]*>/', '', $string );
		$string = str_replace( '<'. $tag_name .'>', '', $string );
		$string = str_replace( '</'. $tag_name .'>', '', $string );
		return $string;
	}

	/**
	 * Switches site/blog to original site (where content came from)
	 */
	private function switch_to_original_site() {
		switch_to_blog( $this->original_site );
	}

	/**
	 * Switches site/blog to target site (where content going too)
	 */
	private function switch_to_target_site() {
		switch_to_blog( $this->target_site );
	}

	/**
	 * Sets 'target_site' from the locale found in the import file.
	 * @param string $locale
	 */
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
