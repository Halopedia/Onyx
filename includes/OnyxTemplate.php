<?php
/**
 * BaseTemplate class for the Onyx skin.
 *
 * @file
 * @ingroup Skins
 */

class OnyxTemplate extends BaseTemplate {

	/* TODO:
	 *
	 * - Personal tools
	 * - Language links
	 * - Search form
	 *
	 * FUTURE EXTENSIONS:
	 *
	 * - Implement dark scheme using CSS media query prefers-color-scheme: dark
	 * - Read Onyx-specific configuration settings from MediaWiki:Onyx.ini
	 * - Read Onyx-specific navigation links from MediaWiki:Onyx-navigation
	 * - Read Onyx-specific toolbox links from MediaWiki:Onyx-toolbox
	 * - Read user-defined Onyx toolbox links from User:USERNAME/Onyx-toolbox
	 * - Support VisualEditor
	*/

	/**
	 * Outputs the entire contents of the page in HTML form.
	 */
	public function execute() : void {
		// TODO: Load config options from MediaWiki:Onyx.ini and, if enabled by the
		//			 wiki, from User:CURRENT_USER/Onyx.ini

		// TODO: Gather all additional data required by the unique features of the
		//			 Onyx skin (recent changes and page contents sidebar modules,
		//			 custom navigation and toolbox, etc) and add it to the data array
		//			 so that those features can be constructed in the same manner as
		//			 the ones using the standard MediaWiki API

		// Initialise HTML string as a empty string
		$html = '';

		// Concatenate auto-generated head element onto HTML string
		$html .= $this->get( 'headelement' );

		// Build banner
		$this->buildBanner( $html );

		// Build page content
		$this->buildPage( $html );

		// Build footer
		$this->buildFooter( $html );

		// Build toolbox
		$this->buildToolbox( $html );

		// Concatenate auto-generated trail onto HTML string
		$html .= $this->getTrail();

		// Print the entire page's HTML code at once
		echo $html;
	}

//////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////
////////////////////////                              ////////////////////////
////////////////////////            BANNER            ////////////////////////
////////////////////////                              ////////////////////////
//////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////

	/**
	 * Builds HTML code for the banner that appears at the top of each page, and
	 * appends it to the string passed to it.
	 *
	 * @param $html string The string onto which the HTML should be appended
	 */
	protected function buildBanner( string &$html ) : void {
		// Open container section for banner
		$html .= Html::openElement( 'section', [ 'id' => 'onyx-banner' ] );

		// Open container div for banner content
		$html .= Html::openElement( 'div', [ 'id' => 'onyx-banner-content' ] );

		// Build banner logo (floats on the left of the div)
		$this->buildBannerLogo( $html );

		// Build user options/login button (floats on the right of the div)
		$this->buildUserOptions( $html );

		// Build the search bar
		$this->buildSearchBar( $html );

		// Close container div for banner content
		$html .= Html::closeElement( 'div' );

		// Close container section for banner
		$html .= Html::closeElement( 'section' );
	}

	/**
	 * Builds HTML code to present a logo for the wiki on the main banner and
	 * appends it to the string passed to it.
	 *
	 * @param $html string The string onto which the HTML should be appended
	 */
	protected function buildBannerLogo( string &$html ) : void {
		// Open container div
		$html .= Html::openElement( 'div', [ 'id' => 'onyx-banner-bannerLogo' ] );

		// TODO: Build logo

		// Close container div
		$html .= Html::closeElement( 'div' );
	}

	/**
	 * Builds HTML code to present the search form to the user, and appends it to
	 * string passed to it.
	 *
	 * @param $html string The string onto which the HTML should be appended
	 */
	protected function buildSearchBar( string &$html ) : void {
		// Open container div
		$html .= Html::openElement( 'div', [ 'id' => 'onyx-banner-search' ] );

		// TODO: Build search bar

		// Close container div
		$html .= Html::closeElement( 'div' );
	}

