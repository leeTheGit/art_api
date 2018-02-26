<?php
namespace mvcr\model;

class Function_logger
{
	protected $db;

	public function __construct($db)
	{
		$this->db = $db;
	}

	public function get_log_from_id($id)
	{
		$sql = 'SELECT * FROM function_log WHERE function_log.id = :id';
		$params = array("id"=>$id);
		$log = $this->db->query($sql, $params);
		return $log;
	}
	public function get_log_from_name($name)
	{
		$sql = 'SELECT * FROM function_log WHERE function_log.function = :name';
		$params = array("name"=>$name);
		$log = $this->db->query($sql, $params);
		return $log;
	}
	public function function_log($name)
	{
		$log = $this->get_log_from_name($name);
		if (count($log) > 0 ) {
			$number = $log[0]->count;
			$number++;
			$sql = "UPDATE function_log set count = :count WHERE id = :id";
			$param = array('count' => $number, 'id'=>$log[0]->id );
			return $this->db->execute($sql, $param);
		}
		else {
			$sql = 'INSERT INTO function_log (function, type, message, meetingid) VALUES (:user, :type, :message, :meetingid)';
			return $this->db->insert($sql, $data);
		}
	}

	public function update($id, $data, $columns = array())
	{
		$param   = array("id" => $id);
		$columns = array_flip($columns);
		$data 	 = array_intersect_key((array)$data, $columns);
		$fields  = array_keys($data);
		$sql = 'UPDATE function_load SET ';
		foreach ($fields as $field) {
			if ($field == 'number') {$data[$field] = (int) $data[$field];}
			$sql .= "{$field} = :{$field}, ";
			$param[$field] = $data[$field];
		}
		$sql .= "WHERE id = :id";
		$sql = str_replace(", WHERE", " WHERE", $sql);
		logThis( sqlPrint($sql, $param));
		$update =  $this->db->execute($sql, $param);
		return $update;
	}
}