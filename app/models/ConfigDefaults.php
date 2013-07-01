<?php

class ConfigDefaults
{
	protected $_settings = array();

	public function __construct()
	{
		$this->_settings['indexPage'] = "index.php";
		$this->_settings['uriProtocol'] = "AUTO";
		$this->_settings['webHost'] = '//'.$_SERVER[ 'HTTP_HOST' ];
		$this->_settings['subdirectory']
			= substr(
					    $_SERVER['PHP_SELF'],
					    0,
					    strpos($_SERVER['PHP_SELF'], basename(FC))  
				    );
		//	= str_replace("index.php", "", $_SERVER['PHP_SELF']);

		$this->_settings['siteName'] = "MunkiReport";
		$this->_settings['vnc_link'] = "vnc://%s:5900";
		$this->_settings['bundleidIgnoreList']
			= array("com.apple.print.PrinterProxy");
		$this->_settings['auth'] = array(
			"auth_config" => array(
				'admin' => '$P$BrBM9FGh3.jOt4nEVRXfMBRuiRyJu01'
			)
		);
		$this->_settings['routes'] = array();
		$this->_settings['paths']
			= array(
				"system"      => APP_ROOT . "system/",
				"application" => APP_ROOT . "app/",
				"view"        => APP_ROOT . "app/views/",
				"controller"  => APP_ROOT . "app/controllers/"
			);
		$this->_settings['pdo']
			= array(
				"dsn" => 'sqlite:'.APP_ROOT.'app/db/db.sqlite',
				"user" => "",
				"pass" => "",
				"opts" => array()
			);
		$this->_settings['timezone'] = @date_default_timezone_get();
		$this->_settings['debugModeEnabled'] = FALSE;
	}




	public function settings()
	{
		return $this->_settings;
	}


	public function writeDefaultValues()
	{
		Plist::writeToXMLFile( $this->_settings, SETTINGS_FILE );
	}
}
