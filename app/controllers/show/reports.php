<?php


function _reports()
{
	$controller = KISS_Controller::get_instance();
	$report = new Munkireport();
	$objects = array();
	
	foreach($report->retrieve_many("id > 0") as $client)
	{
		$objects[] = array(
			"report" => $client,
			"machine" => new Machine($client->rs['serial']),
			"report_data" => new Reportdata($client->rs['serial'])
		);
	}

	$controller->set("objects", $objects);
}