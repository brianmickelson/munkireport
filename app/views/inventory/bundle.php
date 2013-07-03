<?php
	$controller = KISS_Controller::get_instance();
	$controller->add_script("clients/detail_popover.js");
	$controller->add_script("inventory/bundle.js");
	$route = $controller->controller . "/" . $controller->action 
		. "/" . $name;
	$selected_version = isset($version) && $version !='' ? $version : "All";
?>
<legend><?=$name?> 
	<div class="btn-group">
		<a class='btn btn-small dropdown-toggle' href="#" data-toggle="dropdown">
			<?php echo $selected_version;?>
			<span class="caret"></span>
		</a>
		<ul id="bundle-versions" class="dropdown-menu">
			<li><a href="<?php echo url($route);?>">All</a></li>
			<li class="divider"></li>
			<!-- dataTables should be filling in the rest -->
		</ul>
	</div>

</legend>
<? if (count($inventory_items)): ?>
<table id="bundle-table" class='table table-striped table-condensed table-bordered'>
		<thead>
				<tr>
					<th>Hostname</th>
					<th>Username</th>
					<th>OS</th>
					<th>Version</th>
					<th>BundleID</th>
					<th>CFBundleName</th>
					<th>Path</th>
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
		<? foreach($inventory_items as $item): ?>
		<? $url=url('/inventory/detail/' . $item['serial']) ?>
				<tr>
						<td>
							<a href='<?= $url ?>'>
								<?= $item['hostname'] ?>
							</a>
						</td>
						<td><?= $item['username'] ?></td>
						<td><?= $item['version'] ?></td>
						<td><?= $item['bundleid'] ?></td>
						<td><?= $item['bundlename'] ?></td>
						<td><?= $item['path'] ?></td>
				</tr>
		<? endforeach ?>
		</tbody>
</table>
<? else: ?>
<p><i>No results.</i></p>
<? endif ?>