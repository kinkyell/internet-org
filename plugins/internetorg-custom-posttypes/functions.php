<?php
/**
 * This file contains misc. functions for the plugin
 *
 * @package IOrgCustomPosttypes
 * @author arichard <arichard@nerdery.com>
 */

if ( ! function_exists( 'init_internetorg_custom_posttypes_callback' ) ) :
	/**
	 * Plugin initialization callback function
	 *
	 * @return void
	 */
	function init_internetorg_custom_posttypes_callback() {
		$cpt = Internetorg_CustomPostTypes::get_instance();
		$cpt->create_post_types();
	}
endif;

if ( ! function_exists( 'activate_internetorg_custom_posttypes_callback' ) ) :
	/**
	 * Plugin activation callback function
	 *
	 * @return void
	 */
	function activate_internetorg_custom_posttypes_callback() {
		$cpt = Internetorg_CustomPostTypes::get_instance();
		$cpt->activate();
	}
endif;

if ( ! function_exists( 'deactivate_internetorg_custom_posttypes_callback' ) ) :
	/**
	 * Plugin deactivation callback function
	 *
	 * @return void
	 */
	function deactivate_internetorg_custom_posttypes_callback() {
		$cpt = Internetorg_CustomPostTypes::get_instance();
		$cpt->deactivate();
	}
endif;

if ( ! function_exists( 'internetorg_get_content_widget_by_slug' ) ) :
	/**
	 * Look up a content widget with the given slug
	 *
	 * if data is returned it's in the following format:
	 *
	 * array(
	 *     'post' => WP_Post,
	 *     'meta' => array,
	 * )
	 *
	 * @param string $slug slug of the widget to lookup
	 * @return mixed array widget data or false if not found
	 */
	function internetorg_get_content_widget_by_slug( $slug ) {
		$cache_key = sanitize_key( $slug );
		$cache_group = 'internetorg_cntntwdgt';

		$is_cached = false;
		$widget = wp_cache_get( $cache_key, $cache_group, false, $is_cached );

		if ( ! $is_cached ) {

			$args = array(
				'name' => $slug,
				'post_type' => 'io_ctntwdgt',
				'post_status' => 'publish',
				'posts_per_page' => 1,
			);

			$widget = false;
			$wdgtqry = new WP_Query( $args );

			while ( $wdgtqry->have_posts() ) : $wdgtqry->the_post();

				$meta = get_post_meta( $wdgtqry->post->ID );

				if ( ! empty( $meta ) ) {
					$slug = ( ! empty( $meta['slug'][0] ) ? $meta['slug'][0] : '' );
					$data = ( ! empty( $meta['widget-data'][0] ) ? $meta['widget-data'][0] : '' );

					$data = maybe_unserialize( $data );

					if ( ! empty( $data['image'] ) ) {
						$imgurl = wp_get_attachment_url( $data['image'] );
						$data['image'] = $imgurl;
					}

					$meta = array(
						'slug'        => $slug,
						'widget-data' => $data,
					);
				}

				$widget = array(
					'post' => $wdgtqry->post,
					'meta' => $meta,
				);

				break;
			endwhile;

			// cache the results for one day
			wp_cache_set( $cache_key, $widget, $cache_group, 86400 );

			wp_reset_postdata();
		}

		return $widget;
	}
endif;