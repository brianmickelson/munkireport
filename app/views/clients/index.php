<?php

$controller = KISS_Controller::get_instance();
$controller->add_script("clients/client_list.js");
?>
<script type="text/javascript" charset="utf-8">
	$(document).ready(function() {
		$('.clientlist').dataTable({
			"iDisplayLength": 25,
			"aLengthMenu": [[25, 50, -1], [25, 50, "All"]],
			"sPaginationType": "bootstrap",
			"bStateSave": true,
			"aaSorting": [[4,'desc']]
		});
	} );
</script>
  <legend>Machines <span class='badge badge-info'><?=formatted_count($machine_records)?></span></legend>
  
  <table class="clientlist table table-striped table-condensed table-bordered">
    <thead>
      <tr>
        <th>Client    </th>
        <th>Serial    </th>
		<th>OS Version</th>
		<th>IP</th>
        <th>Machine_name</th>
		<th>Available disk space</th>
      </tr>
    </thead>
    <tbody>
	<?foreach($machine_records as $record):?>
      <tr>
        <td><?php View::do_dump('partials/machine_button_group.php', array(
        	'serial_number' => $record['serial_number'],
        	'hostname' => $record['computer_name'],
        	'ip' => $record['munki_report']['remote_ip'],
        	'controller_name' => 'clients',
        	'action_name' => 'detail'
        ))?>
		</td>
		<td><?=$record['serial_number']?></td>
		<td><?=$record['os_version']?></td>
		<td><?=$record['munki_report']['remote_ip'];?></td>
		<td><?=$record['machine_name']?></td>
		<td><?=humanreadablesize($record['available_disk_space'] * 1024, 0)?></td>
      </tr>
	<?endforeach?>
    </tbody>
  </table>
