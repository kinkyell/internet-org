<?php
/**
 * This template part displays the next page footer block if
 *
 * @see     meta_key=next_page (on the page post type)
 *
 * @package Internet.org
 * @author  arichard <arichard@nerdery.com>
 */

/** @var int $next_page Post ID stored in the "next_page" meta field */
$next_page_id = absint( get_post_meta( get_the_ID(), 'next_page', true ) );

if ( empty( $next_page_id ) ) {
	return;
}

?>

<div class="introBlock introBlock_foot js-scrollImage" data-image="<?php echo esc_url( internetorg_get_post_thumbnail( $next_page_id ) ); ?>">
	<div class="introBlock-inner">
		<div class="topicBlock">
			<div class="topicBlock-subHd">
				<div class="hdg hdg_5 mix-hdg_italic mix-hdg_gray"><?php esc_html_e( 'Learn About', 'internetorg' ); ?></div>
			</div>
			<div class="topicBlock-hd topicBlock-hd_plus topicBlock-hd_theme<?php echo esc_attr( ucwords( basename( get_permalink( $next_page_id ) ) ) ); ?>">
				<h2 class="hdg hdg_2 hdg-mix_bold"><?php echo esc_html( get_the_title( $next_page_id ) ); ?></h2>
			</div>
		</div>
	</div>
	<div class="introBlock-ft">
		<a href="<?php echo esc_url( get_permalink( $next_page_id ) ); ?>"
		   class="arrowCta js-stateSwap"
		   data-title="<?php echo esc_attr( get_the_title( $next_page_id ) ); ?>"
		   data-image="<?php echo esc_url( internetorg_get_post_thumbnail( $next_page_id ) ); ?>"
		   data-theme="<?php echo esc_attr( ucwords( basename( get_permalink( $next_page_id ) ) ) ); ?>"></a>
	</div>
</div>