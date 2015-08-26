<?php
/**
 * Created by PhpStorm.
 * User: raber
 * Date: 8/26/15
 * Time: 3:15 PM
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
