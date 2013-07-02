<?php
class Inventoryitem extends Model
{
	function __construct($serial='')
	{
		parent::__construct('id', strtolower(get_class($this))); //primary key, tablename
		$this->rs['id'] = 0;
		$this->rs['serial'] = (string) $serial;
		$this->rs['name'] = '';
		$this->rs['version'] = '';
		$this->rs['bundleid'] = '';
		$this->rs['bundlename'] = '';
		$this->rs['path'] = '';

		// Add indexes
		$this->idx['serial'] = array('serial');
		$this->idx['name_version'] = array('name', 'version');

		// Create table if it does not exist
		$this->create_table();
	}




	/**
	* Returns all inventory items grouped by their version and including an 
	* install count for each version found. If the parameters are not empty,
	* only an array of versions and install counts are returned since it is 
	* assumed the caller already knows the rest of the values.
	*/
	public function all_versions($name = null)
	{
		if ($name == null)
		{
			return $this->select('name, version, COUNT(id) AS num_installs',
				'1 GROUP BY name, version ORDER BY name ASC, COUNT(id) DESC');
		}

		return $this->select('version, COUNT(id) AS num_installs',
			'name = ? GROUP BY name, version ORDER BY name ASC, COUNT(id) DESC',
			$name
		);
	}




	/**
	 * Deletes all application bundles associated with the given $serial.
	 *
	 * @param $serial String The serial number of the machine who's assets 
	 * 	should be deleted.
	 * @return $this
	 */
	function delete_set( $serial ) 
	{
		$dbh=$this->getdbh();
		$sql = 'DELETE FROM '
			. $this->enquote( $this->tablename )
			. ' WHERE '
			. $this->enquote( 'serial' )
			. '=?';
		$stmt = $dbh->prepare( $sql );
		$stmt->bindValue( 1, $serial );
		$stmt->execute();
		return $this;
	}




	/**
	 * Accepts an inventory report in plist format and saves that data to the
	 * databse with an association to the value of $this->serial.
	 */
	function process($data)
	{
		//list of bundleids to ignore
		$bundleid_ignorelist = Config::get('bundleid_ignorelist');

		// Compile regex
		$regex = '/^'.implode('|', $bundleid_ignorelist).'$/';

		if ( ! $this->serial)
			die('Serial missing');

		require_once(APP_PATH . 'lib/CFPropertyList/CFPropertyList.php');
		$parser = new CFPropertyList();
		$parser->parse(
		$data, CFPropertyList::FORMAT_XML);
		$inventory_list = $parser->toArray();
		if (count($inventory_list))
		{
			// clear existing inventory items
			$this->delete_set($this->serial);

			// insert current inventory items
			foreach ($inventory_list as $item)
			{
				if ( ! preg_match($regex, $item['bundleid']))
				{
					$item['bundlename'] = @$item['CFBundleName'];
					$this->id = 0;
					$this->merge($item)->save();
				}
			}
		}
	}
}