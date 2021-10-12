<?php
/**
 * BaseTemplate class for the Onyx skin.
 *
 * @file
 * @ingroup Skins
 */

use Onyx\Config;
use Onyx\ExtraSkinData;
use Onyx\Icon;

class OnyxTemplate extends BaseTemplate {

	/* TODO:
	 *
	 * - Language links
	 *
	 * - Edit section links - icons
	 *
	 * - Refactor so that *EVERYWHERE* possible, standard BaseTemplate functions
	 *   are called instead of building stuff manually - BaseTemplate calls the
	 *   appropriate hooks for us
	 *
	 * - Migrate to Mustache templates - see TemplateParser and
	 * 	 https://www.mediawiki.org/wiki/Manual:HTML_templates
	 *
	 * - Migrate to LESS for stylesheets - can just slot in in place of CSS
	 *
	 * FUTURE EXTENSIONS:
	 *
	 * - Implement dark scheme using CSS media query prefers-color-scheme: dark
	 *
	 * - Read Onyx-specific navigation links from MediaWiki:Onyx-navigation
	 *
	 * - Support VisualEditor
	 */

	/**
	 * Lists all the Special pages which are whitelisted for the page contents
	 * module; i.e. for which the page contents module should be rendered, if
	 * enabled.
	 */
	const PAGE_CONTENTS_SPECIAL_PAGE_WHITELIST = [
		'Interwiki', 'ListGroupRights', 'MediaStatistics', 'Sitenotice',
		'SpecialPages', 'Version'
	];

