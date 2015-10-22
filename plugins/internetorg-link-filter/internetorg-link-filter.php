<?php
/**
 * Created by PhpStorm.
 * User: bkoren
 * Date: 10/20/15
 * Time: 12:14 PM
 *
 * @package Internet.org
 */

require_once dirname( __FILE__ ) . '/includes/content-parser.class.php';
require_once dirname( __FILE__ ) . '/includes/filter-bridge.php';
require_once dirname( __FILE__ ) . '/includes/link-transformer.class.php';

$bridge = internetorg_link_filter_bridge();

add_filter( 'the_content', array( $bridge, 'filter_content' ), 100 );
add_filter( 'widget_content', array( $bridge, 'filter_content' ), 100 );
add_filter( 'the_excerpt', array( $bridge, 'filter_content' ), 100 );
add_filter( 'the_permalink', array( $bridge, 'filter_url' ), 100 );
add_filter( 'page_link', array( $bridge, 'filter_page_link' ), 100, 2 );
add_filter( 'post_link', array( $bridge, 'filter_post_link' ), 100, 3 );
add_filter( 'iorg_url', array( $bridge, 'filter_url' ) );

/**
 * Function used to initialize the plugin and the various required objects.
 *
 * @TODO Move this to a proper singleton / service provider
 *
 * @return FilterBridge
 */
function internetorg_link_filter_bridge() {
	$transformer = new LinkTransformer();
	$parser      = new ContentParser( $transformer );
	$bridge      = new FilterBridge( $parser, $transformer );

	return $bridge;
}
