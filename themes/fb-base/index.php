<?php


/**
 * A Theme to handle redirect to the right language version.
 * Need because the english site shosuld `/en` not `/`
 */

$languages 	   = mlp_get_available_languages( true );
$user_language = substr( $_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2 );

foreach( $languages as $site => $language ) {
    if ( stripos( $language, $user_language ) === 0 ) {
    	$site_prefix = mlp_get_blog_language( $site );
    	wp_redirect( "/{$site_prefix}" . $_SERVER['REQUEST_URI'] );
    	exit;
    }
}

$site 		 = current( array_keys( $languages ) );
$site_prefix = mlp_get_blog_language( $site );
wp_redirect( "/{$site_prefix}" . $_SERVER['REQUEST_URI'] );
exit;
