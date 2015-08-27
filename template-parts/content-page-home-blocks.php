<?php
/**
 * The template used for displaying content blocks on the home page
 *
 * @package Internet.org
 * @author arichard <arichard@nerdery.com>
 */

$custom_fields = get_post_meta( get_the_ID(), 'home-content-section', false );

if ( ! empty( $custom_fields ) ) : ?>
	<?php foreach ( $custom_fields as $group ) : ?>
		<?php if ( ! empty( $group ) ) : ?>
				<?php foreach ( $group as $fieldset ) : ?>
				<div class="narrative-section">
					<div class="narrative-section-slides">
						<?php if ( ! empty( $fieldset['image'] ) ) : ?>
							<div class="narrative-section-slides-item" style="background-image: url('<?php echo esc_attr( wp_get_attachment_url( $fieldset['image'], 'full' ) ); ?>')"></div>
						<?php endif; ?>
					</div>

					<div class="narrative-section-bd">
						<div class="narrative-section-bd-inner">
							<div class="transformBlock">
							<?php if ( ! empty( $fieldset['title'] ) ) : ?>
								<div class="transformBlock-hd">
									<div class="vr vr_x1">
										<h2 class="hdg hdg_1"><?php echo esc_html( $fieldset['title'] ); ?></h2>
									</div>
								</div>
							<?php endif; ?>

								<div class="transformBlock-bd">
									<?php if ( ! empty( $fieldset['content'] ) ) : ?>
									<div class="vr_x5">
										<p class="bdcpy bdcpy_lg"><?php echo esc_html( $fieldset['content'] ); ?></p>
									</div>
									<?php endif; ?>

									<?php if ( ! empty( $fieldset['call-to-action'] ) ) : ?>
										<div class="section-cta">
										<?php foreach ( $fieldset['call-to-action'] as $cta ) : ?>
											<div class="cta">
											<?php if ( ! empty( $cta['link'] ) ) : ?>
												<a href="<?php echo esc_attr( $cta['link'] ); ?>"><?php
												if ( ! empty( $cta['image'] ) ) : ?>
													<?php echo wp_get_attachment_image( $cta['image'], array( 32, 32 ) ); ?>
												<?php else : ?>
													<?php echo esc_html( $cta['link'] ); ?>
												<?php endif;
												?></a>
											<?php endif; ?>
											</div>
										<?php endforeach; ?>
										</div>
									<?php endif; ?>

									<?php
									$sectionHref = '#';
									if ( ! empty( $fieldset['slug'] ) ) {
										$sectionHref = '/' . strtolower( $fieldset['slug'] );
									}

									$sectionSlug = '';
									if ( ! empty( $fieldset['slug'] ) ) {
										$sectionSlug = ucwords( $fieldset['slug'] );
									}
									?>

									<a href="<?php echo esc_attr( $sectionHref ); ?>" class="link link_theme<?php echo esc_attr( $sectionSlug ); ?> js-stateLink"><?php echo esc_html( $fieldset['name'] ); ?>asdf</a>
								</div>
							</div>
						</div>
					</div>

				</div>


				<?php endforeach; ?>
		<?php endif; ?>
	<?php endforeach; ?>
<?php endif;
