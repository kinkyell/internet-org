<?php
/**
 * This is the home page template
 *
 * @package internetorg
 * @author arichard <arichard@nerdery.com>
 */

// Header
get_header();
$home_background_image_url = '';
?>

<div class="viewWindow js-viewWindow" data-route="<?php echo esc_url( home_url( '/' ) ); ?>" data-type="home">
<?php while ( have_posts() ) : the_post(); ?>
	<?php
	if ( has_post_thumbnail() ) {
		$home_background_image_url = internetorg_get_media_image_url( get_post_thumbnail_id( get_the_ID() ), 'full' );
	}

	// Pull the custom fields and parse for placement.
	$custom_fields     = get_post_meta( get_the_ID(), 'home-content-section', false );
	$custom_features   = '';
	$custom_stories    = '';
	// This is a string list of images used on this page (not an array, see comma).
	$dt_image_list     = $home_background_image_url . ',';
	$dt_sub_image_list = '';
	if ( ! empty( $custom_fields ) ) :
		foreach ( $custom_fields as $group ) :
			if ( ! empty( $group ) ) :
				foreach ( $group as $fieldset ) :
					$custom_features .= wp_kses_post( get_internetorg_home_section_feature( $fieldset ) );
					$custom_stories  .= wp_kses_post( get_internetorg_home_section_story( $fieldset ) );

					// Compile a list of images to use for desktop (the scrolly ones).
					$hasMultipleCtas = false;
					if ( ! empty( $fieldset['call-to-action'] ) ) {
						if ( count( $fieldset ) > 1 ) {
							$hasMultipleCtas = true;
						}
						foreach ( $fieldset['call-to-action'] as $cta ) {
							if ( ! empty( $cta['image'] ) ) {
								$imgUrl = wp_get_attachment_url( $cta['image'], 'full' );
								$dt_image_list .= $imgUrl . ',';
								if ( $hasMultipleCtas ) {
									$dt_sub_image_list .= $imgUrl . ',';
								}
							}
						}
					}

				endforeach;
			endif;
		endforeach;
	endif;

	$get_involved_content_widget = internetorg_get_content_widget_by_slug( 'home-get-involved' );
	$get_involved_content_widget_image = array();
	if ( ! empty( $get_involved_content_widget ) ) {
		$meta = ( ! empty( $get_involved_content_widget['meta'] ) ? $get_involved_content_widget['meta'] : '' );
		if ( ! empty( $meta ) ) {
			foreach ( $meta['widget-data'] as $cta ) {
				$get_involved_content_widget_image[] = ( ! empty( $cta['image'] ) ? $cta['image'] : '' );
				$dt_image_list .= ( ! empty( $cta['image'] ) ? $cta['image'] . ',' : '' );
			}
			unset( $meta );
		}
		$get_involved_content_widget = $get_involved_content_widget['post'];
	}

	?>
	<div class="viewWindow-panel isActive">
		<div class="viewWindow-panel-content">
			<div class="viewWindow-panel-content-inner viewWindow-panel-content-inner_home">
				<div class="narrativeView js-narrativeView">
					<div class="narrativeDT"
						data-feature-images="<?php echo esc_attr( ltrim( rtrim( $dt_image_list, ',' ), ',' ) ); ?>"
						data-sub-feature-images="<?php echo esc_attr( ltrim( rtrim( $dt_sub_image_list, ',' ), ',' ) ); ?>"
						>
						<div class="narrativeDT-inner">
							<div class="container">
								<div class="transformBlock js-transformBlock">
									<div class="transformBlock-pre">
										<div class="transformBlock-pre-item transformBlock-pre-item_divide">
											<span class="bdcpy bdcpy_narrative mix-bdcpy_splash"><?php the_title(); ?></span>
										</div>
										<div class="transformBlock-pre-item">
											<span class="bdcpy bdcpy_narrative mix-bdcpy_splash"><?php echo esc_html( ! empty( $get_involved_content_widget ) ? $get_involved_content_widget->post_title : '' ); ?></span>
										</div>
									</div>
									<div class="transformBlock-stmnt">
										<h2 class="hdg hdg_1 mix-hdg_splash"><?php echo wp_kses_post( internetorg_get_the_subtitle( get_the_ID() ) ); ?></h2>
									</div>
									<div class="transformBlock-post">

										<?php echo wp_kses_post( $custom_features ); ?>

										<div class="transformBlock-post-item">
											<div class="splashFooter">
												<?php echo wp_kses_post( ! empty( $get_involved_content_widget ) ? $get_involved_content_widget->post_content : '' ); ?>
											</div>
										</div>

									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="narrative">
						<div class="narrative-section">
							<div class="narrative-section-slides">
								<div class="narrative-section-slides-item" style="background-image: url('<?php echo esc_attr( $home_background_image_url ); ?>')"></div>
							</div>
							<div class="narrative-section-bd narrative-section-bd_low">
								<div class="container container_wide">
									<div class="statementBlock statementBlock_start">
										<div class="statementBlock-pre statementBlock-pre_divide">
											<span class="bdcpy bdcpy_narrative mix-bdcpy_splash"><?php the_title(); ?></span>
										</div>
										<div class="statementBlock-hd">
											<h2 class="hdg hdg_1 mix-hdg_splash"><?php echo wp_kses_post( internetorg_get_the_subtitle( get_the_ID() ) ); ?></h2>
										</div>
									</div>
								</div>
								<div class="narrative-section-bd-ft">
									<a href="#" class="arrowCta arrowCta_light"></a>
								</div>
							</div>
						</div>

						<?php echo wp_kses_post( $custom_stories ); ?>

						<div class="narrative-section">
							<div class="narrative-section-slides">
								<div class="narrative-section-slides-item" style="background-image: url('<?php echo esc_attr( ! empty( $get_involved_content_widget_image[0] ) ? $get_involved_content_widget_image[0] : '' ); ?>')">
									<div class="statementBlock statementBlock_end">
										<div class="statementBlock-pre statementBlock-pre_divide">
											<span class="bdcpy bdcpy_narrative mix-bdcpy_splash"><?php echo esc_html( ! empty( $get_involved_content_widget ) ? $get_involved_content_widget->post_title : '' ); ?></span>
										</div>
										<div class="statementBlock-hd">
											<h2 class="hdg hdg_1 mix-hdg_splash"><?php echo wp_kses_post( internetorg_get_the_subtitle( get_the_ID() ) ); ?></h2>
										</div>
										<div class="statementBlock-bd">
											<div class="splashFooter">
												<?php echo wp_kses_post( ! empty( $get_involved_content_widget ) ? $get_involved_content_widget->post_content : '' ); ?>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

					<ul class="narrative-progress narrative-progress_isHidden">
						<li></li>
						<li></li>
						<li></li>
						<li></li>
						<li></li>
					</ul>
				</div>
			</div>
		</div>
	</div>
	<div class="viewWindow-panel viewWindow-panel_feature">
		<div class="viewWindow-panel-content">
			<div class="viewWindow-panel-content-inner" style="background-image: url('<?php echo esc_attr( $home_background_image_url ); ?>');"></div>
		</div>
	</div>
	<div class="viewWindow-panel viewWindow-panel_story">
		<div class="viewWindow-panel-content">

			<div class="viewWindow-panel-content-inner" style="background-color: #dddddd;">

			</div>

		</div>
	</div>
<?php endwhile; ?>
</div>






<?php

// Sidebar -- no sidebar on the front page
// get_sidebar();

// Footer
get_footer();
