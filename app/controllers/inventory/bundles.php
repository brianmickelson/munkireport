<?php

function _bundles()
{
	$controller = KISS_Controller::get_instance();

	$inventory_item_obj = new InventoryItem();
	$items = $inventory_item_obj->select('name, version, COUNT(id) AS num_installs', '1 GROUP BY name, version ORDER BY name ASC, num_installs DESC');

	$inventory = array();
	foreach($items as $item)
	{
		$name = $item['name'];
		$version = $item['version'];
		$installs = $item['num_installs'];

		$inventory[$name][$version] = $installs;
	}
	$controller->set('inventory', $inventory);
}