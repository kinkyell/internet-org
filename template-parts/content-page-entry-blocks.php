<?php
/**
 * The template used for displaying content blocks on the home page.
 *
 * @package Internet.org
 * @author  arichard <arichard@nerdery.com>
 */

$custom_fields = get_post_meta( get_the_ID(), 'home-content-section', false );

if ( ! empty( $custom_fields ) ) : ?>
	<?php foreach ( $custom_fields as $group ) : ?>
		<?php if ( ! empty( $group ) ) : ?>
			<div class="narrative-section">
				<div class="narrative-section-bd">
					<?php foreach ( $group as $fieldset ) : ?>
						<?php if ( ! empty( $fieldset['title'] ) ) : ?>
							<div class="section-title">
								<h2><?php echo esc_html( $fieldset['title'] ); ?></h2>
							</div>
						<?php endif; ?>
						<?php if ( ! empty( $fieldset['content'] ) ) : ?>
							<div class="section-title"><?php echo esc_html( $fieldset['content'] ); ?></div>
						<?php endif; ?>
						<?php if ( ! empty( $fieldset['call-to-action'] ) ) : ?>
							<div class="section-cta">
								<?php foreach ( $fieldset['call-to-action'] as $cta ) : ?>
									<div class="cta">
										<?php if ( ! empty( $cta['link'] ) ) : ?>
											<a href="<?php echo fix_link( esc_url( apply_filters( 'iorg_url', $cta['link'] ) ) ); ?>">
												<?php if ( ! empty( $cta['image'] ) ) : ?>
													<?php echo wp_get_attachment_image( $cta['image'], 'full' ); ?>
												<?php else : ?>
													<?php echo fix_link( esc_url( $cta['link'] ) ); ?>
												<?php endif; ?>
											</a>
										<?php endif; ?>
									</div>
								<?php endforeach; ?>
							</div>
						<?php endif; ?>
					<?php endforeach; ?>
				</div>
			</div>
		<?php endif; ?>
	<?php endforeach; ?>
<?php endif;
