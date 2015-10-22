<?php
/**
 * Content Page Intro Mobile template part.
 *
 * @package Internet.org
 */

/**
 * The mobile featured image URL.
 *
 * @var string $mobile_featured_image
 */
$mobile_featured_image = internetorg_get_mobile_featured_image( get_post_type(), get_the_ID() );

/**
 * The intro_title meta.
 *
 * @var string $mobile_intro_title
 */
$mobile_intro_title = internetorg_get_the_intro_block( get_the_ID(), 'intro_title' );

/**
 * The intro_content meta.
 *
 * @var string $mobile_intro_content
 */
$mobile_intro_content = internetorg_get_the_intro_block( get_the_ID(), 'intro_content' );

?>

<?php if ( ! empty( $mobile_featured_image ) ) : ?>
	<!-- Secondary (mobile) Feature Image -->
	<div class="topicBlock-media isHidden u-isHiddenMedium" aria-hidden="true">
		<img src="<?php echo esc_url( $mobile_featured_image ); ?>" alt="" />
	</div>
<?php else : ?>
	<div class="mix-topicBlock_push"></div>
<?php endif; ?>

<?php
if ( empty( $mobile_intro_title ) && empty( $mobile_intro_content ) ) {
	return;
}
?>

<!-- Duplicate Content - Mobile Only -->
<div class="topicBlock-bd isHidden u-isHiddenMedium" aria-hidden="true">
	<?php if ( ! empty( $mobile_intro_title ) ) : ?>
		<div class="hdg hdg_3"><?php echo esc_html( $mobile_intro_title ); ?></div>
	<?php endif; ?>
	<?php if ( ! empty( $mobile_intro_content ) ) : ?>
		<p class="bdcpy"><?php echo esc_html( $mobile_intro_content ); ?></p>
	<?php endif; ?>
</div>
