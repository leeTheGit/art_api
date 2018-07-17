<?php declare(strict_types=1);
namespace src\model;

use src\service\l;
use src\router\Request;

class Plant extends Base_model
{

	protected $required = [ "serial"];
	protected $limit = 10;


	public function __construct(Request 		$request, 
								Plantdata 		$plantdata, 
								Roomdata 		$roomdata,
								Plantlocation 	$locations,
								Roomlocation 	$rooms)
	{
		parent::__construct($request);
		$this->plantData 	= $plantdata;
		$this->roomData 	= $roomdata;
		$this->locations 	= $locations;
		$this->rooms 		= $rooms;
	}

	protected $data_view = array(
		'default' => [ "serial", "mortality", "location", "created_at"],
		'edit' 	  => [ "serial", "mortality", "location", "created_at"]
	);




	// select t.username, t.date, t.value
	// from MyTable t
	// inner join (
	// 	select username, max(date) as MaxDate
	// 	from MyTable
	// 	group by username
	// ) tm on t.username = tm.username and t.date = tm.MaxDate
	
	


	// select plant.*, plantdata.id as data_id, plantdata.created as data_created, plantdata.notes 
	// from plant
	// left join plantdata on plant.id = plantdata.plant_id
	// where plant.id = 'acff52e7-cbba-4fe9-a93f-f2e847c57baa'
	// order by data_created DESC
	// limit 1


	public function getPlantData(object $plant, array $params) : object
	{
		$data = $this->plantData->getByPlantId($plant->id);
		$plant->data = $data;
		
		$plantLocations = $this->locations->getPlantLocationHistoryByPlantId($plant->id);
		pprint($plantLocations);
		$rooms = $this->rooms->getRoomLocationHistoryByLocations($plantLocations);
		pprint($rooms);

		$roomData = $this->roomData->getRoomDataByLocationHistory($rooms);

		pprint($roomData);
		// $roomData = $this->roomData->getByPlantHistory(($plant->room_id));
		// $plant->roomData = $roomData;

		return $plant;
	}



	public function getPlants(array $options = []) : array
	{	global $functions;$functions[] = get_class($this).'->'.__FUNCTION__;
		
		$sql = "SELECT {$this->table}.*, location.name
					FROM {$this->table}
					LEFT JOIN location on {$this->table}.location = location.id
					ORDER BY serial
					LIMIT {$this->limit}";


		if (!empty($options['offset']) && is_numeric($options['offset'])) {
			$sql .= " OFFSET {$options['offset']}";
		}

		$plants = $this->fetchAll($sql);

		return $plants;
	}

	public function getPlantById(string $id) : object 
	{	global $functions;$functions[] = get_class($this).'->'.__FUNCTION__;
		
		$sql = "SELECT {$this->table}.*
					FROM {$this->table} 
					WHERE {$this->table}.id = :id";
		$params = ["id" => $id];

		$plants = $this->fetch($sql, $params);


		return $plants;
	}

	public function getPlantBySerial(array $params) : ?object
	{	global $functions;$functions[] = get_class($this).'->'.__FUNCTION__;
		
		$sql = "SELECT  {$this->table}.* FROM {$this->table} WHERE {$this->table}.serial = :serial";
		$params = ["serial" => $params['serial']];

		$plant = $this->fetch($sql, $params);

		return $plant ? $plant : null;
	}



}
