<?php
/**
 * The template for displaying all single posts.
 *
 * @package Internet.org
 */

get_header();

?>

	<div class="viewWindow isShifted js-viewWindow js-stateDefault" data-type="titled">
		<?php while ( have_posts() ) : the_post(); ?>

			<?php get_template_part( 'template-parts/content', 'page-temp-panel' ); ?>
			<?php get_template_part( 'template-parts/content', 'feature-panel' ); ?>


			<div id="storyPanel" class="viewWindow-panel viewWindow-panel_story isActive">
				<div class="viewWindow-panel-content">
					<div class="viewWindow-panel-content-inner">
						<div>

							<?php if(internetorg_get_post_thumbnail( get_the_ID(), 'panel-image' )){ ?>
								<!-- START MOBILE ONLY CONTENT HERE -->
								<div class="isHidden u-isHiddenMedium" aria-hidden="true">
									<img src="<?php echo esc_url( internetorg_get_post_thumbnail( get_the_ID(), 'panel-image' ) ); ?>" alt="" />
								</div>
								<!-- END MOBILE ONLY CONTENT HERE -->
							<?php } ?>

							<div class="introBlock introBlock_flushMobi">
								<div class="introBlock-inner">
									<div class="container">
										<div class="topicBlock">
											<div class="topicBlock-hd topicBlock-hd_plus topicBlock-hd_themeApproach">
												<h2 class="hdg hdg_2 mix-hdg_bold"><?php the_title(); ?></h2>
											</div>
											<div class="topicBlock-bd">
												<p class="bdcpy">
													<?php echo wp_kses_post( internetorg_get_the_subtitle( get_the_ID() ) ); ?>
												</p>
											</div>
										</div>
									</div>
								</div>
							</div>

							<div class="contentCol">
								<div class="container">
									<div class="feature">
										<div class="feature-bd wysiwyg quarantine">
											<?php the_content(); ?>
										</div>
									</div>
								</div>
							</div>
							<?php
							$get_free_services = internetorg_get_free_services();

							/**
							 * An array of io_ctntwdgt Posts and associated meta, else null.
							 *
							 * @var null|WP_Post $get_involved_content_widget_post
							 */
							$free_service_posts  = null;

							if ( ! empty( $get_free_services ) ) {
								$free_service_posts = $get_free_services;
								?>
								<div class="contentCol">
									<div class="container">
										<div class="feature">
											<div class="feature-hd">
												<h2 class="hdg hdg_3">
													<?php esc_html_e('Full List of Free Services', 'internetorg');?>
												</h2>
											</div>
											<div class="feature-bd">
												<ul class="servicesList">
													<?php foreach($free_service_posts as $service){ ?>
														<li>
															<div class="servicesList-item">
																<?php $img = internetorg_get_post_thumbnail($service->ID, 'full' );
																if($img){ ?>
																	<div class="servicesList-item-icon">
																		<img src="<?php echo esc_url( internetorg_get_post_thumbnail( $service->ID, 'full' ) ); ?>" alt="<?php // echo esc_html( ! empty( $service ) ? $service->post_title : '' ); ?>" />
																	</div>
																<?php } ?>
																<div class="servicesList-item-bd">
																	<div class="hdg hdg_5">
																		<?php // echo esc_html( ! empty( $service ) ? $service->post_title : '' ); ?>
																	</div>
																	<div class="bdcpy bdcpy_sm">
																		<?php // echo esc_html( ! empty( $service ) ? $service->post_excerpt : '' ); ?>
																	</div>
																</div>

															</div>
														</li>
													<?php } ?>
												</ul>
											</div>
										</div>
									</div>
								</div> <!-- end contentCol -->

							<?php
								$get_involved_content_widget = internetorg_get_content_widget_by_slug( 'get-involved' );

								/**
								 * An array of io_ctntwdgt Posts and associated meta, else null.
								 *
								 * @var null|WP_Post $get_involved_content_widget_post
								 */
								$get_involved_content_widget_post  = null;
								$get_involved_content_widget_ctas = null;

								if ( ! empty( $get_involved_content_widget ) ) {
									$get_involved_content_widget_post = $get_involved_content_widget['post'];
									if ( ! empty( $get_involved_content_widget['meta'] ) && ! empty( $get_involved_content_widget['meta']['widget-data'] ) ) :
										$get_involved_content_widget_ctas = $get_involved_content_widget['meta']['widget-data'];
									endif;  ?>

									<div class="footBox">
										<div class="container">
											<div class="topicBlock">
												<div class="topicBlock-hd">
													<h2 class="hdg hdg_3">
														<?php echo esc_html( ! empty( $get_involved_content_widget_post ) ? $get_involved_content_widget_post->post_title : '' ); ?>
													</h2>
												</div>
												<div class="topicBlock-bd">
													<?php if(!empty($get_involved_content_widget_ctas)){ ?>
														<ul class="vList">
															<?php foreach($get_involved_content_widget_ctas as $ctas){ ?>
																<li>
																	<a href="<?php esc_attr_e($ctas['url']);?>" class="link link_twoArrows">
																		<?php esc_html_e($ctas['label']);?>
																	</a>
																</li>
															<?php }?>
														</ul>
													<?php } ?>
												</div>
											</div>
										</div>
									</div>
								<?php }?>
						</div>
					</div>
				</div>
			</div>
		<?php endwhile; ?>
	</div>

<?php get_footer();
