<?php
/**
 * The template part for displaying single post summaries in home.php (press page).
 *
 * @package Internet.org
 */


 $showDate = get_post_custom_values('iorg_display_date'); 
 $displayDate = "hide";

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

$story_page = get_post_custom_values('iorg_story_page'); 
$display_story = "full_screen";
if(is_array($story_page) && $story_page[0]!="") {
		$display_story = $story_page[0];
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

<div class="resultsList-list-item">

	<?php if ( has_post_thumbnail() ) : ?>
	<div class="media media_inline"><?php 
		if ( internetorg_get_post_thumbnail( get_the_ID(), 'listing-image' ) ){ ?>
			<div class="media-figure">
				<img src="<?php echo esc_url( internetorg_get_post_thumbnail( get_the_ID(), 'listing-image' ) ); ?>" alt="" />
			</div><?php 
		}?>
		<div class="media-bd">
			<?php endif; ?>

			<div class="feature feature_tight">
				<div class="feature-hd">
					<a
						href="<?php the_permalink(); ?>"
						class="<?php echo esc_attr( internetorg_english_lang_notification_class() ); ?>"
					>
					<h3 class="hdg hdg_4">
						<?php the_title(); ?>
					</h3>
					</a>
				</div>
				<div class="feature-date">
					<div class="hdg hdg_6 mix-hdg_italic mix-hdg_gray">
						<?php internetorg_posted_on_date(); ?>
					</div>
				</div>

				<?php internetorg_media_embed(); ?>

				<div class="feature-bd">
					<p class="bdcpy">
						<?php echo wp_kses_post( get_the_excerpt() ); ?>
					</p>
				</div>
				<div class="feature-cta"> <!-- REMOVED: js-stateLink  -->
					<a
						class="link <?php echo esc_attr( internetorg_english_lang_notification_class( ['or' => 'js-stateLink'] ) ); ?>"
						href="<?php echo esc_url( get_the_permalink() ); ?>"
						data-title="<?php echo esc_attr( get_the_title() ); ?>"
						data-social="true"
						 data-date="<?php if($displayDate=='show') internetorg_posted_on_date(); ?>" <?php if(is_array($showImage) && $showImage[0]!="") { ?> data-image-display="<?php echo $showImage[0]; ?>" <?php } ?> <?php if(is_array($showMedia) && $showMedia[0]!="") { ?> data-video="<?php echo $showMedia[0]; ?>" <?php } ?> data-story-page="<?php echo $display_story; ?>" <?php if(is_array($header_color) && $header_color[0]!="") { ?> data-header-color="<?php echo $header_color[0]; ?>" <?php } ?> <?php if(is_array($header_img_color) && $header_img_color[0]!="") { ?> data-header-img-color="<?php echo $header_img_color[0]; ?>" <?php } ?>
						data-type="titled"
						data-page="press"
					>
						<?php esc_html_e( 'Read More', 'internetorg' ); ?>
					</a>
				</div>
			</div>

			<?php if ( has_post_thumbnail() ) : ?>
		</div><!-- media-bd -->
	</div><!-- media_inline -->
<?php endif; ?>

</div><!-- resultsList-list-item -->
