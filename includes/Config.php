<?php

namespace Onyx;

class Config {

	private const DEFAULT_CONFIG = [
		'customisation' => [
			'logo-banner-source' => 'File:Onyx-logo-banner.png',
			'logo-header-source' => 'File:Onyx-logo-header.png',
			'custom-toolbox' => 'on',
			'custom-toolbox-source' => 'MediaWiki:Onyx-toolbox',
			'custom-navigation' => 'on',
			'custom-navigation-source' => 'MediaWiki:Onyx-navigation'
		],
		'sidebar-modules' => [
			'recent-changes' => 'on',
			'recent-changes-amount' => 7,
			'page-contents' => 'on',
			'page-contents-min-headings' => 3,
			'custom-static' => 'Template:Onyx/Sidebar/Static',
			'custom-sticky' => 'Template:Onyx/Sidebar/Sticky'
		],
		'sidebar-state' => [
			'default' => 'on',
			'page-main' => 'off',
			'action-edit' => 'off'
		]
	];

	private const VALID_OPTIONS = [
		'customisation' => [
			'logo-banner-source', 'logo-header-source', 'custom-toolbox',
			'custom-navigation'
		],
		'sidebar-modules' => [
			'recent-changes', 'recent-changes-amount', 'page-contents',
			'page-contents-min-headings', 'custom-static', 'custom-sticky'
		],
		'sidebar-state' => [
			'default', 'page-main', 'namespace-main', 'namespace-category',
			'namespace-template', 'namespace-file', 'namespace-mediawiki',
			'action-view', 'action-edit', 'action-history', 'action-delete',
			'action-protect'
		]
	];

	private $options;

	public function __construct() {
		$this->options = Config::DEFAULT_CONFIG;
	}

	public function updateWith( array $newOptions ) : void {
		foreach( $newOptions as $category => $options ) {
			if ( array_key_exists( $category, Config::VALID_OPTIONS )
					&& is_array( $options ) ) {
				foreach ( $options as $name => $value ) {
					if ( in_array( $name, Config::VALID_OPTIONS[$category] )
							&& is_string( $value ) ) {
						$this->options[$name] = $value;
					}
				}
			}
		}
	}

	public function isEnabled(string $category, string $option) : ?bool {
		if ( isset( $this->options[$category] )
				&& isset( $this->options[$category][$option] ) ) {
			return strtolower( $this->options[$category][$option] ) === 'on'
					|| strtolower( $this->options[$category][$option] ) === 'yes'
					|| strtolower( $this->options[$category][$option] ) == 'true';
		} else {
			return null;
		}
	}

	public function getSetting( string $category, string $option ) {
		if ( isset( $this->options[$category] )
				&& isset( $this->options[$category][$option] ) ) {
			return $this->options[$category][$option];
		} else {
			return null;
		}
	}

	public static function isEnabledByDefault( string $category,
			string $option ) : ?bool {
		if ( isset( self::DEFAULT_CONFIG[$category] )
				&& isset( self::DEFAULT_CONFIG[$category][$option] ) ) {
			return strtolower( self::DEFAULT_CONFIG[$category][$option] ) === 'on'
					|| strtolower( self::DEFAULT_CONFIG[$category][$option] ) === 'yes'
					|| strtolower( self::DEFAULT_CONFIG[$category][$option] ) == 'true';
		} else {
			return null;
		}
	}

	public static function getDefaultSetting( string $category,
			string $option ) {
		if ( isset( self::DEFAULT_CONFIG[$category] )
				&& isset( self::DEFAULT_CONFIG[$category][$option] ) ) {
			return self::DEFAULT_CONFIG[$category][$option];
		} else {
			return null;
		}
	}

}
