<?php
namespace src\model;

use src\service\l;
use src\router\Request;

class Plantlocation extends Base_model
{
	protected $required = ['plant_id', 'location_id'];

	public function __construct(Request $request)
	{
		parent::__construct($request);
	}

	protected $data_view = array(
		'default' => [ "plant_id", "location_id", "created_at"],
		'edit' 	  => [ "plant_id", "location_id", "created_at"],
	);

	public function getPlantLocations()
	{	global $functions;$functions[] = get_class($this).'->'.__FUNCTION__;
		
		$sql = "SELECT {$this->table}.* FROM {$this->table}";

		$location = $this->fetchAll($sql);


		return $location;
	}

	public function getPlantLocationById(string $id) : ?object
	{	global $functions;$functions[] = get_class($this).'->'.__FUNCTION__;
		
		$sql = "SELECT  {$this->table}.*
					FROM {$this->table} 
					WHERE {$this->table}.id = :id";
		$params = ["id" => $id];
		$locations = $this->fetch($sql, $params);

		return $locations;
	}

	public function getPlantLocationByPlantId(string $id) : ?object
	{	global $functions;$functions[] = get_class($this).'->'.__FUNCTION__;
		
		$sql = "SELECT  {$this->table}.*
					FROM {$this->table} 
					WHERE {$this->table}.plant_id = :id";
		$params = ["id" => $id];
		$location = $this->fetch($sql, $params);

		return $location;
	}

	public function getPlantLocationHistoryByPlantId(string $id) : ?array
	{	global $functions;$functions[] = get_class($this).'->'.__FUNCTION__;
		

		$sql = "SELECT  {$this->table}.*
					FROM {$this->table} 
					WHERE {$this->table}.plant_id = :id
					ORDER BY {$this->table}.created_at ASC";
		$params = ["id" => $id];
		$location = $this->fetchAll($sql, $params);

		return $location;
	}


	// public function getPlantLocationBySerial(string $serial) : ?object
	// {	global $functions;$functions[] = get_class($this).'->'.__FUNCTION__;
		
	// 	$sql = "SELECT  {$this->table}.*
	// 				FROM {$this->table} 
	// 				WHERE {$this->table}.plant_id = :id";
	// 	$params = ["id" => $id];
	// 	$location = $this->fetch($sql, $params);

	// 	return $location;
	// }


}
