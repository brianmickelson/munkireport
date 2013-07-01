
<script type="text/javascript" charset="utf-8">
	$(document).ready(function() {
		$('.table').dataTable({
            "iDisplayLength": 25,
            "sPaginationType": "bootstrap",
            "aLengthMenu": [[25, 50, -1], [25, 50, "All"]],
			"bStateSave": true,
            "aaSorting": [[3,'desc']],
		    "aoColumns": [ 
				/* Client */			null,
				/* Serial */			null,
				/* Hostname */			null,
				/* Status */			null,
				/* Expires in */		null,
				/* Timediff */	{ "bVisible":    false }
			],
            "aoColumnDefs": [
		      { "iDataSort": 5, "aTargets": [ 4 ] }
		    ]


		});
	} );
</script>


  <legend>Warranty Reports <span class='badge badge-info'><?=count($warranty)?></span></legend>
  
  <table class="table table-striped table-condensed table-bordered">
    <thead>
      <tr>
        <th>Client    </th>
        <th>Serial    </th>
        <th>Hostname   </th>
        <th>Status</th>
		<th>Expires in</th>
		<th>Timediff</th>
      </tr>
    </thead>
    <tbody>
    <?$thirty = 60 * 60 * 24 * 30?>
	<?foreach($warranty as $client):?>
	<?$class = $client->status == 'Expired' ? 'important' : ($client->status == 'Supported' ? 'success' : 'warning');
	$timediff = strtotime($client->end_date) - time(); 
	if($timediff < $thirty){ $class = 'warning';}?>
      <tr>
		<?$machine = new Machine($client->sn)?>
        <td>
			<a href="<?=url("clients/detail/$client->sn")?>"><?=(
				$machine->computer_name != '' ? $machine->computer_name : $machine->serial_number)?></a>
		</td>
		<td><?=$machine->serial_number?></td>
		<td><?=$machine->hostname?></td>
		<td><span class="label label-<?=$class?>"><?=$client->status?></span></td>
		<td><?=RelativeTime($timediff)?></td>
		<td><?=$timediff?>
      </tr>
	<?endforeach?>
    </tbody>
  </table>