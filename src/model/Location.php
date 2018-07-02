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
		'default' => [ "name", "rank", "room_id"],
		'edit' 	  => [ "name", "rank", "room_id"]
	);

	public function getLocations()
	{	global $functions;$functions[] = get_class($this).'->'.__FUNCTION__;
		


		$sql = "SELECT
					location.*,
					room.name AS roomname
				FROM location
					LEFT JOIN room ON location.room_id = room.id
				ORDER BY location.rank";

		// $sql = "SELECT 
		// 			*
		// 		from (SELECT DISTINCT on (location.id)
		// 			location.*,
		// 			roomlocation.room_id as room_id,
		// 			room.name as roomname
		// 		FROM location
		// 			left join roomlocation on location.id = roomlocation.location_id 
		// 			left join room on roomlocation.room_id = room.id
		// 		order by location.id, roomlocation.created_at desc
		// 		) locations_with_room
		// 		order by locations_with_room.rank";

		$data = $this->db->fetchAll($sql, $params);

		return $data;
	}


	public function getLocationsByRoom($room_id)
	{	global $functions;$functions[] = get_class($this).'->'.__FUNCTION__;

		$sql = "SELECT
					location.*,
					room.name AS roomname
				FROM location
					LEFT JOIN room ON location.room_id = room.id
				WHERE location.room_id = :roomid
				ORDER BY location.rank";


		$params = [
			'roomid' => $room_id
		];

		// l::og($sql);
		// l::og($params);

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

		$sql = "SELECT rank FROM location 
					WHERE room_id = :room_id
				ORDER BY rank DESC LIMIT 1";
		
		$params = ['room_id' => $location['room_id']];

		$ranking = $this->db->fetch($sql, $params);

		if ($ranking) {
			$location['rank'] = $ranking->rank + 1;
		}

		return $this->create($location);
	}


	public function updateLocationRank($id, $rank, $old) 
	{	global $functions;$functions[] = get_class($this).'->'.__FUNCTION__;

		$room = $this->db->fetch("SELECT room_id FROM location WHERE id = :id", ["id" => $id]);
		l::og($id);
		l::og($rank);
		l::og($old);
		// l::og($room);

		$operator = $rank > $old ? '-' : '+';
		$min = $rank > $old ? '<' : '>';
		$max = $rank > $old ? '>' : '<';

		$sql = "UPDATE location
					SET rank = rank $operator 1
					WHERE rank $min= :rank1
					AND rank $max= :rank2
					AND room_id = :room_id";
		
		l::og($sql);
		$params = [
			"room_id" => $room->room_id, 
			"rank1" => $rank,
			"rank2" => $old
		];

		$result = $this->db->execute($sql, $params);

	}



}
