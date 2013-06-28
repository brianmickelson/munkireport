<?php

function _diskstatus()
{
	$controller = KISS_Controller::get_instance();
	$disk = new DiskReport();
	$controller->set('diskreport', $disk->retrieve_many());
}