	/**
	 * Builds HTML code to present the user account-related options to the reader
	 * and appends it to the string passed to it.
	 *
	 * @param $html string The string onto which the HTML should be appended
	 */
	protected function buildUserOptions( string $html ) : void {
		// Open container div
		$html .= Html::openElement( 'div', [ 'id' => 'onyx-banner-userOptions' ] );

		// TODO: Build user options menu

		// Close container div
		$html .= Html::closeElement( 'div' );
	}

//////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////
////////////////////////                              ////////////////////////
////////////////////////               PAGE           ////////////////////////
////////////////////////                              ////////////////////////
//////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////

	/**
	 * Builds HTML code to present the bulk of the webpage - the actual page
	 * content itself and appends it to the string passed to it.
	 *
	 * @param $html string The string onto which the HTML should be appended
	 */
	protected function buildPage( string &$html ) : void {
		// Open container element for page
		$html .= Html::openElement( 'main',
				[ 'id' => 'onyx-page', 'class' => 'onyx-pageAligned mw-body' ] );

		// Build the header
		$this->buildHeader( $html );

		// Open container element for page body (i.e. actual content such as the
		// article and the sidebar)
		$html .= Html::openElement( 'section', [ 'id' => 'onyx-page-pageBody' ] );

		// Build the sidebar
		$this->buildSidebar( $html );

		// Build the article content
		$this->buildArticle( $html );

		// Close container element for page body
		$html .= Html::closeElement( 'section' );

		// Close container element for page
		$html .= Html::closeElement( 'main' );
	}

//////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////
////////////////////////                              ////////////////////////
////////////////////////           HEADER             ////////////////////////
////////////////////////                              ////////////////////////
//////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////

	/**
	 * Builds HTML code to create the page's header, and appends it to the
	 * string passed to it.
	 *
	 * @param $html string The string onto which the HTML should be appended
	 */
	protected function buildHeader( string &$html ) : void {
		// Open container element for header
		$html .= Html::openElement( 'header', [ 'id' => 'onyx-page-header' ] );

		// Build wiki header
		$this->buildWikiHeader( $html );

		// Build article header
		$this->buildArticleHeader( $html );

		// Close container element
		$html .= Html::closeElement( 'header' );
	}

//////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////
////////////////////////                              ////////////////////////
////////////////////////           WIKI HEADER        ////////////////////////
////////////////////////                              ////////////////////////
//////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////

	/**
	 * Builds HTML code to present global header for wiki pages, containing
	 * content such as the logo, navigation links and wiki name/tagline, and then
	 * appends it to the string passed to it.
	 *
	 * @param $html string The string onto which the HTML should be appended
	 */
	protected function buildWikiHeader( string &$html ) : void {
		// Open container div for wiki header
		$html .= Html::openElement( 'div', [ 'id' => 'onyx-header-wikiHeader' ] );

		// Build the header logo
		$this->buildHeaderLogo( $html );

		// Build the tagline heading
		$this->buildTagline( $html );

		// Build the global navigation options
		$this->buildGlobalNav( $html );

		// Close container div
		$html .= Html::closeElement( 'div' );
	}

	/**
	 * Builds HTML code for the wiki's logo in the header, and appends it to the
	 * string passed to it.
	 *
	 * @param $html string The string onto which the HTML should be appended
	 */
	protected function buildHeaderLogo( string &$html ) : void {
		// Open container div for logo
		$html .= Html::openElement( 'div', [ 'id' => 'onyx-wikiHeader-logo' ] );

		// Open link element
		$html .= Html::openElement( 'a',
				array_merge( [ 'href' => $this->data['nav_urls']['mainpage']['href']],
						Linker::tooltipAndAccesskeyAttribs( 'p-logo' ) ) );

		// Insert logo image
		$html .= Html::rawElement( 'img', [
			'id' => 'onyx-logo-image',
			'src' => $this->get( 'logopath' ),
			'alt' => $this->get( 'sitename' )
		] );

		// Close link element
		$html .= Html::closeElement( 'a' );

		// Close container div
		$html .= Html::closeElement( 'div' );
	}

