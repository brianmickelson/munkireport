<?php
header("HTTP/1.0 500 Internal Server Error: Uncaught Exception");
?>
<h1>Internal Server Error</h1>
<table>
	<tr>
		<th style="text-align: right">Code:</th>
		<td><?php echo $exception->getCode();?></td>
	</tr>
	<tr>
		<th style="text-align: right">File:</th>
		<td><?php echo $exception->getFile();?></td>
	</tr>
	<tr>
		<th style="text-align: right">Line:</th>
		<td><?php echo $exception->getLine();?></td>
	</tr>
	<tr>
		<th style="text-align: right">Message:</th>
		<td><?php echo $exception->getMessage();?></td>
	</tr>
	<tr>
		<th style="text-align: right">Stack Trace:</th>
		<td><pre><?php echo $exception->getTraceAsString();?></pre></td>
	</tr>
</table>