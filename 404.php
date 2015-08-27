<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @package Internet.org
 */

get_header();

?>



	<div class="viewWindow isShifted js-viewWindow js-stateDefault" data-route="<?php echo esc_url( home_url( '/404/' ) ); ?>" data-type="panel" data-theme="<?php echo esc_attr( internetorg_get_page_theme() ); ?>" data-title="<?php esc_attr_e( 'Not Found', 'internetorg' ); ?>" data-image="<?php echo esc_url( get_stylesheet_directory_uri() . '/_static/web/assets/media/uploads/home.jpg' ); ?>">


		<?php get_template_part( 'template-parts/content', 'page-temp-panel' ); ?>


		<?php get_template_part( 'template-parts/content', 'feature-panel' ); ?>


		<div class="viewWindow-panel viewWindow-panel_story isActive">
			<div class="viewWindow-panel-content">

				<div class="viewWindow-panel-content-inner">

					<div class="introBlock">
						<div class="introBlock-inner">

							<div class="topicBlock">
								<div class="topicBlock-hd topicBlock-hd_mega <?php echo esc_attr( 'topicBlock-hd_theme' . internetorg_get_page_theme() ); ?>">
									<h2 class="hdg hdg_2 mix-hdg_bold"><?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'internetorg' ); ?></h2>
								</div>

								<!-- START ADD MOBILE ONLY CONTENT HERE -->

								<!-- Secondary (mobile) Feature Image -->
								<div class="topicBlock-media isHidden u-isHiddenMedium" aria-hidden="true">
									<img src="<?php echo esc_url( get_stylesheet_directory_uri() . '/_static/web/assets/media/uploads/home.jpg' ); ?>" alt="" />
								</div>

								<!-- Duplicate Content - Mobile Only -->
								<div class="topicBlock-bd isHidden u-isHiddenMedium" aria-hidden="true">
									<div class="hdg hdg_3"><?php esc_html_e( 'Not found', 'internetorg' ); ?></div>
									<p class="bdcpy"><?php esc_html_e( 'It looks like nothing was found at this location. Maybe try a search?', 'internetorg' ); ?></p>
								</div>

								<!-- END ADD MOBILE ONLY CONTENT HERE -->

							</div>
						</div>
					</div>


					<div class="<?php echo esc_attr( 'theme-' . strtolower( internetorg_get_page_theme() ) ); ?>">
						<div class="container">
							<div class="contentCol">


								<!-- START ADD DESKTOP ONLY CONTENT HERE -->
								<!-- Duplicate Content - DESKTOP Only -->
								<div class="feature isVissuallyHidden u-isVisuallyHiddenSmall">
									<div class="feature-hd">
										<div class="hdg hdg_3"><?php esc_html_e( 'Not found', 'internetorg' ); ?></div>
									</div>
									<div class="feature-bd">
										<p class="bdcpy bdcpy_sm">
											<?php esc_html_e( 'It looks like nothing was found at this location. Maybe try a search?', 'internetorg' ); ?>
										</p>
									</div>
								</div>
								<!-- END Duplicate Content - DESKTOP Only -->
								<!-- END ADD DESKTOP ONLY CONTENT HERE -->


								<div class="feature">
									<div class="feature-bd wysiwyg quarantine">
										<?php get_search_form(); ?>
									</div>
								</div>


							</div>
						</div>
					</div>


				</div>

			</div>
		</div>
	</div>



<?php get_footer();