	/**
	 * Builds HTML code to display the tagline (or alternatively wiki name) at
	 * top of the header, and appends it to the string that it is passed.
	 *
	 * @param $html string The string onto which the HTML should be appended
	 */
	protected function buildTagline( string &$html ) : void {
		// Open container div for tagline
		$html .= Html::openElement( 'div', [ 'id' => 'onyx-wikiHeader-tagline' ] );

		// Create heading element containing the tagline, or alternatively the wiki
		// name if no tagline is available
		$html .= Html::rawElement( 'h1', [ 'id' => 'onyx' ],
				empty( $this->data['tagline'] )
				? $this->data['sitename']
				: $this->data['tagline'] );

		// Close container div
		$html .= Html::closeElement( 'div' );
	}

	/**
	 * Builds HTML code to display the tagline (or alternatively wiki name) at
	 * top of the header, and appends it to the string that it is passed.
	 *
	 * @param $html string The string onto which the HTML should be appended
	 */
	protected function buildGlobalNav( string &$html ) : void {
		// Open container element for navigation links
		$html .= Html::openElement( 'nav', [ 'id' => 'onyx-wikiHeader-navigation' ] );

		// Open container element for list
		$html .= Html::openElement( 'ul', [ 'id' => 'onyx-navigation-list' ] );

		// Unset the search, toolbox and languages options from the sidebar array,
		// so that only the navigation will be displayed
		unset( $this->data['sidebar']['SEARCH'] );
		unset( $this->data['sidebar']['TOOLBOX'] );
		unset( $this->data['sidebar']['LANGUAGES'] );

		foreach ( $this->getSidebar() as $boxName => $box ) {
			// In some instances, getSidebar() will include the toolbox even when
			// data['sidebar']['TOOLBOX'] is unset, so skip any boxNames that don't
			// equal 'navigation'
			if ( $boxName !== 'navigation' ) {
				continue;
			}

			if ( is_array( $box['content'] ) ) {
				foreach ( $box['content'] as $key => $item ) {
					$html .= $this->makeListItem( $key, $item );
				}
			} else {
				$html .= Html::rawElement( 'li', [], $box['content'] );
			}
		}

		// Close container element for link list
		$html .= Html::closeElement( 'ul' );

		// Close container element for global nav
		$html .= Html::closeElement( 'nav' );
	}

//////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////
////////////////////////                              ////////////////////////
////////////////////////        ARTICLE HEADER        ////////////////////////
////////////////////////                              ////////////////////////
//////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////


	/**
	 * Builds HTML code to display the article section of the header dedicated to
	 * article-specific information and options, such as the title and content
	 * action buttons, and then appends it to the string passed to it.
	 *
	 * @param $html string The string onto which the HTML should be appended
	 */
	protected function buildArticleHeader( string &$html ) : void {
		// Open container div for article header
		$html .= Html::openElement( 'div', [ 'id' => 'onyx-header-articleHeader' ] );

		// Open container div for article action options
		$html .= Html::openElement( 'div', [ 'id' => 'onyx-articleHeader-actions' ] );

		// Insert page status indicators
		$html .= $this->getIndicators();

		// Build content action buttons
		$this->buildActionButtons( $html );

		// Close container div for action options
		$html .= Html::closeElement( 'div' );

		// Insert article title
		$html .= Html::rawElement( 'h1',
				[ 'id' => 'onyx-articleHeader-title' ],
				$this->get( 'title' ) );

		// If it exists, insert the subtitle
		if ( !empty( $this->data['subtitle'] ) ) {
			$html .= Html::rawElement( 'div',
					[ 'id' => 'onyx-articleHeader-subtitle' ],
					$this->get( 'subtitle' ) );
		}

		// If it exists, insert the article undelete message
		if ( !empty( $this->data['subtitle'] ) ) {
			$html .= Html::rawElement( 'div',
					[ 'id' => 'onyx-articleHeader-undelete' ],
					$this->get( 'undelete' ) );
		}

		// Close container div for article header
		$html .= Html::closeElement( 'div' );
	}

	// TODO: Clean up the following three functions (buildActionButtons,
	//			 buildActionButton and buildActionDropdown).

