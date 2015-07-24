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

$after_title_custom_fields = get_post_meta( get_the_ID(), 'after_title_fm_fields', false );

var_dump( $after_title_custom_fields );

// content
?>

<h1>HOME!!!</h1>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<?php while ( have_posts() ) : the_post(); ?>

				<?php get_template_part( 'template-parts/content', 'page' ); ?>

			<?php endwhile; // End of the loop. ?>

			<?php


			$custom_fields = get_post_meta( get_the_ID(), 'home_content_section', false );
			var_dump( $custom_fields );

			?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php

// Sidebar
get_sidebar();

// Footer
get_footer();
