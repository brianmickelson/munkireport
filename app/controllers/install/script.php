<?php


function _script()
{
	$controller = KISS_Controller::get_instance();
	$controller->prevent_render(TRUE);
	View::do_dump("install/script.php");
}