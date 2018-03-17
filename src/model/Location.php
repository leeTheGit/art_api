<?php
namespace src\model;

use src\service\l;
use src\router\Request;

class Location extends Base_model
{

	protected $required = ["name"];


	public function __construct(Request $request)
	{
		parent::__construct($request);
	}

	protected $data_view = array(
		'default' => [ "name", "rank"],
		'edit' 	  => [ "name", "rank"]
	);

	public function getLocations()
	{	global $functions;$functions[] = get_class($this).'->'.__FUNCTION__;
		
		$sql = "SELECT location.* FROM location";

		$data = $this->db->fetchAll($sql, $params);

		return $data;
	}

	public function getLocationById($id)
	{	global $functions;$functions[] = get_class($this).'->'.__FUNCTION__;
		
		$sql = "SELECT  location.*
					FROM location 
					WHERE location.id = :id";
		$params = ["id" => $id];
		$data = $this->db->fetch($sql, $params);

		return $data;
	}


	public function getLocationByName($name)
	{	global $functions;$functions[] = get_class($this).'->'.__FUNCTION__;
		
		$sql = "SELECT  location.*
					FROM location 
					WHERE location.name = :name";
		$params = ["name" => $name];
		$data = $this->db->fetch($sql, $params);

		return $data;
	}


	public function addLocation($location)
	{	global $functions;$functions[] = get_class($this).'->'.__FUNCTION__;

		$location['rank'] = 1;

		$ranking = $this->db->fetch("SELECT rank FROM location ORDER BY rank DESC LIMIT 1");

		if ($ranking) {
			$location['rank'] = $ranking->rank + 1;
		}

		return $this->create($location);
	}


}
