<?php
/**
 * The template for displaying all single posts.
 *
 * @package Internet.org
 */

get_header();


// NOTE: $wp_query->found_posts should also == 1
if ( 'page' == get_option( 'show_on_front' ) && $page_on_front = get_option( 'page_on_front' ) ) {
	$after_title_custom_fields = get_post_meta( get_the_ID(), 'after_title_fm_fields', true );
	$custom_fields = get_post_meta( get_the_ID(), 'home-content-section', false );
}

?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php while ( have_posts() ) : the_post(); ?>

			<?php get_template_part( 'template-parts/content', 'single' ); ?>

			<?php the_post_navigation(); ?>

			<?php
			// If comments are open or we have at least one comment, load up the comment template.
			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;
			?>

		<?php endwhile; // End of the loop. ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
