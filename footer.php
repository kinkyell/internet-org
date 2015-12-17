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

		// If Opera Mini add class to body
		if (isOperaMini === true) {
			var root = document.getElementsByTagName('body')[0];
			root.className += ' opera-mini';
			exit( 'Opera Mini Detected' );
		}

		// remove this later its jsut for testing
		var root = document.getElementsByTagName('body')[0];
		root.className += ' opera-mini';
		exit( 'Opera Mini Detected' );

		var loader = document.createElement('div');
		loader.className = 'loadingIcon loadingIcon_opaque loadingIcon_topLayer js-assetShade';
		document.body.appendChild(loader);

	</script>

	<?php /* <div><?php echo vip_powered_wpcom(); ?></div> */ ?>

	<?php wp_footer(); /* required */ ?>

	<?php get_template_part( 'template-parts/footer', 'requirejs' ); ?>


</body>

</html>
