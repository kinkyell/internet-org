<?php
/**
 * The template part for displaying results in search pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Internet.org
 */

?>

<div id="post-<?php the_ID(); ?>" class="resultsList-list-item">
	<div class="feature feature_tight">
		<div class="feature-hd">
			<?php the_title( '<h2 class="hdg hdg_3">', '</h2>' ); ?>
		</div>
		<div class="feature-bd">
			<p class="bdcpy"><?php the_excerpt(); ?></p>
		</div>
		<?php internetorg_entry_footer_archive(); ?>
	</div>
</div>
