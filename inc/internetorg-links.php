<?php

/**
 * Fix any broken link that may not have had it's data dump exported/imported properly
 * This is great for local, development and staging instances.
 *
 * @param string $link the url to be fixed
 *
 * @return string the fixed url
 */
function internetorg_fix_link( $link ) {

    $proto = ( strpos( $link, 'https' ) ) ? 'https' : 'http';
    $host = preg_replace( array( '/.*?:\/\//', '/^www\./' ), '', get_site_url() );
    $replace = '';
    $domain = '';
    $lang  = bbl_get_default_lang_code();
    $prefix = bbl_get_prefix_from_lang_code( $lang );
    $parts = explode( '/', $link );

    if ( ( $key = array_search( $prefix, $parts ) ) !== false ) {
        unset( $parts[ $key ] );
        $link = implode( '/', $parts );
    }

    switch( $host ) {
        case 'fbinternetorg.wordpress.com':
            $replace = 'fbinternetorg.wordpress.com';
            $domain = 'info.internet.org';
        break;
        case 'internetorgstage.jam3.net':
            $domain = 'internetorgstage.jam3.net';
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
    return "$proto://$domain/$prefix$path";

}
