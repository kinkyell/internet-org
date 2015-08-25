<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @package Internet.org
 */

get_header();

?>

	<div class="viewWindow isShifted js-viewWindow js-stateDefault" data-route="/404" data-type="panel" data-theme="Approach" data-title="Not Found" data-image="<?php echo esc_url( get_stylesheet_directory_uri() . '/_static/web/assets/media/uploads/home.jpg' ); ?>">


		<?php get_template_part( 'template-parts/content', 'page-temp-panel' ); ?>


		<div id="featurePanel" class="viewWindow-panel viewWindow-panel_feature">
			<div class="viewWindow-panel-content">
				<div class="viewWindow-panel-content-inner" style="background-image: url(<?php echo esc_url( get_stylesheet_directory_uri() . '/_static/web/assets/media/uploads/home.jpg' ); ?>);"></div>
			</div>
		</div><!-- end viewWindow-panel_feature -->


		<div id="storyPanel" class="viewWindow-panel viewWindow-panel_story isActive">
			<div class="viewWindow-panel-content">

				<div class="introBlock">
					<div class="introBlock-inner">
						<div class="container">
							<div class="topicBlock">
								<div class="topicBlock-hd topicBlock-hd_mega topicBlock-hd_themeApproach">
									<h2 class="hdg hdg_2 mix-hdg_bold">
										<?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'internetorg' ); ?>
									</h2>
								</div>
								<div class="topicBlock-bd">
									<p class="bdcpy">
									</p>
								</div>
							</div>
						</div>
					</div>
				</div>


				<div class="theme-approach">


					<div class="contentCol">
						<div class="container">

							<div class="feature"> <!-- TEXT -->
								<div class="feature-bd wysiwyg quarantine">

									<p><?php esc_html_e( 'It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'internetorg' ); ?></p>

									<?php get_search_form(); ?>

								</div>
							</div>

						</div><!-- end container -->
					</div><!-- end contentCol -->

				</div> <!-- end theme-approach -->


			</div>
		</div>
	</div>


<?php get_footer();
