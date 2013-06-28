<?php

function _signup()
{
	$controller = KISS_Controller::get_instance();
	$password = @$_POST['password'];
	$controller->set('login', @$_POST['login']);
	
	if ($password)
	{
		require(APP_PATH . '/lib/phpass-0.3/PasswordHash.php');
		$t_hasher = new PasswordHash(8, TRUE);
		$controller->set('generated_pwd', $t_hasher->HashPassword($password));
	}
}