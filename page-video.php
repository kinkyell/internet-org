<?php
/**
 * The template for displaying the video test page
 *
 * Template Name: Video
 *
 * @package Internet.org
 */

get_header();

?>
  <div class="viewWindow isShifted js-viewWindow js-stateDefault navigation-opera-mini" id="main-content" role="main" data-route="<?php the_permalink(); ?>" data-type="panel" data-title="<?php the_title(); ?>" data-image="<?php echo esc_url( internetorg_get_post_thumbnail( get_the_ID(), 'panel-image' ) ); ?>" data-mobile-image="<?php echo esc_url( internetorg_get_post_thumbnail( get_the_ID(), 'inline-image' ) ); ?>">
    <video controls="" style="width:50%;height:auto;" poster="http://www.html5rocks.com/en/tutorials/video/basics/poster.png">
        <source src="http://www.html5rocks.com/en/tutorials/video/basics/devstories.webm" type="video/webm;codecs=&quot;vp8, vorbis&quot;">
        <source src="http://www.html5rocks.com/en/tutorials/video/basics/devstories.mp4" type="video/mp4;codecs=&quot;avc1.42E01E, mp4a.40.2&quot;">
        <source src="http://www.w3schools.com/html/mov_bbb.ogg" type="video/ogg">
    </video>
    <div class="viewWindow js-viewWindow js-stateDefault" id="main-content" role="main" data-route="<?php the_permalink(); ?>" data-type="panel" data-theme="<?php echo esc_attr( internetorg_get_page_theme() ); ?>" data-title="<?php the_title(); ?>" data-image="<?php echo esc_url( internetorg_get_post_thumbnail( get_the_ID(), 'panel-image' ) ); ?>" data-mobile-image="<?php echo esc_url( internetorg_get_post_thumbnail( get_the_ID(), 'inline-image' ) ); ?>">
    </div>
  </div>