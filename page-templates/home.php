<?php
/**
 * This is the home page template
 *
 * Template Name: Home Page
 *
 * @package Internet_org
 * @author arichard <arichard@nerdery.com>
 */

// Header
get_header();

// content
?>
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<?php while ( have_posts() ) : the_post(); ?>

				<?php get_template_part( 'template-parts/content', 'page' ); ?>

				<?php
					// If comments are open or we have at least one comment, load up the comment template.
					if ( comments_open() || get_comments_number() ) :
						comments_template();
					endif;
				?>

			<?php endwhile; // End of the loop. ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php

// Sidebar
get_sidebar();

// Footer
get_footer();
