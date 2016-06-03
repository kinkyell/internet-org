var file_frame;

// "mca_tray_button" is the ID of my button that opens the Media window
function selectMedia(btn,id) {

jQuery('#'+btn).live('click', function( event ){

  event.preventDefault();

	if ( file_frame ) {
		file_frame.open();
		return;
	}

	file_frame = wp.media.frames.file_frame = wp.media({
		title: jQuery( this ).data( 'uploader_title' ),
		button: {
			text: jQuery( this ).data( 'uploader_button_text' ),
		},
		multiple: false  
	});

	file_frame.on( 'select', function() {

		attachment = file_frame.state().get('selection').first().toJSON();
		console.log(attachment);
		// "mca_features_tray" is the ID of my text field that will receive the image
		// I'm getting the ID rather than the URL:

		//jQuery("#mca_features_tray").val(attachment.id);

		// but you could get the URL instead by doing something like this:
		jQuery("#"+id).val(attachment.url);

		// and you can change "thumbnail" to get other image sizes

	});

	file_frame.open();

});
}

function changeMeta() {
	jQuery(document).ready(function($) {

		var data = {
			'action': 'my_action',
			'whatever': 1234
		};

		// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
		jQuery.post(ajaxurl, data, function(response) {
			alert(response);
		});
	});
}