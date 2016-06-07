<?php
/**
 * Internet.org Custom Fields file.
 *
 * @since             1.0.0
 * @package           Internet.org
 *
 * @wordpress-plugin
 * Plugin Name:       Internet.org Custom Fields
 * Description:       Creates custom fields for the Internet.org website
 * Version:           1.0.0
 * Author:            The Nerdery
 * Author URI:        https://nerdery.com/
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

if ( ! function_exists( 'internetorg_custom_fields_init' ) ) {
	/**
	 * Initializes the custom fields using the "Fieldmanager" plugin from Alley Interactive.
	 *
	 * Basic field creation.
	 *
	 * @link http://fieldmanager.org/docs/contexts/post-context/
	 *
	 * List of field types.
	 * @link http://fieldmanager.org/docs/fields/
	 *
	 * Fields being activated.
	 * @see  internetorg_create_fields_internetorg_page_home
	 * @see  internetorg_page_home_after_title_fields
	 * @see  internetorg_create_after_title_fields_internetorg_page_home
	 *
	 * @return void
	 */
	
	function internetorg_custom_fields_init() {
		/*
		 * The desire is have these fields only on the home page but we cannot limit these custom fields to a specific
		 * page upon page creation, we can, however, limit on display with a custom page template for those pages
		 * on which we wish to display the content.
		 */
		//add_action( 'wp_enqueue_scripts', 'register_plugin_styles' );
		add_action( 'admin_enqueue_scripts', 'register_plugin_styles' );
		add_action( 'edit_form_after_title', 'internetorg_page_home_after_title_fields' );
		add_action( 'add_meta_boxes', 'my_custom_field_checkboxes' );
		add_action( 'save_post', 'my_custom_field_data', 1);
		add_filter( 'the_content_more_link', 'modify_read_more_link' );
		add_filter( 'fee_rich_clean', '__return_false' );
		add_filter( 'attachment_fields_to_edit', 'internetorg_image_custom_field_edit', 10, 2 );
		add_filter( 'attachment_fields_to_save', 'internetorg_image_custom_field_save', 10, 2 );
		add_filter( 'media_send_to_editor', 'saveFields', 10, 2 );
		return;
	}

	function saveFields( $html, $id  ) {
	   $attachment = get_post( $id );
        $mime_type = $attachment->post_mime_type;
        $pos = strpos($mime_type, "image");
        // I only needed PDF but you can use whatever mime_type you need
        if ( $pos !== false ) {
        	$mainClass = get_post_meta($id, 'imageClass', true);
            $src = wp_get_attachment_image( $id );
           	$dom = new DOMDocument();
			$dom->loadHTML($src);
			$tags = $dom->getElementsByTagName('img')->item(0);
            $html = '<img';
            foreach ($tags->attributes as $attr) {
            	 $name = $attr->nodeName;
    			 $value = $attr->nodeValue;
    			 if($name=="class") {
    				$html .= ' '.$name.'="'.$value.' '.$mainClass.'"'; 	
    			 } else {
    			 	$html .= ' '.$name.'="'.$value.'"';
    			 }
			    
			}
			$html .=" />";
			$html = json_encode($attachment);
        }

        return $html;

	}

	function internetorg_image_custom_field_edit( $form_fields, $post ) {
	    $field_value = get_post_meta( $post->ID, 'imageClass', true );
	    $form_fields['imageClass'] = array(
	        'value' => $field_value ? $field_value : '',
	        'label' => __( 'Image Class' ),
	        'input' => "html",
	        'html' => "<input type='radio' value='imgLeftSmall' name='attachments[{$post->ID}][imageClass]' id='attachments[{$post->ID}][imageClass1]' /> <label for='attachments[{$post->ID}][imageClass1]'>imgLeftSmall</label><br/><input type='radio' value='imgLeftMid' name='attachments[{$post->ID}][imageClass]' id='attachments[{$post->ID}][imageClass2]' /> <label for='attachments[{$post->ID}][imageClass2]'>imgLeftMid</label><br/><input type='radio' value='imgRightSmall' name='attachments[{$post->ID}][imageClass]' id='attachments[{$post->ID}][imageClass3]' /> <label for='attachments[{$post->ID}][imageClass3]'>imgRightSmall</label><br/><input type='radio' value='imgRightMid' name='attachments[{$post->ID}][imageClass]' id='attachments[{$post->ID}][imageClass4]' /> <label for='attachments[{$post->ID}][imageClass4]'>imgRightMid</label><br/><input type='radio' value='imgCenterSmall' name='attachments[{$post->ID}][imageClass]' id='attachments[{$post->ID}][imageClass5]' /> <label for='attachments[{$post->ID}][imageClass5]'>imgCenterSmall</label><br/><input type='radio' value='imgCenterMid' name='attachments[{$post->ID}][imageClass]' id='attachments[{$post->ID}][imageClass6]' /> <label for='attachments[{$post->ID}][imageClass6]'>imgCenterMid</label>"
	    );
	    $field_value = get_post_meta( $post->ID, 'imageClassmt', true );
	    $form_fields['imageClassMt'] = array(
	        'value' => $field_value ? $field_value : '',
	        'label' => __( 'Class for Mobile/Tablet' ),
	        'input' => "html",
	        'html' => "<input type='radio' value='mtMid' name='attachments[{$post->ID}][imageClassMt]' id='attachments[{$post->ID}][imageClassMt1]' /> <label for='attachments[{$post->ID}][imageClassMt1]'>mtMid</label><br/><input type='radio' value='mtSmall' name='attachments[{$post->ID}][imageClassMt]' id='attachments[{$post->ID}][imageClassMt2]' /> <label for='attachments[{$post->ID}][imageClassMt2]'>mtSmall</label><br/><input type='radio' value='mtLarge' name='attachments[{$post->ID}][imageClassMt]' id='attachments[{$post->ID}][imageClassMt3]' /> <label for='attachments[{$post->ID}][imageClassMt3]'>mtLarge</label>"
	    );
	    return $form_fields;
	}

	function internetorg_image_custom_field_edit_save($post, $attachment) {
		if( isset( $attachment['imageClass'] ) )
	        update_post_meta( $post['ID'], 'imageClass', $attachment['imageClass']  );
	   	
	   	if( isset( $attachment['imageClassMt'] ) )
	        update_post_meta( $post['ID'], 'imageClassMt', $attachment['imageClassMt']  );

		return $post;
	}

	function modify_read_more_link() {
	return '<a class="more-link" href="' . get_permalink() . '">Your Read More Link Text</a>';
	}

	function register_plugin_styles() {
		wp_register_style( 'custom-style', plugins_url('internetorg-custom-fields.css', __FILE__ ), array(), '1', 'all' );
		wp_enqueue_style( 'custom-style' );
		wp_register_script( 'custom-script', plugins_url('internetorg-custom-fields.js', __FILE__ ), array(), '1', 'all' );
		
		wp_enqueue_script( 'custom-script' );
		wp_register_script( 'color-script', plugins_url('jscolor.min.js', __FILE__ ), array(), '1', 'all' );
		wp_enqueue_script( 'color-script' );
	}
		// register the meta box
	
	function my_custom_field_checkboxes() {
	    add_meta_box(
	        'my_meta_box_id',          // this is HTML id of the box on edit screen
	        'Internet org custom fields',    // title of the box
	        'my_customfield_box_content',   // function to be called to display the checkboxes, see the function below
	        'post',        // on which edit screen the box should appear
	        'normal',      // part of page where the box should appear
	        'default'      // priority of the box
	    );
	}

	// display the metabox
	function my_customfield_box_content( $post ) {
	    // nonce field for security check, you can have the same
	    // nonce field for all your meta boxes of same plugin
	    $post_id = $post->ID;
	    wp_nonce_field( plugin_basename( __FILE__ ), 'myplugin_nonce' );

	  

	    $display_date = get_post_meta( $post_id, 'iorg_display_date') ? get_post_meta( $post_id, 'iorg_display_date')[0] : '';
	    $show_footer = get_post_meta( $post_id, 'iorg_show_footer') ? get_post_meta( $post_id, 'iorg_show_footer')[0] : '';
	    $show_hero = get_post_meta( $post_id, 'iorg_show_hero') ? get_post_meta( $post_id, 'iorg_show_hero')[0] : '';

	    $hero_image = get_post_meta( $post_id, 'iorg_hero_image') ? get_post_meta( $post_id, 'iorg_hero_image')[0] : '';
	    $vdo_url = get_post_meta( $post_id, 'iorg_hero_vdo_url') ? get_post_meta( $post_id, 'iorg_hero_vdo_url')[0] : '';
	    $header_color = get_post_meta( $post_id, 'iorg_header_color') ? get_post_meta( $post_id, 'iorg_header_color')[0]: '';

	    $header_img_color = get_post_meta( $post_id, 'iorg_header_img_color') ? get_post_meta( $post_id, 'iorg_header_img_color')[0]: '';

	    $story_page = get_post_meta( $post_id, 'iorg_story_page') ? get_post_meta( $post_id, 'iorg_story_page')[0]: '';

	    if($display_date!="N") {
	    	$show_display_date = " checked ";	
	    } else {
	    	$show_display_date = "";
	    }

	    if($show_footer!="N") {
	    	$show_show_footer = " checked ";	
	    } else {
	    	$show_show_footer = "";
	    }

	    if($show_hero=="Y") {
	    	$display_hero = " checked ";
	    } else {
	    	$display_hero = "";
	    }

	    if(($header_img_color!="") && ($header_img_color!="black")) {
	    	$header_img_color = $header_img_color;
	    } else {
	    	$header_img_color = "000000";
	    }

	    if(($header_color!="") && ($header_color!="black")) {
	    	$header_color = $header_color;
	    } else {
	    	$header_color = "000000";
	    }




	    	?>
		<?php add_thickbox(); ?>
		<div id="specModal" style="display:none;">
			<h1>Specs</h1>
			<br>
			<h2>Size/Dimensions/Quality</h2>
				<li>Recommended Aspect Ratio: 4:3 or 16:9</li>
				<li>Minimum width: 1280px</li>
				<li>Maximum file size for hero assets is 2MB</li>
				<li>Supported file formats: JPEG, GIF, PNG</li>
			<h2>Hero & Navigation</h2>
			<em>Make sure logo and nav menu is legible over the selected image:</em>
			<br>
			<li>Make sure the center of the hero image is the focal point</li>
			<li>Default color of nav is black, but can be changed to white</li>
			<li>Select Navigation color from drop down (choice between black or white)</li>
			<li>Flexibility to override by inputting custom hex color code</li>
			<li>Always preview article before posting by clicking the Preview button in the upper right corner</li>

			<h2>Inline Images</h2>
			<em>These classes define the size and the alignment of the inline image. The format is imgALIGNSIZE.</em>
			<li>imgLeftSmall (aligned left,  30% width)</li>
			<li>imgLeftMid (aligned left, 50% width)</li>
			<li>imgCenterSmall (aligned center, 30% width)</li>
			<li>imgCenterMid (aligned center, 50% width)</li>
			<li>imgRightSmall (aligned right, 30% width)</li>
			<li>imgRightMid (aligned right, 50%width)</li>


			<em>NOTE: The following classes will only display in mobile and tablet sized screens. This will be an addon to the previous listed classes for mobile and tablet optimization.</em>

			<li>mtMid (mobile and tablet align center, 50% width)</li>
			<li>mtSmall (mobile and tablet align center, 30% width)</li>
			<li>mtLarge (mobile and tablet align center, 75% width)</li>


		</div>

		<a href="#TB_inline?width=900&height=1000&inlineId=specModal" class="thickbox"><button>Specs</button></a>
		<div class="iorg-custom-fields">
	    	<div class="iorg-custom-fields-left">Show  post date</div>
	    	<div class="iorg-custom-fields-right">
	    		<input type="checkbox" name="iorg_display_date" value="Y" <?php echo $show_display_date; ?> />
	    	</div>
	    	<div class="iorg-custom-fields-clear"></div>
	    	<div class="iorg-custom-fields-left">Show footer more posts</div>
	    	<div class="iorg-custom-fields-right">
	    		<input type="checkbox" name="iorg_show_footer" value="Y" <?php echo $show_show_footer; ?> />
	    	</div>
	    	<div class="iorg-custom-fields-clear"></div>
	    	<div><h3>Hero Details</h3></div>
	    	<div class="iorg-custom-fields-left">Show Hero</div>
	    	<div class="iorg-custom-fields-right">
	    		<input type="checkbox" name="iorg_show_hero" value="Y" <?php echo $display_hero; ?> />
	    	</div>
	    	<div class="iorg-custom-fields-clear"></div>
	    	<div class="iorg-custom-fields-left">Hero Image</div>
	    	<div class="iorg-custom-fields-right">
	    		<input type="text" class="iorg_text" id="iorg_hero_image" name="iorg_hero_image" value="<?php echo $hero_image; ?>" />
	    		<button id="iorg_select_img" onclick="javascript: selectMedia('iorg_select_img', 'iorg_hero_image');">Select Media</button>
	    	</div>
	    	<div class="iorg-custom-fields-clear"></div>
	    	<div class="iorg-custom-fields-left">Hero Video URL</div>
	    	<div class="iorg-custom-fields-right">
	    		<input type="text" class="iorg_text" id="iorg_hero_vdo_url" name="iorg_hero_vdo_url" value="<?php echo $vdo_url; ?>" />
	    		</div>
	    	<div class="iorg-custom-fields-clear"></div>
	    	<div class="iorg-custom-fields-left">Header image color</div>
	    	<div class="iorg-custom-fields-right">
	    		<input type="text" class="jscolor" id="iorg_header_img_color" name="iorg_header_img_color" value="<?php echo esc_attr($header_img_color); ?>" />
	    		<?php /* ?><select name="iorg_header_img_color">
			    	<option value="white" <?php if($header_img_color=="white") echo " selected "; ?> >White</option>
			    	<option value="black" <?php if($header_img_color!="white") echo " selected "; ?>>Black</option>
		    	</select> <?php */ ?>
	    	</div>
	    	<div class="iorg-custom-fields-clear"></div>
	    	<div class="iorg-custom-fields-left">Header menu color</div>
	    	<div class="iorg-custom-fields-right">
	    		<input type="text" class="jscolor" id="iorg_header_color" name="iorg_header_color" value="<?php echo $header_color; ?>" />
	    		<?php /* ?><select name="iorg_header_color">
			    	<option value="white" <?php if($header_color=="white") echo " selected "; ?> >White</option>
			    	<option value="black" <?php if($header_color!="white") echo " selected "; ?>>Black</option>
			    	
		    	</select><?php */ ?>
	    	</div>
	    	<div class="iorg-custom-fields-clear"></div>
	    	<div class="iorg-custom-fields-left">Story Page</div>
	    	<div class="iorg-custom-fields-right">
		    	<select name="iorg_story_page">
			    	<option value="half_screen" <?php if($story_page!="full_screen") echo " selected "; ?> >Half Screen</option>
			    	<option value="full_screen" <?php if($story_page=="full_screen") echo " selected "; ?>>Full Screen</option>
			    	
		    	</select>
<!--		    	<input type="button" onclick="changeMeta()" value="Change DB to Split Screen">-->
		    </div> 
	    	<div class="iorg-custom-fields-clear"></div>
	    </div>
	    
	    <?php

	}

	// save data from checkboxes
	
	function my_custom_field_data() {

		if( !isset($_POST['post_ID'] ) )
			return false;
		$post_id = $_POST['post_ID'];
		
		// check if this isn't an auto save
	    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
	        return;
	    
		
	    // further checks if you like, 
	    // for example particular user, role or maybe post type in case of custom post types
	    
	    // now store data in custom fields based on checkboxes selected
	    if ( isset( $_POST['iorg_display_date'] ) )
	        update_post_meta( $post_id, 'iorg_display_date', 'Y' );
	    else
	        update_post_meta( $post_id, 'iorg_display_date', 'N' );

	    if ( isset( $_POST['iorg_show_footer'] ) )
	        update_post_meta( $post_id, 'iorg_show_footer', 'Y' );
	    else
	        update_post_meta( $post_id, 'iorg_show_footer', 'N' );

	    if ( isset( $_POST['iorg_show_hero'] ) )
	        update_post_meta( $post_id, 'iorg_show_hero', 'Y' );
	    else
	        update_post_meta( $post_id, 'iorg_show_hero', 'N' );

	    if ( isset( $_POST['iorg_hero_image'] ) )
	        update_post_meta( $post_id, 'iorg_hero_image', $_POST['iorg_hero_image'] );
	    else
	        update_post_meta( $post_id, 'iorg_hero_image', '' );

	    if ( isset( $_POST['iorg_hero_vdo_url'] ) )
	        update_post_meta( $post_id, 'iorg_hero_vdo_url', $_POST['iorg_hero_vdo_url'] );
	    else
	        update_post_meta( $post_id, 'iorg_show_footer', '' );

	    if ( isset( $_POST['iorg_header_color'] ) )
	        update_post_meta( $post_id, 'iorg_header_color', $_POST['iorg_header_color'] );
	    else
	        update_post_meta( $post_id, 'iorg_header_color', '' );

	    if ( isset( $_POST['iorg_header_img_color'] ) )
	        update_post_meta( $post_id, 'iorg_header_img_color', $_POST['iorg_header_img_color'] );
	    else
	        update_post_meta( $post_id, 'iorg_header_img_color', '' );

	    if ( isset( $_POST['iorg_story_page'] ) )
	        update_post_meta( $post_id, 'iorg_story_page', $_POST['iorg_story_page'] );
	    else
	        update_post_meta( $post_id, 'iorg_story_page', '' );
	}
}
add_action( 'init', 'internetorg_custom_fields_init' );
add_action( 'wp_ajax_my_action', 'my_action_callback' );

//function my_action_callback() {
//	global $wpdb; // this is how you get access to the database
//	$q = "UPDATE $wpdb->postmeta SET meta_value = 'half_screen' where meta_key = 'iorg_story_page' and meta_value = 'full_screen'";
//	$wpdb->query($q);
//
//
//    echo "Records updated to split screen view";
//
//	wp_die(); // this is required to terminate immediately and return a proper response
//}
/**
 * Called when the plugin activates, use to do anything that needs to be done once.
 *
 * @return void
 */
function internetorg_cf_on_activate() {
	internetorg_custom_fields_init();

	return;
}



register_activation_hook( __FILE__, 'internetorg_cf_on_activate' );
