<?php
namespace src\model;

use src\service\l;
use src\router\Request;

class Plantdata extends Base_model
{

	protected $table 	= 'plantdata';
	protected $required = [ "plant_id"];


	public function __construct(Request $request)
	{
		parent::__construct($request);
	}

	protected $data_view = array(
		'default' => [ "plant_id", "height", "location", "user_id", "notes", "ph", "conductivity", "temperature", "humidity", "lux", "light_hours", "health"],
		'edit' 	  => [ "height", "location", "user_id", "notes", "ph", "conductivity", "temperature", "humidity", "lux", "light_hours", "health"],
	);

	public function getPlantDataById($id)
	{	global $functions;$functions[] = get_class($this).'->'.__FUNCTION__;
		
		$sql = "SELECT  plantdata.*, 
					FROM {$this->table}
					WHERE id = :id
					ORDER BY created DESC";

		$params = ['id' => $id];

		$plantdata = $this->db->fetch($sql, $params);
		return $plantdata;
	}

	public function getPlantByPlantId($id)
	{	global $functions;$functions[] = get_class($this).'->'.__FUNCTION__;
		
		$sql = "SELECT plantdata.* 
					FROM plantdata 
					WHERE plant_id = :id
					ORDER BY created DESC";
		$params = ["id" => $id];
		// l::og($sql);
		// l::og($params);
		$plants = $this->db->fetchAll($sql, $params);


		return $plants;
	}


}
