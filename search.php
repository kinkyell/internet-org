<?php
/**
 * The template for displaying search results pages.
 *
 * VIP Scanner complains about escaping at line 45, false positive (note the use of esc_html__), PHPCS does not.
 *
 * @package Internet.org
 */

global $wp_query;

/**
 * Determine if we're looking for all posts
 */
$search = get_search_query();
if ( $search == 'all' ) {
	$args = array(
		'post_type' => 'post',
		'post_per_page' => '-1',
		'order' => 'desc'
	);
	$wp_query = new WP_Query( $args );
}

get_header(); ?>

	<div class="viewWindow isShifted js-viewWindow js-stateDefault" id="main-content" role="main" data-route="<?php echo esc_url( get_search_link() ); ?>" data-type="search" data-title="Search" data-search-text="<?php echo esc_attr( get_search_query() ); ?>">


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


					<div class="u-isHiddenMedium">
						<?php get_template_part( 'template-parts/content', 'search-mobile' ); ?>
					</div>


					<div class="contentCol">
						<div class="container">

							<div class="resultsList">
								<div class="resultsList-hd">

									<div class="hdg hdg_6 mix-hdg_italic mix-hdg_gray">
										<?php printf( '%d Results Found', esc_html( $wp_query->found_posts ) ,'internetorg' ); ?>
									</div>

								</div>

								<div class="resultsList-list js-searchState-results" id="search-results">
									<?php if ( have_posts() ) : ?>
										<?php while ( have_posts() ) : ?>
											<?php the_post(); ?>
											<?php get_template_part( 'template-parts/content', 'search' ); ?>
										<?php endwhile; ?>
									<?php else : ?>
										<?php get_template_part( 'template-parts/content', 'search-none' ); ?>
									<?php endif; ?>
								</div>

							</div>

							<?php
							$next_posts_link = get_next_posts_link();
							if ( ! empty( $next_posts_link ) ) {
								?>
								<div class="show-more resultsList-ft js-searchState-ft">
									<div class="resultsList-list resultsList-list_spread">
										<div class="resultsList-list-item">
											<button type="button" class="btn js-ShowMoreView" data-src="search" data-target="search-results" data-args="<?php the_search_query(); ?>">
												<?php esc_html_e( 'Show More', 'internetorg' ); ?>
											</button>
										</div>
									</div>
								</div>
							<?php } ?>

							<div class="resultsList-ft opera-mini-only">
								<div class="resultsList-list resultsList-list_spread">
									<div class="resultsList-list-item">
										<a href="/search/all" type="button" class="btn js-ShowMoreView" data-src="press" data-target="addl-results">
											<?php esc_html_e( 'Show More', 'internetorg' ); ?>
										</a>
									</div>
								</div>
							</div>

						</div>

						<div class="contentCol contentCol_tight">
							<div class="container">
								<?php internetorg_vip_powered_wpcom('pwdByVip-txt pwdByVip-txt_search'); ?>
							</div>
						</div>

					</div>
				</div>
			</div>
		</div>


	</div>

<?php get_footer();
