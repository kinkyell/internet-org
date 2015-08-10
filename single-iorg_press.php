<?php
/**
 * This displays a single press article
 *
 * @package
 * @author arichard <arichard@nerdery.com>
 */

?>

<div class="viewWindow-panel viewWindow-panel_feature">
	<div class="viewWindow-panel-content">
		<div class="viewWindow-panel-content-inner">
			<div class="introBlock introBlock_fill">
				<div class="introBlock-inner">
					<div class="container">
						<div class="topicBlock">
							<div class="topicBlock-hd topicBlock-hd_plus">
								<h2 class="hdg hdg_2 mix-hdg_bold"><?php echo esc_html__( get_the_title(), 'internetorg' ); ?></h2>
							</div>
							<div class="topicBlock-subHd">
								<div class="hdg hdg_6 mix-hdg_italic mix-hdg_gray"><?php internetorg_posted_on(); ?></div>
							</div>
							<div class="topicBlock-bd">
								<p class="bdcpy">
									<?php

									/**
									 * @todo fix escaping of this output
									 */
									echo __( get_the_content(), 'internetorg' ); ?>
								</p>
							</div>
						</div>
					</div>
				</div>
				<div class="introBlock-ft introBlock-ft_rule">
					<ul class="socialParade">
						<li><a class="socialParade-icon socialParade-icon_fb" href=""><?php echo esc_html__( 'Facebook', 'internetorg' ); ?></a></li>
						<li><a class="socialParade-icon socialParade-icon_tw" href=""><?php echo esc_html__( 'Twitter', 'internetorg' ); ?></a></li>
						<li><a class="socialParade-icon socialParade-icon_li" href=""><?php echo esc_html__( 'LinkedIn', 'internetorg' ); ?></a></li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>


<div class="viewWindow-panel viewWindow-panel_story">
	<div class="viewWindow-panel-content">
		<div class="viewWindow-panel-content-inner">
			<?php /* Article Content */ ?>
			<?php the_content(); ?>

			<?php /* Free Services */ ?>
			<div class="contentCol">
				<div class="container">
					<div class="feature">
						<div class="feature-hd">
							<h2 class="hdg hdg_3"><?php esc_html__( 'Full List of Free Services', 'internetorg' ); ?></h2>
						</div>
							<div class="feature-bd">
								<ul class="servicesList">
								<?php $freeServices = internetorg_get_free_services(); ?>
								<?php if ( ! empty( $freeServices )  ) : ?>
									<?php foreach ( $freeServices as $service ) : ?>
										<li>
											<a href="">
												<div class="servicesList-item">
													<?php if ( ! empty( $service['image'] ) ) : ?>
													<div class="servicesList-item-icon">
														<img src="<?php echo esc_attr( $service['image'] ); ?>" alt="<?php echo esc_attr__( $service['title'], 'internetorg' ); ?>" />
													</div>
													<?php endif; ?>
													<div class="servicesList-item-bd">
														<div class="hdg hdg_5"><?php echo esc_html__( $service['title'], 'internetorg' ); ?></div>
														<div class="bdcpy bdcpy_sm"><?php echo esc_html__( $service['excerpt'], 'internetorg' ); ?></div>
													</div>
												</div>
											</a>
										</li>
									<?php endforeach; ?>
								<?php endif; ?>
							</ul>
						</div>
					</div>
				</div>
			</div>

			<?php /* Related Articles */ ?>
			<div class="footBox">
				<div class="container">
					<div class="footBox-hd">More Posts</div>
					<div class="vList vList_footBox">
						<div>
							<div class="topicBlock">
								<div class="topicBlock-hd">
									<h2 class="hdg hdg_8 mix-hdg_bold">Max Three Lines Article Title on Mobile Ipsum</h2>
								</div>
								<div class="topicBlock-bd">
									<p class="bdcpy">
									Fast facts about Internet.org and free basic services lorem ipsum dolor sit amet, consectetur adipiscing elit.
									</p>
								</div>
								<div class="topicBlock-cta"><a href="" class="btn">Read</a></div>
							</div>
						</div>
						<div>
							<div class="topicBlock">
								<div class="topicBlock-hd">
									<h2 class="hdg hdg_8 mix-hdg_bold">Article Title</h2>
								</div>
								<div class="topicBlock-bd"><p class="bdcpy">Example of very short copy.</p></div>
								<div class="topicBlock-cta"><a href="" class="btn">Read</a></div>
							</div>
						</div>
					</div>
				</div>
			</div>


		</div>
	</div>
</div>

