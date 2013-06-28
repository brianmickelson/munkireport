<?php

function _detail($name='', $version='')
{
	$controller = KISS_Controller::get_instance();

	$name = rawurldecode($name);
	$inventory_item_obj = new InventoryItem();
	if ($version)
	{
		$version = rawurldecode($version);
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

	$all_versions = $inventory_item_obj->all_versions(
			$items[0]->rs['name'],
			$items[0]->rs['bundlename'],
			$items[0]->rs['bundleid']
		);


	$inventory_items = array();
	foreach ($items as $item)
	{
		$machine = new Machine($item->serial);
		$reportdata = new Reportdata($item->serial);
		$instance['serial'] = $item->serial;
		$instance['hostname'] = $machine->computer_name;
		$instance['username'] = $reportdata->console_user;
		$instance['version'] = $item->version;
		$instance['bundleid'] = $item->bundleid;
		$instance['bundlename'] = $item->bundlename;
		$instance['path'] = $item->path;
		$inventory_items[] = $instance;
	}
	
	$controller->set('name', $name);
	$controller->set('all_versions', $all_versions);
	$controller->set('inventory_items', $inventory_items);
	$controller->set('version', $version);
}