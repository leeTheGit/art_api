<?php
namespace src\model;

use src\service\l;

abstract class Base_model
{
	protected $allowed_columns  = array();
	protected $request;
	protected $mc;
	protected $db;
	protected $user;
	protected $group;
	protected $data_view;
	protected $table;
	protected $required;
	protected $offset;

	public function __construct(\src\router\Request $request)
	{	global $functions;$functions[] = get_class($this).'->'.__FUNCTION__;

		$this->request = $request;
		$this->mc = $request->mc;
		$this->db = $request->db;
		$this->user = $request->auth_user->userid;
		$this->group = $request->auth_user->groupid;
		$this->setColumns($request->auth_user->access);
		$this->table = strtolower( end( explode('\\', get_class($this) ) ) );
	}

	public function setUser($user) {
		$this->user = $user;
	}

	public function setColumns($access)
	{
		$array  = array_key_exists( $access, $this->data_view) ? $this->data_view[$access] : $this->data_view['default'];

		$this->allowed_columns = array_flip(  $array );
	}

	public function getColumns($view = NULL)
	{
		$allowed = $this->allowed_columns ? $this->allowed_columns : array();

		if ($view && array_key_exists($view, $this->data_view)) {
			return array_intersect_key($allowed, array_flip($this->data_view[$view]));
		}
		return $allowed;

	}

	public function filterColumns($object, $view = NULL)
	{
		return (object) array_intersect_key(get_object_vars($object), array_merge($this->getColumns($view), array_flip(array('id'))));
	}

	public function getById($id)
	{	global $functions;$functions[] = get_class($this).'->'.__FUNCTION__;
		$object = $this->getCache($id);

		if ( ! $object ) {
			$object = $this->db->fetch('SELECT * FROM ' . $this->table . ' WHERE id = :id', array("id"=>$id));
			$set = $this->setCache($id, $object);
		}

		return $object;
	}

	protected function getClass()
	{
		$class = explode( '\\', get_class($this) );
		return strtolower( end($class) );
	}

	public function intersect($arr, $arr2)
	{
		$arr3 = array_intersect_key( (array) $arr, array_flip( $arr2 ) );
		return $arr3;
	}

	public function delete($id)
	{	global $functions;$functions[] = get_class($this).'->'.__FUNCTION__;

		return $this->db->execute('DELETE FROM ' . $this->table . ' WHERE id = :id', array("id"=>$id));
	}

	public function update($id, $data, $permitted_columns = array())
	{	global $functions;$functions[] = get_class($this).'->'.__FUNCTION__;

		$this->clearCache($id);

		if ( count($permitted_columns) === 0 ) {
			$permitted_columns = !empty($this->data_view['edit']) ? $this->intersect( $this->getColumns(), $this->data_view['edit']) : $this->getColumns();
		}

		$data = array_intersect_key((array)$data, $permitted_columns);

		$fields  = array_keys($data);

		$params = array("id" => $id);

		if (!empty($fields)) {

			$sql = 'UPDATE ' . $this->table . ' SET ';
			foreach ($fields as $field) {

				$params[$field] = $data[$field];
				if (empty($params[$field]) && $params[$field] !== "0" && gettype($params[$field]) !== 'integer') {
					$params[$field] = null;
				}
				$sql .= "{$field} = :{$field}, ";

			}
			if (isset($this->last_modified) && $this->last_modified === true) {
				$sql .= "updated_at = timezone(".DB_TIMEZONE."::text, now()) ";
			}

			$sql .= "WHERE id = :id";
			$sql = str_replace(", WHERE", " WHERE", $sql);
			// l::og($sql);
			// l::og($params);

			$result = $this->db->execute($sql, $params);
			if (!$result) {
				l::og('DID NOT UPDATE');
				l::og($sql);
				l::og($params);

			}
			return $result;
		}
		return false;
	}

	public function create($data)
	{	global $functions;$functions[] = get_class($this).'->'.__FUNCTION__;

		l::og('creating');

		$required = $this->required ? array_flip($this->required) : array();

		$permitted_columns = !empty($this->data_view['edit']) ? array_intersect_key($this->getColumns(), array_flip($this->data_view['edit'])) : $this->getColumns();
		l::og($permitted_columns);
		$data = (array)$data;
		l::og($data);
		if ( !array_diff_key($required, $data) ) {
			l::og('here');
			$params = array_intersect_key( $data, array_merge( $permitted_columns, $required ) );
			l::og($params);
			$sql = 'INSERT INTO ' . $this->table . ' ';
			$keys = '(';
			$values = 'VALUES (';
			foreach (array_keys($params) as $key) {
				$keys .= $key . ', ';
				$values .= ':' . $key . ', ';
				if (empty($params[$key]) && $params[$key] !== "0" && gettype($params[$key]) !== 'integer') {
					$params[$key] = null;
				}
			}
			$keys .= ') ';
			$values .= ')';
			$sql .= $keys . $values;
			$sql = str_replace(", )", ")", $sql);
			l::og($sql);
			l::og($params);

			$create = $this->db->insert($sql, $params);

			if (!$create) {

				l::og('Did NOT INSERT');
				l::ogsql($sql, $params);
				// l::og($sql);
				// l::og($params);

			}
			return $create;
		} else {
			l::og('required colimn missing');
			return "Required column missing for " . $this->table;
		}
		return False;
	}

	public function setCache($id, $value)
	{
		return $this->mc->set($this->table . '_' . $id, $value);
	}

	public function getCache($id)
	{

		$object = $this->mc->get($this->table . '_' . $id);

		if (!$object || !$object->id) {
			$this->clearCache($id);
			return false;
		}

		return $object;
	}

	public function clearCache($id)
	{
		return $this->mc->delete($this->table . '_' . $id);
	}

}
