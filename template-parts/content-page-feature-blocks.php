<?php
/**
 * Content page feature blocks template part.
 *
 * PHPCS complains about escaping at line 27, however, we are following the suggestion from Tom Nowell at...
 *
 * @link    http://tomjn.com/2015/05/07/escaping-the-unsecure/
 *
 * @package Internet.org
 */

/**
 * An array of post meta with the home-content-section meta_key.
 *
 * @var array $section_meta
 */
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
			<?php echo wp_kses_post( apply_filters( 'the_content',  $section_fields['content'] ) ); ?>
		</div>
	</div>

	<?php
endforeach;
