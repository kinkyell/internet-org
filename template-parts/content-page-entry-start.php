<?php
/**
 * The template used for displaying page content start/header in page.php
 *
 * @package Internet.org
 * @author arichard <arichard@nerdery.com>
 */

$after_title_custom_fields = get_post_meta( get_the_ID(), 'after_title_fm_fields', true );

?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
		<?php if ( ! empty( $after_title_custom_fields['Subtitle'] ) ) : ?>
		<?php echo '<h2>' . esc_html__( $after_title_custom_fields['Subtitle'] , 'internet_org' ) . '</h2>'; ?>
		<?php endif; ?>
	</header><!-- .entry-header -->
