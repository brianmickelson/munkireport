<div class="tab-pane active" id='machine'>
		<?php View::do_dump('partials/machine_info.php', array(
			"machine" => $machine,
			"warranty" => $warranty,
			"report" => $report)); ?>
	</div>
<ul class="nav nav-tabs">
	<li class="active"><a href="#munki" data-toggle="tab">Munki</a></li>
	<li>
		<a href="#apple-software" data-toggle="tab">Apple Software</a>
	</li>
	<li>
		<a href="#third-party-software" data-toggle="tab">Third Party Software</a>
	</li>
</ul>

<div class="tab-content">

	<div class="tab-pane" id='munki'>
		<legend>Munki</legend>
		<?php View::do_dump('partials/munki.php', array("machine" => $machine));?>
	</div>

	<div class="tab-pane" id='apple-software'>
		<legend>Installed Apple Software</legend>
		<?php View::do_dump('partials/install_history.php', array('apple'=> TRUE, "history" => $history))?>
	</div>

	<div class="tab-pane" id='third-party-software'>
		<legend>Installed Third-Party Software</legend>
		<?php View::do_dump('partials/install_history.php', array('apple'=> FALSE, "history" => $history))?>
	</div>
</div>