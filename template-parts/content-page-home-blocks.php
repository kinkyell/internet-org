<?php
/**
 * The template used for displaying content blocks on the home page
 *
 * @package Internet.org
 * @author arichard <arichard@nerdery.com>
 */

$custom_fields = get_post_meta( get_the_ID(), 'home-content-section', false );

?>

<?php if ( ! empty( $custom_fields ) ) : ?>
	<div class="home-content-blocks">
	<?php foreach ( $custom_fields as $group ) : ?>
		<?php if ( ! empty( $group ) ) : ?>
			<div class="home-content-sectoin">
			<?php foreach ( $group as $fieldset ) : ?>
				<?php if ( ! empty( $fieldset['title'] ) ) : ?>
					<div class="section-title"><?php echo __( $fieldset['title'], 'internet_org' ); ?></div>
				<?php endif; ?>
				<?php if ( ! empty( $fieldset['content'] ) ) : ?>
					<div class="section-title"><?php echo __( $fieldset['content'], 'internet_org' ); ?></div>
				<?php endif; ?>
				<?php if ( ! empty( $fieldset['call-to-action'] ) ) : ?>
					<div class="section-cta">
					<?php foreach ( $fieldset['call-to-action'] as $cta ) : ?>
						<div class="cta">
						<?php if ( ! empty( $cta['link'] ) ) : ?>
							<a href="<?php echo esc_attr__( $cta['link'], 'internet_org' ); ?>">
								<?php if ( ! empty( $cta['image'] ) ) : ?>
									<?php echo wp_get_attachment_image( $cta['image'], 'full' ); ?>
								<?php else : ?>
									<?php echo __( $cta['link'], 'internet_org' ); ?>
								<?php endif; ?>
							</a>
						<?php endif; ?>
						</div>
					<?php endforeach; ?>
					</div>
				<?php endif; ?>
			<?php endforeach; ?>
			</div>
		<?php endif; ?>
	<?php endforeach; ?>
	</div>
<?php endif; ?>