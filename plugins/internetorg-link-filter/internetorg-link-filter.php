<?php
/**
 * Created by PhpStorm.
 * User: bkoren
 * Date: 10/20/15
 * Time: 12:14 PM
 */

require_once dirname(__FILE__) . '/includes/ContentParser.class.php';
require_once dirname(__FILE__) . '/includes/LinkTransformer.class.php';

$current_site = get_current_site();
$transformer = new LinkTransformer(bbl_get_current_lang(), $current_site->domain);
$parser = new ContentParser($transformer);

add_filter('the_content', array($parser, 'parseLinks'), 100);
add_filter('widget_content', array($parser, 'parseLinks'), 100);
add_filter('the_excerpt', array($parser, 'parseLinks'), 100);
add_filter('get_post_metadata', array($parser, 'parseLinks'), 100);
