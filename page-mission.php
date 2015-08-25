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

<?php while ( have_posts() ) : the_post(); ?>

	<div class="viewWindow isShifted js-viewWindow js-stateDefault" data-route="<?php the_permalink(); ?>" data-type="panel" data-theme="Mission" data-title="<?php the_title(); ?>" data-image="<?php echo esc_url( internetorg_get_post_thumbnail( get_the_ID() ) ); ?>">


		<?php get_template_part( 'template-parts/content', 'page-temp-panel' ); ?>


		<?php get_template_part( 'template-parts/content', 'feature-panel' ); ?>


		<div id="storyPanel" class="viewWindow-panel viewWindow-panel_story isActive">

			<div class="viewWindow-panel-content">

				<div class="viewWindow-panel-content-inner">

					<div class="introBlock">
						<div class="introBlock-inner">
							<div class="container">

								<!-- START ADD MOBILE ONLY CONTENT HERE -->

								<!-- Secondary (mobile) Feature Image -->
								<div class="topicBlock-media isHidden u-isHiddenMedium">
									<img src="../assets/media/images/mainstory-img-8.jpg" alt="" />
								</div>

								<!-- Duplicate Content - Mobile Only -->
								<div class="topicBlock-bd isHidden u-isHiddenMedium">
									<div class="hdg hdg_3">Internet Access Changes Lives</div>
									<p class="bdcpy">Paola lives with her husband and young son on an organic farm several hours from Bogota. They live sustainably off the land with very little income and are largely cut off from the outside world.</p>
								</div>

								<!-- END ADD MOBILE ONLY CONTENT HERE -->

							</div>
						</div>
					</div><!-- .introBlock -->


					<div class="theme-mission">


						<div class="contentCol">
							<div class="container">


								<!-- START ADD DESKTOP ONLY CONTENT HERE -->
								<!-- Duplicate Content - DESKTOP Only -->
								<div class="feature isVissuallyHidden u-isVisuallyHiddenSmall">
									<div class="feature-hd">
										<div class="hdg hdg_3">Internet Access Changes Lives</div>
									</div>
									<div class="feature-bd">
										<p class="bdcpy bdcpy_sm">
											Around the world, people are taking the initiative and using Internet.org to improve their lives — they’re doing better in school, building businesses and providing for their families.
										</p>
									</div>
								</div>
								<!-- END Duplicate Content - DESKTOP Only -->
								<!-- END ADD DESKTOP ONLY CONTENT HERE -->


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

						<?php get_template_part( 'template-parts/content', 'free-services' ); ?>

					</div> <!-- end theme-mission -->


					<div class="introBlock js-scrollImage" data-image="http://placehold.it/400x800?text=APPROACH">
						<div class="introBlock-inner">
							<div class="topicBlock">
								<div class="topicBlock-subHd">
									<div class="hdg hdg_5 mix-hdg_italic mix-hdg_gray">Learn About</div>
								</div>
								<div class="topicBlock-hd topicBlock-hd_plus topicBlock-hd_themeApproach">
									<h2 class="hdg hdg_2 hdg-mix_bold">Our Approach</h2>
								</div>
							</div>
						</div>
						<div class="introBlock-ft">
							<a href="/approach" class="arrowCta js-stateSwap" data-title="Our Approach" data-image="http://placehold.it/400x800?text=APPROACH" data-theme="Approach"></a>
						</div>
					</div> <!-- end introBlock -->


				</div><!-- end viewWindow-panel-content-inner -->
			</div><!-- end viewWindow-panel-content -->
		</div><!-- end isActive -->
	</div><!-- end js-viewWindow -->

<?php endwhile; // End of the loop. ?>

<?php get_footer();
