<?php
/**
 * This is the temp/placeholder panel
 *
 * @package Internet.org
 * @author arichard <arichard@nerdery.com>
 */
?>

<div id="homePanel" class="viewWindow-panel">
	<div class="viewWindow-panel-content">
		<div class="viewWindow-panel-content-inner">

			<div class="introBlock introBlock_fill">
				<div class="introBlock-inner">
					<div class="container">
						<div class="topicBlock">
							<div class="topicBlock-hd topicBlock-hd_plus">
								<h2 class="hdg hdg_3 mix-hdg_bold">Example Intro Block</h2>
							</div>
							<div class="topicBlock-subHd">
								<div class="hdg hdg_5 mix-hdg_italic mix-hdg_gray"><?php internetorg_get_post_publish_time_string(); ?></div>
							</div>
							<div class="topicBlock-bd">
								<p class="bdcpy">Will need to replace this layout with the home page narrative one.</p>
							</div>
							<div class="topicBlock-cta"><a class="btn js-stateLink" href="/approach" data-type="panel" data-title="Our Approach" data-image="http://placehold.it/400x800?text=APPROACH" data-theme="Approach">Our Approach</a></div>
						</div>
					</div>
				</div>
				<div class="introBlock-ft introBlock-ft_rule">
					<ul class="socialParade">
						<li><a class="socialParade-icon socialParade-icon_fb" href=""><?php esc_html_e( 'Facebook', 'internetorg' ); ?></a></li>
						<li><a class="socialParade-icon socialParade-icon_tw" href=""><?php esc_html_e( 'Twitter', 'internetorg' ); ?></a></li>
						<li><a class="socialParade-icon socialParade-icon_li" href=""><?php esc_html_e( 'LinkedIn', 'internetorg' ); ?></a></li>
					</ul>
				</div>
			</div>

		</div>
	</div>
</div>