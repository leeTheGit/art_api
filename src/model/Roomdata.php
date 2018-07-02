<?php
namespace src\model;

use src\service\l;
use src\router\Request;

class Roomdata extends Base_model
{
	protected $required = ['id', 'room_id'];

	public function __construct(Request $request)
	{
		parent::__construct($request);
	}

	protected $data_view = array(
		'default' => [ "room_id", "location_id"],
		'edit' 	  => [ "room_id", "location_id"],
	);

	public function getRoomData()
	{	global $functions;$functions[] = get_class($this).'->'.__FUNCTION__;
		
		$sql = "SELECT {$this->table}.* FROM {$this->table}";

		$location = $this->db->fetchAll($sql, $params);


		return $location;
	}

	public function getRoomDataByRoom($id)
	{	global $functions;$functions[] = get_class($this).'->'.__FUNCTION__;
		
		$sql = "SELECT  {$this->table}.*
					FROM {$this->table} 
                    WHERE {$this->table}.room_id = :id
                    ORDER BY created_at DESC";
		$params = ["id" => $id];
		$locations = $this->db->fetchAll($sql, $params);

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
