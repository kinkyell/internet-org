<?php
/**
 * The template for displaying all single posts.
 *
 * @package Internet.org
 */

get_header();

?>

	<div class="viewWindow isShifted js-viewWindow js-stateDefault" id="main-content" role="main" data-type="titled" data-route="<?php the_permalink(); ?>" data-title="<?php the_title(); ?>" data-social="true" data-date="<?php internetorg_posted_on_date(); ?>">
		<?php while ( have_posts() ) : the_post(); ?>

			<?php get_template_part( 'template-parts/content', 'page-temp-panel' ); ?>

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
						<?php get_template_part( 'template-parts/content', 'press-intro-mobile' ); ?>

						<div class="contentCol">
							<div class="container">
								<div class="feature">
									<div class="feature-bd wysiwyg quarantine">
										<?php the_content(); ?>

										<div id="fb-root"></div>
									  <script>(function(d, s, id) {
									    var js, fjs = d.getElementsByTagName(s)[0];
									    if (d.getElementById(id)) return;
									    js = d.createElement(s); js.id = id;
									    js.src = "//connect.facebook.net/<?php echo esc_js( bbl_get_current_lang()->code ); ?>/all.js#xfbml=1&amp;version=v2.3";
									    fjs.parentNode.insertBefore(js, fjs);
									  }(document, 'script', 'facebook-jssdk'));</script>
									  <div class="fb-like"
									      data-layout="standard"
									      data-action="like"
									      data-share="true">
									  </div>

									</div>
								</div>
							</div>
						</div>

						<div class="footBox">
							<div class="container">
								<?php get_template_part( 'template-parts/content', 'single-more' ); ?>
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
		<?php endwhile; ?>
	</div>

<?php get_footer();
