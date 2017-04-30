var pollux = {
	metabox: {},
};

pollux.metabox.hasValue = function( el )
{
	if( el.type === 'checkbox' ) {
		return el.checked === true;
	}
	return el.value !== '';
};

pollux.metabox.onChangeValue = function( el )
{
	pollux.metabox.setVisibility( el );
};

pollux.metabox.setVisibility = function( el )
{
	var dependency = document.getElementById( el.getAttribute( 'data-depends' ));
	var field = el.closest( '.rwmb-field' );
	if( pollux.metabox.hasValue( dependency )) {
		field.classList.remove( 'hidden' );
	}
	else {
		field.classList.add( 'hidden' );
	}
	return dependency;
};

jQuery(function() {

	var depends = document.querySelectorAll( '.rwmb-input [data-depends]' );

	[].forEach.call( depends, function( el ) {
		var dependency = pollux.metabox.setVisibility( el );
		var event = dependency.type === 'checkbox' ? 'change' : 'keyup';
		dependency.addEventListener( event, function() {
			pollux.metabox.onChangeValue( el );
		}, false );
	});

});
