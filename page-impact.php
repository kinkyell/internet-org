<?php
/**
 * The template for displaying the impact page
 *
 * Template Name: Impact
 *
 * @package Internet.org
 */

get_header();

?>

<?php while ( have_posts() ) : the_post(); ?>

	<div class="viewWindow isShifted js-viewWindow js-stateDefault" id="main-content" role="main" data-route="<?php echo esc_url( fix_link( get_the_permalink() ) ); ?>" data-type="panel" data-theme="<?php echo esc_attr( internetorg_get_page_theme() ); ?>" data-title="<?php the_title(); ?>" data-image="<?php echo esc_url( internetorg_get_post_thumbnail( get_the_ID(), 'panel-image' ) ); ?>" data-mobile-image="<?php echo esc_url( internetorg_get_post_thumbnail( get_the_ID(), 'inline-image' ) ); ?>">

		<?php get_template_part( 'template-parts/content', 'page-temp-panel' ); ?>

		<?php get_template_part( 'template-parts/content', 'feature-panel' ); ?>

		<div class="viewWindow-panel viewWindow-panel_story isActive">
			<div class="viewWindow-panel-content">
				<div class="viewWindow-panel-content-inner">

					<div><!-- Needed for JS cache -->

						<?php get_template_part( 'template-parts/content', 'page-intro-block' ); ?>

						<div class="<?php echo esc_attr( 'theme-' . strtolower( internetorg_get_page_theme() ) ); ?>">
							<div class="container">
								<div class="contentCol">

									<?php get_template_part( 'template-parts/content', 'page-intro-desktop' ); ?>

									<?php get_template_part( 'template-parts/content', 'page-wysiwyg' ); ?>

									<?php get_template_part( 'template-parts/content', 'page-feature-blocks' ); ?>

								</div>
							</div>
						</div>

						<?php //get_template_part( 'template-parts/content', 'page-next-page' ); ?>

						<div class="footBox">

							<div class="container page-impact">
								<?php internet_org_get_content_widget_html( 'home-get-involved', false ); ?>
							</div>

							<div class="contentCol contentCol_tight impact">
								<div class="container">
									<?php internetorg_vip_powered_wpcom('pwdByVip-txt pwdByVip-txt_impact'); ?>
								</div>
							</div>

						</div>

					</div>

				</div>
			</div>
		</div>
	</div>


<?php endwhile; // End of the loop. ?>

<?php get_footer();
