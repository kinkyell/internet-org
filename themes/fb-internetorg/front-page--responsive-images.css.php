<?php

  /**
   * Print media queries for responsive images.
   * 
   * @param  string $class_name [description]
   * @param  Object $data {
   *     Includes image ID and image size label
   *     
   *     @type int Image ID.
   *     @type string Image size.
   * }
   */
  function internet_org_front_page_image_style( $class_name, $data ) {

    $img_id = $data->id;
    $size = $data->size;

    if ( empty( $img_id ) ) {
      return;
    }

    $base_img = internetorg_get_media_image_url( $img_id , $size );

    $use_photon = function_exists( 'wpcom_vip_get_resized_attachment_url' ) &&
                  apply_filters( 'internetorg_responsive_images_enabled', true );

    if ( $use_photon === false ) {
      $size = null;
    }

    switch ( $size ) {
      case 'panel-image':
        $md = wpcom_vip_get_resized_attachment_url( $img_id, 768, 960 );
        $sm = wpcom_vip_get_resized_attachment_url( $img_id, 480, 600 );
        $xs = wpcom_vip_get_resized_attachment_url( $img_id, 320, 400 );

        if ( ! empty ( $base_img ) ): ?>
          @media screen and (min-width: 1536px) {
            .<?php echo sanitize_html_class( $class_name ); ?> {
              background-image: url(<?php echo esc_url_raw( $base_img ); ?>);
            }
          }

          @media screen and (max-width: 1535px) and (min-width: 481px) {
            .<?php echo sanitize_html_class( $class_name ); ?> {
              background-image: url(<?php echo esc_url_raw( $md ); ?>);
            }
          }

          @media screen and (max-width: 480px) and (min-width: 321px) {
            .<?php echo sanitize_html_class( $class_name ); ?> {
              background-image: url(<?php echo esc_url_raw( $sm ); ?>);
            }
          }

          @media screen and (max-width: 320px) {
            .<?php echo sanitize_html_class( $class_name ); ?> {
              background-image: url(<?php echo esc_url_raw( $xs ); ?>);
            }
          }
        <?php endif;

      break;
      default:
        if ( ! empty ( $base_img ) ): ?>
          .<?php echo sanitize_html_class( $class_name ); ?> {
            background-image: url(<?php echo esc_url_raw( $base_img ); ?>);
          }
        <?php
        endif;
      break;
    } // switch
  }

  global $internetorg_responsive_images_ids;

  /**
   * Loop through all images loaded on front page through 
   */
  foreach ( $internetorg_responsive_images_ids as $class_name => $data ) {
    internet_org_front_page_image_style($class_name, $data);
  }
