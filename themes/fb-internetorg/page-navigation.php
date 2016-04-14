<?php
/**
 * The template for displaying the navigation page (mostly used for Opera Mini)
 *
 * Template Name: Navigation
 *
 * @package Internet.org
 */

get_header();

?>

<?php while ( have_posts() ) : the_post(); ?>
  <div class="viewWindow isShifted js-viewWindow js-stateDefault navigation-opera-mini" id="main-content" role="main" data-route="<?php the_permalink(); ?>" data-type="panel" data-title="<?php the_title(); ?>" data-image="<?php echo esc_url( internetorg_get_post_thumbnail( get_the_ID(), 'panel-image' ) ); ?>" data-mobile-image="<?php echo esc_url( internetorg_get_post_thumbnail( get_the_ID(), 'inline-image' ) ); ?>">

      <?php internetorg_nav_menu( 'primary' ); ?>
      <?php internetorg_nav_menu( 'secondary' ); ?>
      <?php internetorg_language_switcher_menu_fallback(); ?>
  </div>

<?php endwhile; // End of the loop. ?>
<?php get_footer();
