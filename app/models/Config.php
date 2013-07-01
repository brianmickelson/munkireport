<?php
/**
 * A class that sits between the application and the settings that are
 * configurable by an admin.
 *
 * All settings are stored in app/db/settings.plist
 */
require_once(__DIR__ . "/Plist.php");
define("SETTINGS_FILE", dirname(__DIR__) . "/db/settings.plist");

class Config
{
	protected static $_settings;
	protected static $_install_mode = FALSE;

	protected static function _loadSettings()
	{
		if (self::$_settings == NULL)
		{
			if (!is_file( SETTINGS_FILE ))
			{
				require_once(__DIR__ . "/ConfigDefaults.php");
				$defaults = new ConfigDefaults();
				$defaults->writeDefaultValues();
				self::$_settings = Plist::readFile( SETTINGS_FILE );
				if (count(self::$_settings) === 0)
				{
					self::$_install_mode = TRUE;
					self::$_settings = $defaults->settings();
				}
			}
			else
			{
				self::$_settings = Plist::readFile( SETTINGS_FILE );
			}
		}
	}




	public static function install_mode()
	{
		return self::$_install_mode === TRUE;
	}




	public static function getAllKeys()
	{
		self::_loadSettings();
		return self::_keys2paths(self::$_settings);
	}


	private static function _keys2paths($array, $path_prefix = '')
	{
		//echo "<pre>";
		//var_dump($array);
		//exit;
		$keys = array_keys($array);
		$paths = array();
		for($i = 0; $i < count($keys); $i++)
		{
			$native_key = $keys[$i];
			$key = ($path_prefix != '' ? $path_prefix . "." . $native_key : $native_key);
			if (is_array($array[$native_key]))
			{
				$sub_keys = self::_keys2paths($array[$native_key], $key);
				foreach($sub_keys as $sub_key)
				{
					$paths[] = $sub_key;
				}
			}
			else
				$paths[] = $key;
		}
		return $paths;
	}




	public static function get($aKey)
	{
		if (self::$_install_mode && $aKey == 'debugModeEnabled')
			return TRUE;

		self::_loadSettings();
		$paths = explode(".", $aKey);
		$data = self::$_settings;
		foreach($paths as $path)
		{
			if (!isset($data[$path]))
				return NULL;
			
			$data = $data[$path];
		}
		return $data;
	}




	public static function set($aKey, $aValue)
	{
		self::$_settings[$aKey] = $aValue;
	}




	public static function flush()
	{
		Plist::writeToXMLFile(self::$_settings, SETTINGS_FILE);
	}
}