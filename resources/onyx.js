( function( $, mw ) {

	// Load sidebar state from the cookie (or at least try)
	function loadSidebarState() {
		var cookie = mw.cookie.get( 'onyx-sidebar-state' ),
			$sidebar = $( '#onyx-pageBody-sidebar' );
		if ( !cookie || cookie == 'hidden' ) {
			$sidebar.hide();
		} else {
			// Cookie is presumably set and is 'visible'
			$sidebar.show();
		}
	}

	function toggleSidebar() {
		// Both 'visibility' and 'display' seem to be empty on the initial page load, not sure why
		// @todo FIXME: This could be simplified a lot with jQuery. But hey, this works, so there's that.
		var sidebar = document.getElementById( 'onyx-pageBody-sidebar' );
		if ( sidebar.style.visibility === 'visible' || sidebar.style.visibility === '' ) {
			sidebar.style.display = 'none';
			sidebar.style.visibility = 'hidden';
			console.log( 'Collapsed sidebar' );
			// Set a 30-day cookie
			mw.cookie.set( 'onyx-sidebar-state', 'hidden', { expires: 30 } );
		} else {
			sidebar.style.display = 'block';
			sidebar.style.visibility = 'visible';
			console.log( 'Expanded sidebar' );
			// Set a 30-day cookie
			mw.cookie.set( 'onyx-sidebar-state', 'visible', { expires: 30 } );
		}
	}

	$( function () {
		$( '#onyx-actions-toggleSidebar' ).click( toggleSidebar );
		loadSidebarState();
	} );

} )( jQuery, mediaWiki );