<?php
namespace src\model;

use src\service\l;
use src\router\Request;

class Roomlocation extends Base_model
{
	protected $required = ['room_id', 'location_id'];

	public function __construct(Request $request)
	{
		parent::__construct($request);
	}

	protected $data_view = array(
		'default' => [ "room_id", "location_id"],
		'edit' 	  => [ "room_id", "location_id"],
	);

	public function getRooms()
	{	global $functions;$functions[] = get_class($this).'->'.__FUNCTION__;
		
		$sql = "SELECT {$this->table}.* FROM {$this->table}";

		$location = $this->db->fetchAll($sql, $params);


		return $location;
	}

	public function getRoomLocationById($id)
	{	global $functions;$functions[] = get_class($this).'->'.__FUNCTION__;
		
		$sql = "SELECT  {$this->table}.*
					FROM {$this->table} 
					WHERE {$this->table}.id = :id";
		$params = ["id" => $id];
		$locations = $this->db->fetch($sql, $params);

		return $locations;
	}

	// public function getRoomLocationByName($name)
	// {	global $functions;$functions[] = get_class($this).'->'.__FUNCTION__;
		
	// 	$sql = "SELECT {$this->table}.*
	// 				FROM {$this->table} 
	// 				WHERE {$this->table}.name = :name";
	// 	$params = ["name" => $name];
	// 	$data = $this->db->fetch($sql, $params);

	// 	return $data;
	// }


}
