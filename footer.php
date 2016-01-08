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

		// Detect Opera Mini
		var isOperaMini = Object.prototype.toString.call(window.operamini) === '[object OperaMini]';

		// Ext Execution of JavaScript
		function exit(e){function o(e){e.stopPropagation()}var t;window.addEventListener("error",function(e){e.preventDefault(),e.stopPropagation()},!1);var n=["copy","cut","paste","beforeunload","blur","change","click","contextmenu","dblclick","focus","keydown","keypress","keyup","mousedown","mousemove","mouseout","mouseover","mouseup","resize","scroll","DOMNodeInserted","DOMNodeRemoved","DOMNodeRemovedFromDocument","DOMNodeInsertedIntoDocument","DOMAttrModified","DOMCharacterDataModified","DOMElementNameChanged","DOMAttributeNameChanged","DOMActivate","DOMFocusIn","DOMFocusOut","online","offline","textInput","abort","close","dragdrop","load","paint","reset","select","submit","unload"];for(t=0;t<n.length;t++)window.addEventListener(n[t],function(e){o(e)},!0);throw window.stop&&window.stop(),""};

		function applyOperaFallbacks () {
			var root = document.getElementsByTagName('body')[0];
			root.className += ' opera-mini';
			exit( 'Opera Mini Detected' );
		}

		// If Opera Mini add class to body
		if ( isOperaMini === true ) {
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
    js.src = "//connect.facebook.net/<?php echo wp_json_encode( bbl_get_current_lang()->code ); ?>/all.js#xfbml=1&amp;version=v2.3";
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
