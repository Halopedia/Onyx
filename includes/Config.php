<?php

namespace Onyx;

class Config {

	private const DEFAULT_CONFIG = [
		'navigation-source' => 'MediaWiki:Onyx-navigation',
		'toolbox-source' => 'MediaWiki:Onyx-toolbox',
		'enable-custom-user-toolboxes' => true,
		'custom-user-toolbox-suffix' => 'Onyx-toolbox',
		'banner-logo' => null,
		'use-banner-logo-image' => true,
		'header-logo' => null,
		'show-sidebar-by-default' => true,
		'show-sidebar-by-default-on-edit' => false,
		'show-sidebar-by-default-on-main-page' => false,
		'enable-recent-changes-module' => true,
		'recent-changes-cache-expiry-time' => 30,
		'recent-changes-amount' => 7,
		'enable-page-contents-module' => true,
		'page-contents-min-headings' => 3,
		'custom-sidebar-static-source' => 'Template:Onyx/Sidebar/Sticky',
		'custom-sidebar-sticky-source' => 'Template:Onyx/Sidebar/Static',
		'use-html5' => true
	];

	private const CONFIG_TYPES = [
		'navigation-source' => 'string',
		'toolbox-source' => 'string',
		'enable-custom-user-toolboxes' => 'boolean',
		'custom-user-toolbox-suffix' => 'string',
		'banner-logo' => 'string',
		'use-banner-logo-image' => 'boolean',
		'header-logo' => 'string',
		'show-sidebar-by-default' => 'boolean',
		'show-sidebar-by-default-on-edit' => 'boolean',
		'show-sidebar-by-default-on-main-page' => 'boolean',
		'enable-recent-changes-module' => 'boolean',
		'recent-changes-cache-expiry-time' => 'integer',
		'recent-changes-amount' => 'integer',
		'enable-page-contents-module' => 'boolean',
		'page-contents-min-headings' => 'integer',
		'custom-sidebar-static-source' => 'string',
		'custom-sidebar-sticky-source' => 'string',
		'use-html5' => 'boolean'
	];

	private const CONFIG_NAMES = [
		'navigation-source' => 'wgOnyxNavigationSource',
		'toolbox-source' => 'wgOnyxToolboxSource',
		'enable-custom-user-toolboxes' => 'wgOnyxEnableCustomUserToolboxes',
		'custom-user-toolbox-suffix' => 'wgOnyxCustomUserToolboxSuffix',
		'banner-logo' => 'wgOnyxBannerLogo',
		'use-banner-logo-image' => 'wgOnyxUserBannerLogoImage',
		'header-logo' => 'wgOnyxHeaderLogo',
		'show-sidebar-by-default' => 'wgOnyxShowSidebarByDefault',
		'show-sidebar-by-default-on-edit' => 'wgOnyxShowSidebarByDefaultOnEdit',
		'show-sidebar-by-default-on-main-page' => 'wgOnyxShowSidebarByDefaultOnMainPage',
		'enable-recent-changes-module' => 'wgOnyxEnableRecentChangesModule',
		'recent-changes-cache-expiry-time' => 'wgOnyxRecentChangesCacheExpiryTime',
		'recent-changes-amount' => 'wgOnyxRecentChangesAmount',
		'enable-page-contents-module' => 'wgOnyxEnablePageContentsModule',
		'page-contents-min-headings' => 'wgOnyxPageContentsMinHeadings',
		'custom-sidebar-static-source' => 'wgOnyxCustomSidebarStaticSource',
		'custom-sidebar-sticky-source' => 'wgOnyxCustomSidebarStickySource',
		'use-html5' => 'wgOnyxUseHtml5'
	];

	private $options;

	public function __construct() {
		global $wgOnyxConfig, $wgLogo;

		// Set the options array to the default options upon construction
		$this->options = self::DEFAULT_CONFIG;

		// These two must be manually set, because they are dependent on a
		// non-constant global variable, and hence can't be included in
		// the const DEFAULT_CONFIG array
		$this->options['banner-logo'] = $wgLogo;
		$this->options['header-logo'] = $wgLogo;

		// Loop through the options array and update each entry as necessary
		foreach ( $this->options as $name => &$value ) {
			// Check $wgOnyxConfig first, since it takes priority over individually
			// assigned global variables. If a valid setting is found, assign it and
			// skip to the next option
			if ( isset( $wgOnyxConfig ) && is_array( $wgOnyxConfig ) ) {
				$setting =  $wgOnyxConfig[$name];

				if ( isset( $setting )
					&& gettype( $setting ) === self::CONFIG_TYPES[$name] ) {
					$value = $setting;
				}
			}

			// Otherwise, check the global variable name associated with the option,
			// and assign the setting to the option if it is a valid setting
			$setting = $GLOBALS[self::CONFIG_NAMES[$name]];

			if ( isset( $setting )
				&& gettype( $setting ) === self::CONFIG_TYPES[$name] ) {
				$value = $setting;
			}
		}
	}

	public function isEnabled( string $option ) : ?bool {
		if ( isset( $this->options[$option] )
			&& is_bool( $this->options[$option] ) ) {
			return $this->options[$option];
		} else {
			return null;
		}
	}

	public function getInteger( string $option ) : ?int {
		if ( isset( $this->options[$option] )
			&& is_int( $this->options[$option] ) ) {
			return $this->options[$option];
		} else {
			return null;
		}
	}

	public function getFloat( string $option ) : ?float {
		if ( isset( $this->options[$option] )
			&& is_float( $this->options[$option] ) ) {
			return $this->options[$option];
		} else {
			return null;
		}
	}

	public function getString( string $option ) : ?string {
		if ( isset( $this->options[$option] )
			&& is_string( $this->options[$option] ) ) {
			return $this->options[$option];
		} else {
			return null;
		}
	}

	public function getArray( string $option ) : ?array {
		if ( isset( $this->options[$option] )
			&& is_array( $this->options[$option] ) ) {
			return $this->options[$option];
		} else {
			return null;
		}
	}
}