	/**
	 * Builds HTML code to present the content action options to the user, and
	 * appends it to the string passed to it.
	 *
	 * @param $html string The string onto which the HTML should be appended
	 */
	protected function buildActionButtons( string &$html ) : void {
		$stylePath = $this->getSkin()->getConfig()->get( 'StylePath' );
		$edit = null;
		$talk = null;
		$sidebar = [
			'id' => 'onyx-actions-toggleSidebar',
			'class' => 'onyx-button onyx-button-secondary onyx-button-action',
			'imgSrc' => $stylePath . '/Onyx/resources/icons/sidebar-collapse.svg',
			'text' => 'Sidebar'
		];
		$dropdown = [];

		// Sort through the flat content actions array provided by the API, and
		// extract, discard and modify what is necessary
		foreach ( $this->data['content_actions'] as $key => $tab ) {
			// Discard any content actions of the form 'nstab-***'. These correspond
			// to the options to view the page itself, which have no need to be
			// presented to the user when they are already on the page
			if ( substr( $key, 0, 6 ) === 'nstab-' ) {
				continue;
			}

			switch ( $key ) {
				// If the action is edit or view source, assign the tab array to the
				// edit variable, and specify the path to the image to use as the
				// button's icon
				case 'edit':
					$edit = $tab;
					$edit['imgSrc'] = $stylePath . '/Onyx/resources/icons/edit.svg';
					break;
				case 'viewsource':
					$edit = $tab;
					$edit['imgSrc'] = $stylePath . '/Onyx/resources/icons/view.svg';
					break;
				// If the action is talk, assign the tab array to the talk variable and
				// specify the path to the button icon
				case 'talk':
					$talk = $tab;
					$talk['imgSrc'] = $stylePath . '/Onyx/resources/icons/talk.svg';
					break;
				// If the action is add section, then replace the tooltip (which is, by
				// default, just a '+') with 'Add new section', a more appropriate
				// message for a drop-down list format and then DELIBERATELY fall
				// through to the default case
				case 'addsection':
					$tab['text'] = 'Add new section';
				// Finally, if the content action is none of the above, add it to the
				// growing array of miscellaneous content actions to be displayed in a
				// drop-down list beneath the edit/view soure button
				default:
					$dropdown[$key] = $tab;
					break;
			}
		}

		// Add Onyx-specific IDs and classes to the edit and talk buttons
		if ( !empty( $edit ) ) {
			$edit['id'] .= ' onyx-actions-edit';
			$edit['class'] .= ' onyx-button onyx-button-primary onyx-button-action';
		}
		if ( !empty( $talk ) ) {
			$talk['id'] .= ' onyx-actions-talk';
			$talk['class'] .= ' onyx-button onyx-button-secondary onyx-button-action';
		}

		// If the edit content action is available, display it as a button
		if ( $edit !== null ) {
			$this->buildActionButton( $html, $edit );
		}

		// If there are one or more miscellaneous content actions available,
		// display them as a drop-down list following the edit button
		if ( sizeof( $dropdown ) > 0 ) {
			$this->buildActionDropdown( $html, $dropdown );
		}

		// If the talk content action is available, display it as a button
		if ( $talk !== null ) {
			$this->buildActionButton( $html, $talk );
		}

		// Finally, display the sidebar toggle button, which will always be
		// available
		$this->buildActionButton( $html, $sidebar );
	}

	/**
	 * Builds HTML code to for an individual content action button, and appends
	 * it to the string passed
	 *
	 * @param $html string The string onto which the HTML should be appended
	 * @param $info array An array with the necessary info to build the button
	 */
	protected function buildActionButton( string &$html, array $info ) : void {
		// If the button links to another page, surround it in an <a> element that
		// links there
		if ( !empty( $info['href'] ) ) {
			$html .= Html::openElement( 'a', [ 'href' => $info['href'],
					'title' => $info['title'] ?? '' ] );
		}

		// Open a <div> for the button
		$html .= Html::openElement( 'div', [
			'id' => $info['id'],
			'class' => $info['class']
		] );

		// If the button is to have an icon, create an appropriate <img> element
		if ( $info['imgSrc'] ) {
			$html .= Html::rawElement( 'img', [ 'src' => $info['imgSrc'] ] );
		}

		// Place the button text in a <span> element
		$html .= Html::rawElement( 'span', [ 'class' => 'onyx-button-text' ],
				$info['text'] );

		// Close the main button <div> element
		$html .= Html::closeElement( 'div' );

		// If necessary, close the <a> element surrounding the button too
		if ( isset( $info['href'] ) ) {
			$html .= Html::closeElement( 'a' );
		}
	}

