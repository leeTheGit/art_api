<?php
namespace mvcr\model;
use \mvcr\service\l;
use mvcr\router\Request;

class Plant extends Base_model
{

	protected $table 	= 'plant';
	protected $required = [ "serial"];


	public function __construct(Request $request)
	{
		parent::__construct($request);
	}

	protected $data_view = array(
		'default' => [ "serial", "mortality"],
		'edit' 	  => [ "serial", "mortality"]
	);

	public function getPlants()
	{	global $functions;$functions[] = get_class($this).'->'.__FUNCTION__;
		
		$sql = "SELECT  plant.*, 
						plantdata.* 
					FROM plant 
					LEFT JOIN plantdata ON plant.id = plantdata.plant_id
					ORDER BY serial";

		$plants = $this->db->fetchAll($sql, $params);


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


}
