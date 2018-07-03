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

	public function getById($id)
	{	global $functions;$functions[] = get_class($this).'->'.__FUNCTION__;
		l::og($id);
		$sql = "SELECT  {$this->table}.*, 
					FROM {$this->table}
					WHERE id = :id
					ORDER BY created_at DESC";

		$params = ['id' => $id];

		$plantdata = $this->db->fetch($sql, $params);
		return $plantdata ?? [];
	}

	public function getByPlantId($id)
	{	global $functions;$functions[] = get_class($this).'->'.__FUNCTION__;

		$sql = "SELECT plantdata.* 
					FROM plantdata 
					WHERE plant_id = :id
					ORDER BY created_at DESC";
		$params = ["id" => $id];

		$plants = $this->db->fetchAll($sql, $params);

		
		return $plants;
	}


}
