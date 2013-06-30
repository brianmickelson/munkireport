<?php


function _warranty($status = '')
{
	$status = urldecode($status);
	$controller = KISS_Controller::get_instance();
	$warranty = new Warranty();
	$filter = "";
	if ($status != '')
	{
		if ($status == 'expires_soon')
		{
			$filter = 'status = "Supported" AND DATE(end_date) <= DATE("now", "+1 month")';
		}
		else
		{
			$filter = 'status = "' . $status . '"';
		}
	}
	$controller->set('warranty', $warranty->retrieve_many($filter));
}