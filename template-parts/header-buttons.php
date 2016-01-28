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

<div role="navigation">
	<button type="button" class="header-menuBtn js-headerView-menuBtn u-disableTransitions" aria-controls="mainNav" aria-label="Toggle Main Navigation">
	    <span class="menuTrigger">
	        <span id="menu-trigger-label" class="menuTrigger-label js-headerView-menuBtn-text"><span class="u-isVisuallyHidden"><?php esc_html_e( 'Toggle Navigation', 'internetorg' ); ?> </span><?php esc_html_e( 'Menu', 'internetorg' ); ?></span>
	        <span aria-describedby="menu-trigger-label" class="menuTrigger-icon js-headerView-menuBtn-icon"></span>
	    </span>
	</button>
	<?php
		$URL  = $_SERVER['REQUEST_URI'];
		$splitURL = explode("/", $URL);
		if($splitURL[2] == 'navigation'){ ?>
			<a href='' class="opera-mini-only header-menuBtn js-headerView-menuBtn u-disableTransitions go-back" aria-controls="mainNav" aria-label="Toggle Main Navigation">
			    <span class="menuTrigger">
			        <span id="menu-trigger-label" class="menuTrigger-label js-headerView-menuBtn-text"><span class="u-isVisuallyHidden"><?php esc_html_e( 'Toggle Navigation', 'internetorg' ); ?> </span><?php esc_html_e( 'Menu', 'internetorg' ); ?></span>
			        <span aria-describedby="menu-trigger-label" class="menuTrigger-icon js-headerView-menuBtn-icon"></span>
			    </span>
			</a> <?php
		} else { ?>
		<a href='/navigation' class="opera-mini-only header-menuBtn js-headerView-menuBtn u-disableTransitions" aria-controls="mainNav" aria-label="Toggle Main Navigation">
		    <span class="menuTrigger">
		        <span id="menu-trigger-label" class="menuTrigger-label js-headerView-menuBtn-text"><span class="u-isVisuallyHidden"><?php esc_html_e( 'Toggle Navigation', 'internetorg' ); ?> </span><?php esc_html_e( 'Menu', 'internetorg' ); ?></span>
		        <span aria-describedby="menu-trigger-label" class="menuTrigger-icon js-headerView-menuBtn-icon"></span>
		    </span>
		</a>
<?php
		}
	?>
</div>
<script type="text/javascript">
	if (document.referrer && document.referrer != ""){
		var goBack = document.getElementsByClassName('go-back')[0];
		if(goBack){
			document.getElementsByClassName('go-back')[0].href = document.referrer;
		}
	}
</script>