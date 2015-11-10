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
		var loader = document.createElement('div');
		loader.className = 'loadingIcon loadingIcon_opaque loadingIcon_topLayer js-assetShade';
		document.body.appendChild(loader);
	</script>

	<?php /* <div><?php echo vip_powered_wpcom(); ?></div> */ ?>

	<script type="text/javascript">
    		window._define = window.define;
    		window.define = null;
	</script>

	<?php wp_footer(); /* required */ ?>

	<script type="text/javascript">
    		window.define = window._define;
	</script>

	<?php get_template_part( 'template-parts/footer', 'requirejs' ); ?>


</body>

</html>
