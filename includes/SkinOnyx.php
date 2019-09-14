<?php
/**
 * SkinTemplate class for the Onyx skin.
 * 
 * @file
 * @ingroup Skins
 */

 class SkinOnyx extends SkinTemplate {

	var $skinname = 'onyx';
	var $stylename = 'Onyx';
	var $template = 'OnyxTemplate';
	var $useHeadElement = true;
	
	/**
	 * This function adds JavaScript to the skin, via ResourceLoader.
	 * 
	 * @param OutputPage $out
	 */
	public function initPage(OutputPage $out) : void {
		parent::initPage($out);
		$out->addModules('skins.onyx.js');
	}

	/**
	 * Add CSS to the skin, via ResourceLoader.
	 * 
	 * @param OutputPage $out
	 */
	function setupSkinUserCss(OutputPage $out) : void {
		parent::setupSkinUserCss($out);
		$out->addModuleStyles(array('mediawiki.skinning.interface', 'skins.onyx.styles'));
	}
}