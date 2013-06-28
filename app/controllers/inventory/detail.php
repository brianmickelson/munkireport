<?php


function _detail($serial = '')
{
	$inv = new InventoryItem();
	$controller = KISS_Controller::get_instance();
	$controller->set(
		'inventory_items',
		$inv->retrieve_many('serial=?', array($serial))
	);
	$controller->set('serial', $serial);
	$controller->set('machine', new Machine($serial));
	$controller->set('report', new Reportdata($serial));
}
