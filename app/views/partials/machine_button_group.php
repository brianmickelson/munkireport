<?php
/*
	Displays the button group for use in consistently representing machines in 
	a table.
	Expected variables:
		$serial_number
		$hostname
		$ip
		$controller_name
		$action_name
 */
$computer_name = $hostname;
if (empty($hostname) && empty($serial_number))
	$computer_name = $ip;
else if (empty($hostname))
	$computer_name = $serial_number;

?><div class="btn-group">
	<span class="btn" data-serialnumber="<?=$serial_number?>">
		<i class="icon-info-sign"></i>
	</span>
	<a class="btn<?php echo $ip == '' ? ' disabled' : '';?>" href="<?php
		echo vnc_link($remote_ip);?>" title="Remote Desktop:<?php
		echo $remote_ip;?>"><i class="icon-eye-open"></i></a>
		<a class="btn" href="<?=url($controller_name . "/" . $action_name . "/" . $serial_number)?>">
			<?php echo $computer_name;?>
		</a>
</div>