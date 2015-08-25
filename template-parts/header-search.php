<?php
/**
 * Created by PhpStorm.
 * User: raber
 * Date: 8/14/15
 * Time: 4:32 PM
 */
?>

<form class="searchBox js-searchView js-searchFormView" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label for="mainMenu-search" class="searchBox-icon js-searchView-trigger">
		<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" preserveAspectRatio="xMidYMid" width="40" height="40" viewBox="0 0 40 40">
			<path d="M39.724,35.318 L29.676,25.269 C31.311,22.737 32.449,19.451 32.449,16.219 C32.449,7.268 25.165,-0.011 16.217,-0.011 C7.266,-0.011 -0.012,7.268 -0.012,16.219 C-0.012,25.169 7.266,32.450 16.217,32.450 C19.449,32.450 22.700,31.315 25.234,29.679 L35.281,39.727 C35.615,40.062 36.166,40.062 36.498,39.727 L39.724,36.535 C40.061,36.201 40.061,35.654 39.724,35.318 ZM3.573,16.010 C3.573,9.155 9.152,3.574 16.009,3.574 C22.868,3.574 28.444,9.155 28.444,16.010 C28.444,22.869 22.868,28.447 16.009,28.447 C9.152,28.447 3.573,22.869 3.573,16.010 Z" />
		</svg>
		<span class="u-isVisuallyHidden"><?php esc_html_x( 'Search', 'label', 'internetorg' ); ?></span>
	</label>
	<input type="search" id="mainMenu-search" class="searchBox-input js-searchView-input" name="s" placeholder="<?php echo esc_attr_x( 'Search', 'placeholder', 'internetorg' ) ?>" value="<?php echo esc_attr( get_search_query() ); ?>" />
</form>