	/**
	 * Builds HTML code to for a drop-down list of selectable content actions,
	 * and appends it to a given string
	 *
	 * @param $html string The string onto which the HTML should be appended
	 * @param $info array An array of items which should be placed in the list
	 */
	protected function buildActionDropdown( string &$html, array $items ) : void {
		// Open a <div> element to contain the entire drop-down
		$html .= Html::openElement( 'div', [
			'class' => 'onyx-dropdown',
			'id' => 'onyx-actions-actionsList'
		] );

		// Create a button div that will display the list when hovered over (this
		// is achieved via CSS styling of the onyx-dropdown, onyx-dropdown-button
		// and onyx-dropdown-list classes)
		$html .= Html::rawElement( 'div', [
			'class' => 'onyx-button ' .
				'onyx-button-primary onyx-button-action onyx-dropdown-button',
			'id' => 'onyx-actionsList-button'
		], Onyx\Icon::getIcon( 'dropdown' )->makeSvg() );

		// Open an <ul> element to contain the list itself
		$html .= Html::openElement( 'ul', [
			'class' => 'onyx-dropdown-list',
			'id' => 'onyx-actionsList-list'
		] );

		// Step through the array and use the makeListItem to convert each of the
		// items into a properly formatted HTML <li> element
		foreach ( $items as $key => $value ) {
			$html .= $this->makeListItem( $key, $value );
		}

		// Close the <ul> list container
		$html .= Html::closeElement( 'ul' );

		// Close the <div> container
		$html .= Html::closeElement( 'div' );
	}

//////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////
////////////////////////                              ////////////////////////
////////////////////////        PAGE SIDEBAR          ////////////////////////
////////////////////////                              ////////////////////////
//////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////

	/**
	 * Builds HTML code for the sidebar and its content, and appends it to the
	 * string that is passed to it.
	 *
	 * @param $html string The string onto which the HTML should be appended
	 */
	protected function buildSidebar( string &$html ) : void {
		// Open container element for sidebar
		$html .= Html::openElement( 'aside', [
			'id' => 'onyx-pageBody-sidebar',
			'class' => 'onyx-sidebarAligned'
		] );

		// Open container div for static sidebar modules
		$html .= Html::openElement( 'div', [ 'id' => 'onyx-sidebar-staticModules' ] );

		// Build the static custom sidebar module
		$this->buildStaticCustomModule( $html );

		// Build the recent changes module
		$this->buildRecentChangesModule( $html );

		// Close container div for static modules
		$html .= Html::closeElement( 'div' );

		// Open container div for sticky sidebar modules
		$html .= Html::openElement( 'div', [ 'id' => 'onyx-sidebar-stickyModules' ] );

		// Build the article contents navigation module
		$this->buildPageContentsModule( $html );

		// Build the sticky custom module
		$this->buildStickyCustomModule( $html );

		// Close container div for sticky modules
		$html .= Html::closeElement( 'div' );

		// Close container element for sidebar
		$html .= Html::closeElement( 'aside' );
	}

