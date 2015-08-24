<?php
/**
 * The template part for displaying results in search pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Internet.org
 */

?>

<div class="resultsList-list-item">
	<div class="feature feature_tight">
		<div class="feature-hd">
			<h2 class="hdg hdg_4"><?php the_title(); ?></h2>
		</div>
		<div class="feature-bd">
			<p class="bdcpy"><?php the_excerpt(); ?></p>
		</div>
		<div class="feature-cta">
			<a href="<?php the_permalink(); ?>" class="link mix-link_small">
				<?php esc_html_e( 'Read More', 'internetorg' ); ?>
			</a>
		</div>
	</div>
</div>
