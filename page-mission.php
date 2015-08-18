<?php
/**
 * The template for displaying the mission page
 *
 * Template Name: Mission
 *
 * @todo: Discuss with FED what class to apply on WYSIWYG areas
 * @todo: How to determine if this is being ajaxed in so we don't output header/footer/etc., and is that allowed on VIP?
 * @todo: How to handle fields that weren't intended for use on this template
 *
 * @package Internet.org
 */

get_header();

internetorg_language_switcher();

?>

<?php while ( have_posts() ) : the_post(); ?>

	<div class="viewWindow isShifted js-viewWindow js-stateDefault" data-route="<?php the_permalink(); ?>" data-type="panel" data-theme="Mission" data-title="<?php the_title(); ?>" data-image="<?php echo esc_url( internetorg_get_post_thumbnail( get_the_ID() ) ); ?>">

		<div class="viewWindow-panel viewWindow-panel_feature">
			<div class="viewWindow-panel-content">
				<div class="viewWindow-panel-content-inner" style="background-image: url(<?php echo esc_url( internetorg_get_post_thumbnail( get_the_ID() ) ); ?>);"></div>
			</div>
		</div>

		<div class="viewWindow-panel viewWindow-panel_story isActive">
			<div class="viewWindow-panel-content">
				<div class="introBlock">
					<div class="introBlock-inner">
						<div class="container">
							<div class="topicBlock">
								<div class="topicBlock-hd topicBlock-hd_mega topicBlock-hd_themeMission">
									<h2 class="hdg hdg_2 mix-hdg_bold">
										<?php the_title(); ?>
									</h2>
								</div>
								<div class="topicBlock-bd">
									<p class="bdcpy">
										<?php echo sanitize_text_field( internetorg_get_the_subtitle( get_the_ID() ) ); ?>
									</p>
								</div>
							</div>
						</div>
					</div>
				</div>

				<?php
				$section_meta = get_post_meta( get_the_ID(), 'home-content-section', true );

				if ( ! empty( $section_meta ) ) :
					foreach ( $section_meta as $section_key => $section_fields ) :
					?>
						<div class="feature"> <!-- TEXT -->
							<div class="feature-hd">
								<div class="hdg hdg_3"><?php echo esc_html( $section_fields['title'] ); ?></div>
							</div>
							<div class="feature-bd wysiwyg">
								<?php echo wp_kses_post( ineternetorg_the_section_content( $section_fields['content'] ) ); ?>
							</div>
						</div>
					<?php
					endforeach;
				endif;
				?>

			</div>
		</div>
	</div>

<?php endwhile; // End of the loop. ?>

<?php get_footer();