	/**
	 * Builds HTML code to display the wiki's static custom Onyx sidebar module,
	 * and appends it to the string it is passed.
	 *
	 * @param $html string The string onto which the HTML should be appended
	 */
	protected function buildStaticCustomModule( string &$html ) : void {
		global $wgOut;

		// Open container div for module
		$html .= Html::openElement( 'div', [
			'id' => 'onyx-staticModules-custom',
			'class' => 'onyx-sidebarModule onyx-sidebarModule-static'
		] );

		// Have the MediaWiki parser output the Template:Onyx/Sidebar/Static page
		// and insert it into the page
		// @todo FIXME: use parseAsContent() instead, OutputPage#parse is deprecated since MW 1.32
		$html .= $wgOut->parse( '{{Onyx/Sidebar/Static}}' );

		// Close container div for module
		$html .= Html::closeElement( 'div' );
	}

	/**
	 * Builds HTML code to display a sidebar module showing recent changes and
	 * wiki activity, then appends it to the string it is passed.
	 *
	 * @param $html string The string onto which the HTML should be appended
	 */
	protected function buildRecentChangesModule( string &$html ) : void {
		global $wgOut;

		// Open container div for module
		$html .= Html::openElement( 'div', [
			'id' => 'onyx-staticModules-recentChanges',
			'class' => 'onyx-sidebarModule onyx-sidebarModule-static'
		] );

		// Insert module title
		$html .= Html::rawElement( 'h2', [
			'id' => 'onyx-recentChanges-heading',
			'class' => 'onyx-sidebarHeading onyx-sidebarHeading-static'
		], 'Recent Changes' );

		// Open container div for module content
		$html .= Html::openElement( 'div', [ 'id' => 'onyx-recentChanges-content' ] );

		// TODO: Insert recent changes information

		// Reference for this function:

		// https://github.com/wikimedia/mediawiki-extensions-SocialProfile/blob/master/UserActivity/includes/SiteActivityHook.php

		// Close container div for module content
		$html .= Html::closeElement( 'div' );

		// Close container div for module
		$html .= Html::closeElement( 'div' );
	}

	/**
	 * Retrieves the article contents navigation list from the article content
	 * and builds HTML code to display it to the user as a sidebar module, then
	 * appends this HTML to the string passed to it.
	 *
	 * @param $html string The string onto which the HTML should be appended
	 */
	protected function buildPageContentsModule( string &$html ) : void {
		// HACK: The following function uses messy-looking regexes and is
		//			 generally very inelegant. Ideally, at some point in future, the
		//			 entire OnyxTemplate class should be refactored such that all extra
		//			 data needed by some of Onyx's unique functionalities is extracted
		//			 or otherwise gathered by some helper class, and then added to the
		//			 data array before the page begins being built at all. Then, these
		//			 unique parts of the skin can be constructed in the manner as the
		//			 parts that use the data provided by the default MediaWiki API.

		// Do nothing if we're on NS_SPECIAL (or other virtual namespaces, though
		// de facto only NS_SPECIAL pages are exposed in the UI)
		if ( $this->getSkin()->getTitle()->getNamespace() < 0 ) {
			return;
		}

		// Use regexes to extract the raw HTML for every header in the body
		// content. In order to avoid accidentally matching the header contained in
		// the table of contents generated by the parser, we specify that
		// immediately within the header, a span tag must be opened (this is the
		// case for all headers naturally generated by the parser in the page body)
		$numHeadings = preg_match_all( "/<h[123456][^>]*><span[^>]*>.*<\/h[123456]>/",
			$this->data['bodycontent'], $headings );

		// If there are not at least two headings on the page, don't bother to
		// render the page contents module at all
		if ( $numHeadings <= 2 ) {
			return;
		}

		// Open container div for module
		$html .= Html::openElement( 'div', [
			'id' => 'onyx-stickyModules-pageContents',
			'class' => 'onyx-sidebarModule onyx-sidebarModule-sticky'
		] );

		// Insert module title
		$html .= Html::rawElement( 'h2', [
			'id' => 'onyx-pageContents-heading',
			'class' => 'onyx-sidebarHeading onyx-sidebarHeading-sticky'
		], 'Contents' );

		// Open container div for module content
		$html .= Html::openElement( 'div', [ 'id' => 'onyx-pageContents-content' ] );

		// Open ordered list tag for contents list
		$html .= Html::openElement( 'ol', [ 'id' => 'onyx-pageContents-list' ] );

		// Loop through the headings, delete the unnecessary parts of the raw HTML
		// using regexes, to isolate only the heading title, then add this as a
		// list element to the HTML

		// TODO: Add links, and implement code to display subheading as tree
		//			 rather than a flat list
		for ( $i = 0; $i < $numHeadings; $i++ ) {
			$headings[0][$i] = preg_replace(
					"/(<span[^>]*class=\"[^\"]*mw-editsection-bracket[^\"]*\"[^>]*>[^<]*<\/span>)"
					."|edit"
					."|(<[^>]*>)/",
					 '', $headings[0][$i] );
			$html .= Html::rawElement( 'li', [], $headings[0][$i] );
		}

		// Close unordered list tag for contents lsit
		$html .= Html::closeElement( 'ol' );

		// Close container div for module content
		$html .= Html::closeElement( 'div' );

		// Close container div for module
		$html .= Html::closeElement( 'div' );
	}

