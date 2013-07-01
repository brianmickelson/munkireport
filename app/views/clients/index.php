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
		<th>OS        </th>
        <th>Machine_name</th>
		<th>Available disk space</th>
      </tr>
    </thead>
    <tbody>
	<?foreach($machine_records as $record):?>
      <tr>
		<?php
			$remote_ip = $record['munki_report']['remote_ip'];
			$computer_name = safe_array_fetch(
				$record,
				'computer_name',
				"<i>" . $record['serial_number'] . "</i>");
		?>
        <td>
        	<div class="btn-group">
        		<span class="btn" data-serialnumber="<?=$client->serial_number?>"><i class="icon-info-sign"></i></span>
        		<a class="btn<?php echo $remote_ip =='' ? ' disabled' : '';?>" href="<?php echo vnc_link($remote_ip);?>" title="Remote Desktop:<?php echo $remote_ip;?>"><i class="icon-eye-open"></i></a>
        		<a class="btn" href="<?=url("clients/detail/" . $record['serial_number'])?>"><?=$computer_name?></a>
        	</div>
		</td>
		<td><?=$record['serial_number']?></td>
		<td><?=$record['os_version']?></td>
		<td><?=$record['machine_name']?></td>
		<td><?=humanreadablesize($record['available_disk_space'] * 1024, 0)?></td>
      </tr>
	<?endforeach?>
    </tbody>
  </table>
