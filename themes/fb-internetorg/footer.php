<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package Internet.org
 */

?>
	<!-- Add asset shade BEFORE scripts are loaded -->
	<script type="text/javascript">

    // Detect Android Browser
    var navU = navigator.userAgent;

    // Android Mobile
    var isAndroidMobile = navU.indexOf('Android') > -1 && navU.indexOf('Mozilla/5.0') > -1 && navU.indexOf('AppleWebKit') > -1;

    // Apple webkit
    var regExAppleWebKit = new RegExp(/AppleWebKit\/([\d.]+)/);
    var resultAppleWebKitRegEx = regExAppleWebKit.exec(navU);
    var appleWebKitVersion = (resultAppleWebKitRegEx === null ? null : parseFloat(regExAppleWebKit.exec(navU)[1]));

    // Chrome
    var regExChrome = new RegExp(/Chrome\/([\d.]+)/);
    var resultChromeRegEx = regExChrome.exec(navU);
    var chromeVersion = (resultChromeRegEx === null ? null : parseFloat(regExChrome.exec(navU)[1]));

    // Native Android Browser
    var isAndroidBrowser = isAndroidMobile && (appleWebKitVersion !== null && appleWebKitVersion < 537) || (chromeVersion !== null && chromeVersion < 37);

		// Detect Opera Mini and Opera 10
		var isOperaMini = Object.prototype.toString.call(window.operamini) === '[object OperaMini]';
		var isOpera10 = window.opera && (window.opera.version() >= 10 && window.opera.version() < 11);
		// Ext Execution of JavaScript
		function exit(e){function o(e){e.stopPropagation()}var t;window.addEventListener("error",function(e){e.preventDefault(),e.stopPropagation()},!1);var n=["copy","cut","paste","beforeunload","blur","change","click","contextmenu","dblclick","focus","keydown","keypress","keyup","mousedown","mousemove","mouseout","mouseover","mouseup","resize","scroll","DOMNodeInserted","DOMNodeRemoved","DOMNodeRemovedFromDocument","DOMNodeInsertedIntoDocument","DOMAttrModified","DOMCharacterDataModified","DOMElementNameChanged","DOMAttributeNameChanged","DOMActivate","DOMFocusIn","DOMFocusOut","online","offline","textInput","abort","close","dragdrop","load","paint","reset","select","submit","unload"];for(t=0;t<n.length;t++)window.addEventListener(n[t],function(e){o(e)},!0);throw window.stop&&window.stop(),""};

    var root = document.getElementsByTagName('body')[0];

    function applyOperaFallbacks () {
      if(isOpera10){
        root.className += ' opera-fallback opera-10';
        exit( 'Opera 10 Detected' );
      } else {
        root.className += ' opera-fallback opera-mini';
        exit( 'Opera Mini Detected' );
      }

		}

    if(isAndroidBrowser){
      root.className += ' android-browser';
    }

		function IE(v) {
		  return RegExp('msie' + (!isNaN(v)?('\\s'+v):''), 'i').test(navigator.userAgent);
		}

		if(IE(10)){
			var root = document.getElementsByTagName('body')[0];
			root.className += ' ie10';
			console.log('Internet Explorer 10');
		}

		// If Opera Mini or Opera 10 add class to body
		if ( isOperaMini === true || isOpera10 === true ) {
			applyOperaFallbacks();
		}

		var loader = document.createElement('div');
		loader.className = 'loadingIcon loadingIcon_opaque loadingIcon_topLayer js-assetShade';
		document.body.appendChild(loader);

	</script>

	<div id="fb-root"></div>
  <script>(function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = <?php echo wp_json_encode( "https://connect.facebook.net/" . internetorg_get_current_language( true ) . "/all.js#xfbml=1&amp;version=v2.3" ); ?>;
    fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk'));</script>

  <?php if ( strpos( home_url(), 'internetorg.jam3.net' ) !== false ) : ?>

		<script type="text/javascript">
		(function() {
		var s = document.createElement("script");
		s.type = "text/javascript";
		s.async = true;
		s.src = '//api.usersnap.com/load/'+
		        '6433b6db-f1b1-4f38-9318-a192bc4e2607.js';
		var x = document.getElementsByTagName('script')[0];
		x.parentNode.insertBefore(s, x);
		})();
		</script>

	<?php endif; ?>

	<?php /* <div><?php echo vip_powered_wpcom(); ?></div> */ ?>

	<?php wp_footer(); /* required */ ?>

	<?php get_template_part( 'template-parts/footer', 'requirejs' ); ?>


</body>

</html>
