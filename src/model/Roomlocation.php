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
		'default' => [ "room_id", "location_id", "created_at"],
		'edit' 	  => [ "room_id", "location_id", "created_at"],
	);

	public function getRoomLocations() : ?array 
	{	global $functions;$functions[] = get_class($this).'->'.__FUNCTION__;
		
		$sql = "SELECT {$this->table}.* FROM {$this->table}";

		$location = $this->fetchAll($sql);

		return $location;
	}


	public function getRoomLocationByRoomId(string $room_id)
	{	global $functions;$functions[] = get_class($this).'->'.__FUNCTION__;

		$sql = "WITH location AS(
				SELECT DISTINCT
					ON(location_id)roomlocation. ID AS roomlocationid,
					roomlocation.created_at,
					LOCATION . NAME,
					LOCATION . RANK,
					LOCATION . ID
				FROM
					roomlocation
				LEFT JOIN LOCATION ON LOCATION . ID = location_id
				WHERE
					roomlocation.room_id = :roomid
				ORDER BY
					location_id,
					created_at DESC
			)
			
			SELECT * FROM location
			ORDER BY created_at DESC";
		
		$params = ["roomid" => $room_id];

		$locations = $this->fetchAll($sql, $params);
		return $locations;

	}

	public function getRoomLocationById($id)
	{	global $functions;$functions[] = get_class($this).'->'.__FUNCTION__;
		
		$sql = "SELECT  {$this->table}.*
					FROM {$this->table} 
					WHERE {$this->table}.id = :id";
		$params = ["id" => $id];
		$locations = $this->fetch($sql, $params);

		return $locations;
	}


	public function getRoomLocationHistoryByLocations(array $locations) : ?array 
	{	global $functions;$functions[] = get_class($this).'->'.__FUNCTION__;

		$resultArray = [];

		$now = new \DateTime();
		$now = $now->format('Y-m-d H:i:s');

		foreach($locations as $i => $location) {

			// Get first roomlocation
			$sql = "SELECT  {$this->table}.*, 
						location.name as locationName, 
						room.name as roomName,
						plantLocation.created_at as locationTime
					FROM {$this->table} 
					LEFT JOIN room on room.id = {$this->table}.room_id
					LEFT JOIN location on location.id = {$this->table}.location_id
					LEFT JOIN plantlocation on plantlocation.id = :plantLocationId
					WHERE {$this->table}.location_id = :id
					AND {$this->table}.created_at <= :from
					ORDER BY created_at DESC
					LIMIT 1";
			$params = [
				"id" 	=> $location->location_id,
				"from" 	=> $location->created_at,
				"plantLocationId" => $location->id
			];
			$resultArray[] = $this->fetch($sql, $params);


			

			// Check if location was moved to other rooms
			$dates = [
				"from" => $location->created_at,
				"to" => !isset($locations[$i + 1]) ? $now : $locations[$i+1]->created_at,
			];

			$sql = "SELECT  {$this->table}.*, 
						location.name as locationName, 
						room.name as roomName,
						{$this->table}.created_at as locationTime

						FROM {$this->table}
						LEFT JOIN room on room.id = {$this->table}.room_id
						LEFT JOIN location on location.id = {$this->table}.location_id

						WHERE {$this->table}.location_id = :id
						AND {$this->table}.created_at > :from
						AND {$this->table}.created_at < :to
						ORDER BY created_at ASC";
			$params = [
				"id" 	=> $location->location_id,
				"from" 	=> $dates['from'],
				"to" 	=> $dates['to'],
			];

			$other = $this->fetchAll($sql, $params);
			if (count($other)) {
				$resultArray[] = $other;
			}
		}

		$output = [];
		// Flatten output array
		array_walk_recursive($resultArray, function ($current) use (&$output) {
			if (count($current) > 0 ) {
				$output[] = $current;
			}
		});
		
		// pprint($output);
		return $output;
	}

	// public function getRoomLocationByName($name)
	// {	global $functions;$functions[] = get_class($this).'->'.__FUNCTION__;
		
	// 	$sql = "SELECT {$this->table}.*
	// 				FROM {$this->table} 
	// 				WHERE {$this->table}.name = :name";
	// 	$params = ["name" => $name];
	// 	$data = $this->fetch($sql, $params);

	// 	return $data;
	// }


}
