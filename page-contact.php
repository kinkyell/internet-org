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

?>

<?php while ( have_posts() ) : the_post(); ?>

	<div class="viewWindow isShifted js-viewWindow js-stateDefault" id="main-content" role="main" data-route="<?php the_permalink(); ?>" data-type="titled" data-title="<?php the_title(); ?>" data-desc="<?php echo esc_attr( internetorg_get_the_subtitle( get_the_ID() ) ); ?>">

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

					<?php
					$custom_fields = get_post_meta( get_the_ID(), 'home-content-section', false );
					if ( ! empty( $custom_fields ) ) :
						foreach ( $custom_fields as $group ) : ?>
							<div class="contentCol contentCol_divided">
								<div class="container">
									<?php if ( ! empty( $group ) ) :
										foreach ( $group as $fieldset ) : ?>

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

													<?php foreach ( $fieldset['call-to-action'] as $cta ) :

														$social_attr = 'false';

														$type = 'titled';

														if ( 'page' === $cta['cta_src'] && absint( $cta['link_src'] ) ) {

															$url = esc_url( get_the_permalink( $cta['link_src'] ) );

															$title = esc_attr( get_the_title( $cta['link_src'] ) );

															$desc = wp_kses_post( get_post_field( 'post_excerpt', $cta['link_src'] ) );

															$img = ( internetorg_get_media_image_url( get_post_thumbnail_id( $cta['link_src'] ), 'panel-image' ) )
																? internetorg_get_media_image_url( get_post_thumbnail_id( $cta['link_src'] ), 'panel-image' )
																: '';

															$mobile_image = esc_url( internetorg_get_mobile_featured_image( get_post_type( $cta['link_src'] ), $cta['link_src'] ) );

															if ( 'io_story' === get_post_type( $cta['link_src'] ) ) {
																$type = 'panel';
															}
														} else {
															$url          = esc_url( $cta['link'] );
															$title        = esc_attr( $cta['title'] );
															$desc         = esc_attr( strip_tags( nl2br( $cta['text'] ) ) );
															$img          = ( ! empty( $fieldset['call-to-action'][0] )
																? internetorg_get_media_image_url( $fieldset['call-to-action'][0]['image'], 'panel-image' )
																: '' );
															$mobile_image = esc_url( ( ! empty( $fieldset['call-to-action'][0] )
																? internetorg_get_media_image_url( $fieldset['call-to-action'][0]['image'], 'inline-image' )
																: '' ) );
														}

														if ( 'post' === get_post_type( $cta['link_src'] ) ) {
															$social_attr = 'true';
														}

														$theme = ( ! empty( $fieldset['theme'] ) )
															? $fieldset['theme']
															: $fieldset['slug'];

														?>
														<div class="feaure-cta">
															<?php if ( ! empty( $url ) ) : ?>
																<a href="<?php echo esc_url( $url ); ?>"
																   class="link js-stateLink"
																   data-type="<?php esc_attr( $type ); ?>"
																   data-social="<?php echo esc_attr( $social_attr ); ?>"
                                                                   data-theme="<?php echo esc_attr( strtolower( $theme ) ); ?>"
                                                                   data-title="<?php echo esc_attr( $title ); ?>"
                                                                   data-desc="<?php echo esc_attr( $desc ); ?>"
																	<?php if ( is_string( $mobile_image ) ): ?>
																		data-mobile-image="<?php echo esc_url( $mobile_image ); ?>"
																	<?php endif; ?>
																	<?php if ( is_string( $img ) ): ?>
																		data-image="<?php echo esc_url( $img ); ?>"
																	<?php endif; ?>>
																	<?php echo esc_html__( 'Learn More', 'internetorg' ); ?>
																</a>
															<?php endif; ?>
														</div>

													<?php endforeach; ?>

												<?php endif; ?>

											</div>

											<?php

										endforeach;

									endif;

									?>
								</div>
							</div>

							<?php
						endforeach;
					endif; ?>

					<div class="container">
						<div class="contentCol">
							<div class="vendorForm">
								<?php the_content(); ?>
							</div>

						</div>
						<!-- /.contentCol -->
						<div class="contentCol contentCol_flushTight">
							<div class="container">
								<?php internetorg_vip_powered_wpcom(); ?>
							</div>
						</div>
					</div>


					<div class="socialBlock">
						<div class="socialBlock-inner">
							<div class="container">
								<div class="fbFollowBlock">
									<div class="fbFollowBlock-inner">
										<div class="fbFollowBlock-hd">
											<h2 class="hdg hdg_3 mix-hdg_blackThenWhite"><?php echo esc_html__( 'Follow the Project', 'internetorg' ); ?></h2>
										</div>
										<div class="fbFollowBlock-bd">
											<p class="bdcpy mix-bdcpy_blackThenWhite"><?php echo esc_html__( 'Stay updated about Internet.org and lorem ipsum dolor sit amet.', 'internetorg' ); ?></p>
										</div>
									</div>
									<div class="fbFollowBlock-cta">
										<a href="<?php echo esc_attr__( 'https://fb.me/Internetdotorg', 'internetorg' ); ?>" class="btn btn_facebook" target="_blank"><?php echo esc_html__( 'Like us on Facebook', 'internetorg' ); ?></a>
									</div>
								</div>
							</div>
						</div>
					</div>

				</div>
			</div>
		</div>

	</div>

<?php endwhile; ?>

<?php get_footer();
