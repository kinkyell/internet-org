<?php
/**
 * This is the blog home template.
 *
 * @package Internet.org
 * @author  arichard <arichard@nerdery.com>
 */

get_header();

$next_posts_link = get_next_posts_link();

$archives_years = internetorg_get_archives_years();

?>

<div class="viewWindow isShifted js-viewWindow js-stateDefault" id="main-content" role="main" data-route="<?php echo esc_url( fix_link( internetorg_get_the_permalink_in_lang( get_option( 'page_for_posts' ), internetorg_get_current_content_lang_code() ) ) ); ?>" data-type="titled" data-title="<?php echo esc_html( internetorg_get_the_title_in_lang( get_option( 'page_for_posts' ), internetorg_get_current_content_lang_code() ) ); ?>">

		<?php get_template_part( 'template-parts/content', 'page-temp-panel' ); ?>

		<div id="featurePanel" class="viewWindow-panel viewWindow-panel_feature">
			<div class="viewWindow-panel-content">
				<div class="viewWindow-panel-content-inner">

					<div class="introBlock introBlock_fill">
						<div class="introBlock-inner">
							<div class="topicBlock">
								<div class="topicBlock-hd topicBlock-hd_plus">
									<h2 class="hdg hdg_2"><?php echo esc_html( internetorg_get_the_title_in_lang( get_option( 'page_for_posts' ), internetorg_get_current_content_lang_code() ) ); ?></h2>
								</div>
							</div>
						</div>
					</div>

				</div>
			</div>
		</div>
		<div class="viewWindow-panel viewWindow-panel_story isActive">
			<div class="viewWindow-panel-content">
				<div class="viewWindow-panel-content-inner">
					<div class="introBlock u-isHiddenMedium">
						<div class="introBlock-inner">
							<div class="container">
								<div class="topicBlock">
									<div class="topicBlock-hd topicBlock-hd_plus">
										<h2 class="hdg hdg_2 mix-hdg_bold"><?php echo esc_html( internetorg_get_the_title_in_lang( get_option( 'page_for_posts' ), internetorg_get_current_content_lang_code() ) ); ?></h2>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div>

						<div class="contentCol">
							<div class="container">
								<div class="resultsList">
									<div id="addl-results" class="resultsList-list">

										<?php if ( have_posts() ) : ?>
											<?php while ( have_posts() ) : ?>
												<?php the_post(); ?>
												<?php get_template_part( 'template-parts/content', 'press-item' ); ?>
											<?php endwhile; ?>
										<?php endif; ?>

									</div>


									<div class="resultsList-ft">
										<div class="resultsList-list resultsList-list_spread">

											<div class="resultsList-list-item">
											<?php if ( ! empty( $next_posts_link ) ) : ?>
												<div class="vr vr_x2">
													<button type="button" class="btn js-ShowMoreView" data-src="press" data-target="addl-results" data-filter="press-filter">
														<?php esc_html_e( 'Show More', 'internetorg' ); ?>
													</button>
												</div>
											<?php endif; ?>

											<?php if ( ! empty( $archives_years ) ) : ?>
													<?php internetorg_the_press_filter( $archives_years ); ?>
											<?php endif; ?>
											</div>

										</div>
									</div>


								</div>
							</div>
						</div>
						<div class="footBox">
							<div class="container">
								<div class="vList vList_footBox">
									<div>

										<?php internet_org_get_content_widget_html( 'contact' ); ?>

									</div>
									<div>

										<?php internet_org_get_content_widget_html( 'media-kit' ); ?>

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
	</div>

<?php

get_footer();
