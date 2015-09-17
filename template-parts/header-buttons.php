<?php
/**
 * Header Buttons template part.
 *
 * @package Internet.org
 */

?>

<button type="button" class="header-backBtn js-headerView-backBtn js-stateBack" aria-hidden="true">
	<span class="u-isVisuallyHidden">Back</span>
	<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" preserveAspectRatio="xMidYMid" width="19.938" height="31.938" viewBox="0 0 19.938 31.938">
		<path d="M19.951,3.182 L6.640,15.969 L19.951,28.756 L16.623,31.953 L-0.015,15.969 L3.313,12.772 L3.313,12.772 L16.623,-0.015 L19.951,3.182 Z" />
	</svg>
</button>

<button type="button" class="header-menuBtn js-headerView-menuBtn u-disableTransitions">
    <span class="menuTrigger">
        <span class="menuTrigger-label js-headerView-menuBtn-text"><?php esc_html_e( 'Menu', 'internetorg' ); ?></span>
        <span class="menuTrigger-icon js-headerView-menuBtn-icon"></span>
    </span>
</button>
