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
        <!-- HEADER LOGO -->
        <div id="onyx-header-logo">

        </div>
        <!-- TAGLINE -->
        <?php if (true /* TODO: implement tagline opt-out */) { ?>
        <div id="onyx-header-tagline">
          <h1 id="onyx-tagline-tagline">
            <?php
              if($this->data['tagline'] != '') {
                $this->html('tagline');
              } else {
                $this->html('sitename');
              }
            ?>
          </h1>
        </div>
        <?php } ?>
        <!-- TITLE BAR -->
        <div id="onyx-header-titleBar">
          <!-- BUTTONS -->
          <div id="onyx-titleBar-buttons">

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
      </div>
    </div>
    <!-- FOOTER -->
    <div id="onyx-footer">

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
      
    </div>
  <?php $this->printTrail(); ?>
  </body>
</html>
<?php
  
}