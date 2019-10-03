<?php
/**
 * SkinTemplate class for the Onyx skin.
 * 
 * @file
 * @ingroup Skins
 */

 class SkinOnyx extends SkinTemplate {

  public $skinname = 'onyx',
    $stylename = 'Onyx',
    $template = 'OnyxTemplate';
  
  /**
   * This function adds JavaScript to the skin, via ResourceLoader.
   * 
   * @param OutputPage $out
   */
  public function initPage( OutputPage $out ) : void {
    parent::initPage( $out );
    $out->addModules( 'skins.onyx.js' );
  }

  /**
   * Add CSS to the skin, via ResourceLoader.
   * 
   * @param OutputPage $out
   */
  function setupSkinUserCss( OutputPage $out ) : void {
    parent::setupSkinUserCss( $out );
    $out->addModuleStyles( [
      'mediawiki.skinning.interface',
      'skins.onyx.styles'
    ] );
  }
 }