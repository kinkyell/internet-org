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

	<!-- META DATA -->
	<meta charset="<?php esc_attr_e( get_bloginfo( 'charset' ) ); ?>">
    <meta name="viewport" content="minimal-ui, width=device-width, initial-scale=1.0" />
	<!--[if IE]><meta http-equiv="cleartype" content="on" /><![endif]-->

	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php esc_attr_e( get_bloginfo( 'pingback_url' ) ); ?>">
	<meta http-equiv="content-language" content="<? echo get_bloginfo('language'); ?>" />

	<?php wp_head(); ?>

	<?php get_template_part( 'template-parts/header', 'icons' ); ?>

	<!-- POLYFILLS -->
	<!-- build:js <?php echo esc_url( get_stylesheet_directory_uri() ); ?>/_static/web/assets/scripts/head.js -->
	<!-- endbuild -->

</head>

<body <?php body_class(); ?>>

		<!-- header -->
		<div class="header js-headerView" role="banner">
			<?php get_template_part( 'template-parts/header', 'logo' ); ?>
			<?php get_template_part( 'template-parts/header', 'buttons' ); ?>
		</div>

		<div class="mainMenu js-menuView u-isVisuallyHidden">
			<div class="mainMenu-panel js-menuView-panel">
				<div class="mainMenu-panel-hd">
					<?php get_template_part( 'template-parts/header', 'search' ); ?>
				</div>

					<?php
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
						'walker'          => new Internetorg_Main_Nav_Walker(),
					);
					wp_nav_menu( $main_menu_config );
					?>

					<?php
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
						'walker'          => new Internetorg_Main_SubNav_Walker(),
					);
					wp_nav_menu( $submenu_config );

					?>

				<div class="mainMenu-panel-lang">
					<?php internetorg_language_switcher(); ?>
				</div>

			</div>
		</div> <!-- end header -->

		<!-- CONTENT -->
