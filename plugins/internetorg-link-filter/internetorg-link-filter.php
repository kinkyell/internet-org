<?php
/**
 * Created by PhpStorm.
 * User: bkoren
 * Date: 10/20/15
 * Time: 12:14 PM
 */

require_once dirname(__FILE__) . '/includes/ContentParser.class.php';
require_once dirname(__FILE__) . '/includes/FilterBridge.php';
require_once dirname(__FILE__) . '/includes/LinkTransformer.class.php';

$bridge = internetorg_link_filter_bridge();

add_filter('the_content', array($bridge, 'filterContent'), 100);
add_filter('widget_content', array($bridge, 'filterContent'), 100);
add_filter('the_excerpt', array($bridge, 'filterContent'), 100);
add_filter('the_permalink', array($bridge, 'filterUrl'), 100);
add_filter('page_link', array( $bridge, 'filterPageLink' ), 100, 2 );
add_filter('post_link', array($bridge, 'filterPostLink'), 100, 3 );

/**
 * @TODO Move this to a proper singleton / service provider
 *
 * @return FilterBridge
 */
function internetorg_link_filter_bridge () {
  $transformer = new LinkTransformer();
  $parser = new ContentParser($transformer);
  $bridge = new FilterBridge($parser, $transformer);

  return $bridge;
}
