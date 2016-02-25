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
    <div style="max-width: 480px; margin: 20px auto 20px;">
        <!-- Language Selection Module -->
        <div class="langSelect js-select isOpen">
            <div class="langSelect-label">English</div>
            <div class="langSelect-menu">
                <div class="langSelect-menu-item isSelected" tabindex="0"><span>English</span></div>
                <div class="langSelect-menu-item" tabindex="0"><span>Bahasa</span></div>
                <div class="langSelect-menu-item" tabindex="0"><span>Spanish</span></div>
                <div class="langSelect-menu-item" tabindex="0"><span>French</span></div>
                <div class="langSelect-menu-item" tabindex="0"><span>Portuguese</span></div>
                <div class="langSelect-menu-item" tabindex="0"><span>Arabic</span></div>
                <div class="langSelect-menu-item" tabindex="0"><span>Hindi</span></div>
                <div class="langSelect-menu-item" tabindex="0"><span>Urdu</span></div>
                <div class="langSelect-menu-item" tabindex="0"><span>Bengali</span></div>
                <div class="langSelect-menu-item" tabindex="0"><span>Russian</span></div>
                <div class="langSelect-menu-item" tabindex="0"><span>Japanese</span></div>
                <div class="langSelect-menu-item" tabindex="0"><span>Punjabi</span></div>
            </div>
        </div>
  </div>
<?php endwhile; // End of the loop. ?>

<?php get_footer();