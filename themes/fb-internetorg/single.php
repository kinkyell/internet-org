<?php
/**
 * The template for displaying all single posts.
 *
 * @package Internet.org
 */

get_header();

 $showDate = get_post_custom_values('iorg_display_date'); 
 $displayDate = "show";
 $displayFooterPosts = "show";

if(is_array($showDate) && strtolower($showDate[0])=="n") {
 	$displayDate = "hide";

 } else {
 	$displayDate = "show";

 }

$showMedia = get_post_custom_values('iorg_hero_vdo_url'); 
$showImage = get_post_custom_values('iorg_hero_image'); 
$showHero = get_post_custom_values('iorg_show_hero'); 
if(is_array($showHero) && ($showHero[0]!="Y")) {
	if(is_array($showImage)) {
		$showImage[0] = "";
	}
	if(is_array($showMedia)) {
		$showMedia[0] = "";
	}	
}

$showFooterPosts = get_post_custom_values('iorg_show_footer'); 
if(is_array($showFooterPosts) && strtolower($showFooterPosts[0])=="n") {
 	$displayFooterPosts = "hide";

 } else {
 	$displayFooterPosts = "show";

 }




$story_page = get_post_custom_values('iorg_story_page'); 
$display_story = "half_screen";
if(is_array($story_page) && $story_page[0]!="") {
		$display_story = $story_page[0];
	} 
$display_margin = "";
$display_viewwindow_rtl = "";

if($display_story=="half_screen") {
	$display_margin = 'ContainerPadding';

} else {
	$display_viewwindow_rtl = "isShiftedRtl";
}

$header_color = get_post_custom_values('iorg_header_color'); 
$header_img_color = get_post_custom_values('iorg_header_img_color'); 

if(is_array($showImage) && $showImage[0]=="") {

	if(is_array($showMedia) && $showMedia[0]!="") {

		$thumbnail = internetorg_get_thumbnail($showMedia[0]);
		$showImage[0] = $thumbnail;
	}
}

?>
	<div class="viewWindow isShifted js-viewWindow js-stateDefault <?php echo $display_viewwindow_rtl; ?>" id="main-content" role="main" data-type="titled" data-route="<?php the_permalink(); ?>" data-title="<?php the_title(); ?>" data-social="true" data-date="<?php if($displayDate=='show') internetorg_posted_on_date(); ?>" <?php if(is_array($showImage) && $showImage[0]!="") { ?> data-image-display="<?php echo $showImage[0]; ?>" <?php } ?> <?php if(is_array($showMedia) && $showMedia[0]!="") { ?> data-video="<?php echo $showMedia[0]; ?>" <?php } ?> data-story-page="<?php echo $display_story; ?>" <?php if(is_array($header_color) && $header_color[0]!="") { ?> data-header-color="<?php echo $header_color[0]; ?>" <?php } ?> <?php if(is_array($header_img_color) && $header_img_color[0]!="") { ?> data-header-img-color="<?php echo $header_img_color[0]; ?>" <?php } ?> data-page="single">
		<?php while ( have_posts() ) : the_post(); 
		 ?>

			<?php get_template_part( 'template-parts/content', 'page-temp-panel' ); ?>

		<div id="featurePanel" class="viewWindow-panel viewWindow-panel_feature">
			<div class="viewWindow-panel-content">
				<div class="viewWindow-panel-content-inner">
					<div class="introBlock introBlock_fill">
						<div class="introBlock-inner">
							<div class="container">
								<div class="topicBlock">
								<?php
								if(is_array($showImage) && $showImage[0]!="") {
								?>
								<div class="imgWrap isLoaded"  id="heroImage" style="background: url(<?php echo $showImage[0]; ?>) no-repeat center center; -webkit-background-size: cover; -moz-background-size: cover; -o-background-size: over; background-size: cover; width:100%;">&nbsp;</div>
								<?php
								 }
								?>

								<?php if(is_array($showMedia) && $showMedia[0]!="") { ?>
								<a href="<?php echo $showMedia[0]; ?>" class="contentOnMedia-link contentOnMedia-link_ct js-videoModal swipebox-video HeroImagePlay">
									<span class="circleBtn circleBtn_play"></span>
								</a>
								<?php }//media ?>
									<div class="topicBlock-hd topicBlock-hd_plus">
										<h2 class="hdg hdg_2 mix-hdg_bold"><?php the_title(); ?></h2>
									</div>
									<?php 
										if($displayDate=="show") {
									?>
									<div class="topicBlock-subHd">
										<div class="hdg hdg_6 mix-hdg_italic mix-hdg_gray"><?php internetorg_posted_on_date(); ?></div>
									</div>
									<?php }  ?>
								</div>
							</div>
						</div>

					</div>
				</div>
			</div>
		</div>

		<div id="storyPanel" class="viewWindow-panel viewWindow-panel_story isActive">
			<div class="viewWindow-panel-content">
				<div class="viewWindow-panel-content-inner">
					<div>
						<?php get_template_part( 'template-parts/content', 'press-intro-mobile' ); ?>

						<div class="contentCol_fullPage">
							<div class="container <?php echo $display_margin; ?>">
								<div class="feature">
									<div class="feature-bd wysiwyg quarantine">

										<?php internetorg_media_embed(); ?>
										<?php 
										
										// Fetch post content
										$content = get_post_field( 'post_content', $post->ID);

										// Get content parts
										$content_parts = get_extended( $content );
										$content_short = apply_filters('the_content', $content_parts['main']);
										$content_extended = apply_filters('the_content', $content_parts['extended']);
										
										echo $content_short; 
										if($content_extended!="") {
										 ?>
										 <div class="readMoreDiv" ><button onclick="javascript:  jQuery('.readMoreDiv').hide(); jQuery('#extendedContent').slideDown(1000);" class="readMore">SHOW MORE</button></div>
										 <div id="extendedContent" style="display: none;">
										 	<?php echo $content_extended; ?>
										 </div>
										 <?php } ?>
									  <div class="fb-like"
									      data-layout="standard"
									      data-action="like"
									      data-share="true">
									  </div>

									</div>
								</div>
							</div>
						</div>

						<div class="footBox">
							<?php if($displayFooterPosts=="show") { ?>
							<div class="container">
								<?php get_template_part( 'template-parts/content', 'single-more' ); ?>
							</div>
							<?php } ?>
							<div class="footBox-ft">
								<div class="container">
									<?php internetorg_vip_powered_wpcom( 'pwdByVip-txt' ); ?>
								</div>
							</div>
						</div>

					</div>
				</div>
			</div>
		</div>
		<?php endwhile; ?>
	</div>

<?php get_footer();
