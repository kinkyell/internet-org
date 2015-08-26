<?php
/**
 * Created by PhpStorm.
 * User: raber
 * Date: 8/26/15
 * Time: 2:37 PM
 */

$intro_block = internetorg_get_the_intro_block();

if ( empty( $intro_block ) ) {
	return;
}

?>

<!-- START ADD DESKTOP ONLY CONTENT HERE -->
<!-- Duplicate Content - DESKTOP Only -->
<div class="feature isVissuallyHidden u-isVisuallyHiddenSmall">
	<div class="feature-hd">
		<div class="hdg hdg_3"><?php echo esc_html( $intro_block['intro_title'] ); ?></div>
	</div>
	<div class="feature-bd">
		<p class="bdcpy bdcpy_sm">
			<?php echo esc_html( $intro_block['intro_content'] ); ?>
		</p>
	</div>
</div>
<!-- END Duplicate Content - DESKTOP Only -->
<!-- END ADD DESKTOP ONLY CONTENT HERE -->
