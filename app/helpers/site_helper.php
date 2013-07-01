<?php

// Munkireport version (last number is number of commits)
$GLOBALS['version'] = '0.8.3.57';

function custom_error($msg='') 
{
	$vars['msg']=$msg;
	die(View::do_fetch(APP_PATH.'errors/custom_error.php',$vars));
}




function formatted_count($array)
{
	return number_format(count($array));
}



function vnc_link($ip = '')
{
	if ($ip == '')
		return null;
	return sprintf(Config::get('vnc_link'), $ip);
}



//===============================================
// Database
//===============================================
function getdbh()
{
	if ( ! isset($GLOBALS['dbh']))
	{
		try
		{
			$GLOBALS['dbh'] = new PDO(
				Config::get('pdo.dsn'),
				Config::get('pdo.user'),
				Config::get('pdo.pass'),
				Config::get('pdo.opts')
				);
		}
		catch (PDOException $e)
		{
			die('Connection failed: '.$e->getMessage());
		}
	}
	return $GLOBALS['dbh'];
}

//===============================================
// Autoloading for Business Classes
//===============================================
// Assumes Model Classes start with capital letters and Libraries start with lower case letters
function __autoload( $classname )
{
	$a=$classname[0];
	if ( $a >= 'A' && $a <='Z' ) require_once( APP_PATH.'models/'.$classname.'.php' );
	else require_once( APP_PATH.'libraries/'.$classname.'.php' );  
}

function url($url='', $fullurl = FALSE)
{
  $s = $fullurl ? WEB_HOST : '';
  $s .= WEB_FOLDER.($url && INDEX_PAGE ? INDEX_PAGE.'/' : INDEX_PAGE) . ltrim($url, '/');
  return $s;
}

function redirect($uri = '', $method = 'location', $http_response_code = 302)
{
	if ( ! preg_match('#^https?://#i', $uri))
	{
		$uri = url($uri);
	}
	
	switch($method)
	{
		case 'refresh'	: header("Refresh:0;url=".$uri);
			break;
		default			: header("Location: ".$uri, TRUE, $http_response_code);
			break;
	}
	exit;
}

function humanreadablesize($bytes, $decimals = 2) {
	$suffix = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');
	$idx = 0;
	while($bytes > 1024)
	{
		$bytes = $bytes / 1024;
		$idx++;
	}
	return round($bytes, $decimals) . $suffix[$idx];
}




function time_relative_to_now($seconds)
{
	$time = time() - $seconds;
	$relative_time = RelativeTime(abs($time));

	// Time is in the past
	if ($time > 0)
	{
		return $relative_time . " ago";
	}
	else
	{
		return $relative_time;
	}
}

function RelativeTime($time) 
{
	$points = array(
		'year'     => 31556926,
		'month'    => 2629743,
		'week'     => 604800,
		'day'      => 86400,
		'hour'     => 3600,
		'minute'   => 60,
		'second'   => 1
	);
	$plurals = array( 
		'year'		=> 'years',
		'month'		=> 'months',
		'week'		=> 'weeks',
		'day'		=> 'days',
		'hour'		=> 'hours',
		'minute'	=> 'minutes',
		'second'	=> 'seconds'
	);

	foreach($points as $point => $value)
	{
		$elapsed = floor($time/$value);
		if($elapsed > 0)
		{
			$point = $elapsed > 1 ? $plurals[$point] : $point;
			return "$elapsed $point";
		}
	}
	return "0 seconds";
}




function safe_array_fetch($array, $key, $default = null)
{
	return isset($array[$key]) && ! empty($array[$key]) ? $array[$key] : $default;
}
