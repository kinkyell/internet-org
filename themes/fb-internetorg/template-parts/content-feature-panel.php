<?php
/**
 * Content Feature Panel template part.
 *
 * @package Internet.org
 */

$id = get_the_ID();

?>

<div id="featurePanel" class="viewWindow-panel viewWindow-panel_feature">
	<div class="viewWindow-panel-content">
		<?php if ( empty( $id ) ) : ?>
			<div class="viewWindow-panel-content-inner" style="background-image: url(<?php echo esc_url( get_stylesheet_directory_uri() . '/_static/web/assets/media/uploads/home.jpg' ); ?>);"></div>
		<?php else : ?>
			<div class="viewWindow-panel-content-inner" style="background-image: url(<?php echo esc_url( internetorg_get_post_thumbnail( get_the_ID(), 'panel-image' ) ); ?>);"></div>
		<?php endif; ?>
	</div>
</div><!-- end viewWindow-panel_feature -->
