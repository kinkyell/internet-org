<?php
/**
 * Fix any broken link that may not have had it's data dump exported/imported properly
 *
 * This is great for local, development and staging instances.
 */

function fix_link( $link ) {
    $proto = ( strpos( $link, 'https' ) ) ? 'https' : 'http';
    $host = $_SERVER[ 'HTTP_HOST' ];
    $replace = '';
    $domain = '';
    switch( $host ) {
    	case 'fbinternetorg.wordpress.com':
    		$replace = 'fbinternetorg.wordpress.com';
            $domain = 'info.internet.org';
    	break;
    	case 'internetorg.jam3.net':
            $domain = 'internetorg.jam3.net';
    	break;
    	default:
    		$domain = 'vip.local';
    	break;
    }
    $path = str_replace( array( "$proto://", $domain, $replace ), '', $link );
    $path = ( $path[ 0 ] === '/' ) ? $path : "/$path";
    return "$proto://$domain$path";
}
