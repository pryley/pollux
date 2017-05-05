/** global: wp */

var pollux = {
	media: {
		featured: {},
	},
	metabox: {},
};

/**
 * @return void
 */
pollux.media.featured.init = function()
{
	jQuery( '#postimagediv' )
	.on( 'click', '#pollux-set-featured', function( ev ) {
		ev.preventDefault();
		wp.media.view.settings.post.featuredImageId = Math.round( jQuery( '#featured' ).val() );
		pollux.media.featured.frame = wp.media.featuredImage.frame;
		pollux.media.featured.frame().open();
	})
	.on( 'click', '#pollux-remove-featured', function( ev ) {
		ev.preventDefault();
		pollux.media.featured.frame = wp.media.featuredImage.frame;
		pollux.media.featured.set(-1);
	});
};

/**
 * @return void
 */
pollux.media.featured.select = function()
{
	if( !wp.media.view.settings.post.featuredImageId )return;
	var selection = this.get( 'selection' ).single();
	pollux.media.featured.set( selection ? selection.id : -1 );
};

/**
 * @return void
 */
pollux.media.featured.set = function( id )
{
	wp.media.view.settings.post.featuredImageId = Math.round( id );
	wp.media.post( 'pollux/archives/featured/html', {
		_wpnonce: jQuery( '#_wpnonce' ).val(),
		post_type: jQuery( '#archive-type' ).val(),
		thumbnail_id: id,
	}).done( function( html ) {
		jQuery( '.inside', '#postimagediv' ).html( html );
	});
};

/**
 * @return bool
 */
pollux.metabox.hasValue = function( el )
{
	if( el.type === 'checkbox' ) {
		return el.checked === true;
	}
	return el.value !== '';
};

/**
 * @return void
 */
pollux.metabox.init = function()
{
	var depends = document.querySelectorAll( '.rwmb-input [data-depends]' );
	[].forEach.call( depends, function( el ) {
		var dependency = pollux.metabox.setVisibility( el );
		var event = dependency.type === 'checkbox' ? 'change' : 'keyup';
		dependency.addEventListener( event, function() {
			pollux.metabox.onChangeValue( el );
		}, false );
	});
};

/**
 * @return void
 */
pollux.metabox.onChangeValue = function( el )
{
	pollux.metabox.setVisibility( el );
};

/**
 * @return element
 */
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
	pollux.media.featured.init();
	pollux.metabox.init();
});
