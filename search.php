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

								<?php if ( have_posts() ) : ?>
									<div class="resultsList-list">
										<?php while ( have_posts() ) : ?>
											<?php the_post(); ?>
											<?php get_template_part( 'template-parts/content', 'search' ); ?>
										<?php endwhile; ?>
									</div>
								<?php endif; ?>

							</div>
						</div>
					</div>
				</div>
			</div>
		</div>


	</div>

<?php get_footer();
