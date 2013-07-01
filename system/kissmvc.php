<?php
require('kissmvc_core.php');


//===============================================================
// Controller
//===============================================================
class Controller extends KISS_Controller 
{
	public function before_route()
	{
		$controller = KISS_Controller::get_instance();
		$actionName = $controller->action;
		$controllerName = $controller->controller;

		if (Config::install_mode())
		{
			// The Config class says we're in installation mode, so let's make
			// sure the user knows what needs to be done.
			if ($controllerName != 'install')
			{
				$controller->redirect('install');
			}
			return;
		}

		$allowed_routes = array(
			"install/script",
			"install/plist",
			"report/hash_check",
			"report/check_in",
			"auth/index"
		);
		$current_route = $controller->controller . '/' . $controller->action;

		$session_exists = isset($_SESSION['user']) && isset($_SESSION['auth']);
		
		if (in_array($current_route, $allowed_routes))
			return;

		// redirect the unauthenticated masses to the login page
		if ( ! $session_exists && $controllerName != "auth")
			$controller->redirect('auth');
	}
}

//===============================================================
// Model/ORM
//===============================================================
class Model extends KISS_Model
{
    protected $rt = array(); // Array holding types
    protected $idx = array(); // Array holding indexes

	function save() {
        // one function to either create or update!
        if ($this->rs[$this->pkname] == '')
        {
            //primary key is empty, so create
            $this->create();
        }
        else
        {
            //primary key exists, so update
            $this->update();
        }
    }

	// ------------------------------------------------------------------------


    /**
	 * Run raw query
	 *
	 * @return array
	 * @author 
	 **/
	function query($sql, $bindings=array())
	{
		$dbh=$this->getdbh();
		if ( is_scalar( $bindings ) )
			$bindings=$bindings ? array( $bindings ) : array();
		$stmt = $dbh->prepare( $sql );
		$stmt->execute( $bindings );
		$arr=array();
		while ( $rs = $stmt->fetch( PDO::FETCH_OBJ ) )
		{
			$arr[] = $rs;
		}
		return $arr;
	}


	// ------------------------------------------------------------------------

	/**
	 * Count records
	 *
	 * @param string where
	 * @param mixed bindings
	 * @return void
	 * @author abn290
	 **/
	function count( $wherewhat='', $bindings='' )
	{
		$dbh = $this->getdbh();
		if ( is_scalar( $bindings ) ) $bindings = $bindings ? array( $bindings ) : array();
		$sql = 'SELECT COUNT(*) AS count FROM '.$this->tablename;
		if ( $wherewhat ) $sql .= ' WHERE '.$wherewhat;
		$stmt = $dbh->prepare( $sql );
		$stmt->execute( $bindings );
		if ( $rs = $stmt->fetch( PDO::FETCH_OBJ ) ) 
		{
			return $rs->count;
		}
		return 0;
	}

	// ------------------------------------------------------------------------

	/**
	 * Create table
	 * 
	 * Create table based on $this->rs array
	 * and $this->rt array
	 *
	 * @param array assoc array with optional type strings
	 * @return void
	 * @author bochoven
	 **/
	function create_table()
	{
		$dbh = $this->getdbh();
		
		$dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES,false); 
		
        if( ! $dbh->prepare( "SELECT * FROM ".$this->enquote($this->tablename)." LIMIT 1" ))
        {
			// Get columns
			$columns = array();
			foreach($this->rs as $name => $val)
			{
				// Determine type automagically
				$type = is_int($val) ? 'INTEGER' : (is_string($val) ? 'VARCHAR(255)' : (is_float($val) ? 'REAL' : 'BLOB'));
				
				// Or set type from type array
				$columns[$name] = isset($this->rt[$name]) ? $this->rt[$name] : $type;
			}
			
			// Set primary key
			$columns[$this->pkname] = 'INTEGER PRIMARY KEY';
			
			// Set autoincrement per db engine
			switch($dbh->getAttribute(constant("PDO::ATTR_DRIVER_NAME")))
			{
				case 'sqlite':
					$columns[$this->pkname] .= ' AUTOINCREMENT';
					break;
				case 'mysql':
					$columns[$this->pkname] .= ' AUTO_INCREMENT';
			}
			
			// Compile columns sql
            $sql = '';
			foreach($columns as $name => $type)
			{
				$sql .= $this->enquote($name) . " $type,";
			}
			$sql = rtrim($sql, ',');

            $rowsaffected = $dbh->exec(sprintf("CREATE TABLE %s (%s)", $this->enquote($this->tablename), $sql));

			// Set indexes
			$this->set_indexes();
			
        }
		//print_r($dbh->errorInfo());
        return ($dbh->errorCode() == '00000');
	}
	
	// ------------------------------------------------------------------------

	/**
	 * Set indexes for this table
	 *
	 * @return boolean
	 * @author bochoven
	 **/
	function set_indexes()
	{
		$dbh = $this->getdbh();
		
		foreach($this->idx as $idx_name => $idx_data)
		{
			$dbh->exec(sprintf("CREATE INDEX '%s' ON %s (%s)", $idx_name, $this->enquote($this->tablename), join(',', $idx_data)));
		}
		
		return ($dbh->errorCode() == '00000');
	}
}

//===============================================================
// View
//===============================================================
class View extends KISS_View
{
	
}