
jQuery( function ( $ ) {

	var setCounter = function ( field, limit ) {
		var remaining = limit - field.value.length;
		var $count = $( field ).siblings( '.counter' );
		$count.text( remaining + ' characters remaining.');
	};

	$( '.fm-iorg_description textarea' ).each( function () {
		$( this ).after( '<div class="counter">300 characters remaining.</div>' );
		setCounter( this, 300 );
	}).on( 'keyup blur', function () {
		setCounter( this, 300 );
	});

	$( '.fm-iorg_title input' ).each( function () {
		$( this ).after( '<div class="counter">100 characters remaining.</div>' );
		setCounter( this, 100 );
	}).on( 'keyup blur', function () {
		setCounter( this, 100 );
	});



});
