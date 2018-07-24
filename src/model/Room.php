<?php
namespace src\model;

use src\service\l;
use src\router\Request;

class Room extends Base_model
{
	const LIMIT = 20;
	const MAX_LIMIT = 50;

	protected $required = ['name'];

	public function __construct(Request $request)
	{
		parent::__construct($request);
	}

	protected $data_view = array(
		'default' => [ "name"],
		'edit' 	  => [ "name"]
	);



	public function getLocationData($rooms) 
	{	global $functions;$functions[] = get_class($this).'->'.__FUNCTION__;
		l::og('getting location data now');
		foreach($rooms as $room) {
			$sql = "SELECT DISTINCT
				ON(roomlocation.room_id)roomlocation.*, LOCATION . NAME AS location_name,
				LOCATION . ID AS location_id
			FROM
				roomlocation
			LEFT JOIN LOCATION ON LOCATION . ID = roomlocation.location_id
			WHERE
				roomlocation.room_id = :id
			ORDER BY
				roomlocation.room_id,
				roomlocation.created_at DESC";

			$params = ["id" => $room->id];
			l::og($sql);
			l::og($params);
			$room->locations= $this->fetch($sql, $params);
			l::og($room->locations);
		}

		return $rooms;
	}

	public function getRoomData($room, $params)
	{	global $functions;$functions[] = get_class($this).'->'.__FUNCTION__;

		$data = $this->roomdata->getByPlantId($plant->id);

		$plant->data = $data;

		return $plant;
	}


	public function getRooms()
	{	global $functions;$functions[] = get_class($this).'->'.__FUNCTION__;
		
		$sql = "SELECT * FROM
				( SELECT DISTINCT ON(room. ID)
					room.*, 
					roomdata.temperature,
					roomdata.humidity
				FROM room
					LEFT JOIN roomdata ON room. ID = roomdata.room_id
					ORDER BY room. ID, roomdata.created_at DESC
				) room
			ORDER BY
				room.name";

		$plants = $this->fetchAll($sql);


		return $plants;
	}

	public function getRoomById($id)
	{	global $functions;$functions[] = get_class($this).'->'.__FUNCTION__;
		
		$sql = "SELECT  {$this->table}.*
					FROM {$this->table} 
					WHERE {$this->table}.id = :id";
		$params = ["id" => $id];
		$plants = $this->fetch($sql, $params);

		return $plants;
	}

	public function getRoomByName($name)
	{	global $functions;$functions[] = get_class($this).'->'.__FUNCTION__;
		
		$sql = "SELECT {$this->table}.*
					FROM {$this->table} 
					WHERE {$this->table}.name = :name";
		$params = ["name" => $name];
		$data = $this->fetch($sql, $params);

		return $data;
	}


}
