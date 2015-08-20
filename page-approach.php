<?php
/**
 * The template for displaying the approach page
 *
 * Template Name: Approach
 *
 * @todo    : Discuss with FED what class to apply on WYSIWYG areas
 * @todo    : How to determine if this is being ajaxed in so we don't output header/footer/etc., and is that allowed on
 *          VIP?
 * @todo    : How to handle fields that weren't intended for use on this template
 *
 * @package Internet.org
 */

get_header();
url_to_postid();
?>

<?php while ( have_posts() ) : the_post(); ?>

	<div class="viewWindow isShifted js-viewWindow js-stateDefault" data-route="/approach" data-type="panel" data-theme="Approach" data-title="Our Approach" data-image="http://placehold.it/400x800?text=APPROACH">

		<div class="viewWindow-panel">
			<div class="viewWindow-panel-content">
				<div class="viewWindow-panel-content-inner">


					<div class="introBlock introBlock_fill">
						<div class="introBlock-inner">
							<div class="container">
								<div class="topicBlock">
									<div class="topicBlock-hd topicBlock-hd_plus">
										<h2 class="hdg hdg_3 mix-hdg_bold">Example Intro Block</h2>
									</div>
									<div class="topicBlock-subHd">
										<div class="hdg hdg_5 mix-hdg_italic mix-hdg_gray">May 25, 2015</div>
									</div>
									<div class="topicBlock-bd">
										<p class="bdcpy">Will need to replace this layout with the home page narrative one.</p>
									</div>
									<div class="topicBlock-cta">
										<a class="btn js-stateLink" href="/approach" data-type="panel" data-title="Our Approach" data-image="http://placehold.it/400x800?text=APPROACH" data-theme="Approach">Our Approach</a>
									</div>
								</div>
							</div>
						</div>
						<div class="introBlock-ft introBlock-ft_rule">
							<ul class="socialParade">
								<li><a class="socialParade-icon socialParade-icon_fb" href="https://fb.me/Internetdotorg">Facebook</a></li>
								<li><a class="socialParade-icon socialParade-icon_tw" href="https://twitter.com/internet_org">Twitter</a></li>
								<li><a class="socialParade-icon socialParade-icon_li" href="">LinkedIn</a></li>
							</ul>
						</div>
					</div>


				</div>
			</div>
		</div>


		<div class="viewWindow-panel viewWindow-panel_feature">
			<div class="viewWindow-panel-content">
				<div class="viewWindow-panel-content-inner" style="background-image: url(<?php echo esc_url( internetorg_get_post_thumbnail( get_the_ID() ) ); ?>);"></div>
			</div>
		</div><!-- end viewWindow-panel_feature -->


		<div class="viewWindow-panel viewWindow-panel_story isActive">
			<div class="viewWindow-panel-content">

				<div class="introBlock">
					<div class="introBlock-inner">
						<div class="container">
							<div class="topicBlock">
								<div class="topicBlock-hd topicBlock-hd_mega topicBlock-hd_themeApproach">
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


				<div class="theme-approach">


					<div class="contentCol">
						<div class="container">

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

				</div> <!-- end theme-approach -->

				<div class="introBlock js-scrollImage" data-image="http://placehold.it/400x800?text=IMPACT">
					<div class="introBlock-inner">
						<div class="topicBlock">
							<div class="topicBlock-subHd">
								<div class="hdg hdg_5 mix-hdg_italic mix-hdg_gray">Learn About</div>
							</div>
							<div class="topicBlock-hd topicBlock-hd_plus topicBlock-hd_themeImpact">
								<h2 class="hdg hdg_2 mix-hdg_bold">Our Impact</h2>
							</div>
						</div>
					</div>
					<div class="introBlock-ft">
						<a href="/impact" class="arrowCta js-stateSwap" data-title="Our Impact" data-image="http://placehold.it/400x800?text=IMPACT" data-theme="Impact"></a>
					</div>
				</div>

			</div>
		</div>
	</div>

<?php endwhile; // End of the loop. ?>

<?php get_footer();