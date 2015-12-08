<?php
/**
 * The template part for displaying results in search pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Internet.org
 */

/**
 * Shadow post types for and including io_story post_type.
 *
 * @var array $io_story_shadow
 */
$io_story_shadow = internetorg_get_shadow_post_types_for_ajax( 'io_story' );

/**
 * Shadow post types for and including post post_type.
 *
 * @var array $post_shadow
 */
$post_shadow = internetorg_get_shadow_post_types_for_ajax( 'post' );

/**
 * Whether this is paneled or titled content to link to.
 *
 * @var string $type
 */
$type = ( in_array( get_post_type( get_the_ID() ), $io_story_shadow ) ) ? 'panel' : 'titled';

/**
 * Featured image URL for link data-image attribute.
 *
 * @var string $img
 */
$img = ( internetorg_get_media_image_url( get_post_thumbnail_id( get_the_ID() ), 'panel-image' ) )
	?  internetorg_get_media_image_url( get_post_thumbnail_id( get_the_ID() ), 'panel-image' )
	: '';

/**
 * Mobile featured image URL for link data-mobile-image attribute.
 *
 * @var string $mobile_image
 */
$mobile_image = esc_url( internetorg_get_mobile_featured_image( get_post_type( get_the_ID() ), get_the_ID() ) );

/**
 * Apply the "theme" for the data-theme attribute.
 *
 * @var string $theme
 */
$theme = ( in_array( get_post_type( get_the_ID() ), $io_story_shadow ) ) ? 'approach' : '';

?>

<div class="resultsList-list-item">
	<div class="feature feature_tight">
		<div class="feature-hd">
			<a href="<?php echo esc_url( internetorg_fix_link( get_the_permalink() ) ); ?>" class="mix-link_small js-stateLink"
			    data-title="<?php echo esc_attr( apply_filters( 'the_title',  get_the_title() ) ); ?>"
			    data-image="<?php echo esc_url( $img );?>"
			    data-mobile-image="<?php echo esc_url( $mobile_image );?>"
			    data-theme="<?php echo esc_attr( strtolower( $theme ) ); ?>"
					<?php if ( in_array( get_post_type( get_the_ID() ), $post_shadow ) ) : ?>
					data-date="<?php echo esc_attr( get_the_date( '', get_the_ID() ) ); ?>"
					data-social="true"
					<?php endif; ?>
			   data-type="<?php echo esc_attr( $type ); ?>">
				<h2 class="hdg hdg_4"><?php the_title(); ?></h2>
			</a>
		</div>
		<div class="feature-bd">
			<div class="bdcpy"><?php the_excerpt(); ?></div>
		</div>
		<div class="feature-cta">
			<a href="<?php echo esc_url( internetorg_fix_link( get_the_permalink() ) ); ?>" class="link mix-link_small js-stateLink"
			    data-title="<?php echo esc_attr( apply_filters( 'the_title',  get_the_title() ) ); ?>"
			    data-image="<?php echo esc_url( $img );?>"
			    data-mobile-image="<?php echo esc_url( $mobile_image );?>"
			    data-theme="<?php echo esc_attr( strtolower( $theme ) ); ?>"

				<?php if ( in_array( get_post_type( get_the_ID() ), $post_shadow ) ) : ?>
					data-date="<?php echo esc_attr( get_the_date( '', get_the_ID() ) ); ?>"
					data-social="true"
				<?php endif; ?>

			   data-type="<?php echo esc_attr( $type ); ?>">


				<?php esc_html_e( 'Read More', 'internetorg' ); ?>
			</a>
		</div>
	</div>
</div>
