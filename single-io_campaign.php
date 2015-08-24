<?php
/**
 * The template for displaying the mission page
 *
 * Template Name: Mission
 *
 * @todo: Discuss with FED what class to apply on WYSIWYG areas
 * @todo: How to determine if this is being ajaxed in so we don't output header/footer/etc., and is that allowed on VIP?
 * @todo: How to handle fields that weren't intended for use on this template
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
		    <?php
		    // if we need a video url we'll add this
		    /*
			<a href="" class="contentOnMedia-link contentOnMedia-link_ct"><span class="circleBtn circleBtn_play"></span></a>
             */

			$featured_image_url = '';
			if ( has_post_thumbnail() ) {
				/*
				$featured_image = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'single-post-thumbnail' );
				if ( is_array( $featured_image ) && ! empty( $featured_image[0] ) ) {
					$featured_image_url = $featured_image[0];
				}
				 */
				$featured_image_url = internetorg_get_media_image_url( get_post_thumbnail_id( get_the_ID() ), 'single-post-thumbnail' );
			}

			?>
			<div class="viewWindow-panel-content-inner" style="background-image: url('<?php echo esc_attr( $featured_image_url ); ?>');"></div>
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
