<?php
class Machine extends Model {

	function __construct($serial='')
	{
		parent::__construct('id', strtolower(get_class($this))); //primary key, tablename
		$this->rs['id'] = '';
		$this->rs['serial_number'] = $serial;
			$this->rt['serial_number'] = 'VARCHAR(255) UNIQUE';
		$this->rs['hostname'] = '';
		$this->rs['available_disk_space'] = 0;
		$this->rs['machine_model'] = '';
		$this->rs['machine_desc'] = '';
		$this->rs['img_url'] = '';
		$this->rs['current_processor_speed'] = '';
		$this->rs['cpu_arch'] = '';
		$this->rs['os_version'] = '';
		$this->rs['physical_memory'] = '';
		$this->rs['platform_UUID'] = '';
		$this->rs['number_processors'] = '';
		$this->rs['SMC_version_system'] = '';
		$this->rs['boot_rom_version'] = '';
		$this->rs['bus_speed'] = '';
		$this->rs['computer_name'] = '';
		$this->rs['l2_cache'] = '';
		$this->rs['machine_name'] = '';
		$this->rs['packages'] = '';	   
		
		// Create table if it does not exist
		$this->create_table();
		
		if ($serial)
			$this->retrieve_one('serial_number=?', $serial);
		
		$this->serial = $serial;
		  
	}
	
	// ------------------------------------------------------------------------

	/**
	 * Process data sent by postflight
	 *
	 * @param string data
	 * @author abn290
	 **/
	function process($plist)
	{
		echo "Machine: got data\n";
		
		require_once(APP_PATH . 'lib/CFPropertyList/CFPropertyList.php');
		$parser = new CFPropertyList();
		$parser->parse($plist, CFPropertyList::FORMAT_XML);
		$mylist = $parser->toArray();
		
		$this->merge($mylist)->save();
	}




	/**
	 * Returns all machines along with some select data from the reportdata 
	 * and hash tables.
	 *
	 * If a serial number is provided, this method will only return 0 or 1 
	 * result(s).
	 */
	public function expanded_machines($serial = '')
	{
		$sql = "SELECT
			machine.*,
			COALESCE(
				reportdata.long_username,
				munkireport.console_user,
				reportdata.console_user,
				'<None>'
			) AS console_user,
			munkireport.remote_ip,
			munkireport.timestamp AS munki_timestamp,
			hash.timestamp AS inventory_timestamp,
			diskreport.TotalSize AS diskreport_totalsize,
			diskreport.FreeSpace AS diskreport_freespace,
			diskreport.SMARTStatus AS diskreport_smart_status,
			diskreport.solidstate AS diskreport_solidstate
		FROM machine
			LEFT JOIN reportdata
				ON reportdata.serial = machine.serial_number
			LEFT JOIN hash
				ON hash.serial = machine.serial_number
			LEFT JOIN diskreport
				ON diskreport.serial_number = machine.serial_number
			LEFT JOIN munkireport
				ON munkireport.serial = machine.serial_number"
		. ($serial != '' ? ' WHERE machine.serial_number = ' . $serial : '')
		. " GROUP BY machine.serial_number";
		return $this->query($sql);
	}
}