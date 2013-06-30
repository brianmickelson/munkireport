<div class="well well-small">
	<div class="row">
		<div class="span1">
			<img width="72" height="72" src="<?php echo @$machine->rs['img_url'];?>" />
		</div>
		<div class="span3">
			<h4>
				<?php echo @$machine->rs['hostname'];?><br />
			</h4>
			<small class="muted">
				<?php echo @$machine->rs['machine_desc'];?>
				<?php echo @$machine->rs['machine_model'];?>
				<br />
				Warranty Coverage: <?php
					if ($warranty->rs['status'] == "Supported")
					{
						echo "<span class='text-success'>Supported until "
							. strftime('%x', strtotime($warranty->rs['end_date'])) . "</span>";
					}
					else
						echo "<span class='text-error'>Expired</span>";
				?>
				<br />Current User: <?php echo htmlentities($report->rs['long_username']);?>
				</small>
				<hr />
				<a class="btn btn-mini" href="<?php echo url('clients/recheck_warranty/' . $machine->rs['serial_number']);?>">
					Recheck Warranty Status
				</a>
				<?if(Config::get('vnc_link')):?>

				<a class="btn btn-mini" href="<?printf(Config::get('vnc_link'), $report->rs['remote_ip'])?>">Remote Control (vnc)</a>
				<?endif?>
		</div>
		<div class="span5 offset=6">
			<small>
				<dl class="dl-horizontal">
					<dt>Software</dt>
					<dd>OS X <?=@$machine->rs['os_version'] . ' ('
							. @$machine->rs['cpu_arch'] . ')'?>&nbsp;</dd>
					<dt>CPU Speed</dt>
					<dd><?=@$machine->rs['current_processor_speed']?> ( <?php echo @$machine->rs['number_processors'];?> core )</dd>
					<dt>Serial Number</dt>
					<dd><?php echo @$machine->rs['serial_number'];?>&nbsp;</dd>
					<dt>SMC Version</dt>
					<dd><?php echo @$machine->rs['SMC_version_system'];?>&nbsp;</dd>
					<dt>Boot ROM</dt>
					<dd><?php echo @$machine->rs['boot_rom_version'];?>&nbsp;</dd>
					<dt>Memory</dt>
					<dd><?php echo @$machine->rs['physical_memory'];?>&nbsp;</dd>
					<dt>Hardware UUID</dt>
					<dd><?php echo @$machine->rs['platform_UUID'];?>&nbsp;</dd>
					<dt>Remote IP Address</dt>
					<dd><?php echo $report->rs['remote_ip'];?>&nbsp;</dd>
				</dl>
			</small>
		</div>
	</div>
</div>