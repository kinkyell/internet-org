<?php
/**
 * The template for displaying the mission page
 *
 * VIP Scanner and PHPCS complain about the name of this file containing an underscore, however the custom post type
 * that this file corresponds to is named "io_campaign" so this would be a false positive in our opinion.
 *
 * @package Internet.org
 */

get_header();

?>

<div class="viewWindow isShifted js-viewWindow js-stateDefault">
<?php while ( have_posts() ) : the_post(); ?>

	<?php get_template_part( 'template-parts/content', 'page-temp-panel' ); ?>


	<div id="featurePanel" class="viewWindow-panel viewWindow-panel_feature">
		<div class="viewWindow-panel-content">
			<div class="viewWindow-panel-content-inner" style="background-image: url('<?php echo esc_attr( internetorg_get_media_image_url( get_post_thumbnail_id( get_the_ID() ), 'panel-image' ) ); ?>');"></div>
		</div>
	</div>


	<div id="storyPanel" class="viewWindow-panel viewWindow-panel_story isActive">
		<div class="viewWindow-panel-content">
			<div class="viewWindow-panel-content-inner">

				<div class="theme-impact">
					<div class="introBlock">
						<div class="introBlock-inner">
							<div class="container">
								<div class="topicBlock">
									<div class="topicBlock-hd">
										<h2 class="hdg hdg_2"><?php the_title(); ?></h2>
									</div>
									<div class="topicBlock-bd">
										<p class="bdcpy"><?php echo wp_kses_data( internetorg_get_the_subtitle( get_the_ID() ) ); ?></p>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div>
						<div class="contentCol">
							<div class="container quarantine wysiwyg">
								<?php the_content(); ?>
							</div>
						</div>
						<div class="contentCol contentCol_flush">
							<div class="container">
								<?php /* insert gallery here */ ?>
							</div>
						</div>
					</div>

				</div>

				<div class="footBox">
					<div class="container">
						<?php internet_org_get_content_widget_html( 'get-involved', false ); ?>
					</div>
				</div>

			</div>
		</div>
	</div>



<?php endwhile; // End of the loop. ?>
</div>

<?php get_footer();
