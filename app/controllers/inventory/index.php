<?php


function _index()
{
	$controller = KISS_Controller::get_instance();
	$hash = new Hash();
	$items = $hash->retrieve_many('name =? ORDER BY timestamp DESC', 'InventoryItem');
	$controller->set('inventory_items', $items);
}