<?php
/**
 * The template for displaying the mission page
 *
 * Template Name: Mission
 *
 * @package Internet.org
 */

get_header();

?>

<?php while ( have_posts() ) : the_post(); ?>

	<div class="viewWindow isShifted js-viewWindow js-stateDefault" data-route="<?php the_permalink(); ?>" data-type="panel" data-theme="Mission" data-title="<?php the_title(); ?>" data-image="<?php echo esc_url( internetorg_get_post_thumbnail( get_the_ID() ) ); ?>">


		<?php get_template_part( 'template-parts/content', 'page-temp-panel' ); ?>


		<?php get_template_part( 'template-parts/content', 'feature-panel' ); ?>


		<div class="viewWindow-panel viewWindow-panel_story isActive">
			<div class="viewWindow-panel-content">

				<div class="viewWindow-panel-content-inner">

					<?php get_template_part( 'template-parts/content', 'page-intro-block' ); ?>

					<div class="theme-mission">
						<div class="container">
							<div class="contentCol">

								<?php get_template_part( 'template-parts/content', 'page-intro-desktop' ); ?>

								<?php get_template_part( 'template-parts/content', 'page-wysiwyg' ); ?>

								<?php get_template_part( 'template-parts/content', 'page-feature-blocks' ); ?>

							</div>
						</div>

						<?php get_template_part( 'template-parts/content', 'free-services' ); ?>

					</div>

					<?php get_template_part( 'template-parts/content', 'page-next-page' ); ?>

				</div>

			</div>
		</div>
	</div>


<?php endwhile; // End of the loop. ?>

<?php get_footer();
