<?php
/**
 * The template for displaying archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Internet.org
 */

get_header();

?>
<div class="viewWindow isShifted js-viewWindow js-stateDefault" id="main-content" role="main" data-route="<?php echo esc_url( internetorg_get_archive_link() ); ?>" data-type="titled" data-title="<?php the_archive_title(); ?>">


<?php get_template_part( 'template-parts/content', 'page-temp-panel' ); ?>


	<div id="featurePanel" class="viewWindow-panel viewWindow-panel_feature">
		<div class="viewWindow-panel-content">
			<div class="viewWindow-panel-content-inner">
				<div class="introBlock introBlock_fill">
					<div class="introBlock-inner">
						<div class="topicBlock">
							<div class="topicBlock-hd topicBlock-hd_plus">
								<h2 class="hdg hdg_2 mix-hdg_bold"><?php the_archive_title(); ?></h2>
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
				<div>
					<?php if ( have_posts() ) : ?>
					<div class="contentCol">
						<div class="container">
							<div class="resultsList">
								<div id="addl-results" class="resultsList-list">
									<?php while ( have_posts() ) : the_post(); ?>
										<div class="resultsList-list-item">
											<?php
											$is_media = has_post_thumbnail();
											?>

											<?php if ( $is_media ) : ?>
											<div class="media media_inline">
												<a href="<?php the_permalink(); ?>" class="link_sm" title="<?php the_title_attribute(); ?>">
													<div class="media-figure">
														<?php the_post_thumbnail( array( 960, 960 ), array( 'title' => get_the_title() ) ); ?>
													</div>
												</a>
												<div class="media-bd">
													<?php endif; ?>


													<div class="feature feature_tight">
														<div class="feature-hd">
															<a href="<?php the_permalink(); ?>" class="link_sm" title="<?php the_title_attribute(); ?>">
																<h2 class="hdg hdg_3"><a href="<?php the_permalink(); ?>" class="link"><?php echo esc_html( get_the_title() ); ?></a></h2>
															</a>
														</div>
														<div class="feature-date">
															<div class="hdg hdg_6 mix-hdg_italic mix-hdg_gray"><?php internetorg_posted_on_date(); ?></div>
														</div>

														<?php internetorg_media_embed(); ?>

														<div class="feature-cta">
															<a href="<?php the_permalink(); ?>" class="link link_sm" title="<?php the_title_attribute(); ?>"><?php echo esc_html__( 'Read More', 'internetorg' ) ?></a>
														</div>
													</div>


													<?php if ( $is_media ) : ?>
												</div>
											</div>
										<?php endif; ?>

										</div>
									<?php endwhile; ?>
								</div>

								<?php
								$next_posts_link = get_next_posts_link();
								if ( ! empty( $next_posts_link ) ) {
									?>
									<div class="show-more resultsList-ft">
										<div class="resultsList-list resultsList-list_spread">
											<div class="resultsList-list-item">
												<button type="button" class="btn js-ShowMoreView" data-src="press" data-target="addl-results">
													<?php esc_html_e( 'Show More', 'internetorg' ); ?>
												</button>
											</div>
										</div>
									</div>
									<?php
								}
								?>

								<div class="resultsList-ft opera-mini-only">
									<div class="resultsList-list resultsList-list_spread">
										<div class="resultsList-list-item">
											<a href="/search/all" type="button" class="btn" data-src="press" data-target="addl-results">
												<?php esc_html_e( 'Show More', 'internetorg' ); ?>
											</a>
										</div>
									</div>
								</div>

							</div>
						</div>
						<?php endif; ?>
					</div>

					<div class="footBox">
						<div class="container">
							<div class="vList vList_footBox">
								<div>
									<?php internet_org_get_content_widget_html( 'contact' ); ?>
								</div>
							</div>
						</div>
						<div class="footBox-ft">
							<div class="container">
								<?php internetorg_vip_powered_wpcom( 'pwdByVip-txt' ); ?>
							</div>
						</div>
					</div>

				</div>
			</div>
		</div>
	</div>

<?php

get_footer();
