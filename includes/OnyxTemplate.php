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
      <div id="onyx-banner-bannerContent" class="onyx-pageAligned">
        <!-- BANNER LOGO -->

        <!-- BANNER MENU -->
        <div id="onyx-bannerContent-bannerMenu">

        </div>
      </div>
    </div>
    <!-- MAIN PAGE -->
    <div id="onyx-page" class="onyx-pageAligned mw-body">
      <!-- HEADER -->
      <div id="onyx-page-header">
        <!-- WIKI HEADER -->
        <div id="onyx-header-wikiHeader">
          <!-- HEADER LOGO -->
          <div id="onyx-wikiHeader-logo">

          </div>
          <!-- TAGLINE -->
          <?php if (true /* TODO: implement tagline opt-out */) { ?>
          <div id="onyx-wikiHeader-tagline">
            <h1 id="onyx-tagline-header">
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
          <!-- NAVIGATION -->
          <div id="onyx-wikiHeader-navigation">
            <ul id="onyx-navigation-list">
              
            </ul>
          </div>
        </div>
        <!-- ARTICLE HEADER -->
        <div id="onyx-header-articleHeader">
          <!-- ARTICLe ACTIONS -->
          <div id="onyx-articleHeader-actions">
            <!-- PAGE STATUS INDICATORS -->
            <?php echo $this->getIndicators(); ?>
            <!-- EDIT BUTTON -->

            <!-- TALK BUTTON -->

            <!-- TOGGLE SIDEBAR BUTTON -->
            
          </div>
          <!-- ARTICLE TITLE -->
          <div id="onyx-articleHeader-title">
            <h1 id="onyx-title-tile"><?php $this->html('title') ?></h1>
          </div>
          <!-- ARTICLE SUBTITLE -->
          <?php if ($this->data['subtitle']) { ?>
          <div id="onyx-articleHeader-subtitle">
            <?php $this->html('subtitle'); ?>
          </div>
          <?php } ?>
          <!-- UNDELETE ARTICLE MESSAGE -->
          <?php if ($this->data['undelete']) { ?>
          <div id="onyx-articleHeader-undelete">
            <?php $this->html('undelete'); ?>
          </div>
          <?php } ?>
        </div>
      </div>
      <!-- SIDEBAR -->
      <div id="onyx-page-sidebar">
        <!-- RECENT CHANGES -->
        <div id="onyx-sidebar-recentChanges">
          <h1 id="onyx-recentChanges-header">Recent Changes</h1>
          <div id="onyx-recentChanges-content">
            
          </div>
        </div>
        <!-- CUSTOM SIDEBAR -->
        <div id="onyx-sidebar-customContent">

        </div>
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
      <div id="onyx-footer-footerContent" class="onyx-pageAligned">
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