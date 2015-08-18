<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package Internet.org
 */

?>

	<?php /*
	<div><?php echo vip_powered_wpcom(); ?></div>
    */ ?>

	<?php wp_footer(); /* required */ ?>

	<?php get_template_part( 'template-parts/footer', 'requirejs' ); ?>

</body>

</html>
