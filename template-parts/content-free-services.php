<?php
/**
 * This will display a list of free services, by default all are displayed
 *
 * @package Internet.org
 * @author  arichard <arichard@nerdery.com>
 */

$services = internetorg_get_free_services();

if ( empty( $services ) ) {
	return;
}

?>

<div class="contentCol">
	<div class="container">

		<div class="feature">
			<div class="feature-hd">
				<h2 class="hdg hdg_3"><?php esc_html_e( 'Full List of Free Services', 'internetorg' ); ?></h2>
			</div>
			<div class="feature-bd">
				<ul class="servicesList">
					<?php foreach ( $services as $service ) : ?>
						<li>
							<?php if ( ! empty( $service['service_link'] ) ) : ?>
							<a href="<?php echo esc_url( $service['service_link'] ); ?>" target="_blank">
								<?php endif; ?>
								<div class="servicesList-item">
									<?php if ( ! empty( $service['image'] ) ) : ?>
										<div class="servicesList-item-icon">
											<img src="<?php echo esc_url( $service['image'] ); ?>" alt="<?php echo esc_attr( $service['title'] ); ?>">
										</div>
									<?php endif; ?>
									<div class="servicesList-item-bd">
										<div class="hdg hdg_5"><?php echo esc_html( $service['title'] ); ?></div>
										<div class="bdcpy bdcpy_sm">
											<?php if ( ! empty( $service['excerpt'] ) ) : ?>
												<?php echo esc_html( $service['excerpt'] ); ?>
											<?php endif; ?>
										</div>
									</div>
								</div>
								<?php if ( ! empty( $service['service_link'] ) ) : ?>
							</a>
						<?php endif; ?>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
		</div>

	</div>
</div> <!-- end contentCol -->
