<?php
/**
 * This will display a list of free services, by default all are displayed
 *
 * @package Internet.org
 * @author arichard <arichard@nerdery.com>
 */

$services = internetorg_get_free_services();
?>

<?php if ( count( $services )  ) : ?>
	<div class="feature">
		<div class="feature-hd">
			<h2 class="hdg hdg_2"><?php __( 'Full List of Free Services', 'internetorg' ); ?></h2>
		</div>
		<div class="feature-bd">
			<ul class="servicesList">
	<?php foreach  ( $services as $service ) : ?>
		<li>
			<div class="servicesList-item">
				<?php if ( ! empty( $service['image'] ) ) : ?>
				<div class="servicesList-item-icon">
					<img src="<?php echo esc_attr( $service['image'] ); ?>" alt="<?php esc_attr_e( $service['title'], 'internetorg' ); ?>">
				</div>
				<?php endif; ?>
				<div class="servicesList-item-bd">
					<div class="hdg hdg_4"><?php esc_html_e( $service['title'], 'internetorg' ); ?></div>
					<div class="bdcpy bdcpy_sm">
						<?php if ( ! empty( $service['excerpt'] ) ) : ?>
							<?php esc_html_e( $service['excerpt'] ); ?>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</li>
	<?php endforeach; ?>
			</ul>
		</div>
	</div>
<?php endif;


