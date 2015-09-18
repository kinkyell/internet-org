<?php
/**
 * The template part for displaying results in search pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Internet.org
 */

global $post;
$type = ( get_post_type( get_the_ID() ) === 'io_story') ? 'panel' : 'titled';

$img = ( internetorg_get_media_image_url( get_post_thumbnail_id( get_the_ID() ), 'panel-image' ) )
	?  internetorg_get_media_image_url( get_post_thumbnail_id( get_the_ID() ), 'panel-image' )
	: '';
$mobile_image = esc_url( internetorg_get_mobile_featured_image( get_post_type( get_the_ID() ), get_the_ID() ) );
$theme = ( get_post_type( get_the_ID() ) === 'io_story') ? 'approach' : '';
?>

<div class="resultsList-list-item">
	<div class="feature feature_tight">
		<div class="feature-hd">
			<h2 class="hdg hdg_4"><?php the_title(); ?></h2>
		</div>
		<div class="feature-bd">
			<div class="bdcpy"><?php the_excerpt(); ?></div>
		</div>
		<div class="feature-cta">
			<a href="<?php the_permalink(); ?>" class="link mix-link_small js-stateLink"
			    data-title="<?php echo esc_attr( apply_filters( 'the_title',  $post->post_title ) ); ?>"
			    data-desc="<?php echo wp_kses_post( get_post_field( 'post_excerpt', get_the_ID() ) ); ?>"
			    data-image="<?php echo esc_url( $img );?>"
			    data-mobile-image="<?php echo esc_url( $mobile_image );?>"
			    data-theme="<?php echo esc_attr( strtolower( $theme ) ); ?>"
			    <?php if ( $post->post_type === 'post' ) { ?>
				    data-date="<?php echo esc_attr( get_the_date( '', $post->ID ) ); ?>"
				    data-social="true"
			    <?php } ?>
			   data-type="<?php echo esc_attr( $type ); ?>">
				<?php esc_html_e( 'Read More', 'internetorg' ); ?>
			</a>
		</div>
	</div>
</div>
