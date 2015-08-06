<?php
/**
 * This is the home page template
 *
 * @package Internet_org
 * @author arichard <arichard@nerdery.com>
 */

// Header
get_header();

// content
?>


			<?php while ( have_posts() ) : the_post(); ?>

				<?php get_template_part( 'template-parts/content', 'page-entry-start' ); ?>

				<?php get_template_part( 'template-parts/content', 'page-entry-content' ); ?>

				<?php get_template_part( 'template-parts/content', 'page-home-blocks' ); ?>

				<?php get_template_part( 'template-parts/content', 'page-entry-end' ); ?>

			<?php endwhile; // End of the loop. ?>



// Sidebar -- no sidebar on the front page
// get_sidebar();

// Footer
get_footer();
