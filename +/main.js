/** global: wp, CodeMirror */

var pollux = {
	editors: {},
	media: {
		featured: {},
	},
	metabox: {},
	tabs: {},
};

/**
 * @return bool
 */
pollux.classListAction = function( bool )
{
	return bool ? 'add' : 'remove';
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
		_wpnonce: document.querySelector( '#_wpnonce' ).value,
		post_type: document.querySelector( '#archive-type' ).value,
		thumbnail_id: id,
	}).done( function( html ) {
		document.querySelector( '#postimagediv > .inside' ).innerHTML = html;
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
			pollux.metabox.setVisibility( el );
		});
	});
};

/**
 * @return element
 */
pollux.metabox.setVisibility = function( el )
{
	var dependency = document.getElementById( el.getAttribute( 'data-depends' ));
	var action = pollux.classListAction( !pollux.metabox.hasValue( dependency ));
	el.closest( '.rwmb-field' ).classList[action]( 'hidden' );
	return dependency;
};

/**
 * @return void
 */
pollux.tabs.init = function()
{
	pollux.tabs.active = document.querySelector( '#pollux-active-tab' );
	pollux.tabs.referrer = document.querySelector( 'input[name="_wp_http_referer"]' );
	pollux.tabs.tabs = document.querySelectorAll( '.pollux-tabs a' );
	pollux.tabs.views = document.querySelectorAll( '.pollux-config .form-table' );

	[].forEach.call( pollux.tabs.tabs, function( tab, index ) {
		var active = location.hash ? tab.getAttribute( 'href' ).slice(1) === location.hash.slice(2) : index === 0;
		if( active ) {
			pollux.tabs.setTab( tab );
		}
		tab.addEventListener( 'click', pollux.tabs.onClick );
		tab.addEventListener( 'touchend', pollux.tabs.onClick );
	});
};

/**
 * @return void
 */
pollux.tabs.onClick = function( ev )
{
	ev.preventDefault();
	this.blur();
	pollux.tabs.setTab( this );
	location.hash = '!' + this.getAttribute( 'href' ).slice(1);
};

/**
 * @return void
 */
pollux.tabs.setReferrer = function( index )
{
	var referrerUrl = pollux.tabs.referrer.value.split('#')[0] + '#!' + pollux.tabs.views[index].id;
	pollux.tabs.referrer.value = referrerUrl;
};

/**
 * @return void
 */
pollux.tabs.setTab = function( el )
{
	[].forEach.call( pollux.tabs.tabs, function( tab, index ) {
		var action = pollux.classListAction( tab === el );
		if( action === 'add' ) {
			pollux.tabs.active.value = pollux.tabs.views[index].id;
			pollux.tabs.setReferrer( index );
			pollux.tabs.setView( index );
		}
		tab.classList[action]( 'nav-tab-active' );
	});
};

/**
 * @return void
 */
pollux.tabs.setView = function( idx )
{
	[].forEach.call( pollux.tabs.views, function( view, index ) {
		var action = pollux.classListAction( index !== idx );
		view.classList[action]( 'ui-tabs-hide' );
	});
};

/**
 * @return void
 */
pollux.editors.init = function()
{
	pollux.editors.all = [];
	[].forEach.call( document.querySelectorAll( '.pollux-code' ), function( editor, index ) {
		pollux.editors.all[index] = CodeMirror.fromTextArea( editor, {
			gutters: ['CodeMirror-lint-markers'],
			highlightSelectionMatches: { wordsOnly: true },
			lineNumbers: true,
			lint: true,
			mode: 'text/yaml',
			showInvisibles: true,
			showTrailingSpace: true,
			styleActiveLine: true,
			tabSize: 2,
			theme: 'pollux',
			viewportMargin: Infinity,
		});
		pollux.editors.all[index].setOption( 'extraKeys', {
			Tab: function( cm ) {
				var spaces = Array( cm.getOption( 'indentUnit' ) + 1 ).join( ' ' );
				cm.replaceSelection( spaces );
			},
		});
		pollux.editors.all[index].display.wrapper.setAttribute( 'data-disabled', editor.getAttribute( 'data-disabled' ));
		if( editor.readOnly ) {
			pollux.editors.all[index].setOption( 'theme', 'disabled' );
			pollux.editors.all[index].setOption( 'readOnly', 'nocursor' );
		}
	});
};

jQuery(function() {
	pollux.editors.init();
	pollux.media.featured.init();
	pollux.metabox.init();
	pollux.tabs.init();
});
