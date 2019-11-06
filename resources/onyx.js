( function( $, mw ) {

	const SECS = 1;
	const MINS = 60 * SECS;
	const HOURS = 60 * MINS;
	const DAYS = 24 * HOURS;

	const SIDEBAR_EXPIRY_TIME = 30 * DAYS;
	const SITE_NOTICE_EXPIRY_TIME = 7 * DAYS;

	// Load sidebar state from the cookie (or at least try)
	function loadSidebarState() {
		var cookie = mw.cookie.get( 'OnyxSidebarState' ),
			$sidebar = $( '#onyx-pageBody-sidebar' );
		console.log( "Onyx: Loaded sidebar cookie, value = " + `"${cookie}"` );
		if ( cookie ) {
			// Refresh cookie - if the user is visiting the site, it implies they're
			// still actively using it and so would like their setting remembered
			mw.cookie.set( 'OnyxSidebarState', cookie, { expires: SIDEBAR_EXPIRY_TIME } );
			if ( cookie == 'hidden' ) {
				// Cookie exists and is set to 'hidden'
				$sidebar.hide();
				$sidebar.css( 'visibility', 'hidden' );
			} else {
				// Cookie is unset or is set to any value other than 'hidden'
				$sidebar.show();
				$sidebar.css( 'visibility', 'visible' );
			}
		}
	}

	// Load site notice state from the cookie (or at least try to)
	function loadSiteNoticeState() {
		var cookie = mw.cookie.get( 'OnyxSiteNoticeState' ),
			$siteNotice = $( '#onyx-content-siteNotice' ),
			oldHash = mw.cookie.get( 'OnyxSiteNoticeHash' ),
			newHash = stringHash( $siteNotice.html() );
		console.log( "Onyx: Loaded site notice cookies, value = " + `"${cookie}"` + ", hash = " + `"${oldHash}"` );
		if ( cookie && oldHash && cookie == 'closed' && oldHash == newHash ) {
			// If the cookie is set to closed, and the old hash matches the new hash
			// (i.e. the site notice hasn't changed), then remove the site notice
			$siteNotice.remove();
			// Unlike the sidebar cookie, don't refresh the site notice cookie - the
			// site notice should periodically re-appear even if it doesn't change,
			// to remind the user of its content
		}
	}

	// Toggle whether the sidebar is visible or not
	function toggleSidebar() {
		var $sidebar = $( '#onyx-pageBody-sidebar' );
		if ( $sidebar.css( 'visibility' ) === 'visible'
			|| $sidebar.css( 'visibility' ) === '' ) {
			$sidebar.hide();
			$sidebar.css('visibility', 'hidden');
			console.log( 'Onyx: Collapsed sidebar' );
			// Set a 30-day cookie
			mw.cookie.set( 'OnyxSidebarState', 'hidden', { expires: SIDEBAR_EXPIRY_TIME } );
		} else {
			$sidebar.show();
			$sidebar.css('visibility', 'visible');
			console.log( 'Onyx: Expanded sidebar' );
			// Set a 30-day cookie
			mw.cookie.set( 'OnyxSidebarState', 'visible', { expires: SIDEBAR_EXPIRY_TIME } );
		}
		updateFooterHeight();
	}

	// Close the site notice box
	function closeSiteNotice() {
		var $siteNotice = $( '#onyx-content-siteNotice' );
		var hash = stringHash( $siteNotice.html() );
		$siteNotice.remove();
		console.log( 'Onyx: Closed site notice' );
		mw.cookie.set( 'OnyxSiteNoticeState', 'closed', { expires: SITE_NOTICE_EXPIRY_TIME } );
		mw.cookie.set( 'OnyxSiteNoticeHash', hash, { expires: SITE_NOTICE_EXPIRY_TIME } );
		updateFooterHeight();
	}

	// Update footer height
	function updateFooterHeight() {
		var $footer = $( '#onyx-footer' );
		$footer.height( 'auto' );
		if ( $(window).height() > $footer.offset().top + $footer.outerHeight( false )) {
			$footer.outerHeight( $(window).height() - $footer.offset().top, false );
		}
	}

	// String hash function, roughly translated from Java's String::hashCode function
	function stringHash( string ) {
		var hash = 0;
		if ( string === null || typeof string !== 'string' || string.length === 0 ) {
			return hash;
		}
		for ( var i = 0; i < string.length; i++ ) {
			hash = (( hash << 5 ) - hash ) + string.charCodeAt( i );
			hash |= 0;
		}
		return hash;
	}

	// On page load
	$( function () {
		$( '#onyx-actions-toggleSidebar' ).click( toggleSidebar );
		$( '#onyx-siteNotice-closeButton' ).click( closeSiteNotice );
		loadSidebarState();
		loadSiteNoticeState();
		updateFooterHeight();
	} );

	$(window).resize( updateFooterHeight );

} )( jQuery, mediaWiki );