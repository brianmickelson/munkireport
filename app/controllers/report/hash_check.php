<?php
header("Content-type: application/xml");

function _hash_check()
{
	$controller = KISS_Controller::get_instance();
	$controller->prevent_render(TRUE);

	// Check if we have a serial and data
	if( ! isset($_POST['serial'])) die('Serial is missing');
	if( ! isset($_POST['items'])) die('Items are missing');

	require_once(APP_PATH . 'lib/CFPropertyList/CFPropertyList.php');

	// Create return object
	$out = new CFPropertyList();
	$itemarr = new CFArray();

	$items = $_POST['items'];
	$serial = $_POST['serial'];
	if (get_magic_quotes_gpc())
	{
		$items = stripslashes($items);
		$serial = stripslashes($serial);
	}

	// Parse items
	$parser = new CFPropertyList();
	$parser->parse($items, CFPropertyList::FORMAT_XML);

	// Get stored hashes from db
	$hash = new Hash();
	$hashes = $hash->all($serial);

	// Compare sent hashes with stored hashes
	foreach($parser->toArray() as $key => $val)
	{
		if( ! (isset($hashes[$key]) && $hashes[$key] == $val['hash']))
		{
			$itemarr->add( new CFString( $key ) );
		}
	}

	// Return list of changed hashes
	$out->add( $itemarr );
	echo $out->toXML();
}