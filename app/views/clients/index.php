<?php

$controller = KISS_Controller::get_instance();
$controller->add_script("clients/client_list.js");
?>
<script type="text/javascript" charset="utf-8">
	$(document).ready(function() {
		$('.clientlist').dataTable({
			"iDisplayLength": 25,
			"aLengthMenu": [[25, 50, -1], [25, 50, "All"]],
			"sPaginationType": "full_numbers",
			"bStateSave": true,
			"aaSorting": [[4,'desc']]
		});
	} );
</script>

<?$machine = new Machine()?>
<?$hash = new Hash()?>

  <legend>Machines <span class='badge badge-info'><?=$machine->count()?></span></legend>
  
  <table class="table table-striped table-condensed table-bordered">
    <thead>
      <tr>
        <th>Client    </th>
        <th>Serial    </th>
        <th>IP        </th>
		<th>OS        </th>
        <th>Machine_name</th>
		<th>Available disk space</th>
      </tr>
    </thead>
    <tbody>
	<?foreach($machine->retrieve_many() as $client):?>
      <tr>
		<?$reportdata = new Reportdata($client->serial_number)?>
        <td>
        	<div class="btn-group">
        		<span class="btn" data-serialnumber="<?=$client->serial_number?>"><i class="icon-info-sign"></i></span>
        		<a class="btn" href="<?=url("clients/detail/$client->serial_number")?>"><?=$client->computer_name?></a>
        	</div>
		</td>
		<td><?=$client->serial_number?></td>
		<td><?=$reportdata->remote_ip?></td>
		<td><?=$client->os_version?></td>
		<td><?=$client->machine_name?></td>
		<td><?=humanreadablesize($client->available_disk_space * 1024)?></td>
      </tr>
	<?endforeach?>
    </tbody>
  </table>