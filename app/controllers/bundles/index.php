<?php

function _index()
{
	$controller = KISS_Controller::get_instance();

	$inventory_item_obj = new InventoryItem();
	$items = $inventory_item_obj->select('DISTINCT serial, name, version');
	$inventory = array();
	foreach($items as $item)
	{
		if(!isset($inventory[$item['name']][$item['version']]))
		{
			$inventory[$item['name']][$item['version']] = 1;
		}
		else
		{
			$inventory[$item['name']][$item['version']]++;
		}
	}
	$controller->set('inventory', $inventory);
}