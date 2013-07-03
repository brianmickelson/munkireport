<?php

function _bundle($name = null, $version = null)
{
	$controller = KISS_Controller::get_instance();
	if ($name == null)
		$controller->request_not_found(
			"This page requires a bundle name as the first paramter.");

	$inventory_item_obj = new InventoryItem();
	if ($version != null)
	{
		$items = $inventory_item_obj->retrieve_many(
			'name = ? AND version = ?',
			array($name, $version)
		);
	}
	else
	{
		$items = $inventory_item_obj->retrieve_many(
		'name = ?', array($name));
	}

	// Grab all available versions for the specified bundle name.
	$all_versions = $inventory_item_obj->all_versions($name);

	$inventory_items = array();
	$machine = new Machine();

	foreach ($items as $item)
	{
		$machines = $machine->expanded_machines($item->serial);
		$machines = $machines[0];
		unset($machines->id, $item->rs['id'], $item->rs['serial']);

		$instance = $item->rs;
		$instance['machine_info'] = $machines;
		$inventory_items[] = $instance;
	}
	
	$controller->set('name', $name);
	$controller->set('all_versions', $all_versions);
	$controller->set('inventory_items', $inventory_items);
	$controller->set('version', $version);
}