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

	<?php do_action( 'internetorg_head_bottom' ); ?>

	<?php wp_head(); ?>

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
			<div class="mainMenu-panel-primary">
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
			</div>
			<div class="mainMenu-panel-secondary">
				<?php
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
					'walker'          => new Internetorg_Main_SubNav_Walker(),
				);
				wp_nav_menu( $submenu_config );

				?>
			</div>
			<div class="mainMenu-panel-lang">
				<?php internetorg_language_switcher(); ?>
			</div>
		</div>
	</div>

	<!-- /header -->
