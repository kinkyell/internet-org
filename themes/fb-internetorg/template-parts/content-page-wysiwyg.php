<?php
/**
 * Content Page Wysiwyg template part.
 *
 * @package Internet.org
 */

$the_content = get_the_content();

if ( empty( $the_content ) ) {
	return;
}

?>

<div class="feature">
	<div class="feature-bd wysiwyg quarantine">
		<?php the_content(); ?>
	</div>
</div>
