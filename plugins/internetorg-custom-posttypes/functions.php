<?php
/**
 * This file contains misc. functions for the plugin.
 *
 * @package IOrgCustomPosttypes
 * @author  arichard <arichard@nerdery.com>
 */

if ( ! function_exists( 'init_internetorg_custom_posttypes_callback' ) ) :
	/**
	 * Plugin initialization callback function.
	 *
	 * @return void
	 */
	function init_internetorg_custom_posttypes_callback() {
		$cpt = Internetorg_CustomPostTypes::get_instance();
		$cpt->create_post_types();
	}
endif;

if ( ! function_exists( 'internetorg_get_content_widget_by_slug' ) ) :
	/**
	 * Look up a content widget with the given slug.
	 *
	 * If data is returned it's in the following format:
	 *
	 * array(
	 *     'post' => WP_Post,
	 *     'meta' => [
	 *          'slug' => $slug,
	 *          'meta-data' => [ // no limit to the number of elements
	 *              [
	 *                  'label' => '',
	 *                  'url'   => '',
	 *                  'image' => '', // image url NOT object or ID
	 *              ],
	 *              ...
	 *          ],
	 *      ],
	 * )
	 *
	 * @param string $slug Slug of the widget to lookup.
	 *
	 * @return bool|array Widget data, else false if not found.
	 */
	function internetorg_get_content_widget_by_slug( $slug ) {
		$cache_key   = sanitize_key( $slug );
		$cache_group = 'internetorg_cntntwdgt';

		$is_cached = false;
		$widget    = wp_cache_get( $cache_key, $cache_group, false, $is_cached );

		if ( ! $is_cached ) {

			$args = array(
				'name'           => $slug,
				'post_type'      => 'io_ctntwdgt',
				'post_status'    => 'publish',
				'posts_per_page' => 1,
			);

			$widget  = false;
			$wdgtqry = new WP_Query( $args );

			while ( $wdgtqry->have_posts() ) : $wdgtqry->the_post();

				$meta = get_post_meta( $wdgtqry->post->ID );

				if ( ! empty( $meta ) ) {
					$data = ( ! empty( $meta['widget-data'][0] ) ? $meta['widget-data'][0] : '' );

					$data = maybe_unserialize( $data );

					if ( ! empty( $data ) ) {
						foreach ( $data as $index => $item ) {
							if ( ! empty( $item['image'] ) ) {
								$data[ $index ]['image'] = wp_get_attachment_url( $item['image'] );
							}
						}
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

			// Cache the results for one day.
			wp_cache_set( $cache_key, $widget, $cache_group, 86400 );

			wp_reset_postdata();
		}

		return $widget;
	}
endif;


if ( ! function_exists( 'internetorg_get_free_services' ) ) :
	/**
	 * Helper function to get an array of all free services
	 *
	 * If data is returned it's in the following format:
	 *
	 * array(
	 *     'post' => WP_Post
	 * )
	 *
	 * @return bool|array Service data, else false if not found.
	 */
	function internetorg_get_free_services() {
		$cache_key = 'free-services';
		$cache_group = 'internetorg_freesvc';
		$is_cached = false;
		$srvpost = wp_cache_get($cache_key, $cache_group, false, $is_cached);

		if ( ! $is_cached ) {

			$args = array(
				'post_type'      => 'io_freesvc',
				'post_status'    => 'publish',
				'posts_per_page' => -1,
			);

			$srvpost  = false;
			$srvquery = new WP_Query( $args );

			if (!$srvquery->have_posts() ){
				return $srvpost;
			}

			while ( $srvquery->have_posts() ) : $srvquery->the_post();

				$srvpost[] = array(
					'post' => $srvquery->post
				);

			endwhile;

			// Cache the results for one day.
			wp_cache_set( $cache_key, $srvpost, $cache_group, 86400 );

			wp_reset_postdata();
		}

		return $srvpost;
	}
endif;
