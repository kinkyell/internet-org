<?php
/**
 * This displays a single press article
 *
 * @package
 * @author arichard <arichard@nerdery.com>
 */

get_header();

?>

<div class="viewWindow isShifted js-stateDefault">
	<?php while ( have_posts() ) : the_post(); ?>

		<?php

		/**
		 * @todo replace this panel when real content is available
		 *
		 * @note this is just a dummy panel used for positioning
		 *
		 */

		?>

	<div id="homePanel" class="viewWindow-panel">
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

	<div id="featurePanel" class="viewWindow-panel viewWindow-panel_feature">
		<div class="viewWindow-panel-content">
			<div class="viewWindow-panel-content-inner">
				<div class="introBlock introBlock_fill">
					<div class="introBlock-inner">
						<div class="container">
							<div class="topicBlock">

								<div class="topicBlock-hd topicBlock-hd_plus">
									<h2 class="hdg hdg_2 mix-hdg_bold"><?php the_title(); ?></h2>
								</div>
								<div class="topicBlock-subHd">
									<div class="hdg hdg_6 mix-hdg_italic mix-hdg_gray"><?php internetorg_posted_on_date(); ?></div>
								</div>
								<div class="topicBlock-bd">
									<p class="bdcpy">
										<?php echo wp_kses_post( internetorg_get_the_subtitle( get_the_ID() ) ); ?>
									</p>
								</div>

							</div>
						</div>
					</div>
					<div class="introBlock-ft introBlock-ft_rule">
						<?php
						/**
						 * @todo replace with correct social media links/configs
						 */ ?>
						<ul class="socialParade">
							<li><a class="socialParade-icon socialParade-icon_fb" href=""><?php esc_html_e( 'Facebook', 'internetorg' ); ?></a></li>
							<li><a class="socialParade-icon socialParade-icon_tw" href=""><?php esc_html_e( 'Twitter', 'internetorg' ); ?></a></li>
							<li><a class="socialParade-icon socialParade-icon_li" href=""><?php esc_html_e( 'LinkedIn', 'internetorg' ); ?></a></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div id="storyPanel" class="viewWindow-panel viewWindow-panel_story isActive">
		<div class="viewWindow-panel-content">
			<div class="viewWindow-panel-content-inner">
				<div>

					<div class="contentCol">
						<div class="container">
							<div class="feature">
								<div class="feature-bd wysiwyg quarantine">
									<?php the_content(); ?>
								</div>
							</div>
						</div>
					</div>


				</div>
			</div>
		</div>
	</div>
	<?php endwhile; ?>
</div>

<?php

// get_sidebar();
get_footer();
