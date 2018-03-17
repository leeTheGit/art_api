<?php
namespace src\model;

use src\service\l;
use src\router\Request;

class Plant extends Base_model
{

	protected $required = [ "serial"];


	public function __construct(Request $request)
	{
		parent::__construct($request);
	}

	protected $data_view = array(
		'default' => [ "serial", "mortality"],
		'edit' 	  => [ "serial", "mortality"]
	);




	// select t.username, t.date, t.value
	// from MyTable t
	// inner join (
	// 	select username, max(date) as MaxDate
	// 	from MyTable
	// 	group by username
	// ) tm on t.username = tm.username and t.date = tm.MaxDate
	
	

	public function getPlants()
	{	global $functions;$functions[] = get_class($this).'->'.__FUNCTION__;
		
		$sql = "SELECT  plant.*, 
						plantdata.* 
					FROM plant 
					LEFT JOIN plantdata ON plant.id = plantdata.plant_id
					ORDER BY serial";

		$plants = $this->db->fetchAll($sql);


		return $plants;
	}

	public function getPlantById($id)
	{	global $functions;$functions[] = get_class($this).'->'.__FUNCTION__;
		
		$sql = "SELECT  plant.*, 
						plantdata.* 
					FROM plant 
					LEFT JOIN plantdata ON plant.id = plantdata.plant_id
					WHERE plant.id = :id";
		$params = ["id" => $id];
		// l::og($sql);
		// l::og($params);
		$plants = $this->db->fetch($sql, $params);


		return $plants;
	}

	public function getPlantBySerial($serial)
	{	global $functions;$functions[] = get_class($this).'->'.__FUNCTION__;
		
		$sql = "SELECT  plant.* FROM plant WHERE plant.serial = :serial";
		$params = ["serial" => $serial];

		$plant = $this->db->fetch($sql, $params);

		return $plant;
	}



}
