<?php
/**
 * This is the custom template for the press listing page
 *
 * @package
 * @author arichard <arichard@nerdery.com>
 */

get_header();

?>

<div class="viewWindow isShifted js-viewWindow js-stateDefault" data-route="/press" data-type="titled" data-title="Press">
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
								<div class="topicBlock-subHd"><div class="hdg hdg_5 mix-hdg_italic mix-hdg_gray">May 25, 2015</div></div>
								<div class="topicBlock-bd">
									<p class="bdcpy">Will need to replace this layout with the home page narrative one.</p>
								</div>
								<div class="topicBlock-cta"><a class="btn js-stateLink" href="/approach" data-type="panel" data-title="Our Approach" data-image="http://placehold.it/400x800?text=APPROACH" data-theme="Approach">Our Approach</a></div>
							</div>
						</div>
					</div>
					<div class="introBlock-ft introBlock-ft_rule">
						<ul class="socialParade">
							<li><a class="socialParade-icon socialParade-icon_fb" href="">Facebook</a></li>
							<li><a class="socialParade-icon socialParade-icon_tw" href="">Twitter</a></li>
							<li><a class="socialParade-icon socialParade-icon_li" href="">LinkedIn</a></li>
						</ul>
					</div>
				</div>

			</div>
		</div>
	</div>

	<div class="viewWindow-panel viewWindow-panel_feature">
		<div class="viewWindow-panel-content">
			<div class="viewWindow-panel-content-inner">
				<div class="introBlock introBlock_fill">
					<div class="introBlock-inner">
						<div class="topicBlock">
							<div class="topicBlock-hd topicBlock-hd_plus">
								<h2 class="hdg hdg_2 mix-hdg_bold"><?php esc_html_e( 'Press', 'internetorg' ); ?></h2>
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
								$type = $is_media ? 'media' : 'feature';
								?>

									<?php if ( $is_media ) : ?>
									<div class="media media_inline">
										<div class="media-figure">
											<?php the_post_thumbnail( array( 210, 260 ), array( 'title' => get_the_title() ) ); ?>
										</div>
										<div class="media-bd">
									<?php endif; ?>





										<div class="feature feature_tight">
											<div class="feature-hd">
												<h2 class="hdg hdg_3"><?php echo esc_html( get_the_title() ); ?></h2>
											</div>
											<div class="feature-date">
												<div class="hdg hdg_6 mix-hdg_italic mix-hdg_gray"><?php internetorg_posted_on_date(); ?></div>
											</div>
											<div class="feature-bd">
												<p class="bdcpy"><?php echo esc_html( get_the_excerpt() ); ?></p>
											</div>
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
					<div class="resultsList-ft">
							<div class="resultsList-list resultsList-list_spread">
								<div class="resultsList-list-item">
									<button type="button" class="btn js-ShowMoreView" data-src="press" data-target="addl-results"><?php esc_html_e( 'Show More', 'internetorg' ); ?></button>
								</div>
									<?php
									// display a select list of archive years
									$args = array(
										'type'            => 'yearly',
										'limit'           => '',
										'format'          => 'option',
										'before'          => '',
										'after'           => '',
										'show_post_count' => 0,
										'echo'            => false,
										'order'           => 'DESC',
									);
									?>
								<div class="resultsList-list-item">
									<select class="js-select select_inline">
										<option></option>
										<?php echo wp_get_archives( $args ); ?>
									</select>
								</div>
							</div>
						</div>
				</div>
			</div>
					<?php endif; ?>

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
					</div>

				</div>
			</div>
		</div>
	</div>
</div>

<?php
// now that we have the post listing out of the way we'll display the widgets

get_footer();
