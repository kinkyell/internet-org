<?php
/**
 * This is the home page template
 *
 * @package internetorg
 * @author  arichard <arichard@nerdery.com>
 */

get_header();

if ( has_post_thumbnail( get_the_ID() ) ) {
	/**
	 * URL to the panel-image sized Featured Image, else empty string.
	 *
	 * @var string $home_bg_url
	 */
	$home_bg_url = internetorg_get_media_image_url( get_post_thumbnail_id( get_the_ID() ), 'panel-image' );

	/**
	 * @var id $home_bg_id
	 */
	$home_bg_id = get_post_thumbnail_id( get_the_ID() );
} else {
	$home_bg_url = '';
	$home_bg_id = '';
}

?>

	<div class="interactionPrompt">
		<button class="arrowCta arrowCta_light js-narrativeAdvance"></button>
	</div>

	<div class="viewWindow viewWindow_flush js-viewWindow" id="main-content" role="main" data-route="<?php echo esc_url( home_url( '/' ) ); ?>" data-type="home">
		<?php while ( have_posts() ) : the_post(); ?>
			<?php

			/**
			 * An array of custom field meta assigned to this page.
			 *
			 * @var array $custom_fields
			 */
			$custom_fields = get_post_meta( get_the_ID(), 'home-content-section', false );

			/** Scrub the array to remove the 'proto' keys. */
			internetorg_recursive_unset( $custom_fields, 'proto' );

			/** Scrub the array to remove 'empty' values. */
			$custom_fields = internetorg_array_filter_recursive( $custom_fields );

			/**
			 * An array containing the requested io_ctntwdgt WP_Post.
			 *
			 * @var WP_Post[] $get_involved_content_widget
			 */
			$get_involved_content_widget = internetorg_get_content_widget_by_slug( 'home-get-involved' );

			/**
			 * A WP_Post of type io_ctntwdgt, else null.
			 *
			 * @var null|WP_Post $get_involved_content_widget_post
			 */
			$get_involved_content_widget_post  = null;

			/**
			 * A string representing a panel-image associated with the io_ctntwdgt post, else null.
			 *
			 * @var null|string $get_involved_content_widget_image
			 */
			$get_involved_content_widget_image = null;

			/**
			 * Panel-image id associated with the io_ctntwdgt post, else null.
			 *
			 * @var null|int $get_involved_content_widget_image_id
			 */
			$get_involved_content_widget_image_id = null;

			if ( ! empty( $get_involved_content_widget ) ) {
				$get_involved_content_widget_post = $get_involved_content_widget['post'];

				if ( ! empty( $get_involved_content_widget['meta'] ) && ! empty( $get_involved_content_widget['meta']['widget-data'] ) ) :
					//$get_involved_content_widget_image_id = $get_involved_content_widget['meta']['widget-data'][0]['image-id'];
					$get_involved_content_widget_image = $get_involved_content_widget['meta']['widget-data'][0]['image'];
				endif;
			}

			?>
			<div class="viewWindow-panel isActive">
				<div class="viewWindow-panel-content">
					<div class="viewWindow-panel-content-inner viewWindow-panel-content-inner_home">
						<div class="narrativeView js-narrativeView">
							<div class="narrativeDT">

								<?php if ( ! empty( $custom_fields ) ) : ?>

									<ul class="narrativeDT-sections">

										<li
											class="narrativeDT-sections-item"
											data-feature="<?php echo esc_url( $home_bg_url ); ?>"
											data-feature-classname="<?php echo esc_attr( internetorg_responsive_images_classname( $home_bg_id, 'panel-image' ) ); ?>"
										>

											<?php foreach ( $custom_fields as $group ) : ?>

											<?php if ( ! empty( $group ) ) : ?>

											<?php foreach ( $group as $fieldset ) : ?>

											<?php if ( ! empty( $fieldset['image'] ) ) : ?>

										<li
											class="narrativeDT-sections-item"
											data-feature="<?php echo esc_url( internetorg_get_media_image_url( $fieldset['image'], 'panel-image' ) ); ?>"
											data-feature-classname="<?php echo esc_attr( internetorg_responsive_images_classname( $fieldset['image'], 'panel-image' ) ); ?>"
										>


										<?php if ( ! empty( $fieldset['call-to-action'] ) ) : ?>
												<ul>
													<?php foreach ( $fieldset['call-to-action'] as $cta ) : ?>
														<?php if ( ! empty( $cta['image'] ) ) : $imgUrl = internetorg_get_media_image_url( $cta['image'], 'panel-image' ); ?>

															<li
																data-feature="<?php echo esc_url( $imgUrl ); ?>"
																data-feature-classname="<?php echo esc_attr( internetorg_responsive_images_classname( $cta['image'], 'panel-image' ) ); ?>"
															>
																<?php if ( ! empty( $cta['link'] ) ) : ?>
																	<div class="featureContent">
																		<?php

																		$js_class = 'js-stateLink';
																		if ( internetorg_is_video_url( $cta['link'] ) ) {
																			$js_class = 'js-videoModal';
																		}
																		$social_attr = 'false';
																		if ( 'page' === $cta['cta_src'] && absint( $cta['link_src'] ) ) {

																			$url = esc_url( get_the_permalink( $cta['link_src'] ) );

																			$title = esc_attr( get_the_title( $cta['link_src'] ) );

																			$desc = wp_kses_post( get_post_field( 'post_excerpt', $cta['link_src'] ) );

																			$img = ( internetorg_get_media_image_url( get_post_thumbnail_id( $cta['link_src'] ), 'panel-image' ) )
																				? internetorg_get_media_image_url( get_post_thumbnail_id( $cta['link_src'] ), 'panel-image' ) : '';

																			$mobile_image = esc_url( internetorg_get_mobile_featured_image( get_post_type( $cta['link_src'] ), $cta['link_src'] ) );

																			if ( in_array( $cta['link_src'], internetorg_get_shadow_post_types_for_ajax( 'post' ) ) ) {
																				$social_attr = 'true';
																			}
																		} else {

																			$url = apply_filters( 'iorg_url', $cta['link'] );

																			$title = esc_attr( $cta['title'] );

																			$desc = esc_attr( ( ! empty( $cta['text'] ) ? strip_tags( nl2br( $cta['text'] ) ) : '' ) );

																			$img = ( ! empty( $cf_content_section['call-to-action'][0] )
																				? internetorg_get_media_image_url( $cf_content_section['call-to-action'][0]['image'], 'panel-image' )
																				: '' );

																			$slug = basename( untrailingslashit( $cta['link'] ) );
																			$temp = wpcom_vip_get_page_by_path( $slug, OBJECT, 'io_story' );

																			if ( isset( $temp->ID ) ) {
																				$img = esc_url( internetorg_get_post_thumbnail( $temp->ID, 'panel-image' ) );
																			}

																			$type = 'panel';

																			$mobile_image = esc_url( ( ! empty( $cf_content_section['call-to-action'][0] )
																				? internetorg_get_media_image_url( $cf_content_section['call-to-action'][0]['image'], 'inline-image' )
																				: '' ) );
																		}

																		$theme = ( ! empty( $fieldset['theme'] ) )
																			? $fieldset['theme'] : $fieldset['slug'];

																		?>
																		<a href="<?php echo esc_url( apply_filters( 'iorg_url', $cta['link'] ) ); ?>"
																		   class="tertiaryCta <?php echo esc_attr( $js_class ); ?>"
																			<?php if ( ! internetorg_is_video_url( $cta['link'] ) ) : ?>
																				data-type="<?php echo ( $type ) ? esc_attr( $type ) : 'titled'; ?>"
																				data-route="<?php echo esc_url( $url ); ?>"
																				data-date="<?php echo esc_attr( get_the_date() ); ?>"
																				data-social="<?php echo esc_attr( $social_attr ); ?>"
																				data-theme="<?php echo esc_attr( strtolower( $theme ) ); ?>"
																				data-title="<?php echo esc_attr( $title ); ?>"
																				data-desc="<?php echo esc_attr( $desc ); ?>"
																				data-image="<?php echo esc_url( $img ); ?>"
																				data-mobile-image="<?php echo esc_url( $mobile_image ); ?>"
																			<?php endif; ?>>
																			<?php echo esc_html( strip_tags( $cta['title'] ) ); ?>
																			<span class="circleBtn circleBtn_theme<?php echo esc_attr( ucwords( $theme ) ); ?><?php if ( internetorg_is_video_url( $cta['link'] ) ) : ?> circleBtn_play<?php endif; ?>"></span>
																		</a>
																	</div>
																<?php endif; ?>
															</li>

														<?php endif; ?>
													<?php endforeach; ?>
												</ul>
											<?php endif; ?>
										</li>
										<?php endif; ?>
										<?php endforeach; ?>
										<?php endif; ?>
										<?php endforeach; ?>
										<?php if ( ! empty( $get_involved_content_widget ) ) : ?>
											<?php $meta = ( ! empty( $get_involved_content_widget['meta'] )
												? $get_involved_content_widget['meta'] : '' ); ?>
											<?php if ( ! empty( $meta['widget-data'] ) ) : ?>
												<li
													class="narrativeDT-sections-item"
													data-feature="<?php echo esc_url( ( ! empty( $meta['widget-data'][0] ) ? $meta['widget-data'][0]['image'] : '' ) ); ?>"
													data-feature-classname="<?php echo esc_attr( ! empty( $meta['widget-data'][0] ) ? internetorg_responsive_images_classname( $meta['widget-data'][0]['image-id'], 'panel-image' ) : '' ); ?>"
												></li>
												<?php unset( $meta ); ?>
											<?php endif; ?>

										<?php endif; ?>
									</ul>
								<?php endif; ?>

								<div class="narrativeDT-inner">
									<div class="container container_xl">
										<div class="transformBlock js-transformBlock">
											<div class="transformBlock-pre">
												<div class="transformBlock-pre-item transformBlock-pre-item_divide">
													<span class="bdcpy bdcpy_narrative mix-bdcpy_splash"><?php the_title(); ?></span>
												</div>
												<div class="transformBlock-pre-item transformBlock-pre-item_divide">
													<span class="bdcpy bdcpy_narrative mix-bdcpy_splash"><?php echo esc_html( ! empty( $get_involved_content_widget_post ) ? $get_involved_content_widget_post->post_title : '' ); ?></span>
												</div>
											</div>
											<div class="transformBlock-stmnt">
                        						<div class="transformBlock-stmnt-item">
													<h2 class="hdg hdg_1 mix-hdg_splash"><?php echo wp_kses_post( internetorg_get_the_subtitle( get_the_ID() ) ); ?></h2>
												</div>
											</div>
											<div class="transformBlock-post">

												<?php

												if ( ! empty( $custom_fields ) ) :
													foreach ( $custom_fields as $group ) :
														if ( ! empty( $group ) ) :
															foreach ( $group as $cf_content_section ) : ?>
																<?php $cf_content_section_slug    = ( ! empty( $cf_content_section['slug'] ) ? $cf_content_section['slug'] : '' ); ?>
																<?php $cf_content_section_content = ( ! empty( $cf_content_section['content'] ) ? ltrim( rtrim( $cf_content_section['content'], '</p>' ), '<p>' ) : '' ); ?>
																<div class="transformBlock-post-item">
																	<div class="transformBlock-post-item-bd">
																		<p class="bdcpy bdcpy_narrative"><?php echo wp_kses_post( $cf_content_section_content ); ?></p>
																	</div>
																	<?php

																	if ( 'page' === $cf_content_section['src'] && ! empty( $cf_content_section['url-src'] ) && absint( $cf_content_section['url-src'] ) ) {
																		$url          = ( ! empty( $cf_content_section['url-src'] ) ? esc_url( get_the_permalink( $cf_content_section['url-src'] ) ) : '' );
																		$title        = ( ! empty( $cf_content_section['url-src'] ) ? esc_attr( get_the_title( $cf_content_section['url-src'] ) ) : '' );
																		$desc         = wp_kses_post( get_post_field( 'post_excerpt', $cf_content_section['url-src'] ) );
																		$img          = ( internetorg_get_media_image_url( get_post_thumbnail_id( $cf_content_section['url-src'] ), 'panel-image' ) )
																			? internetorg_get_media_image_url( get_post_thumbnail_id( $cf_content_section['url-src'] ), 'panel-image' )
																			: '';
																		$mobile_image = esc_url( internetorg_get_mobile_featured_image( get_post_type( $cf_content_section['url-src'] ), $cf_content_section['url-src'] ) );

																	} else {
																		$url          = '/' . esc_attr( $cf_content_section_slug );
																		$title        = ( ! empty( $cf_content_section['name'] ) ? esc_attr( $cf_content_section['name'] ) : '' );
																		$desc         = esc_attr( $cf_content_section_content );
																		$img          = ( ! empty( $cf_content_section['call-to-action'][0] ) && ! empty( $cf_content_section['call-to-action'][0]['image'] )
																			? internetorg_get_media_image_url( $cf_content_section['call-to-action'][0]['image'], 'panel-image' )
																			: '' );
																		$mobile_image = esc_url( ( ! empty( $cf_content_section['call-to-action'][0] ) && ! empty( $cf_content_section['call-to-action'][0]['image'] )
																			? internetorg_get_media_image_url( $cf_content_section['call-to-action'][0]['image'], 'inline-image' )
																			: '' ) );
																	}

																	$theme = ( ! empty( $cf_content_section['theme'] ) )
																		? $cf_content_section['theme']
																		: $cf_content_section_slug;
																	?>
																	<div class="transformBlock-post-item-ft">
																		<a href="<?php echo esc_url( apply_filters( 'iorg_url', $url ) ); ?>"
																		   class="link link_theme<?php echo esc_attr( ucwords( $theme ) ); ?> js-stateLink"
																		   data-type="panel"
																		   data-image="<?php echo esc_url( $img ); ?>"
																		   data-theme="<?php echo esc_attr( $theme ); ?>"
																			<?php if ( is_string( $mobile_image ) ) : ?>
																				data-mobile-image="<?php echo esc_url( $mobile_image ); ?>"
																			<?php endif; ?>
																			<?php if ( is_string( $img ) ) : ?>
																				data-image="<?php echo esc_url( $img ); ?>"
																			<?php endif; ?>
	                                                                       data-title="<?php echo esc_attr( $title ); ?>"
	                                                                       data-desc="<?php echo esc_attr( $desc ); ?>">
																			<?php echo esc_html( ! empty( $cf_content_section['name'] ) ? $cf_content_section['name'] : '' ); ?>
																		</a>
																	</div>
																</div>
															<?php endforeach;
														endif;
													endforeach;
												endif;

												?>

												<div class="transformBlock-post-item">
													<div class="splashFooter">
														<?php echo apply_filters( 'the_content', wp_kses_post( ! empty( $get_involved_content_widget_post ) ? $get_involved_content_widget_post->post_content : '' ) ); ?>
													</div>
												</div>

											</div>
										</div>
									</div>
									<?php internetorg_vip_powered_wpcom('pwdByVip-txt pwdByVip-txt_front'); ?>
								</div>
							</div>

							<div class="narrative">
								<div class="narrative-section">
									<div class="narrative-section-slides">
										<div
											class="narrative-section-slides-item" style="background-image: url('<?php echo esc_attr( $home_bg_url ); ?>')"></div>
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
											<button class="arrowCta arrowCta_light js-narrativeAdvance"></button>
										</div>
									</div>
								</div>

								<?php if ( ! empty( $custom_fields ) ) :

									foreach ( $custom_fields as $group ) :

										if ( ! empty( $group ) ) :

											foreach ( $group as $cf_content_section ) : ?>

												<?php $cf_content_section_slug = ( ! empty( $cf_content_section['slug'] ) ? $cf_content_section['slug'] : '' ); ?>

												<div class="narrative-section">
													<div class="narrative-section-slides narrative-section-slides_short">

														<?php if ( ! empty( $cf_content_section['image'] ) ) : ?>

															<div
																class="narrative-section-slides-item"
																style="background-image: url('<?php echo esc_url( internetorg_get_media_image_url( $cf_content_section['image'], 'panel-image' ) ); ?>')"
															>
																<div class="narrative-section-slides-item-inner"></div>
															</div>

														<?php endif; ?>

														<?php
														$data_img = '';
														if ( ! empty( $cf_content_section['call-to-action'] ) ) {
															foreach ( $cf_content_section['call-to-action'] as $cta ) {
																if ( ! empty( $cta['image'] ) ) {
																	if ( empty( $data_img ) ) {
																		$data_img = $cta['image'];
																	} ?>
																	<div
																		class="narrative-section-slides-item"
																		 style="background-image: url('<?php echo esc_url( internetorg_get_media_image_url( $cta['image'], 'panel-image' ) ); ?>')"
																	>

																		<?php if ( ! empty( $cta['link'] ) ) : ?>
																			<div class="narrative-section-slides-item-inner">
																				<?php

																				$js_class = 'js-stateLink';
																				if ( internetorg_is_video_url( $cta['link'] ) ) {
																					$js_class = 'js-videoModal';
																				}

																				$social_attr = 'false';

																				if ( 'page' === $cta['cta_src'] && ! empty( $cta['link_src'] ) && absint( $cta['link_src'] ) ) {
																					$url          = ( ! empty( $cta['link_src'] ) ? esc_url( get_the_permalink( $cta['link_src'] ) ) : '' );
																					$title        = ( ! empty( $cta['link_src'] ) ? esc_attr( get_the_title( $cta['link_src'] ) ) : '' );
																					$desc         = wp_kses_post( get_post_field( 'post_excerpt', $cta['link_src'] ) );
																					$img          = ( internetorg_get_media_image_url( get_post_thumbnail_id( $cta['link_src'] ), 'panel-image' ) )
																						? internetorg_get_media_image_url( get_post_thumbnail_id( $cta['link_src'] ), 'panel-image' )
																						: '';
																					$mobile_image = esc_url( internetorg_get_mobile_featured_image( get_post_type( $cta['link_src'] ), $cta['link_src'] ) );

																				} else {
																					$url          = ( ! empty( $cta['link'] ) ? esc_url( $cta['link'] ) : '' );
																					$title        = ( ! empty( $cta['title'] ) ? esc_attr( $cta['title'] ) : '' );
																					$desc         = ( ! empty( $cta['text'] ) ? esc_attr( strip_tags( nl2br( $cta['text'] ) ) ) : '' );
																					$img          = ( ! empty( $cf_content_section['call-to-action'][0] )
																						? internetorg_get_media_image_url( $cf_content_section['call-to-action'][0]['image'], 'panel-image' ) : '' );
																					$mobile_image = esc_url( ( ! empty( $cf_content_section['call-to-action'][0] )
																						? internetorg_get_media_image_url( $cf_content_section['call-to-action'][0]['image'], 'inline-image' ) : '' ) );
																				}

																				if ( ! empty( $cta['link_src'] ) && in_array( $cta['link_src'], internetorg_get_shadow_post_types_for_ajax( 'post' ) ) ) {
																					$social_attr = 'true';
																				}

																				$theme = ( ! empty( $cf_content_section['theme'] ) )
																					? $cf_content_section['theme']
																					: $cf_content_section_slug;
																				?>
																				<a href="<?php echo esc_url( apply_filters( 'iorg_url', $url ) ); ?>"
																				   class="tertiaryCta <?php echo esc_attr( $js_class ); ?>"
																					<?php if ( ! internetorg_is_video_url( $cta['link'] ) ) : ?>
																						data-type="titled"
																						data-social="<?php echo esc_attr( $social_attr ); ?>"
																						data-theme="<?php echo esc_attr( strtolower( $theme ) ); ?>"
																						data-title="<?php echo esc_attr( $title ); ?>"
																						data-desc="<?php echo esc_attr( $desc ); ?>"
																						<?php if ( is_string( $mobile_image ) ) : ?>
																							data-mobile-image="<?php echo esc_url( $mobile_image ); ?>"
																						<?php endif; ?>
																						<?php if ( is_string( $img ) ) : ?>
																							data-image="<?php echo esc_url( $img ); ?>"
																						<?php endif; ?>
																					<?php endif; ?>>

																					<?php echo esc_html( strip_tags( $cta['title'] ) ); ?>
																					<span class="circleBtn circleBtn_theme<?php echo esc_attr( ucwords( $theme ) ); ?><?php if ( internetorg_is_video_url( $cta['link'] ) ) : ?> circleBtn_play<?php endif; ?>"></span>
																				</a>
																			</div>
																		<?php endif; ?>
																	</div>
																	<?php
																}
															}
														}
														?>
													</div>
													<div class="narrative-section-bd">
														<div class="container container_wide">
															<div class="statementBlock">
																<div class="statementBlock-pre">
																	<h2 class="hdg hdg_heavy mix-hdg_theme<?php echo esc_attr( ucwords( $cf_content_section_slug ) ); ?> u-isHiddenMedium"><?php echo esc_html( isset( $cf_content_section['name'] ) ? $cf_content_section['name'] : '' ); ?></h2>
																</div>
																<div class="statementBlock-hd">
																	<h2 class="hdg hdg_1"><?php echo esc_html( isset( $cf_content_section['title'] ) ? $cf_content_section['title'] : '' ); ?></h2>
																</div>
																<div class="statementBlock-bd">
																	<p class="bdcpy bdcpy_narrative"><?php echo wp_kses_post( ! empty( $cf_content_section['content'] ) ? ltrim( rtrim( $cf_content_section['content'], '</p>' ), '<p>' ) : '' ); ?></p>
																</div>
															</div>
														</div>

														<?php
														if ( 'page' === $cf_content_section['src'] && ! empty( $cf_content_section['url-src'] ) && absint( $cf_content_section['url-src'] ) ) {
															$url          = esc_url( get_the_permalink( $cf_content_section['url-src'] ) );
															$title        = esc_attr( get_the_title( $cf_content_section['url-src'] ) );
															$desc         = wp_kses_post( get_post_field( 'post_excerpt', $cf_content_section['url-src'] ) );
															$img          = ( internetorg_get_media_image_url( get_post_thumbnail_id( $cf_content_section['url-src'] ), 'panel-image' ) ) ? internetorg_get_media_image_url( get_post_thumbnail_id( $cf_content_section['url-src'] ), 'panel-image' ) : '';
															$mobile_image = esc_url( internetorg_get_mobile_featured_image( get_post_type( $cf_content_section['url-src'] ), $cf_content_section['url-src'] ) );

														} else {
															$url          = '/' . esc_attr( $cf_content_section_slug );
															$title        = esc_attr( ! empty( $cf_content_section['name'] ) ? $cf_content_section['name'] : '' );
															$desc         = esc_attr( strip_tags( nl2br( ! empty( $cf_content_section['content'] ) ? $cf_content_section['content'] : '' ) ) );
															$img          = ( ! empty( $cf_content_section['call-to-action'][0] ) && ! empty( $cf_content_section['call-to-action'][0]['image'] )
																? internetorg_get_media_image_url( $cf_content_section['call-to-action'][0]['image'], 'panel-image' )
																: '' );
															$mobile_image = esc_url( ( ! empty( $cf_content_section['call-to-action'][0] ) && ! empty( $cf_content_section['call-to-action'][0]['image'] )
																? internetorg_get_media_image_url( $cf_content_section['call-to-action'][0]['image'], 'inline-image' )
																: '' ) );
														}

														$theme = ( ! empty( $cf_content_section['theme'] ) )
															? $cf_content_section['theme']
															: $cf_content_section_slug;
														?>
														<div class="narrative-section-bd-link u-isHiddenMedium">
															<a href="<?php echo esc_url( apply_filters( 'iorg_url', $url ) ); ?>"
															   class="circleBtn circleBtn_theme<?php echo esc_attr( ucwords( $theme ) ); ?> js-stateLink"
															   data-type="panel"
															   data-theme="<?php echo esc_attr( $theme ); ?>"
															   data-title="<?php echo esc_attr( $title ); ?>"
															   data-desc="<?php echo esc_attr( $desc ); ?>"
																<?php if ( is_string( $mobile_image ) ) : ?>
																	data-mobile-image="<?php echo esc_url( $mobile_image ); ?>"
																<?php endif; ?>
																<?php if ( is_string( $img ) ) : ?>
																	data-image="<?php echo esc_url( $img ); ?>"
																<?php endif; ?>
																>
																<?php echo esc_html( ! empty( $cf_content_section['name'] ) ? $cf_content_section['name'] : '' ); ?>
															</a>
														</div>
													</div>
												</div>

												<?php
											endforeach;
										endif;
									endforeach;
								endif; ?>

								<div class="narrative-section">
									<div class="narrative-section-slides">
										<div
											class="narrative-section-slides-item"
											 style="background-image: url('<?php echo esc_attr( ! empty( $get_involved_content_widget_image ) ? $get_involved_content_widget_image : '' ); ?>')"
											>
											<div class="statementBlock statementBlock_end">
												<div class="statementBlock-pre statementBlock-pre_divide">
													<span class="bdcpy bdcpy_narrative mix-bdcpy_splash"><?php echo esc_html( ! empty( $get_involved_content_widget_post ) ? $get_involved_content_widget_post->post_title : '' ); ?></span>
												</div>
												<div class="statementBlock-hd">
													<h2 class="hdg hdg_1 mix-hdg_splash"><?php echo wp_kses_post( internetorg_get_the_subtitle( get_the_ID() ) ); ?></h2>
												</div>
												<div class="statementBlock-bd">
													<div class="splashFooter">
														<?php echo apply_filters( 'the_content', wp_kses_post( ! empty( $get_involved_content_widget_post ) ? $get_involved_content_widget_post->post_content : '' ) ); ?>
													</div>
												</div>
											</div>
										</div>
									</div>

									<?php internetorg_vip_powered_wpcom('pwdByVip-txt pwdByVip-txt_front'); ?>

								</div>
							</div>

							<ul class="narrativeView-progress narrativeView-progress_isHidden">
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
					<div class="viewWindow-panel-content-inner" style="background-image: url('<?php echo esc_attr( $home_bg_url ); ?>');"></div>
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

get_footer();
