<?php
/**
 * The template used for displaying page content start/header in front-page.php
 *
 * @package Internet.org
 * @author arichard <arichard@nerdery.com>
 */

$after_title_custom_fields = get_post_meta( get_the_ID(), 'after_title_fm_fields', true );

$subtitle = '';
if ( ! empty( $after_title_custom_fields['Subtitle'] ) ) {
	$subtitle = $after_title_custom_fields['Subtitle'];
}

?>

		<div class="narrative js-narrativeView">
			<div class="narrative-section">
				<?php if ( has_post_thumbnail() ) : ?>
				<div class="narrative-section-slides">
					<div class="narrative-section-slides-item" style="background-image: url('<?php echo esc_attr( wp_get_attachment_url( get_post_thumbnail_id() ) ); ?>')"></div>
				</div>
				<?php endif; ?>
				<div class="narrative-section-bd">
					<div class="narrative-section-bd-inner">
						<div class="transformBlock">
							<div class="transformBlock-pre">
								<?php the_title( '<span>', '</span>' ); ?>
							</div>
							<div class="transformBlock-hd">
								<h2 class="hdg hdg_1"><?php echo esc_html__( $subtitle, 'internetorg' ); ?></h2>
							</div>
						</div>
					</div>
				</div>
			</div>
