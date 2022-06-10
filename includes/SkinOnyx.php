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
	 * @inheritDoc
	 * In 1.39 the mediawiki.skinning.interface is removed.
	 * In 1.36 we can cleanly replace it with ResourceLoaderSkinModule.
	 * When support for 1.35 is no longer needed, we should merge the
	 * skins.onyx.styles.future and skins.onyx.styles module and remove this code.
	 **/
	public function initPage( OutputPage $out ) {
		$pre136 = version_compare( MW_VERSION, '1.36', '<' );
		$module = $pre136 ? 'mediawiki.skinning.interface' : 'skins.onyx.styles.future';
		$out->addModules( $module );
		parent::initPage( $out );
	}
}
