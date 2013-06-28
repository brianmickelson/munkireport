<?php



function _logout()
{
	$controller = KISS_Controller::get_instance();
	session_destroy();
	$controller->redirect('admin');
}