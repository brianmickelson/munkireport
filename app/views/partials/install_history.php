<?if(isset($history->rs['packageIdentifiers']) && count($history->rs['packageIdentifiers']) > 1):?>

<table class="table table-striped">
	<thead>
		<tr>
			<th>Name</th>
			<th>Version</th>
			<th>Install Date</th>
			<th>ProcessName</th>
		</tr>
	</thead>
	<tbody>
	<?foreach($history->rs as $item):?>
	<?if($apple == (strpos($item['packageIdentifiers'][0],'com.apple.') === 0)):?>
		<tr>
			<td><?=$item['displayName']?></td>
			<td><?=$item['displayVersion']?></td>
			<td><?=date('Y-m-d G:i:s', $item['date'])?></td>
			<td><?=$item['processName']?></td>
		</tr>
	<?endif?>
	<?endforeach?>
	</tbody>
</table>
<?else:?>
<p><i>No Install History</i></p>
<?endif?>
