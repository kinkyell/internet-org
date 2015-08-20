<?php
/**
 * The template for displaying the mission page
 *
 * Template Name: Mission
 *
 * @todo: Discuss with FED what class to apply on WYSIWYG areas
 * @todo: How to determine if this is being ajaxed in so we don't output header/footer/etc., and is that allowed on VIP?
 * @todo: How to handle fields that weren't intended for use on this template
 *
 * @package Internet.org
 */

get_header();

?>

<div class="viewWindow isShifted js-stateDefault">
<?php while ( have_posts() ) : the_post(); ?>
        <div class="viewWindow-panel">
            <div class="viewWindow-panel-content">
                <div class="viewWindow-panel-content-inner">

                        <div class="introBlock introBlock_fill">
                            <div class="introBlock-inner">
                                <div class="container">
                                    <div class="topicBlock">
                                        <div class="topicBlock-hd topicBlock-hd_plus">
                                            <h2 class="hdg hdg_3 mix-hdg_bold">Example Intro Block</h2>
                                        </div>
                                        <div class="topicBlock-subHd"><div class="hdg hdg_5 mix-hdg_italic mix-hdg_gray">May 25, 2015</div></div>
                                        <div class="topicBlock-bd">
                                           <p class="bdcpy">Will need to replace this layout with the home page narrative one.</p>
                                       </div>
                                        <div class="topicBlock-cta"><a class="btn js-stateLink" href="/approach" data-type="panel" data-title="Our Approach" data-image="http://placehold.it/400x800?text=APPROACH" data-theme="Approach">Our Approach</a></div>
                                    </div>
                                </div>
                            </div>
                            <div class="introBlock-ft introBlock-ft_rule">
                                <ul class="socialParade">
                                    <li><a class="socialParade-icon socialParade-icon_fb" href="">Facebook</a></li>
                                    <li><a class="socialParade-icon socialParade-icon_tw" href="">Twitter</a></li>
                                    <li><a class="socialParade-icon socialParade-icon_li" href="">LinkedIn</a></li>
                                </ul>
                            </div>
                        </div>

                </div>
            </div>
        </div>


        <div class="viewWindow-panel viewWindow-panel_feature">
            <div class="viewWindow-panel-content">
                <a href="" class="contentOnMedia-link contentOnMedia-link_ct">
                    <span class="circleBtn circleBtn_play"></span>
                </a>
                <div class="viewWindow-panel-content-inner" style="background-image: url(http://placehold.it/400x800?text=CAMPAIGN);"></div>
            </div>
        </div>


        <div class="viewWindow-panel viewWindow-panel_story isActive">
            <div class="viewWindow-panel-content">
                <div class="viewWindow-panel-content-inner">

                    <div class="theme-impact">
                        <div class="introBlock">
                            <div class="introBlock-inner">
                                <div class="container">
                                    <div class="topicBlock">
                                        <div class="topicBlock-hd">
                                            <h2 class="hdg hdg_2"><?php the_title(); ?></h2>
                                        </div>
                                        <div class="topicBlock-bd">
                                            <p class="bdcpy"><?php /* sub title custom field */ ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div>
                            <div class="contentCol">
                                <div class="container quarantine wysiwyg">
                                    <?php the_content(); ?>
                                </div>
                            </div>
                            <div class="contentCol contentCol_flush">
                                <div class="container">
                                    <?php /* insert gallery here */ ?>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="footBox">
                        <div class="container">
                            <div class="topicBlock">
                                <?php $involved = internetorg_get_content_widget_by_slug( 'get-involved' ); ?>

                                <?php if ( ! empty( $involved ) || ( isset( $involved['post'] ) && empty( $involved['post'] ) ) ) :
                                    $meta = ( ! empty( $involved['meta'] ) ? $involved['meta'] : null );
							        $post = $involved['post'];
							    ?>
                                <div class="topicBlock-hd">
                                    <h2 class="hdg hdg_3"><?php echo esc_html( $post->post_title ); ?></h2>
                                </div>
                                <div class="topicBlock-bd">
                                    <p class="bdcpy"><?php echo $post->post_content; ?></p>

								<?php if ( ! empty( $meta ) ) : ?>
								<?php
									$label = ( ! empty( $meta['widget-data']['label'] ) ? $meta['widget-data']['label'] : '' );
									$url   = ( ! empty( $meta['widget-data']['url'] )   ? $meta['widget-data']['url'] : '' );
									$file  = ( ! empty( $meta['widget-data']['image'] ) ? $meta['widget-data']['image'] : '' );

									$link = $url ? $url : $file;
								?>
                                    <?php if ( ! empty( $link ) ) : ?>
                                            <div class="topicBlock-cta"><a href="<?php echo esc_url( ! empty( $link ) ? $link : '' ); ?>" class="btn"><?php echo esc_html( $label ); ?></a></div>
                                    <?php endif; ?>
								<?php endif; ?>
								</div>

                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>



<?php endwhile; // End of the loop. ?>
</div>

<?php get_footer();
