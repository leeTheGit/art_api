<?php
namespace src\model;

use src\service\l;
use src\router\Request;

class Mortality extends Base_model
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

	public function getResources()
	{	global $functions;$functions[] = get_class($this).'->'.__FUNCTION__;
		
		$sql = "SELECT {$this->table}.* FROM {$this->table}";

		$data = $this->db->fetchAll($sql, $params);

		return $data;
	}

	public function getResourceById($id)
	{	global $functions;$functions[] = get_class($this).'->'.__FUNCTION__;
		
		$sql = "SELECT  {$this->table}.*
					FROM {$this->table} 
					WHERE {$this->table}.id = :id";
		$params = ["id" => $id];
		$data = $this->db->fetch($sql, $params);

		return $data;
	}

	public function getResourceByName($name)
	{	global $functions;$functions[] = get_class($this).'->'.__FUNCTION__;
		
		$sql = "SELECT {$this->table}.*
					FROM {$this->table} 
					WHERE {$this->table}.name = :name";
		$params = ["name" => $name];
		$data = $this->db->fetch($sql, $params);

		return $data;
	}


}
