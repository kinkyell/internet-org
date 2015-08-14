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
							<h2 class="hdg hdg_2 mix-hdg_bold"><?php esc_html_e( 'Press', 'internetorg' ); ?></h2>
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
<?php if ( have_posts() ) : ?>

	<div class="contentCol">
		<div class="container">
			<div class="resultsList">
				<div id="addl-results" class="resultsList-list">
					<div id="addl-results" class="resultsList-list">
	<?php while ( have_posts() ) : the_post(); ?>
						<div class="resultsList-list-item">

							<?php

							$is_media = has_post_thumbnail();

							$type = $is_media ? 'media' : 'feature';

							?>

								<?php if ( $is_media ) : ?>
								<div class="media media_inline">
									<div class="media-figure">
										<?php the_post_thumbnail( array( 210, 260 ), array( 'title' => get_the_title() ) ); ?>
									</div>
									<div class="media-bd">
								<?php endif; ?>





									<div class="feature feature_tight">

										<div class="feature-hd">
											<h2 class="hdg hdg_3"><?php echo esc_html( get_the_title() ); ?></h2>
										</div>
										<div class="feature-date">
											<div class="hdg hdg_6 mix-hdg_italic mix-hdg_gray"><?php internetorg_posted_on_date(); ?></div>
										</div>
										<div class="feature-bd">
											<p class="bdcpy"><?php echo esc_html( get_the_excerpt() ); ?></p>
										</div>
										<div class="feature-cta">
											<a href="<?php the_permalink(); ?>" class="link link_sm" title="<?php the_title_attribute(); ?>"><?php esc_html__( 'Read More', 'internetorg' ) ?></a>
										</div>
									</div>




								<?php if ( $is_media ) : ?>
									</div>
								</div>
								<?php endif; ?>

							</div>
						</div>
	<?php endwhile; ?>
					</div>
					<div class="resultsList-ft">
						<div class="resultsList-list resultsList-list_spread">
							<div class="resultsList-list-item">
								<button type="button" class="btn js-ShowMoreView" data-src="press" data-target="addl-results"><?php esc_html_e( 'Show More', 'internetorg' ); ?></button>
							</div>
							<div class="resultsList-list-item">
								<select class="js-select select_inline">
								<?php
								// display a select list of archive years
								$args = array(
									'type'            => 'yearly',
									'limit'           => '',
									'format'          => 'option',
									'before'          => '',
									'after'           => '',
									'show_post_count' => 0,
									'echo'            => 1,
									'order'           => 'DESC',
								);

								wp_get_archives( $args );

								?>
								</select>

							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

<?php endif; ?>

				<?php
				/*  content widgets */
				$contentWidgets = array(
					'contact'   => internetorg_get_content_widget_by_slug( 'contact' ),
					'media-kit' => internetorg_get_content_widget_by_slug( 'media-kit' ),
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
										<h2 class="hdg hdg_8 mix-hdg_bold"><?php echo esc_html( $post->post_title ); ?></h2>
									</div>
									<div class="topicBlock-bd"><p class="bdcpy"><?php echo esc_html( $post->post_content ); ?></p></div>

							<?php

							if ( ! empty( $meta ) ) :
								$label = ( ! empty( $meta['widget-data']['label'] ) ? $meta['widget-data']['label'] : '' );
								$url   = ( ! empty( $meta['widget-data']['url'] ) ? $meta['widget-data']['url'] : '' );
								$file  = ( ! empty( $meta['widget-data']['image'] ) ? $meta['widget-data']['image'] : '' );

								$link = $url ? $url : $file;
								?>
									<div class="topicBlock-cta"><a href="<?php echo esc_url( ! empty( $link ) ? $link : '' ); ?>" class="btn"><?php echo esc_html( $label ); ?></a></div>
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
