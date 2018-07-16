<?php
namespace src\model;

use src\service\l;
use src\router\Request;

class Roomdata extends Base_model
{
	protected $required = ['room_id'];

	public function __construct(Request $request)
	{
		parent::__construct($request);
	}

	protected $data_view = array(
		'default' => [ "room_id", "temperature", "humidity", "time"],
		'edit' 	  => [ "room_id", "temperature", "humidity", "time"],
	);

	public function getRoomData() : array
	{	global $functions;$functions[] = get_class($this).'->'.__FUNCTION__;
		
		$sql = "SELECT {$this->table}.* FROM {$this->table}";

		$location = $this->db->fetchAll($sql, $params);

		return $location;
	}

	public function getRoomDataById(string $id) : ?object
	{	global $functions;$functions[] = get_class($this).'->'.__FUNCTION__;
		
		$sql = "SELECT  {$this->table}.*
					FROM {$this->table} 
                    WHERE {$this->table}.id = :id
                    ORDER BY created_at DESC";
		$params = ["id" => $id];
		$locations = $this->db->fetch($sql, $params);

		return $locations;
	}


	public function getRoomDataByRoom(string $id) : array
	{	global $functions;$functions[] = get_class($this).'->'.__FUNCTION__;
		
		$sql = "SELECT  {$this->table}.*
					FROM {$this->table} 
                    WHERE {$this->table}.room_id = :id
                    ORDER BY created_at DESC";
		$params = ["id" => $id];
		$locations = $this->db->fetchAll($sql, $params);

		return $locations;
	}

	public function getRoomDataByLocationHistory(array $rooms) : ?array
	{	global $functions;$functions[] = get_class($this).'->'.__FUNCTION__;

		$now = new \DateTime();
		$now = $now->format('Y-m-d H:i:s');
		$resultArray = [];

		foreach($rooms as $i => $room) {

			$sql = "SELECT  {$this->table}.*
					FROM {$this->table} 
					WHERE {$this->table}.room_id = :room_id
					AND {$this->table}.time >= :from
					AND {$this->table}.time < :to";


			$params = [
				"room_id" => $room->room_id,
				"from" => $room->locationtime,
				"to" => !isset($rooms[$i + 1]) ? $now : $rooms[$i+1]->locationtime,
			];
			l::og($sql);
			l::og($params);
			$resultArray[] = $this->db->fetchAll($sql, $params);

		}

		$output = [];
		// Flatten output array
		array_walk_recursive($resultArray, function ($current) use (&$output) {
			if (count($current) > 0 ) {
				$output[] = $current;
			}
		});

		return $output;
	}


}
