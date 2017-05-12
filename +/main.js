/** global: wp, pollux, CodeMirror */

pollux.dependency = {};
pollux.editors = {};
pollux.featured = {};
pollux.metabox = {};
pollux.tabs = {};

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
pollux.dependency.activate = function( el )
{
	pollux.dependency.updateButtonText( el, pollux.vars.l10n.pluginActivatingLabel );
	return pollux.dependency.ajax( 'pollux/dependency/activate', el );
};

/**
 * @return void
 */
pollux.dependency.ajax = function( action, el )
{
	var args = pollux.dependency.getAjaxOptions( el );
	var options = {
		success: args.success,
		error: args.error,
	};
	delete args.success;
	delete args.error;
	options.data = args;
	wp.ajax.send( action, options );
};

/**
 * @return object
 */
pollux.dependency.getAjaxOptions = function( el )
{
	return {
		_ajax_nonce: wp.updates.ajaxNonce,
		error: pollux.dependency.onError.bind( el ),
		plugin: el.getAttribute( 'data-plugin' ),
		slug: el.getAttribute( 'data-slug' ),
		success: pollux.dependency.onSuccess.bind( el ),
	};
};

/**
 * @return void
 */
pollux.dependency.init = function()
{
	pollux.dependency.buttons = document.querySelectorAll( '.pollux-notice a.button' );
	[].forEach.call( pollux.dependency.buttons, function( button ) {
		button.addEventListener( 'click', pollux.dependency.onClick );
	});
};

/**
 * @return void
 */
pollux.dependency.install = function( el, args )
{
	pollux.dependency.updateButtonText( el, wp.updates.l10n.pluginInstallingLabel );
	return wp.updates.ajax( 'install-plugin', args );
};

/**
 * @return void
 */
pollux.dependency.onClick = function( ev )
{
	var action = this.href.match(/action=([^&]+)/);
	if( action === null )return;
	action = action[1].split('-')[0];
	if( !pollux.dependency[action] )return;
	this.blur();
	ev.preventDefault();
	if( this.classList.contains( 'updating-message' ))return;
	pollux.dependency[action]( this, pollux.dependency.getAjaxOptions( this ));
};

/**
 * @return void
 */
pollux.dependency.onError = function( response )
{
	window.location = this.href;
};

/**
 * @return void
 */
pollux.dependency.onSuccess = function( response )
{
	var el = this;
	if( response.update ) {
		return pollux.dependency.ajax( 'pollux/dependency/updated', el );
	}
	pollux.dependency.setUpdatedMessage( el );
	if( response.install ) {
		el.innerHTML = wp.updates.l10n.pluginInstalledLabel.replace( '%s', response.pluginName );
	}
	if( response.updated ) {
		el.innerHTML = wp.updates.l10n.updatedLabel.replace( '%s', response.pluginName );
	}
	if( response.activateUrl ) {
		setTimeout( function() {
			pollux.dependency.setActivateButton( el, response );
		}, 1000 );
	}
	if( response.activate ) {
		el.innerHTML = pollux.vars.l10n.pluginActivatedLabel.replace( '%s', response.pluginName );
		setTimeout( function() {
			var notice = el.closest( '.pollux-notice' );
			if( pollux.dependency.buttons.length < 2 ) {
				pollux.editors.all.forEach( function( editor, index ) {
					pollux.editors.enable( index );
				});
				notice.parentNode.removeChild( notice );
			}
			else {
				var plugin = notice.querySelector( '.plugin-' + response.slug );
				plugin.parentNode.removeChild( plugin );
				el.parentNode.removeChild( el );
			}
		}, 1000 );
	}
};

/**
 * @return void
 */
pollux.dependency.setActivateButton = function( el, response )
{
	el.classList.remove( 'updated-message' );
	el.classList.remove( 'button-disabled' );
	el.classList.add( 'button-primary' );
	el.href = response.activateUrl;
	el.innerHTML = wp.updates.l10n.activatePluginLabel.replace( '%s', response.pluginName );
};

/**
 * @return void
 */
pollux.dependency.setUpdatedMessage = function( el )
{
	el.classList.remove( 'updating-message' );
	el.classList.add( 'updated-message' );
	el.classList.add( 'button-disabled' );
};

/**
 * @return void
 */
pollux.dependency.updateButtonText = function( el, text )
{
	var label = text.replace( '%s', el.getAttribute( 'data-name' ));
	if( el.innerHTML !== label ) {
		el.innerHTML = label;
		el.classList.add( 'updating-message' );
	}
};

/**
 * @return void
 */
pollux.dependency.upgrade = function( el, args )
{
	pollux.dependency.updateButtonText( el, wp.updates.l10n.updatingLabel );
	return wp.updates.ajax( 'update-plugin', args );
};

/**
 * @return void
 */
pollux.editors.disable = function( index )
{
	pollux.editors.all[index].setOption( 'theme', 'disabled' );
	pollux.editors.all[index].setOption( 'readOnly', 'nocursor' );
	pollux.editors.all[index].getTextArea().readOnly = true;
	pollux.editors.all[index].refresh();
};

/**
 * @return void
 */
pollux.editors.enable = function( index )
{
	pollux.editors.all[index].setOption( 'theme', 'pollux' );
	pollux.editors.all[index].setOption( 'readOnly', false );
	pollux.editors.all[index].getTextArea().readOnly = false;
	pollux.editors.all[index].refresh();
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
			pollux.editors.disable( index );
		}
	});
};

/**
 * @return void
 */
pollux.featured.init = function()
{
	jQuery( '#postimagediv' )
	.on( 'click', '#pollux-set-featured', function( ev ) {
		ev.preventDefault();
		wp.media.view.settings.post.featuredImageId = Math.round( jQuery( '#featured' ).val() );
		pollux.featured.frame = wp.media.featuredImage.frame;
		pollux.featured.frame().open();
	})
	.on( 'click', '#pollux-remove-featured', function( ev ) {
		ev.preventDefault();
		pollux.featured.set(-1);
	});
};

/**
 * @return void
 */
pollux.featured.select = function()
{
	if( !wp.media.view.settings.post.featuredImageId )return;
	var selection = this.get( 'selection' ).single();
	pollux.featured.set( selection ? selection.id : -1 );
};

/**
 * @return void
 */
pollux.featured.set = function( id )
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

jQuery(function() {
	for( var key in pollux ) {
		if( !pollux[key].hasOwnProperty( 'init' ))continue;
		pollux[key].init();
	}
});
