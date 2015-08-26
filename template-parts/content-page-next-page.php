<?php
/**
 * This template part displays the next page footer block if
 *
 * @see meta_key=next_page (on the page post type)
 *
 * @package Internet.org
 * @author arichard <arichard@nerdery.com>
 */

// get the "next page" meta field
$next_page = get_post_meta( get_the_ID(), 'next_page', true );
$next_page_obj = false;

if ( ! empty( $next_page ) ) {
	$next_page_obj = get_post( $next_page );
}

if ( ! empty( $next_page_obj ) ) : ?>

	<?php $thumbnail = internetorg_get_post_thumbnail( $next_page_obj->ID ); ?>

<div class="introBlock introBlock_foot js-scrollImage" data-image="<?php echo esc_url( $thumbnail ); ?>">
	<div class="introBlock-inner">
		<div class="topicBlock">
			<div class="topicBlock-subHd">
				<div class="hdg hdg_5 mix-hdg_italic mix-hdg_gray"><?php esc_html_e( 'Learn About', 'internetorg' ); ?></div>
			</div>
			<div class="topicBlock-hd topicBlock-hd_plus topicBlock-hd_theme<?php echo esc_attr( ucwords( $next_page_obj->post_name ) ); ?>">
				<h2 class="hdg hdg_2 hdg-mix_bold"><?php echo esc_html( $next_page_obj->post_title ); ?></h2>
			</div>
		</div>
	</div>
	<div class="introBlock-ft">
		<a href="/<?php echo esc_attr( $next_page_obj->post_name ); ?>"
		   class="arrowCta js-stateSwap"
		   data-title="<?php echo esc_attr( $next_page_obj->post_title ); ?>"
		   data-image="<?php echo esc_url( $thumbnail ); ?>"
		   data-theme="<?php echo esc_attr( ucwords( $next_page_obj->post_name ) ); ?>"></a>
	</div>
</div>

<?php endif; ?>