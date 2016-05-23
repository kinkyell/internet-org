<?php
/**
 * Content Page Intro Block template part.
 *
 * @package Internet.org
 */

$subtitle = internetorg_get_the_subtitle( get_the_ID() );

?>

<div class="introBlock introBlock_media">
	<div class="introBlock-inner">

		<div class="topicBlock">
			<div class="topicBlock-hd topicBlock-hd_mega <?php echo esc_attr( 'topicBlock-hd_theme' . internetorg_get_page_theme() ); ?>">
				<h2 class="hdg hdg_2 mix-hdg_bold"><?php the_title(); ?></h2>
			</div>

			<?php if ( ! empty( $subtitle ) ) : ?>
			<div class="topicBlock-bd">
				<p class="bdcpy">
					<?php echo esc_html( internetorg_get_the_subtitle( get_the_ID() ) ); ?>
				</p>
			</div>
			<?php endif; ?>

			<?php get_template_part( 'template-parts/content', 'page-intro-mobile' ); ?>

		</div>
	</div>
</div>
