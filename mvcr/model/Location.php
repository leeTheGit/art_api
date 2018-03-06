<?php
namespace mvcr\model;
use \mvcr\service\l;
use mvcr\router\Request;

class Location extends Base_model
{

	protected $table 	= get_class($this);
	protected $required = [ "id"];


	public function __construct(Request $request)
	{
		parent::__construct($request);
	}

	protected $data_view = array(
		'default' => [ "name", "order"],
		'edit' 	  => [ "name", "order"]
	);

	public function getLocations()
	{	global $functions;$functions[] = get_class($this).'->'.__FUNCTION__;
		
		$sql = "SELECT location.* FROM location";

		$plants = $this->db->fetchAll($sql, $params);


		return $plants;
	}

	public function getLocationById($id)
	{	global $functions;$functions[] = get_class($this).'->'.__FUNCTION__;
		
		$sql = "SELECT  location.*
					FROM location 
					WHERE location.id = :id";
		$params = ["id" => $id];
		$plants = $this->db->fetch($sql, $params);

		return $plants;
	}


}
