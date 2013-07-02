<?php


function _index()
{
	$controller = KISS_Controller::get_instance();
	
	// only perform the query if we're rendering HTML since Datatables is
	// going to come right back to grab the json payload.
	if ($controller->view_format == '')
		return;

	$machine_obj = new Machine();
	$controller->set('machines', $machine_obj->expanded_machines());
}