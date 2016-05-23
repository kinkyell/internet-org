<?php

/**
 * Returns the current state of the given WordPress filter.
 *
 * @param string $tag The filter name
 *
 * @return WordPress filters set to a specific tage
 */
function internetorg_get_filters( $tag ) {
  global $wp_filter;
  return $wp_filter[ $tag ];
}

/**
 * Sets the given WordPress filter to a state saved by get_filter.
 *
 * @param string $tag The filter name
 * @param object a set of WordPress filters to be applied
 *
 */
function internetorg_set_filters( $tag, $saved ) {
  remove_all_filters( $tag );
  foreach ( $saved as $priority => $func_list ) {
    foreach ( $func_list as $func_name => $func_args ) {
      add_filter( $tag, $func_args[ 'function' ], $priority, $func_args[ 'accepted_args' ] );
    }
  }
}
