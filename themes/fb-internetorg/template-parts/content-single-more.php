<?php
/**
 * Content Single More posts template part.
 *
 * @package Internet.org
 */

$next_post = get_next_post();
$prev_post = get_previous_post();

if(!$next_post) {
	 $next = new WP_Query('posts_per_page=1&order=ASC'); 
	 $next_post = $next->posts[0];

}

if(!$prev_post) {
	 $prev = new WP_Query('posts_per_page=1&order=DESC'); 
	 $prev_post = $prev->posts[0];
}



if ( empty( $next_post ) && empty( $prev_post ) ) {
	return;
}
if($next_post) {
 $showDateNext = get_post_custom_values('iorg_display_date', $next_post->ID); 
 $displayDateNext = "hide";

if(is_array($showDateNext) && strtolower($showDateNext[0])=="n") {
 	$displayDateNext = "hide";

 } else {
 	$displayDateNext = "show";

 }

$showMediaNext = get_post_custom_values('iorg_hero_vdo_url', $next_post->ID); 
$showImageNext = get_post_custom_values('iorg_hero_image', $next_post->ID); 
$showHeroNext = get_post_custom_values('iorg_show_hero', $next_post->ID); 
if(is_array($showHeroNext) && ($showHeroNext[0]!="Y")) {
	if(is_array($showImageNext)) {
		$showImageNext[0] = "";
	}
	if(is_array($showMediaNext)) {
		$showMediaNext[0] = "";
	}		
}

$story_page_Next = get_post_custom_values('iorg_story_page', $next_post->ID); 
$header_color_Next = get_post_custom_values('iorg_header_color', $next_post->ID); 
$header_img_color_Next = get_post_custom_values('iorg_header_img_color', $next_post->ID); 
$featured_img_Next = internetorg_get_post_thumbnail( $next_post->ID, 'listing-image' ) ;
$display_story_Next = "half_screen";
if(is_array($story_page_Next) && $story_page_Next[0]!="") {
		$display_story_Next = $story_page_Next[0];
	} 
} else {
	$showDateNext = '';
	$displayDateNext = "show";
	$showMediaNext = '';
	$showImageNext = '';
	$featured_img_Next = '';
}

if($prev_post) {
 $showDatePrev = get_post_custom_values('iorg_display_date', $prev_post->ID); 
 $displayDatePrev = "hide";

if(is_array($showDatePrev) && strtolower($showDatePrev[0])=="n") {
 	$displayDatePrev = "hide";

 } else {
 	$displayDatePrev = "show";

 }

$showMediaPrev = get_post_custom_values('iorg_hero_vdo_url', $prev_post->ID); 
$showImagePrev = get_post_custom_values('iorg_hero_image', $prev_post->ID); 

$showHeroPrev = get_post_custom_values('iorg_show_hero', $prev_post->ID); 
if(is_array($showHeroPrev) && ($showHeroPrev[0]!="Y")) {
	if(is_array($showImagePrev)) {
		$showImagePrev[0] = "";
	}

	if(is_array($showMediaPrev)) {
		$showMediaPrev[0] = "";
	}	
}

$story_page_Prev = get_post_custom_values('iorg_story_page', $prev_post->ID); 
$header_color_Prev = get_post_custom_values('iorg_header_color', $prev_post->ID); 
$header_img_color_Prev = get_post_custom_values('iorg_header_img_color', $prev_post->ID); 
$featured_img_Prev = internetorg_get_post_thumbnail( $prev_post->ID, 'listing-image' ) ;
$display_story_Prev = "half_screen";
if(is_array($story_page_Prev) && $story_page_Prev[0]!="") {
		$display_story_Prev = $story_page_Prev[0];
	} 

} else {

	$showDatePrev = '';
	$displayDatePrev = "show";
	$showMediaPrev = '';
	$showImagePrev = '';
	$featured_img_Prev = '';
}

$story_page_Cur = get_post_custom_values('iorg_story_page'); 
$display_story_Cur = "half_screen";
if(is_array($story_page_Cur) && $story_page_Cur[0]!="") {
		$display_story_Cur = $story_page_Cur[0];
	} 

