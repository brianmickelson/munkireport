<?php


function _warranty()
{
	$controller = KISS_Controller::get_instance();
	$warranty = new Warranty();
	$controller->set('warranty', $warranty->retrieve_many());
}