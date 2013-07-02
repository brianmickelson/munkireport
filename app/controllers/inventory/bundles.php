<?php

function _bundles()
{
	$controller = KISS_Controller::get_instance();
	// The html view doesn't actually get any data, so we'll juet let it
	// render without performing any queries if that's what's been requested.
	if ($controller->view_format == '')
		return;

	$inventory_item_obj = new InventoryItem();
	$items = $inventory_item_obj->all_versions();
	unset($inventory_item_obj);


	// First, we group the results by application name, storing each version 
	// we find as a subarray
	$inventory = array();
	foreach($items as $item)
	{
		$name = $item['name'];
		$version = $item['version'];
		$count = $item['num_installs'];

		$inventory[$name][] = array($version, $count);
	}
	unset($items);


	// Next, we flatten the array so that the top level element is an array,
	// each element of which contains a hash table consisting of keys 'name'
	// and 'versions'. 'name' is obvious, 'versions' contains a subarray, with
	// the 0th item being the version and the 1st item being the number of
	// machines with that version installed.
	$rows = array();
	foreach($inventory as $name => $val)
	{
		$rows[] = array(
			'name' => $name,
			'versions' => $val
		);
	}

	$controller->set('inventory', $rows);
}