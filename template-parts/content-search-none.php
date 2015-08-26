<?php
/**
 * Used for when there are no results
 *
 * @package Internet.org
 * @author  arichard <arichard@nerdery.com>
 */

?>

<div class="resultsList-list-item">
	<div class="feature feature_tight">
		<div class="feature-bd">
			<p class="bdcpy"><?php esc_html_e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'internetorg' ); ?></p>
			<?php get_search_form(); ?>
		</div>
	</div>
</div>
