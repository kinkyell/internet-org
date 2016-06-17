<?php
/**
 * Content Press Intro Mobile template part.
 *
 * @package Internet.org
 */
 $showDate = get_post_custom_values('iorg_display_date', $post->ID); 
 $displayDate = "show";

if(is_array($showDate) && strtolower($showDate[0])=="n") {
 	$displayDate = "hide";

 } else {
 	$displayDate = "show";

 }
$showMedia = get_post_custom_values('iorg_hero_vdo_url', $post->ID); 
$showImage = get_post_custom_values('iorg_hero_image', $post->ID); 
$showHero = get_post_custom_values('iorg_show_hero', $post->ID); 
$HeroMobile = "";
if(is_array($showHero) && ($showHero[0]!="Y")) {
	if(is_array($showImage)) {
		$showImage[0] = "";
	}	
}
$story_page = get_post_custom_values('iorg_story_page'); 
$display_story = "half_screen";
if(is_array($story_page) && $story_page[0]!="") {
	$display_story = $story_page[0];
}
if(((is_array($showImage) && $showImage[0]!="") || (is_array($showMedia) && $showMedia[0]!="")) || ($display_story=="full_screen")) {
		$HeroMobile = "HeroImage";
	}
$HeroTitleClass = "";

if(is_array($showImage) && $showImage[0]=="") {

	if(is_array($showMedia) && $showMedia[0]!="") {

		$thumbnail = internetorg_get_thumbnail($showMedia[0]);
		$showImage[0] = $thumbnail;
	}
}

?>

<!-- START MOBILE ONLY CONTENT HERE -->
<div class="introBlock isHidden u-isHiddenMedium <?php echo esc_attr( $HeroMobile ); ?>" aria-hidden="true">
	<div class="introBlock-inner introBlock-inner_stack">

		<div class="topicBlock">

			<?php
			if($display_story=="full_screen") {
			if(is_array($showImage) && $showImage[0]!="") {
			?>
			<div class="imgWrap isLoaded"  id="heroImage" style="background: url(<?php echo esc_url( $showImage[0] ); ?>) no-repeat center center; -webkit-background-size: cover; -moz-background-size: cover; -o-background-size: over; background-size: cover; width:100%;">&nbsp;</div>
			<?php
			 } elseif ($HeroMobile=="HeroImage") {
			 	$HeroTitleClass = "HeaderPadding";
			 }
			?>

			<?php if(is_array($showMedia) && $showMedia[0]!="") { ?>
			<a href="<?php echo esc_url( $showMedia[0] ); ?>" class="contentOnMedia-link contentOnMedia-link_ct js-videoModal swipebox-video HeroImagePlay" rel="vimeo0">
				<span class="circleBtn circleBtn_play"></span>
			</a>
			<?php }//media

			} ?>
			<div class="topicBlock-hd topicBlock-hd_plus <?php echo esc_attr( $HeroTitleClass ); ?>">
				<h2 class="hdg hdg_2 mix-hdg_bold"><?php the_title(); ?></h2>
			</div>

			<?php $featured_image = internetorg_get_mobile_featured_image( get_post_type(), get_the_ID() );
			if ( ! empty( $featured_image ) ) { ?>
				<div class="topicBlock-media">
					<img src="<?php echo esc_url( internetorg_get_mobile_featured_image( get_post_type(), get_the_ID() ) ); ?>" alt="" />
				</div>
			<?php } ?>
			<?php 
				if($displayDate=="show") {
			?>
			<div class="topicBlock-subHd">
				<div class="hdg hdg_6 mix-hdg_italic mix-hdg_gray"><?php internetorg_posted_on_date(); ?></div>
			</div>
			<?php } ?>
			<div class="topicBlock-bd">
				<p class="bdcpy" >
					<?php if($display_story=="half_screen"){echo wp_kses_post( get_post_field( 'post_excerpt', get_the_ID() ) );} else {echo "";} ?>
				</p>
			</div>
		</div>

	</div>

</div>
<!-- END MOBILE ONLY CONTENT HERE -->
