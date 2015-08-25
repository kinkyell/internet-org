<?php
/**
 * The template for displaying all single posts.
 *
 * @package Internet.org
 */

get_header();

?>

	<div class="viewWindow isShifted js-stateDefault">
		<?php while ( have_posts() ) : the_post(); ?>

			<?php get_template_part( 'template-parts/content', 'page-temp-panel' ); ?>

		<div id="featurePanel" class="viewWindow-panel viewWindow-panel_feature">
			<div class="viewWindow-panel-content">
				<div class="viewWindow-panel-content-inner">
					<div class="introBlock introBlock_fill">
						<div class="introBlock-inner">
							<div class="container">
								<div class="topicBlock">

									<div class="topicBlock-hd topicBlock-hd_plus">
										<h2 class="hdg hdg_2 mix-hdg_bold"><?php the_title(); ?></h2>
									</div>
									<div class="topicBlock-subHd">
										<div class="hdg hdg_6 mix-hdg_italic mix-hdg_gray"><?php internetorg_posted_on_date(); ?></div>
									</div>
									<div class="topicBlock-bd">
										<p class="bdcpy">
											<?php echo wp_kses_post( internetorg_get_the_subtitle( get_the_ID() ) ); ?>
										</p>
									</div>

								</div>
							</div>
						</div>
						<div class="introBlock-ft introBlock-ft_rule">
							<ul class="socialParade">
								<li><a class="socialParade-icon socialParade-icon_fb" href=""><?php esc_html_e( 'Facebook', 'internetorg' ); ?></a></li>
								<li><a class="socialParade-icon socialParade-icon_tw" href=""><?php esc_html_e( 'Twitter', 'internetorg' ); ?></a></li>
								<li><a class="socialParade-icon socialParade-icon_li" href=""><?php esc_html_e( 'LinkedIn', 'internetorg' ); ?></a></li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div id="storyPanel" class="viewWindow-panel viewWindow-panel_story isActive">
			<div class="viewWindow-panel-content">
				<div class="viewWindow-panel-content-inner">
					<div>

						<div class="contentCol">
							<div class="container">
								<div class="feature">
									<div class="feature-bd wysiwyg quarantine">
										<?php the_content(); ?>
									</div>
								</div>
							</div>
						</div>


					</div>
				</div>
			</div>
		</div>
		<?php endwhile; ?>
	</div>

<?php get_footer();
