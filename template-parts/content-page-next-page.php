<?php
/**
 * Content Page Next Page template part.
 *
 * This template part displays the next page footer block if meta_key=next_page (on the page post type).
 *
 * @package Internet.org
 * @author  arichard <arichard@nerdery.com>
 */

/**
 * Post ID stored in the "next_page" meta field.
 *
 * @var int $next_page
 */
$next_page_id = absint( get_post_meta( get_the_ID(), 'next_page', true ) );

if ( empty( $next_page_id ) ) {
	return;
}

?>

<div class="js-scrollImage" data-image="<?php echo esc_url( internetorg_get_post_thumbnail( $next_page_id, 'panel-image' ) ); ?>">
	<a href="<?php echo esc_url( get_permalink( $next_page_id ) ); ?>"
		class="introBlock introBlock_foot js-stateSwap"
		data-title="<?php echo esc_attr( get_the_title( $next_page_id ) ); ?>"
		data-image="<?php echo esc_url( internetorg_get_post_thumbnail( $next_page_id, 'panel-image' ) ); ?>"
	    data-mobile-image="<?php echo esc_url( internetorg_get_mobile_featured_image( get_post_type( $next_page_id ), $next_page_id ) ); ?>"
		data-theme="<?php echo esc_attr( ucwords( basename( get_permalink( $next_page_id ) ) ) ); ?>">
		<div class="introBlock-inner">
			<div class="topicBlock">
				<div class="topicBlock-subHd">
					<div class="hdg hdg_5 mix-hdg_italic mix-hdg_gray mix-hdg_light"><?php esc_html_e( 'Learn About', 'internetorg' ); ?></div>
				</div>
				<div class="topicBlock-hd topicBlock-hd_plus topicBlock-hd_theme<?php echo esc_attr( ucwords( basename( get_permalink( $next_page_id ) ) ) ); ?>">
					<h2 class="hdg hdg_2 hdg-mix_bold"><?php echo esc_html( get_the_title( $next_page_id ) ); ?></h2>
				</div>
			</div>
		</div>
		<div class="introBlock-ft">
			<?php
				$user_agent = isset( $_SERVER['HTTP_USER_AGENT'] ) ? $_SERVER['HTTP_USER_AGENT'] : '';
				if ( stristr( $user_agent, 'Opera Mini' ) ) { 
					if( esc_html( get_the_title( $next_page_id ) ) == 'Our Impact') {
						?> <span class="link impact"></span> <?php
					} else {
						if( esc_html( get_the_title( $next_page_id ) ) == 'Our Approach'){
							?> <span class="link approach"></span> <?php
						} else {
							if( esc_html( get_the_title( $next_page_id ) ) == 'Our Mission') {
								?> <span class="link mission"></span> <?php
							}
						}
					}
					?>
				<?php
				} else { ?>
					<span class="arrowCta <?php echo esc_html( get_the_title( $next_page_id ) ); ?>"></span>
				<?php
				}
			?>
		</div>
	</a>
</div>
