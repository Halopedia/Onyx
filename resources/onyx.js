( function( $, mw ) {

	// Load sidebar state from the cookie (or at least try)
	function loadSidebarState() {
		var cookie = mw.cookie.get( 'OnyxSidebarState' ),
			$sidebar = $( '#onyx-pageBody-sidebar' );
		console.log( `Onyx:\ Loaded sidebar cookie,\ value\ =\ "${cookie}"` );
		if ( cookie && cookie == 'hidden' ) {
			// Cookie exists and is set to 'hidden'
			$sidebar.hide();
			$sidebar.css( 'visibility', 'hidden' );
		} else {
			// Cookie is unset or is set to any value other than 'hidden'
			$sidebar.show();
			$sidebar.css( 'visibility', 'visible' );
		}
	}

	// Load site notice state from the cookie (or at least try to)
	function loadSiteNoticeState() {
		var cookie = mw.cookie.get( 'OnyxSiteNoticeState' ),
			$siteNotice = $( '#onyx-content-siteNotice' );
			console.log( `Onyx:\ Loaded site notice cookie,\ value\ =\ "${cookie}"` );
		if ( cookie && cookie == 'closed' ) {
			$siteNotice.remove();
		}
	}

	function toggleSidebar() {
		var $sidebar = $( '#onyx-pageBody-sidebar' );
		if ( sidebar.style.visibility === 'visible' || sidebar.style.visibility === '' ) {
			$sidebar.hide();
			$sidebar.css('visibility', 'hidden');
			console.log( 'Onyx: Collapsed sidebar' );
			// Set a 30-day cookie
			mw.cookie.set( 'OnyxSidebarState', 'hidden', { expires: 30 } );
		} else {
			$sidebar.show();
			$sidebar.css('visibility', 'visible');
			console.log( 'Onyx: Expanded sidebar' );
			// Set a 30-day cookie
			mw.cookie.set( 'OnyxSidebarState', 'visible', { expires: 30 } );
		}
	}

	function closeSiteNotice() {
		$( '#onyx-content-siteNotice' ).remove();
		console.log( 'Onyx: Closed site notice' );
		mw.cookie.set( 'OnyxSiteNoticeState', 'closed', { expires: 30 } );
	}

	$( function () {
		$( '#onyx-actions-toggleSidebar' ).click( toggleSidebar );
		$( '#onyx-siteNotice-closeButton' ).click( closeSiteNotice );
		loadSidebarState();
		loadSiteNoticeState();
	} );

} )( jQuery, mediaWiki );