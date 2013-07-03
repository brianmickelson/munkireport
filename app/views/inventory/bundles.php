<?php
$controller = KISS_Controller::get_instance();
$controller->add_script('inventory/bundles.js');

?><legend>Inventory → Application Bundles</legend>
<table id="bundles-table" class='table table-striped table-condensed table-bordered'>
  <thead>
    <tr>
      <th>Name</th>
      <th>Version(s)</th>
    </tr>
  </thead>
  <tbody>
  </tbody>
</table>