	/**
	 * Outputs the entire contents of the page in HTML form.
	 */
	public function execute() : void {
		$config = new Config();
		$skin = $this->getSkin();

		ExtraSkinData::extractAndUpdate( $this->data, $config, $skin );

		// Initialise HTML string as a empty string
		$html = '';

		// Concatenate auto-generated head element onto HTML string
		$html .= $this->get( 'headelement' );

		// Build banner
		$this->buildBanner( $html, $config );

		// Build page content
		$this->buildPage( $html, $config );

		// Build footer
		$this->buildFooter( $html, $config );

		// Build toolbox
		$this->buildToolbox( $html, $config );

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
	protected function buildBanner( string &$html, Config $config ) : void {
		// Open container section for banner
		$html .= Html::openElement( 'section', [ 'id' => 'onyx-banner' ] );

		// Open container div for banner content
		$html .= Html::openElement( 'div', [ 'id' => 'onyx-banner-content',
			'class' => 'onyx-pageAligned' ] );

		// Build banner logo (floats on the left of the div)
		$this->buildBannerLogo( $html, $config );

		// Build the search bar
		$this->buildSearchBar( $html, $config );

		// Build user options/login button (floats on the right of the div)
		$this->buildUserOptions( $html, $config );

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
	protected function buildBannerLogo( string &$html, Config $config ) : void {
		// Open container div
		$html .= Html::openElement( 'div', [ 'id' => 'onyx-banner-bannerLogo' ] );

		// Open link element
		$html .= Html::openElement( 'a',
			array_merge( [ 'href' => $this->data['nav_urls']['mainpage']['href'] ],
				Linker::tooltipAndAccesskeyAttribs( 'p-logo' ) ) );

		// Insert logo image
		$html .= Html::rawElement( 'img', [ 'id' => 'onyx-bannerLogo-image',
			'src' => $config->getString( 'banner-logo' ), 'alt' => $this->get( 'sitename' ) ] );

		// Close link element
		$html .= Html::closeElement( 'a' );

		// Close container div
		$html .= Html::closeElement( 'div' );
	}

	/**
	 * Builds HTML code to present the user account-related options to the reader
	 * and appends it to the string passed to it.
	 *
	 * @param $html string The string onto which the HTML should be appended
	 */
	protected function buildUserOptions( string &$html, Config $config ) : void {
		// Open container div
		$html .= Html::openElement( 'div', [ 'id' => 'onyx-banner-userOptions' ] );

		$this->buildNotifications( $html, $config );

		$this->buildPersonalTools( $html, $config );

		// Close container div
		$html .= Html::closeElement( 'div' );
	}

	protected function buildPersonalTools( string &$html, Config $config ) : void {
		$skin = $this->getSkin();

		$html .= Html::openElement( 'div',
			[ 'id' => 'onyx-userOptions-personalTools',
			'class' => 'onyx-dropdown onyx-bannerOption' ] );

		$html .= Html::openElement( 'div', [ 'id' => 'onyx-personalTools-userButton',
			'class' => 'onyx-dropdown-button onyx-bannerOption-button' ] );

		if ( class_exists( 'wAvatar' ) ) {
			$avatar = new wAvatar( $skin->getUser()->getId(), 'm' );
			$avatarElement = $avatar->getAvatarURL();
		} else {
			$avatarElement = Icon::getIcon( 'avatar' )->makeSvg( 28, 28 );
		}

		$html .= Html::rawElement( 'div', [ 'id' => 'onyx-userButton-avatar',
			'class' => 'onyx-bannerOption-icon' ],
			$avatarElement );

		$html .= Html::rawElement( 'span', [ 'id' => 'onyx-userButton-label' ],
			empty( $this->data['username'] )
				? $skin->msg( 'onyx-personaltools-anonusername' )->escaped()
				: $this->get( 'username' ) );

		$html .= Html::rawElement( 'div', [ 'id' => 'onyx-userButton-icon',
			'class' => 'onyx-dropdown-icon onyx-bannerOption-dropdownIcon' ],
			Icon::getIcon( 'dropdown' )->makeSvg( 14, 14 ) );

		$html .= Html::closeElement( 'div' );

		$html .= Html::openElement( 'ul', [ 'id' => 'onyx-personalTools-list',
			'class' => 'onyx-dropdown-list' ] );

		$personalUrls = $this->data['personal_urls'];
		// If we have Echo icons, remove them from the user menu
		if ( isset( $personalUrls['notifications-alert'] ) && $personalUrls['notifications-alert'] ) {
			unset( $personalUrls['notifications-alert'] );
		}
		if ( isset( $personalUrls['notifications-notice'] ) && $personalUrls['notifications-notice'] ) {
			unset( $personalUrls['notifications-notice'] );
		}

		foreach ( $personalUrls as $key => $item ) {
			switch ( $key ) {
				case 'userpage':
					$item['text'] = $skin->msg( 'onyx-personaltools-userpage' )->escaped();
					break;
				case 'mytalk':
					$item['text'] = $skin->msg( 'onyx-personaltools-usertalk' )->escaped();
					break;
				case 'anontalk':
					$item['text'] = $skin->msg( 'onyx-personaltools-anontalk' )->escaped();
					break;
				default:
					break;
			}

			$tooltip = $skin->msg( 'tooltip-pt-' . $key );

			if ( !empty( $tooltip ) ) {
				$item['title'] = $tooltip->escaped();
			}

			$html .= $this->makeListItem( $key, $item );
		}

		$html .= Html::closeElement( 'ul' );

		$html .= Html::closeElement( 'div' );
	}

	// HACK: This function is inelegant, and should be refactored so that the
	//       construction of the icons and list is done by one function which is
	//       called multiple times, but supplied with different info
	protected function buildNotifications( string &$html, Config $config ) : void {
		$skin = $this->getSkin();

		$html .= Html::openElement( 'div', [ 'id' => 'onyx-userOptions-notifications',
			'class' => 'onyx-dropdown onyx-bannerOption' ] );

		$html .= Html::openElement( 'div', [ 'id' => 'onyx-notifications-notifsButton',
			'class' => 'onyx-dropdown-button onyx-bannerOption-button' ] );

		$html .= Html::rawElement( 'div', [
			'class' => 'onyx-bannerOption-icon onyx-notifsButton-icon' ],
			Icon::getIcon( 'notification' )->makeSvg( 28, 28 ) );

		$html .= Html::rawElement( 'div', [
			'class' => 'onyx-dropdown-icon onyx-bannerOption-dropdownIcon onyx-notifsButton-icon' ],
			Icon::getIcon( 'dropdown' )->makeSvg( 14, 14 ) );

		if ( $this->data['onyx_notifications']['numNotifs'] > 0 ) {
			$html .= Html::element( 'div', [ 'id' => 'onyx-notifsButton-numNotifs',
				'class' => 'onyx-notifications-numNotifs' ],
				$this->data['onyx_notifications']['numNotifs']
			);
		}

		$html .= Html::closeElement( 'div' );

		$html .= Html::openElement( 'ul', [
			'id' => 'onyx-notifications-list',
			'class' => 'onyx-dropdown-list'
		] );

		if ( $this->data['onyx_notifications']['numNotifs'] > 0 ) {
			foreach ( $this->data['onyx_notifications']['notifs'] as $notif ) {
				$html .= Html::openElement( 'li' );

				if ( !empty( $notif['href'] ) ) {
					$html .= Html::openElement( 'a', [ 'href' => $notif['href'] ] );
				}

				$html .= $notif['text'];

				if ( !empty( $notif['href'] ) ) {
					$html .= Html::closeElement( 'a' );
				}

				$html .= Html::closeElement( 'li' );
			}
		} else {
			$html .= Html::openElement( 'li', [
				'class' => 'onyx-emptyListMessage' ] );

			$html .= Html::element( 'div', [], $skin->msg( 'onyx-notifications-nonotifs' ) );

			$html .= Html::closeElement( 'li' );
		}

		$html .= Html::closeElement( 'ul' );

		$html .= Html::closeElement( 'div' );

		$html .= Html::openElement( 'div', [ 'id' => 'onyx-userOptions-messages',
			'class' => 'onyx-dropdown onyx-bannerOption' ] );

		$html .= Html::openElement( 'div', [ 'id' => 'onyx-messages-messagesButton',
			'class' => 'onyx-dropdown-button onyx-bannerOption-button' ] );

		$html .= Html::rawElement( 'div', [
			'class' => 'onyx-bannerOption-icon onyx-messagesButton-icon' ],
			Icon::getIcon( 'message' )->makeSvg( 28, 28 ) );

		$html .= Html::rawElement( 'div', [
			'class' => 'onyx-dropdown-icon onyx-bannerOption-dropdownIcon onyx-messagesButton-icon' ],
			Icon::getIcon( 'dropdown' )->makeSvg( 14, 14 ) );

		if ( $this->data['onyx_notifications']['numMessages'] > 0 ) {
			$html .= Html::element( 'div', [ 'id' => 'onyx-messagesButton-numMessages',
				'class' => 'onyx-notifications-numNotifs' ],
				$this->data['onyx_notifications']['numMessages']
			);
		}

		$html .= Html::closeElement( 'div' );

		$html .= Html::openElement( 'ul', [ 'id' => 'onyx-messages-list',
			'class' => 'onyx-dropdown-list' ] );

		if ( $this->data['onyx_notifications']['numMessages'] > 0 ) {
			foreach ( $this->data['onyx_notifications']['messages'] as $message ) {
				$html .= Html::openElement( 'li' );

				if ( !empty( $message['href'] ) ) {
					$html .= Html::openElement( 'a', [ 'href' => $message['href'] ] );
				}

				$html .= $message['text'];

				if ( !empty( $message['href'] ) ) {
					$html .= Html::closeElement( 'a' );
				}

				$html .= Html::closeElement( 'li' );
			}
		} else {
			$html .= Html::openElement( 'li', [
				'class' => 'onyx-emptyListMessage' ] );

			$html .= Html::rawElement( 'div', [],
				$skin->msg( 'onyx-notifications-nomessages' ) );

			$html .= Html::closeElement( 'li' );
		}

		$html .= Html::closeElement( 'ul' );

		$html .= Html::closeElement( 'div' );
	}

	/**
	 * Builds HTML code to present the search form to the user, and appends it to
	 * string passed to it.
	 *
	 * @param $html string The string onto which the HTML should be appended
	 */
	protected function buildSearchBar( string &$html, Config $config ) : void {
		// Open container div
		$html .= Html::openElement( 'div', [
			'id' => 'onyx-banner-search',
			'role' => 'search'
		] );

		// Open search form
		$html .= Html::openElement( 'form', [
			'action' => $this->get( 'wgScript' ),
			'id' => 'onyx-search-form'
		] );

		// Insert hidden search title
		$html .= Html::element( 'input', [
			'type' => 'hidden',
			'name' => 'title',
			'value' => $this->get( 'searchtitle' )
		] );

		// Insert search bar
		$html .= $this->makeSearchInput( [
			'id' => 'searchInput',
			'class' => 'onyx-search-input'
		] );

		$html .= Html::openElement( 'div', [
			'id' => 'onyx-search-buttonContainer',
			'class' => 'onyx-bannerOption-button'
		] );

		// Insert search icon
		$html .= Html::rawElement( 'div', [
			'id' => 'onyx-search-buttonIcon',
			'class' => 'onyx-bannerOption-icon' ],
			Icon::getIcon( 'search' )->makeSvg( 28, 28 )
		);

		// Insert search button
		$html .= $this->makeSearchButton( 'go', [
			'id' => 'searchButton',
			'class' => 'onyx-search-button'
		] );

		// Insert fallback search button
		$html .= $this->makeSearchButton( 'fulltext', [
			'id' => 'mw-searchButton',
			'class' => 'mw-fallbackSearchButton onyx-search-button'
		] );

		$html .= Html::closeElement( 'div' );

		// Close form
		$html .= Html::closeElement( 'form' );

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
	protected function buildPage( string &$html, Config $config ) : void {
		// Open container element for page
		$html .= Html::openElement( 'main',
			[ 'id' => 'onyx-page', 'class' => 'onyx-pageAligned mw-body', 'role' => 'main' ] );

		// Build the header
		$this->buildHeader( $html, $config );

		// Open container element for page body (i.e. actual content such as the
		// article and the sidebar)
		$html .= Html::openElement( 'section', [ 'id' => 'onyx-page-pageBody' ] );

		$html .= Html::openElement( 'div', [ 'class' => 'onyx-articleContainer' ] );

		// Build the article content
		$this->buildArticle( $html, $config );

		// Build the sidebar
		$this->buildSidebar( $html, $config );

		$html .= Html::closeElement( 'div' );

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
	protected function buildHeader( string &$html, Config $config ) : void {
		// Open container element for header
		$html .= Html::openElement( 'header', [ 'id' => 'onyx-page-header' ] );

		// Build wiki header
		$this->buildWikiHeader( $html, $config );

		// Build article header
		$this->buildArticleHeader( $html, $config );

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
	protected function buildWikiHeader( string &$html, Config $config ) : void {
		// Open container div for wiki header
		$html .= Html::openElement( 'div', [ 'id' => 'onyx-header-wikiHeader' ] );

		// Build the header logo
		$this->buildHeaderLogo( $html, $config );

		// Build the tagline heading
		$this->buildTagline( $html, $config );

		// Build the global navigation options
		$this->buildGlobalNav( $html, $config );

		// Close container div
		$html .= Html::closeElement( 'div' );
	}

	/**
	 * Builds HTML code for the wiki's logo in the header, and appends it to the
	 * string passed to it.
	 *
	 * @param $html string The string onto which the HTML should be appended
	 */
	protected function buildHeaderLogo( string &$html, Config $config ) : void {
		// Open container div for logo
		$html .= Html::openElement( 'div', [ 'id' => 'onyx-wikiHeader-headerLogo', 'role' => 'banner' ] );

		// Open link element
		$html .= Html::openElement( 'a',
			array_merge( [ 'href' => $this->data['nav_urls']['mainpage']['href'] ],
				Linker::tooltipAndAccesskeyAttribs( 'p-logo' ) ) );

		// Insert logo image
		$html .= Html::rawElement( 'img', [
			'id' => 'onyx-headerLogo-image',
			'src' => $config->getString( 'header-logo' ),
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
	protected function buildTagline( string &$html, Config $config ) : void {
		$skin = $this->getSkin();

		// Open container div for tagline
		$html .= Html::openElement( 'div', [ 'id' => 'onyx-wikiHeader-tagline' ] );

		if ( $config->isEnabled( 'use-sys-message-for-header-tagline' ) ) {
			$msg = $skin->msg( $config->getString( 'header-tagline-sys-message-source' ) )->exists()
				? $skin->msg( $config->getString( 'header-tagline-sys-message-source' ) )
				: $this->getSkin()->msg( 'tagline' );
			$tagline = $msg->exists()
				? $msg->parse()
				: $this->data['sitename'];
		} else {
			$tagline = $this->data['sitename'];
		}

		// Create heading element containing the tagline
		$html .= Html::rawElement( 'h1', [ 'id' => 'onyx' ], $tagline );

		// Close container div
		$html .= Html::closeElement( 'div' );
	}

	/**
	 * Builds HTML code to display the tagline (or alternatively wiki name) at
	 * top of the header, and appends it to the string that it is passed.
	 *
	 * @param $html string The string onto which the HTML should be appended
	 */
	protected function buildGlobalNav( string &$html, Config $config ) : void {
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
			if ( strtolower( $boxName ) !== 'navigation' ) {
				continue;
			}

			if ( is_array( $box['content'] ) ) {
				$content = array_reverse( $box['content'] );

				foreach ( $content as $key => $item ) {
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
	protected function buildArticleHeader( string &$html, Config $config ) : void {
		// Open container div for article header
		$html .= Html::openElement( 'div', [ 'id' => 'onyx-header-articleHeader' ] );

		// Open container div for article action options
		$html .= Html::openElement( 'div', [ 'id' => 'onyx-articleHeader-actions' ] );

		// Build content action buttons
		$this->buildActionButtons( $html, $config );

		// Close container div for action options
		$html .= Html::closeElement( 'div' );

		// Open h1 element for article title
		$html .= Html::openElement( 'h1',
			[ 'id' => 'onyx-articleHeader-title' ] );

		// Insert page status indicators (these will float right inside the h1
		// element)
		$html .= $this->getIndicators();

		// Insert article title
		$html .= Html::rawElement( 'span',
			[ 'id' => 'onyx-title-text' ],
			$this->get( 'title' ) );

		// Close h1 element
		$html .= Html::closeElement( 'h1' );

		// Close container div for article header
		$html .= Html::closeElement( 'div' );
	}

	/**
	 * Builds HTML code to present the content action options to the user, and
	 * appends it to the string passed to it.
	 *
	 * @param $html string The string onto which the HTML should be appended
	 */
	protected function buildActionButtons( string &$html, Config $config ) : void {
		$skin = $this->getSkin();
		$title = $skin->getTitle();
		$talkTitle = empty( $title ) ? null : $title->getTalkPageIfDefined();
		$isEditing = false;
		$isViewSource = false;
		$isHistory = false;
		$isSpecialAction = false;
		$isTalkPage = !empty( $title ) ? $title->isTalkPage() : false;
		$view = null;
		$edit = null;
		$talk = null;
		$sidebar = [
			'id' => 'onyx-actions-toggleSidebar',
			'class' => 'onyx-button onyx-button-secondary onyx-button-action',
			'imgType' => 'svg',
			'imgSrc' => 'sidebar',
			'text' => $skin->msg( 'onyx-sidebar-label' )->escaped(),
			'title' => $skin->msg( 'onyx-sidebar-tooltip' )->escaped()
		];
		$dropdown = [];

		// Sort through the flat content actions array provided by the API, and
		// extract, discard and modify what is necessary
		foreach ( $this->data['content_actions'] as $key => $tab ) {
			switch ( $key ) {
				// If the action is edit or view source, assign the tab array to the
				// edit variable, and specify the path to the image to use as the
				// button's icon
				case 'edit':
					$edit = $tab;
					$edit['imgType'] = 'svg';
					$edit['imgSrc'] = 'edit';
					if ( stripos( $tab['class'], 'selected' ) !== false ) {
						$isEditing = true;
					}
					break;
				case 'viewsource':
					$edit = $tab;
					$edit['imgType'] = 'svg';
					$edit['imgSrc'] = 'view';
					if ( stripos( $tab['class'], 'selected' ) !== false ) {
						$isViewSource = true;
					}
					break;
				// If the action is talk, assign the tab array to the talk variable and
				// specify the path to the button icon
				case 'talk':
					$talk = $tab;
					$talk['text'] = $skin->msg( 'onyx-actions-talk' )->escaped();
					$talk['imgType'] = 'svg';
					$talk['imgSrc'] = 'talk';
					break;
				// If the action is add section, then replace the tooltip (which is, by
				// default, just a '+') with 'Add new section', a more appropriate
				// message for a drop-down list format and then DELIBERATELY fall
				// through to the default case
				case 'addsection':
					$tab['text'] = $skin->msg( 'onyx-actions-addsection' )->escaped();
				// Finally, if the content action is none of the above, add it to the
				// growing array of miscellaneous content actions to be displayed in a
				// drop-down list beneath the edit/view soure button
				default:
					if ( substr( $key, 0, 6 ) === 'nstab-' ) {
						$view = $tab;
					} else {
						if ( stripos( $tab['class'], 'selected' ) === false ) {
							$dropdown[$key] = $tab;
						} else {
							if ( $key === 'history' ) {
								$isHistory = true;
							} else {
								$isSpecialAction = true;
							}
						}
					}
					break;
			}
		}

		// Add Onyx-specific IDs to the view, edit and talk buttons
		if ( !empty( $view ) ) {
			$view['id'] .= ' onyx-actions-view';
		}
		if ( !empty( $edit ) ) {
			$edit['id'] .= ' onyx-actions-edit';
		}
		if ( !empty( $talk ) ) {
			$talk['id'] .= ' onyx-actions-talk';
		}

		$primary = null;
		$secondary = null;
		if ( $isEditing || $isSpecialAction ) {
			if ( $isTalkPage ) {
				// Primary button leads back to talk page
				if ( !empty( $talk ) ) {
					$talk['imgType'] = 'svg';
					$talk['imgSrc'] = 'cancel';
					$talk['text'] = $skin->msg( 'onyx-actions-cancel' )->escaped();
					// Set href to the talk URL, so that if the talk page doesn't exist,
					// clicking the button while editing it doesn't use the redlink URL
					// that would take the user straight back to edit page
					if ( !empty( $talkTitle ) ) {
						$talk['href'] = $talkTitle->getLinkURL();
					}
					$primary = $talk;
				}
				// Secondary button leads back to article
				if ( !empty( $view ) ) {
					$view['imgType'] = 'svg';
					$view['imgSrc'] = 'back';
					$view['text'] = $skin->msg( 'onyx-actions-backtopage', $view['text'] )->escaped();
					$secondary = $view;
				}
			} else {
				// Primary button leads back to article
				if ( !empty( $view ) ) {
					$view['imgType'] = 'svg';
					$view['imgSrc'] = 'cancel';
					$view['text'] = $skin->msg( 'onyx-actions-cancel' )->escaped();
					// Set href to the page URL, so that if the page doesn't exist,
					// clicking the button while editing it doesn't use the redlink URL
					// that would take the user straight back to edit page
					if ( !empty( $title ) ) {
						$view['href'] = $title->getLinkURL();
					}
					$primary = $view;
				}
				// Secondary button leads to talk page
				if ( !empty( $talk ) ) {
					$talk['imgType'] = 'svg';
					$talk['imgSrc'] = 'talk';
					$secondary = $talk;
				}
			}
			// Edit pushed to dropdown
			if ( !$isEditing && !empty( $edit ) ) {
				array_unshift( $dropdown, $edit );
			}
		} else if ( $isHistory || $isViewSource ) {
			if ( $isTalkPage ) {
				// Primary button leads back to talk page
				if ( !empty( $talk ) ) {
					$talk['imgType'] = 'svg';
					$talk['imgSrc'] = 'back';
					$talk['text'] = $skin->msg( 'onyx-actions-back' )->escaped();
					$primary = $talk;
				}
				// Secondary button leads back to article
				if ( !empty( $view ) ) {
					$view['imgType'] = 'svg';
					$view['imgSrc'] = 'back';
					$view['text'] = $skin->msg( 'onyx-actions-backtopage', $view['text'] )->escaped();
					$secondary = $view;
				}
			} else {
				// Primary button leads back to article
				if ( !empty( $view ) ) {
					$view['imgType'] = 'svg';
					$view['imgSrc'] = 'back';
					$view['text'] = $skin->msg( 'onyx-actions-back' )->escaped();
					$primary = $view;
				}
				// Secondary button leads to talk page
				if ( !empty( $talk ) ) {
					$secondary = $talk;
				}
			}
			// Edit pushed to dropdown
			if ( !$isViewSource && !empty( $edit ) ) {
				array_unshift( $dropdown, $edit );
			}
		} else {
			if ( $isTalkPage ) {
				// Primary button leads to talk page edit
				if ( !empty( $edit ) ) {
					$primary = $edit;
				}
				// Secondary button leads back to article
				if ( !empty( $view ) ) {
					$view['imgType'] = 'svg';
					$view['imgSrc'] = 'back';
					$view['text'] = $skin->msg( 'onyx-actions-backtopage', $view['text'] )->escaped();
					$secondary = $view;
				}
			} else {
				// Primary button leads to article edit
				if ( !empty( $edit ) ) {
					$primary = $edit;
				}
				// Secondary button leads to talk page
				if ( !empty( $view ) ) {
					$secondary = $talk;
				}
			}
		}

		// Add Onyx-specific classes to the primary and secondary buttons
		if ( !empty( $primary ) ) {
			$primary['class'] .= ' onyx-button onyx-button-primary onyx-button-action';
		}
		if ( !empty( $secondary ) ) {
			$secondary['class'] .= ' onyx-button onyx-button-secondary onyx-button-action';
		}

		// If the primary content action is available, display it as a button
		if ( $primary !== null ) {
			$this->buildActionButton( $html, $config, $primary );
		}

		// If there are one or more miscellaneous content actions available,
		// display them as a drop-down list following the primary button
		if ( sizeof( $dropdown ) > 0 ) {
			$this->buildActionDropdown( $html, $config, $dropdown );
		}

		// If the secondary content action is available, display it as a button
		if ( $secondary !== null ) {
			$this->buildActionButton( $html, $config, $secondary );
		}

		// Finally, display the sidebar toggle button, which will always be
		// available
		$this->buildActionButton( $html, $config, $sidebar );
	}

	/**
	 * Builds HTML code to for an individual content action button, and appends
	 * it to the string passed
	 *
	 * @param $html string The string onto which the HTML should be appended
	 * @param $info array An array with the necessary info to build the button
	 */
	protected function buildActionButton( string &$html, Config $config, array $info ) : void {
		// If the button links to another page, surround it in an <a> element that
		// links there
		if ( !empty( $info['href'] ) ) {
			$html .= Html::openElement( 'a', [ 'href' => $info['href'],
				'title' => $info['title'] ?? '' ] );
		}

		// Open a <div> for the button
		$html .= Html::openElement( 'div', [ 'id' => $info['id'],
				'class' => $info['class'] ] );

		if ( isset( $info['imgSrc'] ) ) {
			// If the button is to have an icon, display the icon in the format
			// corresponding to the given image type
			switch ( $info['imgType'] ) {
				case 'svg':
					$icon = Icon::getIcon( $info['imgSrc'] );
					if ( !isset( $icon ) ) {
						break;
					}
					$html .= $icon->makeSvg( 28, 28, [ 'class' => 'onyx-button-icon' ] );
					break;
				default:
					$stylePath = $this->getSkin()->getConfig()->get( 'StylePath' );
					$html .= Html::rawElement( 'img', [ 'src' => $stylePath
						. '/Onyx/resources/icons/' . $info['imgSrc'] ] );
					break;
			}
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
	protected function buildActionDropdown( string &$html, Config $config, array $items ) : void {
		// Open a <div> element to contain the entire drop-down
		$html .= Html::openElement( 'div', [
			'class' => 'onyx-dropdown',
			'id' => 'onyx-actions-actionsList'
		] );

		// Open a div for a button that will display the list when hovered over
		// (this is achieved via CSS styling of the onyx-dropdown,
		// onyx-dropdown-button, onyx-dropdown-icon and onyx-dropdown-list classes)
		$html .= Html::openElement( 'div', [
			'class' => 'onyx-button onyx-button-primary onyx-button-action '
				. 'onyx-dropdown-button',
			'id' => 'onyx-actionsList-button'
		] );

		// Insert the dropdown icon
		$html .= Html::rawElement( 'div', [
			'id' => 'onyx-actionsList-dropdownIcon',
			'class' => 'onyx-dropdown-icon'
			], Icon::getIcon( 'dropdown' )->makeSvg( 14, 14 ) );

			// Close the button div
		$html .= Html::closeElement( 'div' );

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
	protected function buildSidebar( string &$html, Config $config ) : void {
		// Open container element for sidebar
		$html .= Html::openElement( 'aside', [
			'id' => 'onyx-pageBody-sidebar',
			'class' => 'onyx-sidebarAligned'
		] );

		// Open container div for static sidebar modules
		$html .= Html::openElement( 'div', [ 'id' => 'onyx-sidebar-staticModules' ] );

		// Build the static custom sidebar module
		$this->buildStaticCustomModule( $html, $config );

		// Build the recent changes module
		$this->buildRecentChangesModule( $html, $config );

		// Close container div for static modules
		$html .= Html::closeElement( 'div' );

		// Open container div for sticky sidebar modules
		$html .= Html::openElement( 'div', [ 'id' => 'onyx-sidebar-stickyModules' ] );

		// Build the article contents navigation module
		$this->buildPageContentsModule( $html, $config );

		// Build the sticky custom module
		$this->buildStickyCustomModule( $html, $config );

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
	protected function buildStaticCustomModule( string &$html, Config $config ) : void {
		if ( empty( $this->data['onyx_customSidebarStatic'] ) ) {
			return;
		}

		// Open container div for module
		$html .= Html::openElement( 'div', [
			'id' => 'onyx-staticModules-custom',
			'class' => 'onyx-sidebarModule onyx-sidebarModule-static onyx-sidebarModule-custom'
		] );

		$html .= $this->data['onyx_customSidebarStatic'];

		// Close container div for module
		$html .= Html::closeElement( 'div' );
	}

	/**
	 * Builds HTML code to display a sidebar module showing recent changes and
	 * wiki activity, then appends it to the string it is passed.
	 *
	 * @param $html string The string onto which the HTML should be appended
	 */
	protected function buildRecentChangesModule( string &$html, Config $config ) : void {
		if (
			!$config->isEnabled( 'enable-recent-changes-module' )
			|| empty( $this->data['onyx_recentChanges'] )
			|| count( $this->data['onyx_recentChanges'] ) <= 0
		) {
			return;
		}
		$skin = $this->getSkin();

		// Open container div for module
		$html .= Html::openElement( 'div', [
			'id' => 'onyx-staticModules-recentChanges',
			'class' => 'onyx-sidebarModule onyx-sidebarModule-static'
		] );

		// Insert module title
		$html .= Html::rawElement( 'h2', [
			'id' => 'onyx-recentChanges-heading',
			'class' => 'onyx-sidebarHeading onyx-sidebarHeading-static'
			], $skin->msg( 'recentchanges' )->escaped() );

		// Open container div for module content
		$html .= Html::openElement( 'div', [ 'id' => 'onyx-recentChanges-content' ] );

		// Open unordered list
		$html .= Html::openElement( 'ul' );

		// Get the current time
		$currentTime = DateTime::createFromFormat( 'YmdHis', wfTimestampNow() );

		// Loop through all the recent changes provided by Onyx\ExtraSkinData
		if ( !empty( $this->data['onyx_recentChanges'] ) ) {
			foreach ( $this->data['onyx_recentChanges'] as $recentChange ) {
				// Get the time the edit was made
				$time = DateTime::createFromFormat( 'YmdHis', $recentChange['timestamp'] );

				// Get a string representing the time difference
				$timeDiff = $this->getDateTimeDiffString( $currentTime->diff( $time ) );

				// Get the title of the page that was edited
				$page = Title::newFromText( $recentChange['title'], $recentChange['namespace'] );

				// Get the title of the userpage of the user who edited it
				$user = Title::newFromText( $recentChange['user'], NS_USER );

				// Open list item for recent change
				$html .= Html::openElement( 'li' );

				$html .= Html::openElement( 'div', [ 'class' => 'onyx-recentChanges-page' ] );

				// Create a link to the edited page
				$html .= Html::openElement( 'a', [ 'href' => $page->getInternalURL() ] );
				$html .= $page->getFullText();
				$html .= Html::closeElement( 'a' );

				$html .= Html::closeElement( 'div' );

				$html .= Html::openElement( 'div', [ 'class' => 'onyx-recentChanges-info' ] );

				// Create a link to the user who edited it
				$html .= Html::openElement( 'a', [ 'href' => $user->getInternalURL() ] );
				$html .= $user->getText();
				$html .= Html::closeElement( 'a' );

				// Display how long ago it was edited
				$html .= ' • ';
				$html .= $timeDiff;

				$html .= Html::closeElement( 'div' );

				// Close the list item
				$html .= Html::closeElement( 'li' );
			}
		}

		// Close unordered list
		$html .= Html::closeElement( 'ul' );

		// Close container div for module content
		$html .= Html::closeElement( 'div' );

		// Close container div for module
		$html .= Html::closeElement( 'div' );
	}


	/**
	 * Generates a textual representation of a DateInterval, ignoring all but the
	 * largest denomination of time
	 *
	 * @param $interval DateInterval The interval to generate a representation of
	 */
	protected function getDateTimeDiffString( DateInterval $interval ) : string {
		$skin = $this->getSkin();
		if ( $interval->y > 0 ) {
			$msg = $skin->msg( 'years', $interval->y );
		} elseif ( $interval->m > 0 ) {
			$msg = $skin->msg( 'months', $interval->m );
		} elseif ( $interval->d > 7 ) {
			$msg = $skin->msg( 'weeks', floor( $interval->d / 7 ) );
		} elseif ( $interval->d > 0 ) {
			$msg = $skin->msg( 'days', $interval->d );
		} elseif ( $interval->h > 0 ) {
			$msg = $skin->msg( 'hours', $interval->h );
		} elseif ( $interval->i > 0 ) {
			$msg = $skin->msg( 'minutes', $interval->i );
		} else {
			$msg = $skin->msg( 'seconds', $interval->s );
		}
		return $skin->msg( 'ago', $msg );
	}

	/**
	 * Retrieves the article contents navigation list from the article content
	 * and builds HTML code to display it to the user as a sidebar module, then
	 * appends this HTML to the string passed to it.
	 *
	 * @param $html string The string onto which the HTML should be appended
	 */
	protected function buildPageContentsModule( string &$html, Config $config ) : void {
		$skin = $this->getSkin();

		// Do nothing if we're on NS_SPECIAL (or other virtual namespaces, though
		// de facto only NS_SPECIAL pages are exposed in the UI), unless the page
		// has been specifically whitelisted for the inclusion of the module
		if ( $skin->getTitle()->getNamespace() < 0 &&
			!( $skin->getTitle()->getNamespace() == NS_SPECIAL
			&& in_array( $skin->getTitle()->getText(), self::PAGE_CONTENTS_SPECIAL_PAGE_WHITELIST ) ) ) {
			return;
		}

		// Open container div for module
		$html .= Html::openElement( 'div', [
			'id' => 'onyx-stickyModules-pageContents',
			'class' => 'onyx-sidebarModule onyx-sidebarModule-sticky',
			'data-min-headings' => $config->getInteger( 'page-contents-min-headings' ),
			'data-enable-highlighting' => true
		] );

		// Insert module title
		$html .= Html::rawElement( 'h2', [
			'id' => 'onyx-pageContents-heading',
			'class' => 'onyx-sidebarHeading onyx-sidebarHeading-sticky'
			], $skin->msg( 'toc' )->escaped() );

		// Open container div for module content
		$html .= Html::openElement( 'div', [ 'id' => 'onyx-pageContents-content' ] );

		$this->buildPageContentsModuleList( $html, $config, [] );

		// Close container div for module content
		$html .= Html::closeElement( 'div' );

		// Close container div for module
		$html .= Html::closeElement( 'div' );
	}

	/**
	 * Builds the list that will be displayed in the page contents module.
	 *
	 * @param $html string The string onto which the HTML should be appended
	 * @param $headings array The list of page headings generated by Onyx\ExtraSkinData
	 */
	protected function buildPageContentsModuleList( string &$html,
			Config $config, array $headings ) : void {
		$html .= Html::openElement( 'template', [ 'id' => 'onyx-pageContents-expandButtonTemplate' ] );

		$html .= Html::rawElement( 'div', [ 'class' => 'onyx-pageContents-expandButton '],
			Icon::getIcon( 'dropdown' )->makeSvg( 14, 14 )
		);

		$html .= Html::closeElement( 'template' );

		$html .= Html::openElement( 'template', [ 'id' => 'onyx-pageContents-noExpandIconTemplate' ] );

		$html .= Html::rawElement( 'div', [ 'class' => 'onyx-pageContents-noExpandIcon '],
			Icon::getIcon( 'bullet' )->makeSvg( 14, 14 )
		);

		$html .= Html::closeElement( 'template' );

		$html .= Html::openElement( 'div', [ 'id' => 'onyx-pageContents-backToTop' ] );

		$html .= Html::element( 'a', [ 'href' => '#' ], '↑ '.$this->getSkin()->msg( 'onyx-pagecontents-backtotop' )->escaped() );

		$html .= Html::closeElement( 'div' );

		// Create the unordered list element that will contain the list
		$html .= Html::element( 'ul', [ 'class' => 'onyx-pageContents-list' ] );
	}

	/**
	 * Builds HTML code to display the wiki's sticky custom Onyx sidebar module,
	 * and appends it to the string it is passed.
	 *
	 * @param $html string The string onto which the HTML should be appended
	 */
	protected function buildStickyCustomModule( string &$html, Config $config ) : void {
		if ( empty( $this->data['onyx_customSidebarSticky'] ) ) {
			return;
		}

		// Open container div for module
		$html .= Html::openElement( 'div', [
			'id' => 'onyx-stickyModules-custom',
			'class' => 'onyx-sidebarModule onyx-sidebarModule-sticky onyx-sidebarModule-custom'
		] );
		$html .= $this->data['onyx_customSidebarSticky'];

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
	protected function buildArticle( string &$html, Config $config ) : void {
		// Open container element for article
		$html .= Html::openElement( 'article', [ 'id' => 'onyx-pageBody-content' ] );

		// If it exists, insert the page subtitle
		if ( !empty( $this->data['subtitle'] ) ) {
			$html .= Html::rawElement( 'div',
				[ 'id' => 'onyx-pageContent-subtitle' ],
				$this->get( 'subtitle' ) );
		}

		// If it exists, insert the article undelete message
		if ( !empty( $this->data['undelete'] ) ) {
			$html .= Html::rawElement('div',
				[ 'id' => 'onyx-pageContent-undelete' ],
				$this->get( 'undelete' ) );
		}

		// If it exists, display the site notice at the top of the article
		if ( !empty( $this->data['sitenotice'] ) ) {
			$html .= Html::openElement( 'div', [
				'id' => 'onyx-content-siteNotice',
				'data-site-notice-hash' => hash( 'crc32b', $this->get( 'sitenotice' ) )
			] );

			// Display the site notice close button
			$html .= Html::rawElement( 'div', [
				'class' => 'onyx-button onyx-button-primary',
				'id' => 'onyx-siteNotice-closeButton'
				], Icon::getIcon( 'close' )->makeSvg( 14, 14,
					[ 'id' => 'onyx-siteNotice-closeIcon' ] )
			);

			$html .= $this->get( 'sitenotice' );

			$html .= Html::closeElement( 'div' );
		}

		// Insert the content of the article itself
		$html .= $this->get( 'bodytext' );

		// If appropriate, insert the category links at the bottom of the page
		if ( !empty( $this->data['catlinks'] ) ) {
			$html .= Html::rawElement( 'span', [
				'id' => 'onyx-content-categories'
				], $this->get( 'catlinks' )
			);
		}

		// If there is any additional data or content to show, insert it now
		if ( !empty( $this->data['dataAfterContent'] ) ) {
			$html .= Html::rawElement( 'span', [
				'id' => 'onyx-content-additionalContent'
				], $this->get( 'dataAfterContent' )
			);
		}

		// Close container element for article
		$html .= Html::closeElement( 'article' );
	}

//////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////
////////////////////////                              ////////////////////////
////////////////////////            FOOTER            ////////////////////////
////////////////////////                              ////////////////////////
//////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////

	/**
	 * Builds HTML code for the page foooter, and appends it to the string passed
	 * to it.
	 *
	 * @param $html string The string onto which the HTML should be appended
	 */
	protected function buildFooter( string &$html, Config $config ) : void {
		// Open container element for footer
		$html .= Html::openElement( 'footer', [
			'id' => 'onyx-footer',
			'class' => 'mw-footer',
			'role' => 'contentinfo',
			'lang' => $this->get( 'userlang' ),
			'dir' => $this->get( 'dir' )
		] );

		// Open container element for footer content
		$html .= Html::openElement( 'div', [
			'class' => 'onyx-pageAligned'
		] );

		$html .= Html::openElement( 'div', [
			'id' => 'onyx-footer-footerContent'
		] );

		$html .= Html::openElement( 'div', [
			'class' => 'onyx-articleContainer'
		] );

		// Build the footer links
		$this->buildFooterLinks( $html, $config );

		// Build the footer icons
		$this->buildFooterIcons( $html, $config );

		$html .= Html::closeElement( 'div' );

		$html .= Html::closeElement( 'div' );

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
	protected function buildFooterIcons( string &$html, Config $config ) : void {
		$skin = $this->getSkin();

		// Open container div for icons
		$html .= Html::openElement( 'div', [
			'id' => 'onyx-footerContent-footerIcons',
			'class' => 'onyx-sidebarAligned'
		] );

		// Open unordered list element for icon list
		$html .= Html::openElement( 'ul', [ 'id' => 'onyx-footerIcons-list' ] );

		// Loop through each footer icon and generate a list item element
		// which contains the icon to display
		$footerIcons = $this->get( 'footericons' );
		foreach ( $footerIcons as $footerIconsKey => &$footerIconsBlock ) {
			foreach ( $footerIconsBlock as $footerIconKey => $footerIcon ) {
				if ( !isset( $footerIcon['src'] ) ) {
					unset( $footerIconsBlock[$footerIconKey] );
				}

				$html .= Html::openElement( 'li', [
					'class' => 'onyx-footerIcons-listItem'
				] );

				$html .= $skin->makeFooterIcon( $footerIcon );

				$html .= Html::closeElement( 'li' );
			}
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
	protected function buildFooterLinks( string &$html, Config $config ) : void {
		// Open container div for footer links
		$html .= Html::openElement( 'div', [
			'id' => 'onyx-footerContent-footerLinks',
			'class' => 'onyx-articleAligned'
		] );

		foreach ( $this->getFooterLinks() as $category => $links ) {
			// Open unordered list element for link list
			$html .= Html::openElement( 'ul', [
				'id' => "onyx-footerLinks-$category",
				'class' => 'onyx-footerLinks-list'
			 ] );

			foreach ( $links as $key ) {
				$html .= Html::rawElement( 'li', [
					'class' => 'onyx-footerLinks-listItem'
					], $this->get( $key )
				);
			}
			// Close unordered list element
			$html .= Html::closeElement( 'ul' );
		}

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
	protected function buildToolbox( string &$html, Config $config ) : void {
		// Open container element for toolbox
		$html .= Html::openElement( 'section', [ 'id' => 'onyx-toolbox' ] );

		// Open container div for toolbox content
		$html .= Html::openElement( 'div', [ 'id' => 'onyx-toolbox-tools' ] );

		// Begin unordered list to contain tool links
		$html .= Html::openElement( 'ul', [ 'id' => 'onyx-tools-list' ] );

		// Make a list item for each of the tool links
		foreach ( $this->data['sidebar']['TOOLBOX'] ?? [] as $key => $toolboxItem ) {
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
