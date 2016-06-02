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
 * Featured image URL for link data-image attribute.
 *
 * @var string $img
 */
$img = ( internetorg_get_media_image_url( get_post_thumbnail_id( get_the_ID() ), 'panel-image' ) )
	?  internetorg_get_media_image_url( get_post_thumbnail_id( get_the_ID() ), 'panel-image' )
	: '';

/**
 * If the post story is io_story but does not have an image, the current single will show
 * standard home image so we will have to do the same here.
 */
if ( empty( $img ) && get_post_type( get_the_ID() ) == 'io_story' ) {
	$img = get_stylesheet_directory_uri() . '/_static/web/assets/media/uploads/home.jpg';
}

/**
 * If a fearured image is found then we need to override type to panel
 */
$type = 'titled';
if ( $img ) {
	$type = 'panel';
}

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


$showDate = get_post_custom_values('iorg_display_date', get_the_ID()); 
 $displayDate = "show";
 $displayFooterPosts = "show";

if(is_array($showDate) && strtolower($showDate[0])=="n") {
 	$displayDate = "hide";

 } else {
 	$displayDate = "show";

 }

$showMedia = get_post_custom_values('iorg_hero_vdo_url', get_the_ID()); 
$showImage = get_post_custom_values('iorg_hero_image', get_the_ID()); 
$showHero = get_post_custom_values('iorg_show_hero', get_the_ID()); 
if(is_array($showHero) && ($showHero[0]!="Y")) {
	if(is_array($showImage)) {
		$showImage[0] = "";
	}
	if(is_array($showMedia)) {
		$showMedia[0] = "";
	}	
}

$story_page = get_post_custom_values('iorg_story_page', get_the_ID()); 
$display_story = "half_screen";
if(is_array($story_page) && $story_page[0]!="") {
		$display_story = $story_page[0];
	} 
$header_color = get_post_custom_values('iorg_header_color', get_the_ID()); 
$header_img_color = get_post_custom_values('iorg_header_img_color', get_the_ID()); 

if(is_array($showImage) && $showImage[0]=="") {

	if(is_array($showMedia) && $showMedia[0]!="") {

		$thumbnail = internetorg_get_thumbnail($showMedia[0]);
		$showImage[0] = $thumbnail;
	}
}
?>

<div class="resultsList-list-item">
	<div class="feature feature_tight">
		<div class="feature-hd">
			<h2 class="hdg hdg_4">
				<a href="<?php the_permalink(); ?>" class="mix-link_small js-stateLink"
			    data-title="<?php echo esc_attr( apply_filters( 'the_title',  get_the_title() ) ); ?>"
			    data-image="<?php echo esc_url( $img );?>"
			    data-mobile-image="<?php echo esc_url( $mobile_image );?>"
			    data-theme="<?php echo esc_attr( strtolower( $theme ) ); ?>"
					<?php if ( in_array( get_post_type( get_the_ID() ), $post_shadow ) ) : ?>
					data-date="<?php echo esc_attr( get_the_date( '', get_the_ID() ) ); ?>"
					data-social="true"
					<?php endif; ?>
			   data-type="<?php echo esc_attr( $type ); ?>"
			   data-date="<?php if($displayDate=='show') internetorg_posted_on_date(); ?>" <?php if(is_array($showImage) && $showImage[0]!="") { ?> data-image-display="<?php echo $showImage[0]; ?>" <?php } ?> <?php if(is_array($showMedia) && $showMedia[0]!="") { ?> data-video="<?php echo $showMedia[0]; ?>" <?php } ?> data-story-page="<?php echo $display_story; ?>" <?php if(is_array($header_color) && $header_color[0]!="") { ?> data-header-color="<?php echo $header_color[0]; ?>" <?php } ?> <?php if(is_array($header_img_color) && $header_img_color[0]!="") { ?> data-header-img-color="<?php echo $header_img_color[0]; ?>" <?php } ?>>
				<h2 class="hdg hdg_4"><?php echo $header_img_color[0]; ?> - <?php the_title(); ?></h2>
			</a>
			</h2>
		</div>
		<div class="feature-bd">
			<div class="bdcpy"><?php the_excerpt(); ?></div>
		</div>
		<div class="feature-cta">
			<a href="<?php the_permalink(); ?>" class="link mix-link_small js-stateLink"
			    data-title="<?php echo esc_attr( apply_filters( 'the_title',  get_the_title() ) ); ?>"
			    data-image="<?php echo esc_url( $img );?>"
			    data-mobile-image="<?php echo esc_url( $mobile_image );?>"
			    data-theme="<?php echo esc_attr( strtolower( $theme ) ); ?>"

				<?php if ( in_array( get_post_type( get_the_ID() ), $post_shadow ) ) : ?>
					data-social="true"
				<?php endif; ?>

			   data-type="<?php echo esc_attr( $type ); ?>"
			   data-date="<?php if($displayDate=='show') internetorg_posted_on_date(); ?>" <?php if(is_array($showImage) && $showImage[0]!="") { ?> data-image-display="<?php echo $showImage[0]; ?>" <?php } ?> <?php if(is_array($showMedia) && $showMedia[0]!="") { ?> data-video="<?php echo $showMedia[0]; ?>" <?php } ?> data-story-page="<?php echo $display_story; ?>" <?php if(is_array($header_color) && $header_color[0]!="") { ?> data-header-color="<?php echo $header_color[0]; ?>" <?php } ?> <?php if(is_array($header_img_color) && $header_img_color[0]!="") { ?> data-header-img-color="<?php echo $header_img_color[0]; ?>" <?php } ?>
			   >
				<?php esc_html_e( 'Read More', 'internetorg' ); ?>
			</a>
		</div>
	</div>
</div>
