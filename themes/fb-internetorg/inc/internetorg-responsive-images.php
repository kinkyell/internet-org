<?php

/** enqueue after internetorg primary styles */
add_action( 'wp_enqueue_scripts', 'internetorg_responsive_images_css', 1000 );

/**
 * Enqueue responsive image CSS. Each template must be defined here.
 */
function internetorg_responsive_images_css() {

  /**
   * Currently, responsive images are only being loaded from homepage. Enqueue additional styles
   * with the URL of the page.
   */
  wp_enqueue_style(
    'internetorg-dynamic',
    add_query_arg( 'internetorg_responsive_images_css', true, site_url('/') ),
    array(),
    false,
    'screen, projection'
  );
}

add_filter( 'query_vars', 'internetorg_responsive_images_query_var');

/**
 * Add custom query var to designate responsive image CSS request.
 * @param  array $vars Query vars.
 * @return array       Updated query vars.
 */
function internetorg_responsive_images_query_var( $vars ){
    $vars[] = "internetorg_responsive_images_css";
    return $vars;
}

add_action( 'parse_request', 'internetorg_responsive_images_update_request' );

/**
 * Check if query var requesting responsive image CSS is used. If so, replace flag with
 * a global so query runs normally.
 *
 * @global bool $internetorg_responsive_images_requested Are responsive images being requested?
 * @param  WP_Query $query The query.
 */
function internetorg_responsive_images_update_request( $query ) {
  global $internetorg_responsive_images_requested;

  if ( isset( $query->query_vars['internetorg_responsive_images_css'] ) ) {
    $internetorg_responsive_images_requested = true;
    unset( $query->query_vars['internetorg_responsive_images_css'] );
  } else {
    $internetorg_responsive_images_requested = false;
  }
}

add_action( 'send_headers', 'internetorg_responsive_images_update_header' );

/**
 * Update heaer for css output.
 *
 * @global bool $internetorg_responsive_images_requested Are responsive images being requested?
 */
function internetorg_responsive_images_update_header() {
  global $internetorg_responsive_images_requested;

  if ( $internetorg_responsive_images_requested === true ) {
    header("Content-type: text/css; charset: UTF-8");
  }
}

add_filter( 'template_include', 'internetorg_responsive_images_update_template', 99 );

/**
 * Attempt to load template responsive image CSS.
 *
 * @global bool $internetorg_responsive_images_requested Are responsive images being requested?
 * @param  string $template Requested template.
 * @return string Responsive image CSS template, if one exists.
 */
function internetorg_responsive_images_update_template( $template ) {
  global $internetorg_responsive_images_requested;

  if ( $internetorg_responsive_images_requested === true ) {
    $path = pathinfo( $template );

    $file = $path['filename'] . '--responsive-images.css.php';

    // Check for valid file, to prevent directory traversal.
    if ( validate_file( $file ) !== 0 ) {
      exit;
    }

    $new_template = locate_template( array( $file) );

    if ( ! empty( $new_template ) ) {
      ob_start();
      include ( $template );
      ob_end_clean();

      return $new_template;
    } else {
      exit;
    }
  }

  return $template;
}

/**
 * Return the unique classname representing the image and size.
 *
 * @global array $internetorg_responsive_images_ids Image IDs.
 * @param  inbt $img_id Imager ID.
 * @param  string $size Image size.
 * @return string Responsive image classname.
 */
function internetorg_responsive_images_classname( $img_id, $size = 'large' ) {
  $image_id = absint( $img_id );
  $size = sanitize_key( $size );

  global $internetorg_responsive_images_ids;

  // Initialize global.
  if ( is_null( $internetorg_responsive_images_ids ) ) {
    $internetorg_responsive_images_ids = array();
  }

  $class_name = "iorg-img-{$img_id}-{$size}";

  $internetorg_responsive_images_ids[ $class_name ] = (object) array(
    'id' => $img_id,
    'size' => $size
  );

  return $class_name;
}