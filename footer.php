<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package Internet.org
 */

?>

	</div><!-- #content -->

	<?php echo vip_powered_wpcom(); /* required */ ?>

	<?php wp_footer(); /* required */ ?>

	<?php do_action( 'internetorg_body_bottom' ); ?>

</body>

</html>
