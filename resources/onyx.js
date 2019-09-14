( function( $, mw ) {

	function onyx_toggleSidebar() {
		// Both 'visibility' and 'display' seem to be empty on the initial page load, not sure why
		// @todo FIXME: This could be simplified a lot with jQuery. But hey, this works, so there's that.
		// @todo FIXME: Should probably store the state somewhere via a cookie or something?
		var sidebar = document.getElementById( 'onyx-pageBody-sidebar' );
		if ( sidebar.style.visibility === 'visible' || sidebar.style.visibility === '' ) {
			sidebar.style.display = 'none';
			sidebar.style.visibility = 'hidden';
			console.log( 'Collapsed sidebar' );
		} else {
			sidebar.style.display = 'block';
			sidebar.style.visibility = 'visible';
			console.log( 'Expanded sidebar' );
		}
	}

	$( function () {
		$( '#onyx-actions-toggleSidebar' ).click( onyx_toggleSidebar );
	} );

} )( jQuery, mediaWiki );