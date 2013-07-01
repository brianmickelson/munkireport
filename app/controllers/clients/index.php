<?php


function _index()
{
	$controller = KISS_Controller::get_instance();
	$machines = new Machine();
	$hash = new Hash();
	$data = array();
	foreach($machines->retrieve_many() as $machine)
	{
		$record = array();
		foreach(array_keys($machine->rs) as $key)
		{
			$record[$key] = $machine->rs[$key];
		}
		$munki_report = new MunkiReport($machine->serial_number);
		$record['munki_report']['remote_ip'] = $munki_report->remote_ip;
		$data[] = $record;
	}


	$controller->set('machine_records', $data);
}