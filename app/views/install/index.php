<?php

// see if we can write to the app/db directory
$db_file = Config::get('paths.application') . "db/ephemeral.txt";
$db_writable = @file_put_contents($db_file, "testing");
if ($db_writable)
	unlink($db_file);
?>

<?php if ( ! $db_writable ):?>
	<legend>Improper Permissions</legend>
	<div class="well well-small">
		<p>
			The following path must be writable by your web server:
			<br />
			<code><?php echo Config::get('paths.application') . "db/"?></code>
			<br />
			Please rectify this, then reload this page.
		</p>
	</div>
<?php else:?>
	<legend class="text-success">Success!</legend>
	<p>
		If you examine <code><?php
			echo Config::get('paths.application') . "db/";
		?></code>, you will find an sqlite database along with a plist file. Examine the plist file to adjust the global settings or to specify an alternate database engine/host.
	</p>

	<legend>Your Current Settings:</legend>
	<table class="table table-condensed table-striped table-bordered">
		<tr>
			<th>Config Key Path</th>
			<th>Config Value</th>
		</tr>
	<?php
		foreach(Config::getAllKeys() as $key)
		{
			echo "<tr>";
				echo "<td><strong class='pull-right'>" . htmlentities($key)
					. "</strong></td>";
				echo "<td>" . htmlentities(Config::get($key)) . "</td>";
			echo "</tr>";
		}
	?>
	</table>
<?php endif;?>