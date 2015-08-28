<?php
/**
 * Created by PhpStorm.
 * User: raber
 * Date: 8/26/15
 * Time: 2:42 PM
 *
 * PHPCS complains about escaping at line 27, however, we are following the suggestion from Tom Nowell at
 * @link http://tomjn.com/2015/05/07/escaping-the-unsecure/
 */

/** @var array $section_meta An array of post meta with the home-content-section meta_key */
$section_meta = get_post_meta( get_the_ID(), 'home-content-section', true );

if ( empty( $section_meta ) ) {
	return;
}

foreach ( $section_meta as $section_key => $section_fields ) :
	?>

	<div class="feature"> <!-- TEXT -->
		<div class="feature-hd">
			<div class="hdg hdg_3"><?php echo esc_html( $section_fields['title'] ); ?></div>
		</div>
		<div class="feature-bd wysiwyg quarantine">
			<?php echo apply_filters( 'the_content', wp_kses_post( $section_fields['content'] ) ); ?>
		</div>
	</div>

	<?php
endforeach;
