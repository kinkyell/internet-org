<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package Internet.org
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">


<?php

// #############################################################################

// MANUAL INCLUSION DURING INITIAL INTEGRATION                             START

// #############################################################################

?>

 <!--[if IE]><meta http-equiv="cleartype" content="on" /><![endif]-->

 <!-- ICONS -->
 <link rel="apple-touch-icon" sizes="57x57" href="/wp-content/themes/vip/internet_org/_static/web/assets/media/images/apple-touch-icon-57x57.png">
 <link rel="apple-touch-icon" sizes="60x60" href="/wp-content/themes/vip/internet_org/_static/web/assets/media/images/apple-touch-icon-60x60.png">
 <link rel="apple-touch-icon" sizes="72x72" href="/wp-content/themes/vip/internet_org/_static/web/assets/media/images/apple-touch-icon-72x72.png">
 <link rel="apple-touch-icon" sizes="76x76" href="/wp-content/themes/vip/internet_org/_static/web/assets/media/images/apple-touch-icon-76x76.png">
 <link rel="apple-touch-icon" sizes="114x114" href="/wp-content/themes/vip/internet_org/_static/web/assets/media/images/apple-touch-icon-114x114.png">
 <link rel="apple-touch-icon" sizes="120x120" href="/wp-content/themes/vip/internet_org/_static/web/assets/media/images/apple-touch-icon-120x120.png">
 <link rel="apple-touch-icon" sizes="144x144" href="/wp-content/themes/vip/internet_org/_static/web/assets/media/images/apple-touch-icon-144x144.png">
 <link rel="apple-touch-icon" sizes="152x152" href="/wp-content/themes/vip/internet_org/_static/web/assets/media/images/apple-touch-icon-152x152.png">
 <link rel="apple-touch-icon" sizes="180x180" href="/wp-content/themes/vip/internet_org/_static/web/assets/media/images/apple-touch-icon-180x180.png">
 <link rel="icon" type="image/png" sizes="192x192" href="/wp-content/themes/vip/internet_org/_static/web/assets/media/images/favicon-192x192.png">
 <link rel="icon" type="image/png" sizes="160x160" href="/wp-content/themes/vip/internet_org/_static/web/assets/media/images/favicon-160x160.png">
 <link rel="icon" type="image/png" sizes="96x96" href="/wp-content/themes/vip/internet_org/_static/web/assets/media/images/favicon-96x96.png">
 <link rel="icon" type="image/png" sizes="32x32" href="/wp-content/themes/vip/internet_org/_static/web/assets/media/images/favicon-32x32.png">
 <link rel="icon" type="image/png" sizes="16x16" href="/wp-content/themes/vip/internet_org/_static/web/assets/media/images/favicon-16x16.png">
 <meta name="msapplication-TileImage" content="/wp-content/themes/vip/internet_org/_static/web/assets/media/images/mstile-144x144.png">
 <meta name="msapplication-TileColor" content="#ff0000">

 <script>
     /*! grunt-grunticon Stylesheet Loader - v2.1.6 | https://github.com/filamentgroup/grunticon | (c) 2015 Scott Jehl, Filament Group, Inc. | MIT license. */

