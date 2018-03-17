<?php
namespace src\model;

use src\service\l;
use src\router\Request;

class Lifecycle extends Base_model
{
	protected $required = ['name'];

	public function __construct(Request $request)
	{
		parent::__construct($request);
	}

	protected $data_view = array(
		'default' => [ "name"],
		'edit' 	  => [ "name"]
	);

	public function getAll()
	{	global $functions;$functions[] = get_class($this).'->'.__FUNCTION__;
		
		$sql = "SELECT {$this->table}.* FROM {$this->table}";

		$plants = $this->db->fetchAll($sql, $params);


		return $plants;
	}

	public function getById($id)
	{	global $functions;$functions[] = get_class($this).'->'.__FUNCTION__;
		
		$sql = "SELECT  {$this->table}.*
					FROM {$this->table} 
					WHERE {$this->table}.id = :id";
		$params = ["id" => $id];
		$plants = $this->db->fetch($sql, $params);

		return $plants;
	}

	public function getByName($name)
	{	global $functions;$functions[] = get_class($this).'->'.__FUNCTION__;
		
		$sql = "SELECT {$this->table}.*
					FROM {$this->table} 
					WHERE {$this->table}.name = :name";
		$params = ["name" => $name];
		$data = $this->db->fetch($sql, $params);

		return $data;
	}


}
