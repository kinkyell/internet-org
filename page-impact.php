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

	<div class="viewWindow isShifted js-viewWindow js-stateDefault" data-route="<?php the_permalink(); ?>" data-type="panel" data-theme="Impact" data-title="<?php the_title(); ?>" data-image="<?php echo esc_url( internetorg_get_post_thumbnail( get_the_ID() ) ); ?>">


		<?php get_template_part( 'template-parts/content', 'page-temp-panel' ); ?>


		<div id="featurePanel" class="viewWindow-panel viewWindow-panel_feature">
			<div class="viewWindow-panel-content">
				<div class="viewWindow-panel-content-inner" style="background-image: url(<?php echo esc_url( internetorg_get_post_thumbnail( get_the_ID() ) ); ?>);"></div>
			</div>
		</div><!-- end viewWindow-panel_feature -->


		<div id="storyPanel" class="viewWindow-panel viewWindow-panel_story isActive">
			<div class="viewWindow-panel-content">

				<div class="introBlock">
					<div class="introBlock-inner">
						<div class="container">
							<div class="topicBlock">
								<div class="topicBlock-hd topicBlock-hd_mega topicBlock-hd_themeImpact">
									<h2 class="hdg hdg_2 mix-hdg_bold">
										<?php the_title(); ?>
									</h2>
								</div>
								<div class="topicBlock-bd">
									<p class="bdcpy">
										<?php echo sanitize_text_field( internetorg_get_the_subtitle( get_the_ID() ) ); ?>
									</p>
								</div>
							</div>
						</div>
					</div>
				</div>


				<div class="theme-impact">


					<div class="contentCol">
						<div class="container">

							<div class="feature"> <!-- TEXT -->
								<div class="feature-bd wysiwyg quarantine">
									<?php the_content(); ?>
								</div>
							</div>

							<?php
							$section_meta = get_post_meta( get_the_ID(), 'home-content-section', true );

							if ( ! empty( $section_meta ) ) :
								foreach ( $section_meta as $section_key => $section_fields ) :
									?>
									<div class="feature"> <!-- TEXT -->
										<div class="feature-hd">
											<div class="hdg hdg_3"><?php echo esc_html( $section_fields['title'] ); ?></div>
										</div>
										<div class="feature-bd wysiwyg quarantine">
											<?php echo apply_filters( 'the_content', wp_kses_post( $section_fields['content'] ) ); ?>
										</div>
									</div>
									<?php
								endforeach;
							endif;
							?>

						</div><!-- end container -->
					</div><!-- end contentCol -->

				</div> <!-- end theme-impact -->

			</div>
		</div>
	</div>

<?php endwhile; // End of the loop. ?>

<?php get_footer();
