<?php


function _recheck_warranty($sn = '')
{
	$controller = KISS_Controller::get_instance();
	$warranty = new Warranty($sn);
	$warranty->check_status($force=TRUE);
	$controller->redirect("clients/detail/$sn");
}