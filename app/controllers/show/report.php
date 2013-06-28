<?php


function _report($sn = '')
{
	$controller = KISS_Controller::get_instance();

	$client = new Munkireport($sn);
	$report = $client->report_plist;

	$install_results  = safe_array_fetch($report, "InstallResults",  array());
	$items_to_install = safe_array_fetch($report, 'ItemsToInstall',  array());
	$items_to_remove  = safe_array_fetch($report, 'ItemsToRemove',   array());
	$managed_installs = safe_array_fetch($report, 'ManagedInstalls', array());
	$apple_updates    = safe_array_fetch($report, 'AppleUpdates',    array());
	$removal_results  = safe_array_fetch($report, 'AppleUpdates',    array());
	$results = array(
		"managed_installs" => array(),
		"managed_uninstalls" => array(),
		"apple_updates" => array()
	);

	//echo "<pre>";
	//var_dump(array_keys($report));
	//var_dump($report);
	//var_dump($clean_managed_installs);
	//exit;

	// Move install results over to their install items.
	$tmp = array();
	foreach($install_results as $result)
	{
		$tmp[$result["name"] . '-' . $result["version"]] = 
			array('result' => $result["status"] == 0 ? 'Installed' : 'error');
	}


	foreach($managed_installs as $key => &$item)
	{
		$dversion = $item["display_name"].'-'.$item["installed_version"];
		if(isset($tmp[$dversion]))
		{
			$item['install_result'] = $tmp[$dversion]['result'];
		}
	}

	// Move install results to managed installs
	foreach($apple_updates as $key => &$item)
	{
		if(isset($item["version_to_install"]))
		{
			$dversion = $item["display_name"].'-'.$item["version_to_install"];
			if(isset($tmp[$dversion]) && $tmp[$dversion]['result'] == 'Installed')
			{
				$item['installed'] = TRUE;
			}
		}
	}
	

	foreach($apple_updates as $key => &$item)
	{
		$item['install_result'] = 'Pending';
		$dversion = $item["display_name"].'-'.$item["version_to_install"];
		
		if(isset($tmp[$dversion]))
		{
			$item['install_result'] = $tmp[$dversion]['result'];
		}
	}

	// Move removal results over to their removal items.
	$tmp_removal_results = array();
	foreach($removal_results as $result)
	{
		if(is_string($result) && preg_match('/^Removal of (.+): (.+)$/', $result, $matches))
		{
			$tmp_removal_results[$matches[1]]['result'] = $matches[2] == 'SUCCESSFUL' ? 'Removed' : $matches[2];
		}
	}


	foreach($items_to_remove as $key => &$item)
	{
		$item['install_result'] = 'Pending';
		$dversion = $item["display_name"];
		if(isset($removal_results[$dversion]))
		{
			$item['install_result'] = $tmp_removal_results[$dversion]['result'];
		}
	}
	

	$controller->set('client', $client);
	$controller->set('machine', new Machine($sn));
	$controller->set('warranty', new Warranty($sn));
	$controller->set('report', $report);
	$controller->set('reportdata', new Reportdata($sn));
	$controller->set('serial', $sn);
}