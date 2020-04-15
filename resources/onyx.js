( function( $, mw ) {

	// Naming conventions for variables:
	//
	// - Uppercase with underscores for constants
	// - Lowercase camelCase for normal variables
	// - Prefix with '$' if the variable/constant will store jQuery objects

		/* CONSTANTS */

	/**
	 * Various time units expressed as quantities of seconds
	 */
	const SECS = 1;
	const MINS = 60 * SECS;
	const HOURS = 60 * MINS;
	const DAYS = 24 * HOURS;

	/**
	 * The expiry time for cookies relating to the sidebar and site notice,
	 * respectively
	 */
	const SIDEBAR_EXPIRY_TIME = 30 * DAYS;
	const SITE_NOTICE_EXPIRY_TIME = 7 * DAYS;

	/**
	 * The default minimum number of headings that should be included in the page
	 * contents module, if the server fails to supply a number via a HTML5 data
	 * attribute
	 */
	const DEFAULT_PAGE_CONTENTS_MIN_HEADINGS = 3;
	
	/**
	 * A shorthand for a selector query that will select all of the headings in
	 * the main body of the article, save for those within the table of contents
	 * included in some articles
	 */
	const HEADING_QUERY = '#mw-content-text h1:not(.toc h1), '
		+ '#mw-content-text h2:not(.toc h2), '
		+ '#mw-content-text h3:not(.toc h3), '
		+ '#mw-content-text h4:not(.toc h4), '
		+ '#mw-content-text h5:not(.toc h5), '
		+ '#mw-content-text h6:not(.toc h6)';

		/* FUNCTIONS */

	/**
	 * Checks for a cookie storing the sidebar state, and if one is found,
	 * expands or collapses the sidebar as appropriate
	 */
	function loadSidebarState() {
		var cookie = mw.cookie.get( 'OnyxSidebarState' ),
			$sidebar = $( '#onyx-pageBody-sidebar' );
		console.log( 'Onyx: Loaded sidebar cookie, value = ' + `"${cookie}"` );
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

	/**
	 * Checks for a cookie storing whether the site notice has been closed, and
	 * if it has been, closes the site notice unless its hash has changed (i.e.
	 * the content is not the same as it was when the user closed it)
	 */
	function loadSiteNoticeState() {
		var cookie = mw.cookie.get( 'OnyxSiteNoticeState' ),
			$siteNotice = $( '#onyx-content-siteNotice' ),
			oldHash = mw.cookie.get( 'OnyxSiteNoticeHash' ),
			newHash = $siteNotice.data( 'siteNoticeHash' );
		console.log( `Onyx:\ Loaded site notice cookies,\ value \= \"${cookie}\"\, oldHash \= \"${oldHash}\"\, newHash \= \"${newHash}"` );
		if ( cookie && oldHash && cookie == 'closed' && oldHash == newHash ) {
			// If the cookie is set to closed, and the old hash matches the new hash
			// (i.e. the site notice hasn't changed), then remove the site notice
			$siteNotice.remove();
			// Unlike the sidebar cookie, don't refresh the site notice cookie - the
			// site notice should periodically re-appear even if it doesn't change,
			// to remind the user of its content
		}
	}

	/**
	 * Loads the table of contents for the page contents sidebar module, and
	 * builds that sidebar module using the gathered data
	 */
	function loadPageContents() {
		var $contentsModule = $( '#onyx-stickyModules-pageContents' );
		// If the contents module DOM element doesn't exist, it means the page
		// contents module was disabled server-side, so do nothing
		if ( $contentsModule == null || $contentsModule[0] == null) {
			console.log( 'Onyx: Page contents module disabled server-side.' );
			return;
		}
		var minHeadings = $contentsModule.data( 'minHeadings' );
		// Resort to the default minimum number of headings if a value wasn't given
		// by the server
		if ( minHeadings == null ) {
			console.log( 'Onyx: Page contents min headings value not supplied, resorting to default.');
			minHeadings = DEFAULT_PAGE_CONTENTS_MIN_HEADINGS;
		}
		var $headings = $( HEADING_QUERY );
		// Delete the page contents module if there are not enough headings to fill
		// it
		if ( $headings.length < minHeadings ) {
			console.log( 'Onyx: Insufficient headings for page contents module.' );
			$contentsModule.remove();
			return;
		}
		// Do nothing if there are no headings to process, but the server has opted
		// to permit the page contents list to be empty, by setting minHeadings to
		// zero or less
		if ( $headings.length < 0 ) {
			console.log( 'Onyx: No headings found. Leaving contents module blank.' );
			return;
		}
		// Delegate to another function to loop through the $headings array and
		// recursively build the table of contents list
		recursivelyBuildPageContentsList(
			$( '.onyx-pageContents-list' ),
			{
				index: 0,
				num: 1
			},
			1,
			'',
			$headings
		);
	}

	/**
	 * Builds a page contents list for the given headings array, with nested
	 * lists within each list element for subheadings of the given heading.
	 * 
	 * @param {Object} $dest The jQuery object to which the list items should be appended
	 * @param {Object} pos An object encapsulating the "position" the function should start at
	 * @param {number} pos.index The index in the headings array that the function should start at
	 * @param {number} pos.num The current subheading number, used to generate the "1.3.2"-style prefix
	 * @param {number} level The level of heading the function should consider - i.e. h1 = 1, h2 = 2, etc
	 * @param {number} prefix The existing prefix for the list item label, passed down from the previous level of recursion
	 * @param {Object} $headings The array of headings being looped through
	 * @returns {Object} The new value of its pos argument, after its execution
	 */
	function recursivelyBuildPageContentsList( $dest, pos, level, prefix, $headings ) {
		// Guard for index going out of bounds
		if ( $headings[pos.index] == null) {
			return pos;
		}
		// If we're not at the right depth to process the current heading, recurse
		// again but keep the same values - so that the heading and all subsequent
		// ones are treated as immediate subheadings of the previous one, not
		// subheadings of some other phantom heading between them (i.e. so that
		// "<h2>a</h2> ... <h4>b</h4>" produces "1: a" and "1.1: b", rather than
		// "1: a" and "1.1.1: b")
		if ( level < getLevel( $headings[pos.index] ) ) {
			pos = recursivelyBuildPageContentsList(
				$dest,
				{
					index: pos.index,
					num: 1
				},
				level + 1,
				prefix,
				$headings
			);
		}
		// Move pos properties into local variables for easy access
		var i = pos.index;
		var n = pos.num;
		// Loop through the $headings array until we either hit its end, or
		// encounter a heading that's below the level that we're meant to be
		// dealing with
		while ( $headings[i] != null
			&& level <= getLevel( $headings[i] ) ) {
			// Construct a list item to insert into the list
			var $prefix = $( '<span/>', { class: 'onyx-pageContents-itemPrefix' } )
				.html( `${prefix}${n}` );
			var $label = $( '<span/> ', { class: 'onyx-pageContents-itemLabel' } )
				.html( getName( $headings[i] ) );
			var $listItem = $( '<li/>', { class: 'onyx-pageContents-listItem' } );
			if ( getId( $headings[i] ) != null ) {
				var $link = $( '<a/>', { href: `#${getId( $headings[i] )}` } )
					.append( $prefix, $label );
				$listItem.append( $link );
			} else {
				$listItem.append( $prefix, $label );
			}
			// Create a new unordered list to store the subheadings of this heading
			var $nestedList = $( '<ul/>', { class: 'onyx-pageContents-list' } );
			// Recurse down a level, passing this new list as the $dest for all list
			// items created
			i++;
			var newPos = recursivelyBuildPageContentsList(
				$nestedList,
				{
					index: i,
					num: 1
				},
				level + 1,
				`${prefix}${n}.`,
				$headings
			);
			if ( newPos.index == i ) {
				// If the index has not changed (i.e. no subheadings were found), do
				// nothing with the $nestedList, and prepend an icon to the list item
				// to show that it has no subheadings
				var $noExpandIcon = $( '#onyx-pageContents-noExpandIconTemplate' ).html();
				$listItem.prepend( $noExpandIcon );
			} else {
				// If the index *has* changed (i.e. at least one subheading was found),
				// prepend a button to the list element to allow it to be expanded and
				// collapsed by the user, and append the $nestedList, which will now
				// contain the subheadings
				var $expandButton = $( '#onyx-pageContents-expandButtonTemplate' ).html();
				$listItem.prepend( $expandButton );
				$listItem.append( $nestedList );
				// Set i to the index returned by the recursion, so that we do not
				// consider the subheadings when we go into the next iteration of this
				// loop
				i = newPos.index;
			}
			// Add the now-completed heading list item to the $dest list, increment
			// the heading number and begin the loop again
			$dest.append( $listItem );
			n++;
		}
		// Re-construct the new value of pos from the local variables, and return
		return {
			index: i,
			num: n
		}
	}
	
	/**
	 * Gets the numerical 'level' of a heading from its DOM object - i.e. h6 = 6,
	 * h5 = 5, etc
	 * 
	 * @param {object} heading The DOM object of the heading whose level to calculate
	 * @returns {number} The heading's level
	 */
	function getLevel( heading ) {
		return Number( heading.tagName.charAt( 1 ) );
	}
	
	/**
	 * Gets the display name of a heading, for the page contents table. This will
	 * first search the object's children for an element with the class
	 * 'mw-headline', and use the innerText from that, or otherwise will simply
	 * use the element's own innerText. This is to ensure that the edit section
	 * links are not included in the name.
	 * 
	 * @param {object} heading The DOM object of the heading whose name to get
	 * @returns {string} The heading's name
	 */
	function getName( heading ) {
		$children = $( heading ).children( '.mw-headline' );
		if ( $children.length > 0 ) {
			return $children[0].innerText;
		}
		return heading.innerText;
	}

	/**
	 * Gets the id of a heading, for the page contents table. This will first
	 * search the object's children for an element with the class 'mw-headline',
	 * and use the id of that element, or if none are found, it will simply
	 * use the given element's own id.
	 * 
	 * @param {object} heading The DOM object of the heading whose id to get
	 * @returns {string} The heading's id
	 */
	function getId( heading ) {
		$children = $( heading ).children( '.mw-headline' );
		if ( $children.length > 0 ) {
			return $children[0].id;
		}
		return heading.id;
	}

	/**
	 * Toggles the visibility of the sidebar
	 */
	function toggleSidebar() {
		var $sidebar = $( '#onyx-pageBody-sidebar' );
		if ( $sidebar.css( 'visibility' ) === 'visible'
			|| $sidebar.css( 'visibility' ) === '' ) {
			// If currently visible, hide the sidebar
			$sidebar.hide();
			$sidebar.css( 'visibility', 'hidden' );
			console.log( 'Onyx: Collapsed sidebar' );
			// Set a cookie to remember the user's preference
			mw.cookie.set( 'OnyxSidebarState', 'hidden', { expires: SIDEBAR_EXPIRY_TIME } );
		} else {
			// If currently not visible, show the sidebar
			$sidebar.show();
			$sidebar.css( 'visibility', 'visible' );
			console.log( 'Onyx: Expanded sidebar' );
			// Set a cookie to remember the user's preference
			mw.cookie.set( 'OnyxSidebarState', 'visible', { expires: SIDEBAR_EXPIRY_TIME } );
		}
		updateFooterHeight();
	}

	/**
	 * Closes the site notice
	 */
	function closeSiteNotice() {
		// Get the site notice and its hash value
		var $siteNotice = $( '#onyx-content-siteNotice' );
		var hash = $siteNotice.data( 'siteNoticeHash' );
		// Remove them from the DOM tree
		$siteNotice.remove();
		console.log( 'Onyx: Closed site notice' );
		// Store the user's choice to close the site notice, and the hash of the
		// message on the site notice when it was closed, as cookies 
		mw.cookie.set( 'OnyxSiteNoticeState', 'closed', { expires: SITE_NOTICE_EXPIRY_TIME } );
		mw.cookie.set( 'OnyxSiteNoticeHash', hash, { expires: SITE_NOTICE_EXPIRY_TIME } );
		// Update the height of the footer if necessary
		updateFooterHeight();
	}

	/**
	 * Updates the height of the footer, in order to make sure it always fills
	 * the space between the bottom of the page, and the bottom of the viewport,
	 * regardless of how small the page is
	 */
	function updateFooterHeight() {
		var $footer = $( '#onyx-footer' );
		// Reset the footer height to its default value
		$footer.height( 'auto' );
		if ( $(window).height() > $footer.offset().top + $footer.outerHeight( false )) {
			// If the footer is not large enough to fill the bottom of the page,
			// resize its outer height accordingly
			$footer.outerHeight( $(window).height() - $footer.offset().top, false );
		}
	}

	/**
	 * Adds a number of event handlers to buttons in the page contents module,
	 * and a 'scrollspy' event handler, to highlight the current heading in the
	 * list as the user scrolls
	 */
	function addPageContentsEventHandlers() {
		var $headings = $( HEADING_QUERY );
		// Return immediately if there are no headings - there would be no point
		// in adding event handlers in that case
		if ( $headings.length <= 0 ) {
			console.log( 'Onyx: No headings found, no event handlers registered.' );
			return;
		}
		// Return immediately if the page contents module has been disabled - there
		// would be no point in adding event handlers in that case
		if ( $( '#onyx-pageContents-content' ).length <= 0 ) {
			console.log( 'Onyx: Page contents module disabled, no event handlers registered.' );
		}
		// Pre-generate the necessary data structures, because doing so within the
		// event handlers would cause noticable lag - *especially* in the scroll
		// event handler. Performance is critical for the scroll event handler, as
		// it can cause scrolling to feel laggy for the user on slower machines, if
		// not performant enough
		var $banner = $( '#onyx-banner' );
		var sections = [ {
			$begin: null,
			$end: $( $headings[0] ),
			id: null,
			$link: $(),
			$parents: $()
		} ];
		for ( var i = 0; i < $headings.length; i++ ) {
			sections[i + 1] = {
				$begin: $( $headings[i] ),
				$end: $headings[i + 1] == null ? null : $( $headings[i + 1] ),
				id: getId( $headings[i] ),
				$link: $( `.onyx-pageContents-listItem a[href="#${getId( $headings[i] )}"]` ).parent(),
				$parents: $()
			};
			var $elem = sections[i + 1].$link;
			// The $parents list should include $link and every list item that is a
			// parent of it, up to the root of the page contents list - i.e. every
			// list item that needs to have its contents set to "expanded" in order
			// for the current list item and its direct child list to be visible
			while ( $elem.prop('tagName') == 'LI' || $elem.prop('tagName') == 'UL' ) {
				if ( $elem.hasClass( 'onyx-pageContents-listItem' ) ) {
					sections[i + 1].$parents = $.merge( sections[i + 1].$parents, $elem );
				}
				$elem = $elem.parent();
			}
		}
		// Add an event handler for each of the expand buttons in the page contents
		// list
		sections.forEach( section => {
			section.$link.children( '.onyx-pageContents-expandButton' ).click( function () {
				// The expand buttons control two classes: hardExpanded and
				// forceCollapsed. The hardExpanded class forces the heading to be
				// expanded regardless of the scroll position. Conversely, the
				// forceCollapsed class forces the heading to be collapsed, regardless
				// of the scroll position.
				if ( section.$link.hasClass( 'onyx-pageContents-hardExpanded' ) ) {
					// If the heading already has the hardExpanded class, then it that
					// class should be removed, and the forceCollapsed class should be
					// added, to guarantee the heading will be collapsed 
					section.$link.addClass( 'onyx-pageContents-forceCollapsed' );
					section.$link.removeClass( 'onyx-pageContents-hardExpanded' );
				} else if ( section.$link.hasClass( 'onyx-pageContents-softExpanded' )
					&& !section.$link.hasClass( 'onyx-pageContents-forceCollapsed' ) ) {
					// If the heading has the softExpanded class (i.e. it's been marked
					// for expansion because of the scroll position), and doesn't have
					// the forceCollapsed class (i.e. it's not already collapsed), then
					// collapse it by adding the forceCollapsed class
					section.$link.addClass( 'onyx-pageContents-forceCollapsed' );
				} else {
					// Finally, if neither of the above are the case, then the heading is
					// not already expanded, so expand it by adding the hardExpanded
					// class and removing the forceCollapsed class
					section.$parents.addClass( 'onyx-pageContents-hardExpanded' );
					section.$parents.removeClass( 'onyx-pageContents-forceCollapsed' );
				}
			} );
		} );
		// If the highlighting is disabled, return before the scroll event handler
		// is added
		if ( $( '#onyx-stickyModules-pageContents' ).data( 'enableHighlighting' ) === false ) {
			return;
		}
		// Cache the previously expanded section and a reference to the window
		// object, for performance reasons
		var prevSection = sections[0];
		var $window = $( window );
		// Add a scroll event handler to highlight the current section in the
		// page contents list
		$window.scroll( function() {
			// Generate the scroll value before going into the loop, to avoid
			// unnecessary complication
			var scroll = $window.scrollTop() + $banner.outerHeight();
			sections.forEach( section => {
				if ( prevSection != section
					&& ( section.$begin == null
						|| section.$begin.offset().top <= scroll )
					&& ( section.$end == null
						|| scroll < section.$end.offset().top ) ) {
					// If the section is not the section that was previously expanded,
					// and the user is currently viewing that section, first collapse
					// previous section but remove the forceCollapse class, if it has it,
					// so that the scroll event handler can re-expand it later, if the
					// user scrolls back past the section...
					prevSection.$link.removeClass( 'onyx-pageContents-selected onyx-pageContents-forceCollapsed' );
					prevSection.$parents.removeClass( 'onyx-pageContents-softExpanded' );
					// ... then select and expand the new section...
					section.$link.addClass( 'onyx-pageContents-selected' );
					section.$parents.addClass( 'onyx-pageContents-softExpanded' );
					// ... and finally, cache the current section as the one that's now
					// expanded.
					prevSection = section;
				}
			} );
		} );
	}

	// Once the document is ready for JavaScript manipulation, add the necessary
	// event handlers, and generated any content that is meant to be generated
	// client-side
	$( document ).ready( function () {
		// Load the initial states for the sidebar and site notice
		loadSidebarState();
		loadSiteNoticeState();
		// Generate the page contents list
		loadPageContents();
		// Update the footer height, if necessary
		updateFooterHeight();
		// Add event handlers for the sidebar toggle and site notice close buttons
		$( '#onyx-actions-toggleSidebar' ).click( toggleSidebar );
		$( '#onyx-siteNotice-closeButton' ).click( closeSiteNotice );
		// Add event handlers for interactions with the page contents table
		addPageContentsEventHandlers();
	} );

	// On window resize, update the footer height if necessary
	$( window ).resize( updateFooterHeight );

} )( jQuery, mediaWiki );
