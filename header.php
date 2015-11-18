<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package Internet.org
 */

print_r(home_url('/'));

?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>

	<!-- META DATA -->
	<meta charset="<?php esc_attr_e( get_bloginfo( 'charset' ) ); ?>">
    <meta name="viewport" content="minimal-ui, width=device-width, initial-scale=1.0" />
	<!--[if IE]><meta http-equiv="cleartype" content="on" /><![endif]-->

	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php esc_attr_e( get_bloginfo( 'pingback_url' ) ); ?>">
	<meta http-equiv="content-language" content="<?php esc_attr_e( get_bloginfo( 'language' ) ); ?>" />

	<?php wp_head(); ?>

	<?php get_template_part( 'template-parts/header', 'icons' ); ?>

	<!-- POLYFILLS -->
	<!-- build:js <?php echo esc_url( get_stylesheet_directory_uri() ); ?>/_static/web/assets/scripts/head.js -->
	<!-- endbuild -->

</head>

<body <?php body_class(); ?>>
		<!-- Google Tag Manager -->
		<noscript><iframe src="//www.googletagmanager.com/ns.html?id=GTM-NF52FT"
		height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
		<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
		new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
		j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
		'//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
		})(window,document,'script','dataLayer','GTM-NF52FT');</script>
		<!-- End Google Tag Manager -->


		<a href="#main-content" class="u-isVisuallyHidden">Skip to main content</a>

		<!-- header -->
		<div class="header js-headerView" role="banner">
			<?php get_template_part( 'template-parts/header', 'logo' ); ?>
			<?php get_template_part( 'template-parts/header', 'buttons' ); ?>
		</div>

		<div class="mainMenu js-menuView u-isVisuallyHidden" id="mainNav">
			<div class="mainMenu-panel js-menuView-panel u-isHidden">
				<div class="mainMenu-panel-hd">
					<?php get_template_part( 'template-parts/header', 'search' ); ?>
				</div>

				<?php internetorg_nav_menu( 'primary' ); ?>

				<?php internetorg_nav_menu( 'secondary' ); ?>

				<div class="mainMenu-panel-lang">
					<?php internetorg_language_switcher(); ?>
				</div>

			</div>
		</div> <!-- end header -->

		<!-- CONTENT -->
