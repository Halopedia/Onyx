<?php
/**
 * BaseTemplate class for the Onyx skin.
 * 
 * @file
 * @ingroup Skins
 */

class OnyxTemplate extends BaseTemplate {

  /**
   * Outputs the entire contents of the page/
   */
  public function execute() {
    $this->html('headelement');
?>

<!-- TODO: Implement skin here -->

<?php
    $this->printTrail();
?>

  </body>
</html>

<?php
  }
}