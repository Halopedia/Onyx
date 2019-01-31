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
   * - Logo(s) and main page link
   * - Personal tools
   * - Content actions
   * - Sidebar
   * - Language links
   * - Search form
   * 
   * FUTURE EXTENSIONS:
   * 
   * - Read Onyx-specific configuration settings from MediaWiki:Onyx-config
   * - Read Onyx-specific navigation links from MediaWiki:Onyx-navigation
   * - Read Onyx-specific toolbox links from MediaWiki:Onyx-toolbox
   * - Read user-defined Onyx toolbox links from User:USERNAME/Onyx-toolbox
   * - Support VisualEditor
  */

  /**
   * Outputs the entire contents of the page.
   */
  public function execute() { ?>
<!-- START OF PAGE -->
  <?php $this->html('headelement'); ?>
    <!-- BANNER -->
    <div id="onyx-banner">
      <div id="onyx-banner-bannerContent">
        <!-- BANNER LOGO -->

        <!-- BANNER MENU -->
        <div id="onyx-bannerContent-bannerMenu">

        </div>
      </div>
    </div>
    <!-- MAIN PAGE -->
    <div id="onyx-page" class="mw-body">
      <!-- HEADER -->
      <div id="onyx-page-header">
        <!-- HEADER LOGO -->
        <div id="onyx-header-logo">

        </div>
        <!-- TAGLINE -->
        <?php if (true /* TODO: implement tagline opt-out */) { ?>
        <div id="onyx-header-tagline">
          <h1 id="onyx-tagline-text">
            <?php
              if($this->data['tagline'] != '') {
                $this->msg('tagline');
              } else {
                $this->text('sitename');
              }
            ?>
          </h1>
        </div>
        <?php } ?>
        <!-- TITLE BAR -->
        <div id="onyx-header-titleBar">
          <!-- ARTICLe ACTIONS -->
          <div id="onyx-titleBar-actions">
            <!-- PAGE STATUS INDICATORS -->
            <?php echo $this->getIndicators(); ?>
            <!-- EDIT BUTTON -->

            <!-- TALK BUTTON -->

            <!-- TOGGLE SIDEBAR BUTTON -->
            
          </div>
          <!-- ARTICLE TITLE -->
          <div id="onyx-titleBar-title">
            <h1 id="onyx-title-tile"><?php $this->html('title') ?></h1>
          </div>
          <!-- ARTICLE SUBTITLE -->
          <?php if ($this->data['subtitle']) { ?>
          <div id="onyx-titleBar-subtitle">
            <?php $this->html('subtitle'); ?>
          </div>
          <?php } ?>
          <!-- UNDELETE ARTICLE MESSAGE -->
          <?php if ($this->data['undelete']) { ?>
          <div id="onyx-titleBar-undelete">
            <?php $this->html('undelete'); ?>
          </div>
          <?php } ?>
        </div>
      </div>
      <!-- SIDEBAR -->
      <div id="onyx-page-sidebar">

      </div>
      <!-- ARTICLE -->
      <div id="onyx-page-content">
        <!-- SITE NOTICE -->
        <?php if ($this->data['sitenotice']) { ?>
        <div id="onyx-content-siteNotice">
          <?php $this->html('sitenotice'); ?>
        </div>
        <?php } ?>
        <!-- ARTICLE CONTENT -->
        <?php $this->html('bodytext'); ?>
        <!-- CATEGORY LINKS -->
        <span id="onyx-content-categories">
          <?php $this->html('catlinks'); ?>
        </span>
        <!-- ADDITIONAL CONTENT -->
        <span id="onyx-content-additionalContent">
          <?php $this->html('dataAfterContent'); ?>
        </span>
      </div>
    </div>
    <!-- FOOTER -->
    <div id="onyx-footer">
      <div id="onyx-footer-footerContent">
        <!-- FOOTER ICONS -->
        <div id="onyx-footerContent-footerIcons">
          <ul id="onyx-footerIcons-list">
            <?php
              foreach ($this->getFooterIcons('icononly') as $blockName => $footerIcons) {
            ?>
            <li class="onyx-footerIcons-listItem">
              <?php
                foreach ($footerIcons as $icon) {
                  echo $this->getSkin()->makeFooterIcon($icon);
                }
              ?>
            </li>
            <?php } ?>
          </ul>
        </div>
        <!-- FOOTER LINKS -->
        <div id="onyx-footerContent-footerLinks">
          <ul id="onyx-footerLinks-list">
            <?php
              foreach ($this->getFooterLinks() as $category => $links) {
            ?>
            <li class="onyx-footerLinks-listItem">
              <?php
                foreach ($links as $key) {
                  echo $this->html($key);
                }
              ?>
            </li>
            <?php } ?>
          </ul>
        </div>
      </div>
    </div>
    <!-- FLOATING NOTIFICATIONS -->
    <div id="onyx-floating">
      <!-- NEW TALK NOTIFICATION -->
      <?php if($this->data['newtalk']) { ?>
      <div id="onyx-floating-newTalk">
        <?php $this->html('newtalk'); ?>
      </div>
      <?php } ?>
    </div>
    <!-- TOOLBOX -->
    <div id="onyx-toolbox">
      <ul id="onyx-toolbox-toolList">
        <?php
          foreach ($this->getToolbox() as $key => $toolboxItem) {
            echo $this->makeListItem($key, $toolboxItem);
          }
          /* TODO: Debug - why is this printing the entire HTML doc's content
                   at the end of the list?

          wfRunHooks('SkinTemplateToolboxEnd', array(&$this));
          
          */
        ?>
      </ul>
    </div>
  <?php $this->printTrail(); ?>
<!-- END OF PAGE -->
<?php
  }
}