if(is_array($showImageNext) && $showImageNext[0]=="") {

	if(is_array($showMediaNext) && $showMediaNext[0]!="") {

		$thumbnailNext = internetorg_get_thumbnail($showMediaNext[0]);
		$showImageNext[0] = $thumbnailNext;
	}
}

if(is_array($showImagePrev) && $showImagePrev[0]=="") {

	if(is_array($showMediaPrev) && $showMediaPrev[0]!="") {

		$thumbnailPrev = internetorg_get_thumbnail($showMediaPrev[0]);
		$showImagePrev[0] = $thumbnailPrev;
	}
}
?>

<div class="footBox-hd">
	<h2 class="hdg hdg_8"><?php esc_html_e( 'More Posts', 'internetorg' ) ?></h2>
</div>

<div class="vList vList_footBox">

	<?php if ( ! empty( $next_post ) ) : ?>
		<div class="footer-left">
			<div class="topicBlock">
				<div class="topicBlock-hd">
					<a
						 class="<?php echo esc_attr( internetorg_english_lang_notification_class( ['or' => 'js-stateLink'], $next_post->ID ) ); ?>"
						 href="<?php echo esc_url( get_permalink( $next_post->ID ) ); ?>"
					   data-title="<?php echo esc_attr( apply_filters( 'the_title',  $next_post->post_title ) ); ?>"
					  data-date="<?php if($displayDateNext=='show') echo esc_attr( get_the_date( '', $next_post->ID ) ); ?>"
					   data-social="true"
					   data-type="titled" <?php if($display_story_Next == "full_screen") { if(is_array($showImageNext) && $showImageNext[0]!="") { ?> data-image-display="<?php echo $showImageNext[0]; ?>" <?php } ?> <?php if(is_array($showMediaNext) && $showMediaNext[0]!="") { ?> data-video="<?php echo $showMediaNext[0]; ?>" <?php } } ?>  data-story-page="<?php echo $display_story_Next; ?>" <?php if(is_array($header_color_Next) && $header_color_Next[0]!="") { ?> data-header-color="<?php echo $header_color_Next[0]; ?>" <?php } ?> <?php if(is_array($header_img_color_Next) && $header_img_color_Next[0]!="") { ?> data-header-img-color="<?php echo $header_img_color_Next[0]; ?>" <?php } ?>  data-page="single">
					   <?php if( ($display_story_Cur=="full_screen") && (has_post_thumbnail($next_post)) && ($featured_img_Next!="")) { ?>
					   	<img src="<?php  echo esc_url($featured_img_Next); ?>" class="FeatureImg" />
					   <?php } ?>
						<h2 class="hdg hdg_8 mix-hdg_bold">
							<?php echo esc_html( apply_filters( 'the_title',  $next_post->post_title ) ); ?>
						</h2>
						<div class="hdg hdg_7 mix-hdg_italic mix-hdg_gray hdg_date" style="clear: both;"><?php echo esc_attr( get_the_date( '', $next_post->ID ) ); ?></div>
						
					</a>
				</div>
				<div class="topicBlock-bd">
					<p class="bdcpy bdcpy_more">
						<?php
						$temp = $post;
						$post = get_post( $next_post->ID );
						setup_postdata( $post );
							echo get_the_excerpt(); 
						wp_reset_postdata();
						$post = $temp;
						?>
						
					</p>
				</div>
				<div class="topicBlock-cta">
				<?php if($display_story_Cur=="half_screen") { ?> 
					<a class="btn js-stateLink"
					   href="<?php echo esc_url( get_permalink( $next_post->ID ) ); ?>"
					   data-title="<?php echo  esc_attr( apply_filters( 'the_title', $next_post->post_title ) ); ?>"
					   data-date="<?php if($displayDateNext=='show') echo esc_attr( get_the_date( '', $next_post->ID ) ); ?>"
					   data-social="true"
					   data-type="titled" <?php if($display_story_Next == "full_screen") { if(is_array($showImageNext) && $showImageNext[0]!="") { ?> data-image-display="<?php echo $showImageNext[0]; ?>" <?php } ?> <?php if(is_array($showMediaNext) && $showMediaNext[0]!="") { ?> data-video="<?php echo $showMediaNext[0]; ?>" <?php } } ?>  data-story-page="<?php echo $display_story_Next; ?>" <?php if(is_array($header_color_Next) && $header_color_Next[0]!="") { ?> data-header-color="<?php echo $header_color_Next[0]; ?>" <?php } ?> <?php if(is_array($header_img_color_Next) && $header_img_color_Next[0]!="") { ?> data-header-img-color="<?php echo $header_img_color_Next[0]; ?>" <?php } ?>  data-page="single">
						<?php esc_html_e( 'Read', 'internetorg' ); ?>
					</a>
					<?php } else { ?>
					<a class="link <?php echo esc_attr( internetorg_english_lang_notification_class( ['or' => 'js-stateLink'] ) ); ?>" href="<?php echo esc_url( get_permalink( $next_post->ID ) ); ?>"
					   data-title="<?php echo esc_attr( apply_filters( 'the_title',  $next_post->post_title ) ); ?>"
					   data-date="<?php if($displayDateNext=='show') echo esc_attr( get_the_date( '', $next_post->ID ) ); ?>"
					   data-social="true"
					   data-type="titled" <?php if($display_story_Next == "full_screen") { if(is_array($showImageNext) && $showImageNext[0]!="") { ?> data-image-display="<?php echo $showImageNext[0]; ?>" <?php } ?> <?php if(is_array($showMediaNext) && $showMediaNext[0]!="") { ?> data-video="<?php echo $showMediaNext[0]; ?>" <?php } } ?>  data-story-page="<?php echo $display_story_Next; ?>" <?php if(is_array($header_color_Next) && $header_color_Next[0]!="") { ?> data-header-color="<?php echo $header_color_Next[0]; ?>" <?php } ?> <?php if(is_array($header_img_color_Next) && $header_img_color_Next[0]!="") { ?> data-header-img-color="<?php echo $header_img_color_Next[0]; ?>" <?php } ?>  data-page="single">
						<?php esc_html_e( 'Read More', 'internetorg' ); ?>
					</a>
					<?php } ?>
				</div>
			</div>
		</div>
	<?php endif; ?>

	<?php if ( ! empty( $prev_post ) ) : ?>
		<div class="footer-right">
			<div class="topicBlock">
				<div class="topicBlock-hd">
					<a class="<?php echo esc_attr( internetorg_english_lang_notification_class( ['or' => 'js-stateLink'], $prev_post->ID ) ); ?>"
					  href="<?php echo esc_url( get_permalink( $prev_post->ID ) ); ?>"
					  data-title="<?php echo  esc_attr( apply_filters( 'the_title', $prev_post->post_title ) ); ?>"
					  data-social="true"
					  data-date="<?php  if($displayDatePrev=='show') echo esc_attr( get_the_date( '', $prev_post->ID ) ); ?>"
					  data-type="titled" <?php if($display_story_Prev == "full_screen") { if(is_array($showImagePrev) && $showImagePrev[0]!="") { ?> data-image-display="<?php echo $showImagePrev[0]; ?>" <?php } ?> <?php if(is_array($showMediaPrev) && $showMediaPrev[0]!="") { ?> data-video="<?php echo $showMediaPrev[0]; ?>" <?php } } ?>  data-story-page="<?php echo $display_story_Prev; ?>" <?php if(is_array($header_color_Prev) && $header_color_Prev[0]!="") { ?> data-header-color="<?php echo $header_color_Prev[0]; ?>" <?php } ?> <?php if(is_array($header_img_color_Prev) && $header_img_color_Prev[0]!="") { ?> data-header-img-color="<?php echo $header_img_color_Prev[0]; ?>" <?php } ?>  data-page="single">
					  <?php if( ($display_story_Cur=="full_screen") &&  (has_post_thumbnail($prev_post)) && ($featured_img_Prev!="")) { ?>
					   	<img src="<?php  echo esc_url($featured_img_Prev); ?>" class="FeatureImg" />
					   <?php } ?>
						<h2 class="hdg hdg_8 mix-hdg_bold">
							<?php echo  esc_html( apply_filters( 'the_title', $prev_post->post_title ) ); ?>
						</h2>
						<div class="hdg hdg_7 mix-hdg_italic mix-hdg_gray hdg_date" style="clear: both;"><?php echo esc_attr( get_the_date( '', $prev_post->ID ) ); ?></div>

					</a>
				</div>
				<div class="topicBlock-bd">
					<p class="bdcpy bdcpy_more">
						
						<?php
						
						$temp = $post;
						$post = get_post( $prev_post->ID );
						setup_postdata( $post );

						echo get_the_excerpt(); 
						wp_reset_postdata();
						$post = $temp;
						 ?>
						</p>
				</div>
				<div class="topicBlock-cta">
				<?php if($display_story_Cur=="half_screen") { ?> 
					<a class="btn js-stateLink"
					   href="<?php echo esc_url( get_permalink( $prev_post->ID ) ); ?>"
					   data-title="<?php echo  esc_attr( apply_filters( 'the_title', $prev_post->post_title ) ); ?>"
					   data-social="true"
					   data-date="<?php if($displayDatePrev=='show') echo esc_attr( get_the_date( '', $prev_post->ID ) ); ?>"
					   data-type="titled" <?php if($display_story_Prev == "full_screen") { if(is_array($showImagePrev) && $showImagePrev[0]!="") { ?> data-image-display="<?php echo $showImagePrev[0]; ?>" <?php } ?> <?php if(is_array($showMediaPrev) && $showMediaPrev[0]!="") { ?> data-video="<?php echo $showMediaPrev[0]; ?>" <?php } } ?>  data-story-page="<?php echo $display_story_Prev; ?>" <?php if(is_array($header_color_Prev) && $header_color_Prev[0]!="") { ?> data-header-color="<?php echo $header_color_Prev[0]; ?>" <?php } ?> <?php if(is_array($header_img_color_Prev) && $header_img_color_Prev[0]!="") { ?> data-header-img-color="<?php echo $header_img_color_Prev[0]; ?>" <?php } ?>  data-page="single">
						<?php esc_html_e( 'Read', 'internetorg' ); ?>
					</a>
					<?php } else { ?>
					<a class="link <?php echo esc_attr( internetorg_english_lang_notification_class( ['or' => 'js-stateLink'] ) ); ?>"
					   href="<?php echo esc_url( get_permalink( $prev_post->ID ) ); ?>"
					   data-title="<?php echo  esc_attr( apply_filters( 'the_title', $prev_post->post_title ) ); ?>"
					   data-social="true"
					   data-date="<?php if($displayDatePrev=='show') echo esc_attr( get_the_date( '', $prev_post->ID ) ); ?>"
					   data-type="titled" <?php if($display_story_Prev == "full_screen") { if(is_array($showImagePrev) && $showImagePrev[0]!="") { ?> data-image-display="<?php echo $showImagePrev[0]; ?>" <?php } ?> <?php if(is_array($showMediaPrev) && $showMediaPrev[0]!="") { ?> data-video="<?php echo $showMediaPrev[0]; ?>" <?php } } ?>  data-story-page="<?php echo $display_story_Prev; ?>" <?php if(is_array($header_color_Prev) && $header_color_Prev[0]!="") { ?> data-header-color="<?php echo $header_color_Prev[0]; ?>" <?php } ?> <?php if(is_array($header_img_color_Prev) && $header_img_color_Prev[0]!="") { ?> data-header-img-color="<?php echo $header_img_color_Prev[0]; ?>" <?php } ?>  data-page="single">
						<?php esc_html_e( 'Read More', 'internetorg' ); ?>
					</a>
					<?php } ?>
				</div>
			</div>
		</div>
	<?php endif; ?>

</div>
