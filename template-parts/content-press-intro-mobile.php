<?php
/**
 * Content Press Intro Mobile template part.
 *
 * @package Internet.org
 */

?>

<!-- START MOBILE ONLY CONTENT HERE -->
<div class="introBlock isHidden u-isHiddenMedium" aria-hidden="true">
	<div class="introBlock-inner introBlock-inner_stack">

		<div class="topicBlock">
			<div class="topicBlock-hd topicBlock-hd_plus">
				<h2 class="hdg hdg_2 mix-hdg_bold"><?php the_title(); ?></h2>
			</div>

			<?php $featured_image = internetorg_get_mobile_featured_image( get_post_type(), get_the_ID() );
			if ( !empty( $featured_image ) ) { ?>
				<div class="topicBlock-media">
					<img src="<?php echo esc_url( internetorg_get_mobile_featured_image( get_post_type(), get_the_ID() ) ); ?>" alt="" />
				</div>
			<?php } ?>

			<div class="topicBlock-subHd">
				<div class="hdg hdg_6 mix-hdg_italic mix-hdg_gray"><?php internetorg_posted_on_date(); ?></div>
			</div>
			<div class="topicBlock-bd">
				<p class="bdcpy">
					<?php echo wp_kses_post( get_post_field( 'post_excerpt', get_the_ID() ) ); ?>
				</p>
			</div>
		</div>

	</div>

	<?php get_template_part( 'template-parts/content', 'social-links' ); ?>

</div>
<!-- END MOBILE ONLY CONTENT HERE -->
