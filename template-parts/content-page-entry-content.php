<?php
/**
 * The template used for displaying page content "content" section in page.php
 *
 * @package Internet.org
 * @author arichard <arichard@nerdery.com>
 */

?>
<div class="entry-content">
	<?php the_content(); ?>
	<?php
		wp_link_pages( array(
			'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'internet_org' ),
			'after'  => '</div>',
		) );
	?>
</div><!-- .entry-content -->
