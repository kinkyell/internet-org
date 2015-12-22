
jQuery( function ( $ ) {

	var setCounter = function ( field ) {
		var remaining = 300 - field.value.length;
		var $count = $( field ).siblings( '.counter' );
		$count.text( remaining + ' characters remaining.');
	};

	$( '.fm-iorg_description textarea' ).each( function () {

		$( this ).after( '<div class="counter">300 characters remaining.</div>' );
		setCounter( this );

	}).on( 'keypress' , function () {

		setCounter( this );

	});

});
