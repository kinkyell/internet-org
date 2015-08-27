<?php
/**
 * Custom template for the contact page
 *
 * Template Name: Contact
 *
 * @package Internet.org
 * @author arichard <arichard@nerdery.com>
 */

get_header();

?>

<?php while ( have_posts() ) : the_post(); ?>

<div class="viewWindow isShifted js-viewWindow js-stateDefault" data-route="<?php the_permalink(); ?>" data-type="titled" data-title="<?php the_title(); ?>" data-desc="<?php echo esc_attr( internetorg_get_the_subtitle( get_the_ID() ) ); ?>">

	<?php get_template_part( 'template-parts/content', 'page-temp-panel' ); ?>

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
								<div class="topicBlock-bd">
									<p class="bdcpy">
										<?php echo esc_html( internetorg_get_the_subtitle( get_the_ID() ) ); ?>
									</p>
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
	</div>

</div>

<?php endwhile; ?>

<?php get_footer();