	/**
	 * Builds HTML code to display the wiki's sticky custom Onyx sidebar module,
	 * and appends it to the string it is passed.
	 *
	 * @param $html string The string onto which the HTML should be appended
	 */
	protected function buildStickyCustomModule( string &$html ) : void {
		global $wgOut;

		// Open container div for module
		$html .= Html::openElement( 'div', [
			'id' => 'onyx-stickyModules-custom',
			'class' => 'onyx-sidebarModule onyx-sidebarModule-sticky'
		] );

		// Have the MediaWiki parser output the Template:Onyx/Sidebar/Sticky page
		// and insert it into the page
		// @todo FIXME: use parseAsContent() instead, OutputPage#parse is deprecated since MW 1.32
		$html .= $wgOut->parse( '{{Onyx/Sidebar/Sticky}}' );

		// Close container div for module
		$html .= Html::closeElement( 'div' );
	}

//////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////
////////////////////////                              ////////////////////////
////////////////////////        PAGE CONTENT          ////////////////////////
////////////////////////                              ////////////////////////
//////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////

	/**
	 * Builds HTML code to display the content of the page itself and appends it
	 * to the string it is given.
	 *
	 * @param $html string The string onto which the HTML should be appended
	 */
	protected function buildArticle( string &$html ) : void {
		// Open container element for article
		$html .= Html::openElement( 'article', [ 'id' => 'onyx-pageBody-content' ] );

		// If it exists, display the site notice at the top of the article
		if ( !empty( $this->data['sitenotice'] ) ) {
			$html .= Html::rawElement( 'div', [ 'id' => 'onyx-content-siteNotice' ],
					$this->get( 'sitenotice' ) );
		}

		// Insert the content of the article itself
		$html .= $this->get( 'bodytext' );

		// If appropriate, insert the category links at the bottom of the page
		if ( !empty( $this->data['catlinks'] ) ) {
			$html .= Html::rawElement( 'span', [ 'id' => 'onyx-content-categories' ],
					$this->get( 'catlinks' ) );
		}

		// If there is any additional data or content to show, insert it now
		if ( !empty( $this->data['dataAfterContent'] ) ) {
			$html .= Html::rawElement( 'span',
					[ 'id' => 'onyx-content-additionalContent' ],
					$this->get( 'dataAfterContent' ) );
		}

		// Close container element for article
		$html .= Html::closeElement( 'article' );
	}

//////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////
////////////////////////                              ////////////////////////
////////////////////////        FOOTER                ////////////////////////
////////////////////////                              ////////////////////////
//////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////

	/**
	 * Builds HTML code for the page foooter, and appends it to the string passed
	 * to it.
	 *
	 * @param $html string The string onto which the HTML should be appended
	 */
	protected function buildFooter( string &$html ) : void {
		// Open container element for footer
		$html .= Html::openElement( 'footer', [ 'id' => 'onyx-footer' ] );

		// Open container element for footer content
		$html .= Html::openElement( 'div', [
			'id' => 'onyx-footer-footerContent',
			'class' => 'onyx-pageAligned'
		] );

		// Build the footer icons
		$this->buildFooterIcons( $html );

		// Build the footer links
		$this->buildFooterLinks( $html );

		// Close container element for footer content
		$html .= Html::closeElement( 'div' );

		// Close container element for footer
		$html .= Html::closeElement( 'footer' );
	}

