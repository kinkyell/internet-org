<?php
/**
 * This is the custom template for the press listing page
 *
 * @package
 * @author arichard <arichard@nerdery.com>
 */

get_header();

?>

<div class="viewWindow-panel viewWindow-panel_feature">
	<div class="viewWindow-panel-content">
		<div class="viewWindow-panel-content-inner">
			<div class="introBlock introBlock_fill">
				<div class="introBlock-inner">
					<div class="topicBlock">
						<div class="topicBlock-hd topicBlock-hd_plus">
							<h2 class="hdg hdg_2 mix-hdg_bold"><?php echo esc_html__( 'Press', 'internet_org' ); ?></h2>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


<div class="viewWindow-panel viewWindow-panel_story">
	<div class="viewWindow-panel-content">
		<div class="viewWindow-panel-content-inner">
			<div>
<?php

if ( have_posts() ) :
	while ( have_posts() ) : the_post();

		the_title();

		echo '<div class="press-content">';
		the_content();
		echo '</div>';

	endwhile;
endif;
?>

				<?php
				/*  content widgets */
				$contentWidgets = array(
					'contact'   => iorg_get_content_widget_by_slug( 'contact' ),
					'media-kit' => iorg_get_content_widget_by_slug( 'media-kit' ),
				);
				?>

				<div class="footBox">
					<div class="container">
						<div class="vList vList_footBox">

				<?php foreach ( $contentWidgets as $key => $widget ) : ?>
					<?php if ( ! empty( $widget ) ) :
						$meta = ( ! empty( $widget['meta'] ) ? $widget['meta'] : null );
						$post = ( ! empty( $widget['post'] ) ? $widget['post'] : null );

						// if we don't have post data skip everything else
						if ( empty( $post ) ) {
							continue;
						}
						?>

							<div>
								<div class="topicBlock">
									<div class="topicBlock-hd">
										<h2 class="hdg hdg_8 mix-hdg_bold"><?php echo esc_html__( $post->post_title, 'internet_org' ); ?></h2>
									</div>
									<div class="topicBlock-bd"><p class="bdcpy"><?php echo esc_html__( $post->post_content, 'internet_org' ); ?></p></div>

							<?php

							if ( ! empty( $meta ) ) :
								$label = ( ! empty( $meta['widget-data']['label'] ) ? __( $meta['widget-data']['label'], 'internet_org' ) : '' );
								$url   = ( ! empty( $meta['widget-data']['url'] ) ? __( $meta['widget-data']['url'], 'internet_org' ) : '' );
								$file  = ( ! empty( $meta['widget-data']['image'] ) ? __( $meta['widget-data']['image'], 'internet_org' ) : '' );

								$link = $url ? $url : $file;
								?>
									<div class="topicBlock-cta"><a href="<?php echo esc_attr( ! empty( $link ) ? $link : '' ); ?>" class="btn"><?php echo esc_html( $label ); ?></a></div>
							<?php endif; ?>
								</div>
							</div>
					<?php endif; ?>
				<?php endforeach; ?>
						</div>
					</div>
				</div>


			</div>
		</div>
	</div>
</div>

<?php
// now that we have the post listing out of the way we'll display the widgets

get_footer();