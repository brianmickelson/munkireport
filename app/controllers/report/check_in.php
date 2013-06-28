<?php


function _check_in()
{
	$controller = KISS_Controller::get_instance();
	$controller->prevent_render(TRUE);

	require_once(APP_PATH . 'lib/CFPropertyList/CFPropertyList.php');
	$items = $_POST['items'];
	$serial = $_POST['serial'];
	if (get_magic_quotes_gpc())
	{
		$items = stripslashes($items);
		$serial = stripslashes($serial);
	}
	$parser = new CFPropertyList();
	$parser->parse($items, CFPropertyList::FORMAT_XML);
	$arr = $parser->toArray();

	foreach($arr as $key => $val)
	{
		// Skip items without data
		if ( ! isset($val['data']))
			continue;

		printf("Starting: %s\n", $key);

		// Todo: prevent admin and user models, sanitize $key
		if( ! file_exists(APP_PATH . 'models/' . $key . '.php'))
		{
			printf("Model not found: %s\n", $key);
			continue;
		}
		require_once(APP_PATH . 'models/' . $key . '.php');

		if ( ! class_exists( $key, false ) )
		{
			printf("Class not found: %s\n", $key);
			continue;
		}

		// Load model
		$class = new $key($serial);


		if( ! method_exists($class, 'process'))
		{
			printf("No process method in: %s\n", $key);
			continue;
		}

		try {
			// Process data (todo: why do we have to convert to iso-8859?)
			$class->process(iconv('UTF-8', 'ISO-8859-1//IGNORE', $val['data']));
			//$class->process($val['data']);

			// Store hash
			$hash = new Hash($_POST['serial'], $key);
			$hash->hash = $val['hash'];
			$hash->save();

		} catch (Exception $e) {
			printf('An error occurred while processing: %s', $key);

		}

	}
}