	/**
	 * Builds HTML code to display the footer icons, and appends it to the string
	 * that is passed to it.
	 *
	 * @param $html string The string onto which the HTML should be appended
	 */
	protected function buildFooterIcons( string &$html ) : void {
		// Open container div for icons
		$html .= Html::openElement( 'div', [
			'id' => 'onyx-footerContent-footerIcons',
			'class' => 'onyx-sidebarAligned'
		] );

		// Open unordered list element for icon list
		$html .= Html::openElement( 'ul', [ 'id' => 'onyx-footerIcons-list' ] );

		// TODO: Split blocks of footer icons appropriately (i.e. make a new list
		//			 for each iteration of the outer loop)

		// Loop through each footer icon and generate a list item element
		// which contains the icon to display
		foreach ( $this->getFooterIcons( 'icononly' ) as $blockName => $footerIcons ) {
			$html .= Html::openElement( 'li',
					[ 'class' => 'onyx-footerIcons-listItem' ] );

			foreach ( $footerIcons as $icon ) {
				$html .= $this->getSkin()->makeFooterIcon( $icon );
			}

			$html .= Html::closeElement( 'li' );
		}

		// Close unordered list element
		$html .= Html::closeElement( 'ul' );

		// Close container div
		$html .= Html::closeElement( 'div' );
	}

	/**
	 * Builds HTML code to display the footer links, and appends it to the string
	 * that is passed to it.
	 *
	 * @param $html string The string onto which the HTML should be appended
	 */
	protected function buildFooterLinks( string &$html ) : void {
		// Open container div for footer links
		$html .= Html::openElement( 'div',
				[ 'id' => 'onyx-footerContent-footerLinks' ] );

		// Open unordered list element for link list
		$html .= Html::openElement( 'ul', [ 'id' => 'onyx-footerLinks-list' ] );

		// TODO: Migrate to using getFooterLinks() instead of
		//			 getFooterLinks('flat'), so that footer links can be divided into
		//			 categories.

		// Loop through each footer link and generate a list item element
		// which contains the link text
		foreach ( $this->getFooterLinks( 'flat' ) as $link ) {
			$html .= Html::rawElement( 'li',
					[ 'class' => 'onyx-footerLinks-listItem' ],
					$this->get( $link ) );
		}

		// Close unordered list element
		$html .= Html::closeElement( 'ul' );

		// Close container div
		$html .= Html::closeElement( 'div' );
	}

//////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////
////////////////////////                              ////////////////////////
////////////////////////            TOOLBOX           ////////////////////////
////////////////////////                              ////////////////////////
//////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////

	/**
	 * Builds HTML code for the toolbox that is displayed at the bottom of the
	 * page, and appends it to the string of HTML that is it passed.
	 *
	 * @param $html string The string onto which the HTML should be appended
	 */
	protected function buildToolbox( string &$html ) : void {
		// Open container element for toolbox
		$html .= Html::openElement( 'section', [ 'id' => 'onyx-toolbox' ] );

		// Open container div for toolbox content
		$html .= Html::openElement( 'div', [ 'id' => 'onyx-toolbox-tools' ] );

		// Begin unordered list to contain tool links
		$html .= Html::openElement( 'ul', [ 'id' => 'onyx-tools-list' ] );

		// Make a list item for each of the tool links
		foreach ( $this->getToolbox() as $key => $toolboxItem ) {
			$html .= $this->makeListItem( $key, $toolboxItem );
		}

		// Avoid PHP 7.1 warnings
		$skin = $this;
		Hooks::run( 'SkinTemplateToolboxEnd', [ &$skin, true ] );

		// End unordered list
		$html .= Html::closeElement( 'ul' );

		// Close container div
		$html .= Html::closeElement( 'div' );

		// Close container element
		$html .= Html::closeElement( 'section' );
	}

}
