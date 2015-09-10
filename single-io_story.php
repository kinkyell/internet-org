<?php
/**
 * The template for displaying all single io_story (substory) posts.
 *
 * @package Internet.org
 */

get_header();

?>

<?php while ( have_posts() ) : the_post(); ?>

	<div class="viewWindow isShifted js-viewWindow js-stateDefault" role="main" data-type="titled">

		<?php get_template_part( 'template-parts/content', 'page-temp-panel' ); ?>

		<?php get_template_part( 'template-parts/content', 'feature-panel' ); ?>

		<div class="viewWindow-panel viewWindow-panel_story isActive">
			<div class="viewWindow-panel-content">
				<div class="viewWindow-panel-content-inner">

					<div>

						<!-- START MOBILE ONLY CONTENT HERE -->
						<div class="isHidden u-isHiddenMedium" aria-hidden="true">
							<img src="<?php echo esc_url( internetorg_get_mobile_featured_image( get_post_type(), get_the_ID() ) ); ?>" alt="" />
						</div>
						<!-- END MOBILE ONLY CONTENT HERE -->

						<div class="introBlock introBlock_flushMobi">
							<div class="introBlock-inner">
								<div class="container">
									<div class="topicBlock">
										<div class="topicBlock-hd topicBlock-hd_plus topicBlock-hd_themeApproach">
											<h2 class="hdg hdg_2 mix-hdg_bold"><?php the_title(); ?></h2>
										</div>
										<div class="topicBlock-bd">
											<p class="bdcpy">
												<?php echo esc_html( internetorg_get_the_subtitle( get_the_ID() ) ); ?>
											</p>
										</div>
									</div>
								</div>
							</div>
						</div>

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
