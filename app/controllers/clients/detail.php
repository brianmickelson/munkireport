<?php

function _detail($sn = '')
{
	$controller = KISS_Controller::get_instance();

	$controller->set("machine", new Machine($sn));
	$controller->set("hash", new Hash($sn, "Machine"));
	$controller->set("report", new Reportdata($sn));
	$controller->set("warranty", new Warranty($sn));
	$controller->set("history", new InstallHistory($sn));
}