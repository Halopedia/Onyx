<?php
/**
 * BaseTemplate class for the Onyx skin.
 * 
 * @file
 * @ingroup Skins
 */

class OnyxTemplate extends BaseTemplate {

  /**
   * Outputs the entire contents of the page.
   */
  public function execute() {
    $this->html('headelement'); ?>
    <!-- BANNER -->
    <div id="onyx-banner">

    </div>
    <!-- MAIN PAGE -->
    <div id="onyx-page">
      <!-- HEADER -->
      <div id="onyx-page-header">

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
      </div>
    </div>
    <!-- FOOTER -->
    <div id="onyx-footer">

    </div>
    <!-- FLOATING NOTIFICATIONS -->
    <?php if(hasFloatingNotifications()) { ?>
    <div id="onyx-floatingNotifications">
      <!-- NEW TALK NOTIFICATION -->
      <?php if($this->data['newtalk']) { ?>
      <div id="onyx-floatingNotifications-newTalk">
        <?php $this->html('newtalk'); ?>
      </div>
      <?php } ?>
    </div>
    <?php } ?>
    <!-- TOOLBOX -->
    <div id="onyx-toolbox">
      
    </div>
  <?php $this->printTrail(); ?>
  </body>
</html
<?php
  }

  private function hasFloatingNotifications() {
    return $this->data['newtalk'];
  }
}