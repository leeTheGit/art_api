<?php
namespace mvcr\model;
use \mvcr\service\l;
use mvcr\router\Request;

class Plant extends Base_model
{

	protected $table 	= 'room';
	protected $required = [ "id"];


	public function __construct(Request $request)
	{
		parent::__construct($request);
	}

	protected $data_view = array(
		'default' => [ "name"],
		'edit' 	  => [ "name"]
	);

	public function getRooms()
	{	global $functions;$functions[] = get_class($this).'->'.__FUNCTION__;
		
		$sql = "SELECT room.* FROM room";

		$plants = $this->db->fetchAll($sql, $params);


		return $plants;
	}

	public function getRoomById($id)
	{	global $functions;$functions[] = get_class($this).'->'.__FUNCTION__;
		
		$sql = "SELECT  room.*
					FROM room 
					WHERE room.id = :id";
		$params = ["id" => $id];
		$plants = $this->db->fetch($sql, $params);

		return $plants;
	}


}
