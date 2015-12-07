<?php
/**
 * The template for displaying all single io_story (substory) posts.
 *
 * @package Internet.org
 */

get_header();

?>

<?php while ( have_posts() ) : the_post(); ?>

	<div class="viewWindow isShifted js-viewWindow js-stateDefault" id="main-content" role="main" data-route="<?php echo esc_url( fix_link( get_the_permalink() ) ); ?>" data-type="panel" data-title="<?php the_title(); ?>" data-image="<?php echo esc_url( internetorg_get_post_thumbnail( get_the_ID(), 'panel-image' ) ); ?>">

		<?php get_template_part( 'template-parts/content', 'page-temp-panel' ); ?>

		<?php get_template_part( 'template-parts/content', 'feature-panel' ); ?>

		<div class="viewWindow-panel viewWindow-panel_story isActive">
			<div class="viewWindow-panel-content">
				<div class="viewWindow-panel-content-inner">

					<div>

						<?php get_template_part( 'template-parts/content', 'page-intro-block' ); ?>

						<div class="container <?php echo esc_attr( 'theme-' . strtolower( internetorg_get_page_theme() ) ); ?>">
							<div class="contentCol">

								<?php get_template_part( 'template-parts/content', 'page-wysiwyg' ); ?>

							</div>
						</div>

						<?php get_template_part( 'template-parts/content', 'free-services' ); ?>

						<div class="footBox">
							<div class="container">
								<?php internet_org_get_content_widget_html( 'get-involved', false ); ?>
							</div>
						</div>

					</div>

				</div>
			</div>
		</div>
	</div>

<?php endwhile; ?>

<?php get_footer();
