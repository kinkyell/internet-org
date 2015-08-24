<?php
/**
 * Custom template for the contact page
 *
 * @package Internet.org
 * @author arichard <arichard@nerdery.com>
 */

$after_title_custom_fields = get_post_meta( get_the_ID(), 'after_title_fm_fields', true );
$subtitle = '';
if ( ! empty( $after_title_custom_fields['Subtitle'] ) ) {
	$subtitle = $after_title_custom_fields['Subtitle'];
}

get_header();

?>
<div class="viewWindow isShifted js-viewWindow js-stateDefault" data-route="/contact" data-type="titled" data-title="Contact" data-desc="If you&CloseCurlyQuote;re working on a project related to connectivity or just want to keep up with Internet.org, let us know.">

<?php while ( have_posts() ) : the_post(); ?>
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
								<div class="topicBlock-subHd">
									<div class="hdg hdg_5 mix-hdg_italic mix-hdg_gray"><?php internetorg_get_post_publish_time_string(); ?></div>
								</div>
								<div class="topicBlock-bd">
									<p class="bdcpy">Will need to replace this layout with the home page narrative one.</p>
								</div>
								<div class="topicBlock-cta"><a class="btn js-stateLink" href="/approach" data-type="panel" data-title="Our Approach" data-image="http://placehold.it/400x800?text=APPROACH" data-theme="Approach">Our Approach</a></div>
							</div>
						</div>
					</div>
					<div class="introBlock-ft introBlock-ft_rule">
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
								<?php if ( ! empty( $subtitle ) ) : ?>
								<div class="topicBlock-bd">
									<p class="bdcpy"><?php echo esc_html( $subtitle ); ?></p>
								</div>
								<?php endif; ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div id="storyPanel" class="viewWindow-panel viewWindow-panel_story isActive">
		<div class="viewWindow-panel-content">

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
							<div class="hdg hdg_4"><?php echo esc_html( $fieldset['title'] ); ?></div>
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
							<?php foreach ( $fieldset['call-to-action'] as $cta ) : ?>
								<div class="feaure-cta">
								<?php if ( ! empty( $cta['link'] ) ) : ?>
									<a href="<?php echo esc_attr( $cta['link'] ); ?>" class="link"><?php echo esc_html__( 'Learn More', 'internetorg' ); ?></a>
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
					<div class="vr vr_x1">
						<div class="hdg hdg_3"><?php echo esc_html__( 'Get in Touch', 'internetorg' ); ?></div>
					</div>

					<div class="vendorForm">
					<?php the_content(); ?>
					</div>

				</div><!-- /.contentCol -->
			</div>

			<div class="socialBlock">
				<div class="socialBlock-inner">
					<div class="container">
						<div class="fbFollowBlock">
							<div class="fbFollowBlock-bd">
								<h2 class="hdg hdg_3 mix-hdg_white"><?php echo esc_html__( 'Follow the Project', 'internetorg' ); ?></h2>
								<p class="bdcpy mix-bdcpy_light"><?php echo esc_html__( 'Stay updated about Internet.org and lorem ipsum dolor sit amet.', 'internetorg' ); ?></p>
							</div>
							<div class="fbFollowBlock-cta">
								<a href="<?php echo esc_attr__( 'https://fb.me/Internetdotorg', 'internetorg' ); ?>" class="btn btn_facebook"><?php echo esc_html__( 'Like us on Facebook', 'internetorg' ); ?></a>
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
