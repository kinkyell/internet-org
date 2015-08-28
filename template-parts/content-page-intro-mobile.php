<?php
/**
 * Content Page Intro Mobile template part.
 *
 * @package Internet.org
 */

?>

<!-- Secondary (mobile) Feature Image -->
<div class="topicBlock-media isHidden u-isHiddenMedium" aria-hidden="true">
	<img src="<?php echo esc_url( internetorg_get_mobile_featured_image( get_post_type(), get_the_ID() ) ); ?>" alt="" />
</div>

<!-- Duplicate Content - Mobile Only -->
<div class="topicBlock-bd isHidden u-isHiddenMedium" aria-hidden="true">
	<div class="hdg hdg_3"><?php echo esc_html( internetorg_get_the_intro_block( get_the_ID(), 'intro_title' ) ); ?></div>
	<p class="bdcpy"><?php echo esc_html( internetorg_get_the_intro_block( get_the_ID(), 'intro_content' ) ); ?></p>
</div>
