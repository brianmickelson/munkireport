<?php
$controller = KISS_Controller::get_instance();
$controller->add_script('inventory/index.js');

?><legend>Inventory Clients <span id="machines-count-badge" class="badge badge-info">( loading )</span></legend>

<table id="machines-table" class="table table-striped inventory table-condensed table-bordered">
<thead>
  <tr>
    <th>Client      </th>
    <th>Console User</th>
    <th>IP          </th>
    <th>OS Version  </th>
    <th>Last Inventory</th>
    <!-- extra columns for searching -->
    <th>Serial number</th>
    <th>Machine Model</th>
    <th>Machine Description</th>
    <th>Architecture</th>
    <th>platform_UUID</th>
    <th>SMC Version</th>
    <th>Boot Rom Version</th>
  </tr>
</thead>
<tbody>
</tbody>
</table>
