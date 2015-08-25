<?php
/**
 * Used for when there are no results
 *
 * @package Internet.org
 * @author arichard <arichard@nerdery.com>
 */

?>

<div class="resultsList-hd">
	<div class="hdg hdg_5 mix-hdg_italic mix-hdg_gray"><?php esc_html__( 'No Results Found', 'internetorg' ); ?></div>
</div>
<div class="resultsList-list">
	<div class="resultsList-list-item">
		<div class="feature feature_tight">
			<div class="feature-bd">
				<p class="bdcpy"><?php esc_html_e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'internetorg' ); ?></p>
				<?php /* get_search_form(); */ ?>
			</div>
		</div>
	</div>
</div>
