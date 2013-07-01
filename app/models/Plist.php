<?php

require_once(dirname(__DIR__) . '/lib/CFPropertyList/CFPropertyList.php');

/**
 * Just a simple wrapper class around the CFPropertyList classes.
 */
class Plist
{
	public static function readFile($aFile)
	{
		if (!is_file($aFile) || !is_readable($aFile))
		{
			return array();
		}

		$plist = new CFPropertyList($aFile);
		return $plist->toArray();
	}


	public static function writeToXMLFile($data, $aFile)
	{
		self::writeToFile($data, $aFile, TRUE);
	}


	public static function writeToBinaryFile($data, $aFile)
	{
		self::writeToFile($data, $aFile, FALSE);
	}


	public static function writeToFile($data, $aFile, $useXML = TRUE)
	{	
		$td = new CFTypeDetector();
		$structure = $td->toCFType( $data );
		$plist = new CFPropertyList();
		$plist->add($structure);
		if ($useXML == TRUE)
		{
			$xml = $plist->toXML(TRUE);
			if ( ! @file_put_contents($aFile, $xml))
			{
				trigger_error("The webserver doesn't have adequate permissions to write to the file '" . $aFile . "'", E_USER_WARNING);
			}
		}
		else
			$plist->saveBinary($aFile);
	}
}