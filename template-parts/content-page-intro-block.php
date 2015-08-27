<?php
/**
 * Created by PhpStorm.
 * User: raber
 * Date: 8/26/15
 * Time: 2:33 PM
 */
?>

<div class="introBlock">
	<div class="introBlock-inner">

		<div class="topicBlock">
			<div class="topicBlock-hd topicBlock-hd_mega topicBlock-hd_themeImpact">
				<h2 class="hdg hdg_2 mix-hdg_bold"><?php the_title(); ?></h2>
			</div>
			<div class="topicBlock-bd">
				<p class="bdcpy">
					<?php echo esc_html( internetorg_get_the_subtitle( get_the_ID() ) ); ?>
				</p>
			</div>

			<?php get_template_part( 'template-parts/content', 'page-intro-mobile' ); ?>

		</div>
	</div>
</div>