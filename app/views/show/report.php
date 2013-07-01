<?$report_type = (object) array('name'=>'Munkireport', 'desc' => 'munkireport')?>
<?php View::do_dump('partials/machine_info.php', array(
	'machine' => $machine,
	'report' => $reportdata,
	'warranty' => $warranty
));?>

<?if($report['Errors'] OR $report['Warnings']):?>
<span id="errors">
	<hr />
	<?if(isset($report['Errors'])):?>
		<pre class="text-error">ERRORS<br /><?=implode("\n", $report['Errors'])?></pre>
	<?endif?>
	
	<?if(isset($report['Warnings'])):?>
	<pre class="text-warning">WARNINGS<br /><?=implode("\n", $report['Warnings'])?></pre>
	<?endif?>
	<hr/>
</span>
<?endif?>

<?$package_tables = array(	'Apple Updates' =>'AppleUpdates',
							'Active Installs' => 'ItemsToInstall',
							'Active Removals' => 'ItemsToRemove',
							'Problem Installs' => 'ProblemInstalls')?>

<!--! Package tables -->
<?foreach($package_tables AS $title => $report_key):?>  
	<?if(isset($report[$report_key]) && $report[$report_key]):?>
	<legend><?=$title?> <span class="badge badge-info"><?php echo count(@$report[$report_key]);?></span></legend>
	<table class="table table-condensed table-striped table-bordered">
      <thead>
        <tr>
          <th>Name</th>
          <th>Size</th>
        </tr>
      </thead>
      <tbody>
		<?foreach($report[$report_key] AS $item):?>
        <tr>
          <td><small><?php
          	$item_is_installed = isset($item['install_result']);
          	$item_is_installed = $item_is_installed || @$item['installed'];
          	?>
          	<span class="pull-left"><i class="<?php
          		echo $item_is_installed ? "text-success icon-ok" : "text-error icon-remove"?>"></i>&nbsp;
          	</span>
			<?=@$item['display_name']?>
			<span class="muted">
				<?=@$item['version_to_install']?>
				<?=@$item['installed_version']?>
			</span>
          </small></td>
          <td><span class="pull-right"><small><?=isset($item['installed_size']) ? humanreadablesize($item['installed_size'] * 1024): '?'?></span></small></td>
        </tr>
		<?endforeach?>
      </tbody>
    </table>
	<?endif?>
<?endforeach?>

<?$package_tables = array(	'Managed Installs' =>'ManagedInstalls')?>

<table class="twocol table">
  <tbody>
    <td>
		<?foreach($package_tables AS $title => $report_key):?>
		  <legend><?=$title?> <span class="badge badge-info"><?php
		  	echo count($report[$report_key]);
		  ?></span></legend>

			<?if(isset($report[$report_key]) && $report[$report_key]):?>
			<?php $is_installed = isset($item['installed']) && $item['installed'];
			?>
			<table class="table table-striped table-condensed table-bordered">
		      <thead>
		        <tr>
		          <th>Name</th>
		          <th>Size</th>
		        </tr>
		      </thead>
		      <tbody>
				<?foreach($report[$report_key] AS $item):?>
		        <tr>
		          <td>
		          	<?php if ($item['installed'] == TRUE):?>
					<span class="pull-left text-success" title="Installed">
						<i class="icon-ok"></i> &nbsp;
					</span>
		        	<?php else:?>
		        	<span class="pull-left text-error" title="Not installed">
		        		<i class="icon-remove"></i>
		          	</span>
			    	<?php endif;?>
					<?=@$item['display_name']?>
					<span class="muted">
					<?=@$item['version_to_install']?>
					<?=@$item['installed_version']?>
					</span>
		          </td>
		          <td style="text-align: right;"><?=isset($item['installed_size']) ? humanreadablesize($item['installed_size'] * 1024): '?'?></td>
		        </tr>
				<?endforeach?>
		      </tbody>
		    </table>
		    <?else:?>
		      <p><i>No <?=strtolower($title)?></i></p>
			<?endif?>
		<?endforeach?>
    </td>
    <td>
    
<?if(isset($report['managed_uninstalls_list'])):?>
  <legend>Managed Uninstalls <span class="badge badge-info"><?php
		  	echo count($report['managed_uninstalls_list']);
		  ?></span></legend>

  <table class="table table-striped table-condensed table-bordered">
    <thead>
      <tr>
        <th>Name</th>
      </tr>
    </thead>
    <tbody>
	<?foreach($report['managed_uninstalls_list'] AS $item):?>
      <tr>
        <td>
          <?=$item?>
          <?php if (in_array($item, $report['RemovedItems'])):?>
          <span class="pull-left text-success" title="Not installed">
          	<i class="icon-ok"></i>&nbsp;
          </span>
	      <?php else:?>
	      <span class="pull-right text-error" title="Installed">
          	<i class="icon-remove"></i>&nbsp;
          </span>
		  <?php endif;?>
        </td>
      </tr>
	<?endforeach?>
    </tbody>
  </table>
<?endif?>

    </td>
  </tbody>
</table>
<!--

	<legend>Installed Apple Software</legend>
	<?php //$this->view('partials/install_history', array('apple'=> TRUE))?>

	 <legend>Installed Third-Party Software</legend>
	<?php //$this->view('partials/install_history', array('apple'=> FALSE))?>
-->