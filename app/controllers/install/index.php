<?php

function _index()
{
	$controller = KISS_Controller::get_instance();
	$controller->redirect('install/script');
}