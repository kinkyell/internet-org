<?php
/**
 * The template part for displaying the social block on contact page.
 *
 * @package Internet.org
 */

?>

<div class="socialBlock">
	<div class="socialBlock-inner">
		<div class="container">
			<div class="fbFollowBlock">
				<div class="fbFollowBlock-inner">
					<div class="fbFollowBlock-hd">
						<h2 class="hdg hdg_3 mix-hdg_blackThenWhite">
							<?php echo esc_html__( 'Follow the Project', 'internetorg' ); ?>
						</h2>
					</div>
					<div class="fbFollowBlock-bd">
						<p class="bdcpy mix-bdcpy_blackThenWhite">
							<?php echo esc_html__( 'Stay updated about Internet.org.', 'internetorg' ); ?>
						</p>
					</div>
				</div>
				<div class="fbFollowBlock-cta">
					<a href="<?php echo esc_attr__( 'https://fb.me/Internetdotorg', 'internetorg' ); ?>"
					   	class="btn btn_facebook" target="_blank">
					   	<span class="btn-icon"></span>
						<span class="btn-txt"><?php echo esc_html__( 'Like us on Facebook', 'internetorg' ); ?></span>
					</a>
				</div>
			</div>
		</div>
		<?php internetorg_vip_powered_wpcom('pwdByVip-txt pwdByVip-txt_contact'); ?></div>
	</div>
</div>
