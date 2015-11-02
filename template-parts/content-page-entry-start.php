<?php
/**
 * The template used for displaying page content start/header in page.php.
 *
 * @package Internet.org
 * @author  arichard <arichard@nerdery.com>
 */

$after_title_custom_fields = get_post_meta( get_the_ID(), 'after_title_fm_fields', true );

?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<div class="narrative-section">
		<div class="narrative-section-bd">
			<?php the_title( '<h2 class="entry-title">', '</h2>' ); ?>
			<?php if ( ! empty( $after_title_custom_fields['Subtitle'] ) ) : ?>
				<?php echo esc_html( $after_title_custom_fields['Subtitle'] ); ?>
			<?php endif; ?>
		</div>
	</div>
