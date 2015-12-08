<?php
/**
 * Custom template for the contact page
 *
 * Template Name: Contact
 *
 * @package Internet.org
 * @author  arichard <arichard@nerdery.com>
 */

get_header();

/**
 * An array of custom meta associated with this post via Fieldmanager.
 *
 * @var array $custom_fields
 */
$custom_fields = get_post_meta( get_the_ID(), 'home-content-section', false );

?>

<?php while ( have_posts() ) : the_post(); ?>

	<div class="viewWindow isShifted js-viewWindow js-stateDefault" id="main-content" role="main" data-route="<?php echo esc_url( internetorg_fix_link( get_the_permalink() ) ); ?>" data-type="titled" data-title="<?php the_title(); ?>" >

		<?php get_template_part( 'template-parts/content', 'page-temp-panel' ); ?>

		<div id="featurePanel" class="viewWindow-panel viewWindow-panel_feature isDouble">
			<div class="viewWindow-panel-content">
				<div class="viewWindow-panel-content-inner">
					<div class="introBlock introBlock_fill">
						<div class="introBlock-inner">
							<div class="container">
								<div class="topicBlock">
									<div class="topicBlock-hd topicBlock-hd_plus">
										<h2 class="hdg hdg_2"><?php the_title(); ?></h2>
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
				</div>
			</div>
		</div>

		<div id="storyPanel" class="viewWindow-panel viewWindow-panel_story isActive">
			<div class="viewWindow-panel-content">
				<div class="viewWindow-panel-content-inner">

					<div class="introBlock u-isHiddenMedium" aria-hidden="true">
						<div class="introBlock-inner">
							<div class="container">
								<div class="topicBlock">
									<div class="topicBlock-hd topicBlock-hd_plus">
										<h2 class="hdg hdg_2"><?php the_title(); ?></h2>
									</div>
									<div class="topicBlock-bd">
										<p class="bdcpy"><?php echo esc_html( internetorg_get_the_subtitle( get_the_ID() ) ); ?></p>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="container">
						<div class="contentCol">
							<div class="vendorForm">

								<?php if ( ! empty( $custom_fields ) ) : ?>

									<?php foreach ( $custom_fields as $group ) : ?>

										<?php if ( ! empty( $group ) ) : ?>

<!-- 											<div class="contentCol_divided contentCol_flush">
												<div class="container">
 -->
													<?php foreach ( $group as $fieldset ) : ?>

														<?php if ( ! empty( $fieldset ) ) : ?>

															<div class="feature">
																<?php if ( ! empty( $fieldset['title'] ) ) : ?>
																	<div class="feature-hd">
																		<div class="hdg hdg_3"><?php echo esc_html( $fieldset['title'] ); ?></div>
																	</div>
																<?php endif; ?>

																<?php if ( ! empty( $fieldset['content'] ) ) : ?>
																	<div class="feature-bd">
																		<p class="bdcpy">
																			<?php echo esc_html( strip_tags( $fieldset['content'] ) ); ?>
																		</p>
																	</div>
																<?php endif; ?>

																<?php if ( ! empty( $fieldset['call-to-action'] ) ) : ?>

																	<?php

																	if ( ! empty( $fieldset['theme'] ) ) {
																		$theme = $fieldset['theme'];
																	} else if ( ! empty( $fieldset['slug'] ) ) {
																		$theme = $fieldset['slug'];
																	} else {
																		$theme = '';
																	}

																	if ( ! empty( $fieldset['call-to-action'][0]['image'] ) ) {
																		$fieldset_image = $fieldset['call-to-action'][0]['image'];
																	} else {
																		$fieldset_image = '';
																	}

																	internetorg_contact_call_to_action(
																		$fieldset['call-to-action'],
																		$theme,
																		$fieldset_image
																	);

																	?>

																<?php endif; ?>

															</div>

														<?php endif; ?>

													<?php endforeach; ?>

<!-- 												</div>

											</div>
 -->
										<?php endif; ?>

									<?php endforeach; ?>

								<?php endif; ?>

								<?php the_content(); ?>

							</div>

						</div>
					</div>

					<?php get_template_part( 'template-parts/content-page-contact-social-block' ); ?>
				</div>
			</div>
		</div>

	</div>

<?php endwhile; ?>

<?php get_footer();
