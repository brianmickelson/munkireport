<?php


function _index($return = '')
{
	$check = FALSE;
	$controller = KISS_Controller::get_instance();
	$mechanisms = array('config');

	if ( count($_POST) == 0 && !isset($_SESSION['auth_success_redirect']))
	{
		$_SESSION['auth_success_redirect'] = $_SERVER['HTTP_REFERER'];
	}
	
	$login = @$_POST['login'];
	$password = @$_POST['password'];
	
	$controller->set('login', $login);
	$controller->set('url', url('auth/index/' . $return));
	
	// Check if there's a valid auth mechanism in config
	$auth_mechanisms = array();
	$authSettings = Config::get('auth');
	foreach($mechanisms as $mech)
	{
		if (isset($authSettings["auth_$mech"]) && is_array($authSettings["auth_$mech"]))
		{
			$auth_mechanisms[$mech] = $authSettings["auth_$mech"];
		}
	}
	
	// No valid mechanisms found, bail
	if ( ! $auth_mechanisms)
	{
		$controller->redirect('auth/generate');
	}
	
	if ($login && $password)
	{
		
		// Get hasher object
		require(APP_PATH . '/lib/phpass-0.3/PasswordHash.php');
		$t_hasher = new PasswordHash(8, TRUE);
		
		foreach($auth_mechanisms as $mechanism => $auth_data)
		{
			// Local is just a username => hash array
			if($mechanism == 'config')
			{
				if(isset($auth_data[$login]))
				{
					$check = $t_hasher->CheckPassword($password, $auth_data[$login]);
					break;
				}
			}
		}
		
		if($check)
		{
			$_SESSION['user'] = $login;
			$_SESSION['auth'] = $mechanism;
			$return = $_SESSION['auth_success_redirect'];
			unset($_SESSION['auth_success_redirect']);

			// use header() here instead of the KISS_Controller's redirect
			// method since we need to redirect to a full URL
			header("Location: " . $return);
		}
		
	}
	
	if($_POST)
	{
		$data['error'] = "Your username and password didn't match. Please try again";
	}
}