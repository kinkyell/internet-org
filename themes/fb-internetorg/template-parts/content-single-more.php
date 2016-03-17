<?php
/**
 * Content Single More posts template part.
 *
 * @package Internet.org
 */

$next_post = get_next_post();
$prev_post = get_previous_post();

if ( empty( $next_post ) && empty( $prev_post ) ) {
	return;
}

?>

<div class="footBox-hd">
	<?php esc_html_e( 'More Posts', 'internetorg' ) ?>
</div>

<div class="vList vList_footBox">

	<?php if ( ! empty( $next_post ) ) : ?>
		<div>
			<div class="topicBlock">
				<div class="topicBlock-hd">
					<a class="js-stateLink" href="<?php echo esc_url( get_permalink( $next_post->ID ) ); ?>"
					   data-title="<?php echo esc_attr( apply_filters( 'the_title',  $next_post->post_title ) ); ?>"
					   data-date="<?php echo esc_attr( get_the_date( '', $next_post->ID ) ); ?>"
					   data-social="true"
					   data-type="titled">
						<h2 class="hdg hdg_8 mix-hdg_bold">
							<?php echo esc_html( apply_filters( 'the_title',  $next_post->post_title ) ); ?>
						</h2>
					</a>
				</div>
				<div class="topicBlock-bd">
					<p class="bdcpy">
						<?php echo wp_kses_post( $next_post->post_excerpt ); ?>
					</p>
				</div>
				<div class="topicBlock-cta">
					<a class="btn js-stateLink" href="<?php echo esc_url( get_permalink( $next_post->ID ) ); ?>"
					   data-title="<?php echo esc_attr( apply_filters( 'the_title',  $next_post->post_title ) ); ?>"
					   data-date="<?php echo esc_attr( get_the_date( '', $next_post->ID ) ); ?>"
					   data-social="true"
					   data-type="titled">
						<?php esc_html_e( 'Read', 'internetorg' ); ?>
					</a>
				</div>
			</div>
		</div>
	<?php endif; ?>

	<?php if ( ! empty( $prev_post ) ) : ?>
		<div>
			<div class="topicBlock">
				<div class="topicBlock-hd">
					<a class="js-stateLink"
					  href="<?php echo esc_url( get_permalink( $prev_post->ID ) ); ?>"
					  data-title="<?php echo  esc_attr( apply_filters( 'the_title', $prev_post->post_title ) ); ?>"
					  data-social="true"
					  data-date="<?php echo esc_attr( get_the_date( '', $prev_post->ID ) ); ?>"
					  data-type="titled">
						<h2 class="hdg hdg_8 mix-hdg_bold">
							<?php echo  esc_html( apply_filters( 'the_title', $prev_post->post_title ) ); ?>
						</h2>
					</a>
				</div>
				<div class="topicBlock-bd">
					<p class="bdcpy">
						<?php echo wp_kses_post( $prev_post->post_excerpt ); ?>
					</p>
				</div>
				<div class="topicBlock-cta">
					<a class="btn js-stateLink"
					   href="<?php echo esc_url( get_permalink( $prev_post->ID ) ); ?>"
					   data-title="<?php echo  esc_attr( apply_filters( 'the_title', $prev_post->post_title ) ); ?>"
					   data-social="true"
					   data-date="<?php echo esc_attr( get_the_date( '', $prev_post->ID ) ); ?>"
					   data-type="titled">
						<?php esc_html_e( 'Read', 'internetorg' ); ?>
					</a>
				</div>
			</div>
		</div>
	<?php endif; ?>

</div>