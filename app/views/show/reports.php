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


<legend>Munki Clients <span class='badge badge-info'><?=count($objects)?></span></legend>

<table class="table clientlist table-striped table-condensed table-bordered">
<thead>
  <tr>
    <th>Client    </th>
    <th>User      </th>
    <th>IP        </th>
	<th>OS        </th>
    <th>Latest Run</th>
	<th>Manifest</th>
  </tr>
</thead>
<tbody>
<?foreach($objects as $item):?>
<?php
	$machine = $item['machine'];
	$report = $item['report'];
	$report_data = $item['report_data'];
?>
  <tr>
    <?$url = url("show/report/" . $machine->serial_number)?>
    <td>
		<?if($report->report_plist):?>
		<a href="<?=$url?>"><?=$machine->computer_name?></a>
		<?else:?>
		<?=$machine->computer_name?>
		<?endif?>
	</td>
	<td><?=$report->console_user?></td>
	<td><?=$report->remote_ip?></td>
	<td><?=$machine->os_version?> <?=$machine->cpu_arch?></td>
	<td>
		<?=time_relative_to_now(strtotime($report->timestamp));?>
		<?=$report_data->runtype?>
		<?=$report_data->runstate?>
		<?if($report->report_plist['Errors']):?>
		<a title="errors" href="<?=$url?>#errors" class="badge badge-important"><?php
			echo count($report->report_plist['Errors']);
		?></a>
		<?endif?>
		<?if($report->report_plist['Warnings']):?>
		<a title="warnings" href="<?=$url?>#errors" class="badge badge-warning"><?php
			echo count($report->report_plist['Warnings']);
		?></a>
		<?endif?>
	</td>
	<td><?php
		if(isset($report->report_plist['ManifestName']))
			echo $report->report_plist['ManifestName'];
		else
			echo '<span class="text-error">unknown</span>';
		?>
	</td>
  </tr>
<?endforeach?>
</tbody>
</table>