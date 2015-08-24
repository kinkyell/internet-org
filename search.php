<?php
/**
 * The template for displaying search results pages.
 *
 * @package Internet.org
 */

get_header(); ?>

	<div class="viewWindow isShifted js-viewWindow js-stateDefault" data-route="<?php echo esc_url( get_search_link() ); ?>" data-type="search" data-title="Search">


		<?php get_template_part( 'template-parts/content', 'page-temp-panel' ); ?>


		<div class="viewWindow-panel viewWindow-panel_feature isDouble">
			<div class="viewWindow-panel-content">
				<div class="viewWindow-panel-content-inner">
					<div class="introBlock introBlock_fill">
						<div class="introBlock-inner">
							<div class="container">

								<?php get_search_form(); ?>

							</div>
						</div>
					</div>
				</div>
			</div>
		</div>


		<div class="viewWindow-panel viewWindow-panel_story isActive">
			<div class="viewWindow-panel-content">
				<div class="viewWindow-panel-content-inner">
					<div class="contentCol">
						<div class="container">
							<div class="resultsList">
								<div class="resultsList-hd">

									<div class="hdg hdg_6 mix-hdg_italic mix-hdg_gray"><?php echo esc_html( $wp_query->found_posts ); ?> Results Found</div>

								</div>
								<div class="resultsList-list">

									<?php if ( have_posts() ) : ?>
										<?php while ( have_posts() ) : ?>
											<?php the_post(); ?>

											<div class="resultsList-list-item">
												<div class="feature feature_tight">
													<div class="feature-hd">
														<h2 class="hdg hdg_4"><?php the_title(); ?></h2>
													</div>
													<div class="feature-bd">
														<p class="bdcpy"><?php the_excerpt(); ?></p>
													</div>
													<div class="feature-cta">
														<a href="<?php the_permalink(); ?>" class="link mix-link_small">
															<?php _e( 'Read More', 'internetorg' ); ?>
														</a>
													</div>
												</div>
											</div>

										<?php endwhile; ?>
									<?php endif; ?>

								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>


	</div>

<?php get_footer();