!function(){function e(e,n,t,o){"use strict";var a=window.document.createElement("link"),i=n||window.document.getElementsByTagName("script")[0],d=window.document.styleSheets;return a.rel="stylesheet",a.href=e,a.media="only x",o&&(a.onload=o),i.parentNode.insertBefore(a,i),a.onloadcssdefined=function(n){for(var t,o=0;o<d.length;o++)d[o].href&&d[o].href.indexOf(e)>-1&&(t=!0);t?n():setTimeout(function(){a.onloadcssdefined(n)})},a.onloadcssdefined(function(){a.media=t||"all"}),a}function n(e,n){e.onload=function(){e.onload=null,n&&n.call(e)},"isApplicationInstalled"in navigator&&"onloadcssdefined"in e&&e.onloadcssdefined(n)}!function(t){var o=function(a,i){"use strict";if(a&&3===a.length){var d=t.navigator,r=t.document,l=t.Image,s=!(!r.createElementNS||!r.createElementNS("http://www.w3.org/2000/svg","svg").createSVGRect||!r.implementation.hasFeature("http://www.w3.org/TR/SVG11/feature#Image","1.1")||t.opera&&-1===d.userAgent.indexOf("Chrome")||-1!==d.userAgent.indexOf("Series40")),c=new l;c.onerror=function(){o.method="png",o.href=a[2],e(a[2])},c.onload=function(){var t=1===c.width&&1===c.height,d=a[t&&s?0:t?1:2];t&&s?o.method="svg":t?o.method="datapng":o.method="png",o.href=d,n(e(d),i)},c.src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==",r.documentElement.className+=" grunticon"}};o.loadCSS=e,o.onloadCSS=n,t.grunticon=o}(this)}();

     grunticon([
         "/wp-content/themes/vip/internet_org/_static/web/assets/media/images/icons/icons.data.svg.css",
         "/wp-content/themes/vip/internet_org/_static/web/assets/media/images/icons/icons.data.png.css",
         "/wp-content/themes/vip/internet_org/_static/web/assets/media/images/icons/icons.fallback.css"
     ], grunticon.svgLoadedCallback);
 </script>
 <noscript><link href="/wp-content/themes/vip/internet_org/_static/web/assets/media/images/icons/icons.fallback.css" rel="stylesheet"></noscript>

 <!-- STYLESHEETS -->
     <link rel="stylesheet" media="screen, projection" href="/wp-content/themes/vip/internet_org/_static/web/assets/styles/screen.css" />

<?php

// #############################################################################

// MANUAL INCLUSION DURING INITIAL INTEGRATION                               END

// #############################################################################

?>


<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

	<!-- header -->
	<div class="header js-headerView" role="banner">
		<a class="header-logo js-headerView-logo js-stateHome header-logo_min mix-header-logo_center" href="/">
			<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="-358 260 242.7 42" xml:space="preserve" width="243" height="42">
				<path d="M-336.9,268.8c-0.8-0.3-1.6-0.6-2.4-0.8c-0.2,0-0.3-0.2-0.2-0.4l3.2-7.6h-2.5l-13.5,31.8
					c-1.1-1.2-1.9-2.5-2.5-4c-1.3-3.2-1.2-6.8,0.2-10c1.2-2.8,3.3-5.1,5.9-6.4l1.3-3.1c-4,1.3-7.5,4.3-9.3,8.6c-2.6,6-1,12.8,3.3,17.1
					c0.1,0.1,0.1,0.3,0,0.4l-3.2,7.6h2.5l13.5-31.9c0.9,0.2,1.8,0.5,2.7,0.8c3.1,1.4,5.5,3.9,6.8,7.2c1.3,3.2,1.2,6.8-0.2,10
					c-2,4.8-6.6,7.9-11.7,7.9c-1.1,0-2.2-0.2-3.3-0.5l-0.9,2.2c1.4,0.4,2.8,0.6,4.2,0.6c5.8,0,11.4-3.5,13.9-9.3
					C-325.7,281.3-329.3,272.2-336.9,268.8z"></path>
				<g class="internet-org">
					<path d="M-262.7,279c-2.4,0-4.6,2-4.6,5.1c0,3.3,2.3,5.2,4.8,5.2c2.3,0,3.8-1.4,4.3-3.1l-1.9-0.6
						c-0.3,1-1,1.7-2.4,1.7c-1.4,0-2.5-1.1-2.6-2.5h6.9c0,0,0.1-0.4,0.1-0.7C-258.1,281-259.8,279-262.7,279z M-265,283.1
						c0.1-1,0.9-2.1,2.3-2.1c1.6,0,2.3,1,2.3,2.1H-265z"></path>
					<path d="M-247.4,285.9c-0.9,0-1.6,0.7-1.6,1.6c0,0.9,0.7,1.6,1.6,1.6c0.9,0,1.6-0.7,1.6-1.6
						C-245.8,286.6-246.5,285.9-247.4,285.9z"></path>
					<path d="M-272.8,279.1c-1.1,0-2.2,0.5-2.8,1.6v-1.3h-2.2v9.7h2.3v-5.6c0-1.3,0.7-2.3,2-2.3c1.4,0,1.9,1,1.9,2.1v5.7
						h2.3v-6.1C-269.3,280.8-270.4,279.1-272.8,279.1z"></path>
					<path d="M-253,285.9v-4.5h2v-2h-2v-3h-2v1.5c0,0.9-0.4,1.5-1.5,1.5h-0.5v2h1.8v4.9c0,1.8,1.1,2.9,2.8,2.9
						c0.8,0,1.2-0.2,1.4-0.2V287c-0.1,0-0.5,0.1-0.8,0.1C-252.7,287.1-253,286.7-253,285.9z"></path>
					<rect x="-319.1" y="279.3" width="2.2" height="9.7"></rect>
					<path d="M-318,274.4c-0.8,0-1.5,0.7-1.5,1.5s0.7,1.5,1.5,1.5c0.8,0,1.5-0.7,1.5-1.5S-317.2,274.4-318,274.4z"></path>
					<path d="M-299.9,285.9v-4.5h2v-2h-2v-3h-2v1.5c0,0.9-0.4,1.5-1.5,1.5h-0.5v2h1.8v4.9c0,1.8,1.1,2.9,2.8,2.9
						c0.8,0,1.2-0.2,1.4-0.2V287c-0.1,0-0.5,0.1-0.8,0.1C-299.6,287.1-299.9,286.7-299.9,285.9z"></path>
					<path d="M-309,279.1c-1.1,0-2.2,0.5-2.8,1.6v-1.3h-2.2v9.7h2.3v-5.6c0-1.3,0.7-2.3,2-2.3c1.4,0,1.9,1,1.9,2.1v5.7h2.3
						v-6.1C-305.5,280.8-306.6,279.1-309,279.1z"></path>
					<path d="M-283.1,280.9v-1.6h-2.2v9.7h2.3v-4.6c0-1.8,0.8-2.8,2.6-2.8c0.2,0,0.5,0,0.7,0.1v-2.3
						c-0.1,0-0.3-0.1-0.6-0.1C-281.6,279.2-282.7,279.8-283.1,280.9z"></path>
					<path d="M-291.9,279c-2.4,0-4.6,2-4.6,5.1c0,3.3,2.3,5.2,4.8,5.2c2.3,0,3.8-1.4,4.3-3.1l-1.9-0.6
						c-0.3,1-1,1.7-2.4,1.7c-1.4,0-2.5-1.1-2.6-2.5h6.9c0,0,0.1-0.4,0.1-0.7C-287.2,281-288.9,279-291.9,279z M-294.2,283.1
						c0.1-1,0.9-2.1,2.3-2.1c1.6,0,2.3,1,2.3,2.1H-294.2z"></path>
					<path d="M-218.5,280.6c-0.4-0.8-1.3-1.4-2.8-1.4c-2.6,0-4.4,2.1-4.4,4.7c0,2.7,1.8,4.7,4.4,4.7c1.4,0,2.3-0.7,2.7-1.4
						v1c0,2-0.9,2.9-2.7,2.9c-1.3,0-2.2-0.9-2.4-2.1l-2.1,0.6c0.3,1.9,2,3.5,4.5,3.5c3.6,0,4.9-2.4,4.9-5v-8.8h-2.2V280.6z
						M-220.9,286.6c-1.5,0-2.5-1.1-2.5-2.7c0-1.7,1-2.7,2.5-2.7c1.4,0,2.4,1.1,2.4,2.7C-218.5,285.5-219.5,286.6-220.9,286.6z"></path>
					<path d="M-229.8,280.9v-1.6h-2.2v9.7h2.3v-4.6c0-1.8,0.8-2.8,2.6-2.8c0.2,0,0.5,0,0.7,0.1v-2.3
						c-0.1,0-0.3-0.1-0.6-0.1C-228.3,279.2-229.3,279.8-229.8,280.9z"></path>
					<path d="M-239.2,279c-2.8,0-4.9,2.2-4.9,5.1c0,3,2.1,5.2,4.9,5.2c2.8,0,4.9-2.2,4.9-5.2
						C-234.3,281.2-236.4,279-239.2,279z M-239.2,287.3c-1.4,0-2.6-1.1-2.6-3.1c0-2,1.3-3,2.6-3c1.4,0,2.6,1,2.6,3
						C-236.5,286.2-237.8,287.3-239.2,287.3z"></path>
				</g>
				<g class="by-facebook u-isHiddenSmall">
					<path d="M-139.6,279.5c-3,0-4.5,1.9-4.5,4.6v0.5c0,2.7,1.5,4.6,4.5,4.6c3,0,4.5-1.9,4.5-4.6v-0.5
						C-135,281.4-136.5,279.5-139.6,279.5z M-137.8,284.8c0,1.2-0.5,2.2-1.8,2.2c-1.3,0-1.8-1-1.8-2.2v-0.9c0-1.2,0.5-2.2,1.8-2.2
						c1.3,0,1.8,1,1.8,2.2V284.8z"></path>
					<path d="M-166.9,281.7c0.7,0,1.4,0.2,1.8,0.4l0.6-2c-0.6-0.4-1.6-0.6-2.6-0.6c-3.2,0-4.7,1.8-4.7,4.6v0.4
						c0,2.9,1.5,4.6,4.7,4.6c1.1,0,2.1-0.2,2.6-0.6l-0.6-2c-0.4,0.2-1.1,0.4-1.8,0.4c-1.5,0-2.1-0.9-2.1-2.4v-0.5
						C-169.1,282.6-168.4,281.7-166.9,281.7z"></path>
					<path d="M-159.4,279.5c-3,0-4.5,1.7-4.5,4.4v0.8c0,2.7,1.3,4.5,4.8,4.5c1.3,0,2.8-0.3,3.5-0.7l-0.5-1.9
						c-0.8,0.4-1.9,0.6-2.9,0.6c-1.5,0-2.1-0.6-2.1-1.8h5.8v-1.5C-155.4,281.3-156.6,279.5-159.4,279.5z M-157.8,283.5h-3.3
						c0-1.3,0.5-2.1,1.7-2.1c1.2,0,1.6,0.8,1.6,1.8V283.5z"></path>
					<path d="M-148.9,279.5c-1.3,0-2.2,0.7-2.6,1.5v-6.2l-2.8,0.3v14h2.6v-1.4c0.4,0.9,1.3,1.6,2.7,1.6
						c2.5,0,3.8-2,3.8-4.6v-0.6C-145.1,281.5-146.4,279.5-148.9,279.5z M-147.9,284.7c0,1.3-0.5,2.3-1.8,2.3c-1.1,0-1.7-0.8-1.7-2.2v-1
						c0-1.3,0.6-2.2,1.7-2.2c1.3,0,1.8,1,1.8,2.3V284.7z"></path>
					<path d="M-129.4,279.5c-3,0-4.5,1.9-4.5,4.6v0.5c0,2.7,1.5,4.6,4.5,4.6c3,0,4.5-1.9,4.5-4.6v-0.5
						C-124.9,281.4-126.4,279.5-129.4,279.5z M-127.6,284.8c0,1.2-0.5,2.2-1.8,2.2c-1.3,0-1.8-1-1.8-2.2v-0.9c0-1.2,0.5-2.2,1.8-2.2
						c1.3,0,1.8,1,1.8,2.2V284.8z"></path>
					<polygon points="-118.3,284.3 -115.4,279.7 -118.4,279.7 -121.1,284.1 -121.1,274.8 -123.9,275 -123.9,289
						-121.1,289 -121.1,284.4 -118.3,289 -115.3,289         "></polygon>
					<path d="M-175.3,281.1c-0.4-0.9-1.2-1.6-2.6-1.6c-2.5,0-3.7,2-3.7,4.6v0.6c0,2.6,1.2,4.6,3.7,4.6
						c1.4,0,2.2-0.7,2.6-1.6v1.4h2.5v-9.3h-2.5V281.1z M-175.4,284.8c0,1.3-0.6,2.2-1.7,2.2c-1.3,0-1.8-0.9-1.8-2.3V284
						c0-1.4,0.5-2.3,1.8-2.3c1.1,0,1.7,0.8,1.7,2.2V284.8z"></path>
					<path d="M-202.7,282.6c-0.3-0.4-0.6-0.7-1-0.9c-0.4-0.2-0.9-0.3-1.4-0.3c-0.5,0-1,0.1-1.5,0.4c-0.5,0.2-0.9,0.6-1.1,1
						v-4.1h-0.8v10.4h0.8v-1.3h0c0.1,0.3,0.3,0.5,0.5,0.7c0.2,0.2,0.4,0.3,0.6,0.5c0.2,0.1,0.5,0.2,0.7,0.3c0.2,0.1,0.5,0.1,0.7,0.1
						c0.6,0,1.1-0.1,1.5-0.3c0.4-0.2,0.8-0.5,1-0.8c0.3-0.4,0.5-0.8,0.6-1.2c0.1-0.5,0.2-1,0.2-1.5c0-0.5-0.1-1-0.2-1.5
						C-202.3,283.4-202.5,283-202.7,282.6z M-203,286.9c-0.1,0.4-0.3,0.7-0.5,0.9c-0.2,0.3-0.5,0.4-0.8,0.6c-0.3,0.1-0.6,0.2-1,0.2
						c-0.4,0-0.8-0.1-1.1-0.2c-0.3-0.2-0.6-0.4-0.8-0.7c-0.2-0.3-0.4-0.6-0.5-1c-0.1-0.4-0.2-0.8-0.2-1.3c0-0.4,0.1-0.8,0.2-1.2
						c0.1-0.4,0.3-0.7,0.5-1c0.2-0.3,0.5-0.5,0.8-0.7c0.3-0.2,0.7-0.3,1-0.3c0.4,0,0.8,0.1,1.1,0.3c0.3,0.2,0.6,0.4,0.8,0.7
						c0.2,0.3,0.4,0.7,0.5,1.1c0.1,0.4,0.1,0.8,0.1,1.3C-202.8,286.1-202.9,286.5-203,286.9z"></path>
					<path d="M-197.6,288.1l-2.3-6.4h-0.9l2.8,7.5c-0.1,0.2-0.2,0.4-0.2,0.6c-0.1,0.2-0.1,0.5-0.2,0.7
						c-0.1,0.2-0.2,0.4-0.4,0.6c-0.2,0.2-0.3,0.2-0.6,0.2c-0.1,0-0.2,0-0.4,0c-0.1,0-0.2,0-0.3-0.1v0.8c0.1,0,0.2,0.1,0.4,0.1
						c0.1,0,0.3,0,0.4,0c0.3,0,0.5-0.1,0.8-0.2c0.2-0.1,0.4-0.3,0.6-0.6c0.2-0.3,0.3-0.6,0.5-1c0.2-0.4,0.3-0.8,0.5-1.3l2.7-7.4h-0.9
						L-197.6,288.1z"></path>
					<path d="M-186.9,278.3v1.4h-1.9v2.3h1.9v7h2.8v-7h2.1l0.2-2.3h-2.3v-1.4c0-0.9,0.2-1.3,1.1-1.3h1.2v-2.2
						c-0.4-0.1-1.1-0.1-1.6-0.1C-186,274.7-186.9,276-186.9,278.3z"></path>
				</g>
			</svg>
		</a>

		<button type="button" class="header-backBtn js-headerView-backBtn js-stateBack">
			<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" preserveAspectRatio="xMidYMid" width="19.938" height="31.938" viewBox="0 0 19.938 31.938">
				<path d="M19.951,3.182 L6.640,15.969 L19.951,28.756 L16.623,31.953 L-0.015,15.969 L3.313,12.772 L3.313,12.772 L16.623,-0.015 L19.951,3.182 Z"></path>
			</svg>
		</button>

		<button type="button" class="header-menuBtn js-headerView-menuBtn" style="display: inline-block;">
			<span class="menuTrigger">
				<span class="menuTrigger-label js-headerView-menuBtn-text u-isVisuallyHidden">Menu</span>
				<span class="menuTrigger-icon js-headerView-menuBtn-icon isOpen"></span>
			</span>
		</button>
	</div>

	<div class="mainMenu js-menuView" style="background-color: rgba(0, 0, 0, 0.701961);">
		<div class="mainMenu-panel js-menuView-panel" style="transform: matrix(1, 0, 0, 1, 0, 0);">
			<div class="mainMenu-panel-hd">
				<form class="searchBox js-searchView js-searchFormView" role="search">
					<label for="mainMenu-search" class="searchBox-icon js-searchView-trigger">
						<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" preserveAspectRatio="xMidYMid" width="40" height="40" viewBox="0 0 40 40">
							<path d="M39.724,35.318 L29.676,25.269 C31.311,22.737 32.449,19.451 32.449,16.219 C32.449,7.268 25.165,-0.011 16.217,-0.011 C7.266,-0.011 -0.012,7.268 -0.012,16.219 C-0.012,25.169 7.266,32.450 16.217,32.450 C19.449,32.450 22.700,31.315 25.234,29.679 L35.281,39.727 C35.615,40.062 36.166,40.062 36.498,39.727 L39.724,36.535 C40.061,36.201 40.061,35.654 39.724,35.318 ZM3.573,16.010 C3.573,9.155 9.152,3.574 16.009,3.574 C22.868,3.574 28.444,9.155 28.444,16.010 C28.444,22.869 22.868,28.447 16.009,28.447 C9.152,28.447 3.573,22.869 3.573,16.010 Z"></path>
						</svg>
						<span class="u-isVisuallyHidden">Search</span>
					</label>
					<input type="search" id="mainMenu-search" class="searchBox-input js-searchView-input" name="s" placeholder="Search">
				</form>
			</div>
			<?php

			// Configure and build the main portion of the menu (Our Mission, etc.)
			$main_menu_config = array(
				'container_class' => 'mainMenu-panel-primary',
				'container_id'    => '',
				'menu_class'      => '',
				'menu_id'         => '',
				'before'          => '',
				'after'           => '',
				'link_before'     => '',
				'link_after'      => '',
				'theme_location'  => 'primary',
				'walker'          => new IOrg_Main_Nav_Walker(),
			);
			wp_nav_menu( $main_menu_config );

			// Configure and build the secondary portion of the menu (Careers, etc.)
			$submenu_config = array(
				'container_class' => 'mainMenu-panel-secondary',
				'container_id'    => '',
				'menu_class'      => 'borderBlocks borderBlocks_2up',
				'menu_id'         => '',
				'before'          => '',
				'after'           => '',
				'link_before'     => '',
				'link_after'      => '',
				'theme_location'  => 'primary-sub-nav',
				'walker'          => new IOrg_Main_SubNav_Walker(),
			);
			wp_nav_menu( $submenu_config );

			?>

			<div class="mainMenu-panel-lang">

				<!-- Internal Lang. Sel. Function -->
				<?php iorg_language_switcher(); ?>

				<?php /*
				<div class="langSelect js-select">
					<select class="">
						<option value="">English</option>
						<option value="">Bahasa</option>
						<option value="">Spanish</option>
						<option value="">French</option>
						<option value="">Portuguese</option>
						<option value="">Arabic</option>
						<option value="">Hindi</option>
						<option value="">Urdu</option>
						<option value="">Bengali</option>
						<option value="">Russian</option>
						<option value="">Japanese</option>
						<option value="">Punjabi</option>
					</select>
					<div class="langSelect-label">English</div>
					<div class="langSelect-menu" style="height: auto;">
						<div class="langSelect-menu-item isSelected" tabindex="0"><span>English</span></div>
						<div class="langSelect-menu-item" tabindex="0"><span>Bahasa</span></div>
						<div class="langSelect-menu-item" tabindex="0"><span>Spanish</span></div>
						<div class="langSelect-menu-item" tabindex="0"><span>French</span></div>
						<div class="langSelect-menu-item" tabindex="0"><span>Portuguese</span></div>
						<div class="langSelect-menu-item" tabindex="0"><span>Arabic</span></div>
						<div class="langSelect-menu-item" tabindex="0"><span>Hindi</span></div>
						<div class="langSelect-menu-item" tabindex="0"><span>Urdu</span></div>
						<div class="langSelect-menu-item" tabindex="0"><span>Bengali</span></div>
						<div class="langSelect-menu-item" tabindex="0"><span>Russian</span></div>
						<div class="langSelect-menu-item" tabindex="0"><span>Japanese</span></div>
						<div class="langSelect-menu-item" tabindex="0"><span>Punjabi</span></div>
					</div>
				</div>
				*/ ?>
			</div>
		</div>
	</div>

	<!-- /header -->

	<div id="content" class="site-content">
