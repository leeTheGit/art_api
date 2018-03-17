<?php
namespace src\model;

use src\service\l;
use src\router\Request;

class Users extends Base_model
{
	public function __construct(Request $request)
	{
		parent::__construct($request);
	}

	protected $data_view = array(
			'default' => [ "name", "access", "useraccess", "groupname", "groupaccess" ],
			'edit' => [ "name", "access", "useraccess", "groupname", "groupaccess" ]
		);

	public function getUser($UID, $GID = '')
	{	global $functions;$functions[] = get_class($this).'->'.__FUNCTION__;
		if ($GID == 'all') {
			$sql = "SELECT users.id userid,
												 users.name username,
												 users.firstname,
												 users.lastname,
												 users.access,
												 users.password,
												 users.access useraccess,
												 groups.id groupid,
												 groups.name groupname,
												 groups.access groupaccess
										FROM users
										LEFT JOIN groups on users.usergroup = groups.id
											WHERE users.id = :UID
											ORDER BY users.name";
			$params = ["UID" 	=> $UID];
			// sqlLog($sql, $params);
			$user = $this->db->fetchAll($sql, $params);

		} else {
			$user = $this->db->fetchAll("SELECT users.id userid,
												 users.name username,
												 users.firstname,
												 users.lastname,
												 users.access,
												 groups.id groupid,
												 groups.name groupname,
												 groups.access groupaccess
										FROM users
										LEFT JOIN groups on users.usergroup = groups.id
										WHERE users.id = :UID and users.usergroup = :GID
										ORDER BY users.name",
											array(	"UID" 	=> $UID,
													"GID"	=> $GID));
		}

		return $user;
	}


	public function getUserByAccountName($name)
	{	global $functions;$functions[] = get_class($this).'->'.__FUNCTION__;
		$name = str_replace('_',  ' ', $name);
		$sql = "SELECT   users.id userid,
						 users.name username,
						 users.firstname,
						 users.lastname,
						 users.access,
						 users.password,
						 users.access useraccess,
						 groups.id groupid,
						 groups.name groupname,
						 groups.access groupaccess
				FROM users
				LEFT JOIN groups on users.usergroup = groups.id
					WHERE users.name = :name
					ORDER BY users.name";
		// sqlLog($sql, array("name" => $name));
		$user = $this->db->fetch($sql, array("name" => $name));
		return $user;
	}





	public function getAllUsers($groupid = null)
	{	global $functions;$functions[] = get_class($this).'->'.__FUNCTION__;
		$params = [];
		$groupsql = '';

		if ($groupid) {
			$groupsql = ' WHERE usergroup = :group ';
			$params['group'] = $groupid;
		}

		$sql = "SELECT  users.id userid,
						users.name username,
						users.access useraccess,
						groups.id groupid,
						users.firstname,
						users.lastname,
						users.password,
						groups.name groupname,
						groups.access groupaccess
					FROM users
					LEFT JOIN groups ON users.usergroup = groups.id
					{$groupsql}
					ORDER BY users.name, groups.name";
		// sqlPrint($sql, $params);
		return $this->db->fetchAll($sql, $params);
	}

	public function getGroupUsers($group)
	{   global $functions;$functions[] = get_class($this).'->'.__FUNCTION__;

		return $this->db->fetchAll('SELECT users.id userid,
										users.name username,
										users.access useraccess,
										users.firstname,
										users.lastname,
										groups.id groupid,
										users.password,
										groups.name groupname,
										groups.access groupaccess
									FROM users
									LEFT JOIN groups ON users.usergroup = groups.id
									WHERE users.usergroup = :GID
									ORDER BY users.name',
						array("GID"=>$group));
	}

	public function getGroupById($GID, $input)
	{	global $functions;$functions[] = get_class($this).'->'.__FUNCTION__;

		$sql = "SELECT id groupid, name groupname, access groupaccess FROM groups
									WHERE id = :GID";
		// sqlLog($sql, array("GID"=>$GID));
		$group = $this->db->fetch($sql, array("GID"=>$GID));

		$group = $this->getGroupData($group, $input);

		return $group;
	}

	public function getGroupByName($group)
	{	global $functions;$functions[] = get_class($this).'->'.__FUNCTION__;
		if ($group == 'News Ltd') {$group = 'News';}
		if ($group == 'The Pagemasters Bugle') {$group = 'Pagemasters';}
		$sql = "SELECT id groupid, name groupname, access groupaccess FROM groups
									WHERE name = :group";
		// sqlPrint($sql, array("group"=>$group));
		$group = $this->db->fetch($sql, array("group"=>$group));
		// pprint($group);
		return $group;
	}

	private function getGroupData($group, $input) {
		if ($input['users'] && $input['users'] === "true") {
			$group->users = $this->getAllUsers($group->groupid);
		}
		if ($input['panels'] && $input['panels'] === "true") {
			$group->panels = $this->getAllPanels($group->groupid);
		}

		return $group;
	}

	public function getAllGroups($input)
	{	global $functions;$functions[] = get_class($this).'->'.__FUNCTION__;

		$groups = $this->db->fetchAll('SELECT id groupid, name groupname, access groupaccess FROM groups ORDER BY groupname');

		foreach ($groups as &$group) {
			$this->getGroupData($group, $input);
		}

		return $groups;
	}

	private function getAllPanels($id)
	{	global $functions;$functions[] = get_class($this).'->'.__FUNCTION__;

		$sql = "SELECT * FROM tipspanels WHERE publication = :id";
		$param = array("id" => $id);
		$result = $this->db->fetchAll($sql, $param);
		return $result;
	}


	public function createUser($id, $data)
	{	global $functions;$functions[] = get_class($this).'->'.__FUNCTION__;

		if ($data['access'] != 'admin' && $data['access'] != 'user') {
			$data['access'] = 'read';
		}
		if ($data['name'] 		 != ''
			&& $data['password'] != ''
			&& $id 			     != ''
			&& ($data['access']  == 'admin'
			|| $data['access'] 	 == 'user'
			|| $data['access'] 	 == 'read')
		) {

			$sql = "INSERT INTO users (name, password, usergroup, access)
						   VALUES (:name, crypt(:password, gen_salt('bf')), :group, :access)";

			$params = array("name" 	 	=> $data['name'],
							"password" 	=> $data['password'],
							"group" 	=> $id,
							"access" 	=> $data['access']);

			$insert = $this->db->insert($sql, $params);

			return $insert;
		}
	}

	public function createGroup($data)
	{	global $functions;$functions[] = get_class($this).'->'.__FUNCTION__;

		if ($data['access'] != 'full') {
			$data['access'] = 'self';
		}

		if (!empty($data['name'])) {

			$sql = "INSERT INTO groups (name, access) VALUES (:name, :access)";
			$params = array("name"=>$data['name'], "access" => $data['access']);
			$insert = $this->db->insert($sql, $params);

			return $insert;
		}

		return false;
	}


	public function updateUser($id, $data, $columns = array())
	{	global $functions;$functions[] = get_class($this).'->'.__FUNCTION__;
		$param   = array("id" => $id);
		$columns = array_flip($columns);
		$data 	 = array_intersect_key((array)$data, $columns);
		$fields  = array_keys($data);
		$sql = 'UPDATE users SET ';
		foreach ($fields as $field) {
			$sql .= "{$field} = :{$field}, ";
			$param[$field] = $data[$field];
		}
		$sql .= "WHERE id = :id";
		$sql = str_replace(", WHERE", " WHERE", $sql);
		$sql = str_replace(":password", "crypt(:password, gen_salt('bf'))", $sql);

		$update =  $this->db->execute($sql, $param);
		return $update;
	}



	public function updateGroup($id, $data, $columns = array())
	{	global $functions;$functions[] = get_class($this).'->'.__FUNCTION__;
		$param   = array("id" => $id);
		$columns = array_flip($columns);
		$data 	 = array_intersect_key((array)$data, $columns);
		$fields  = array_keys($data);
		$sql = 'UPDATE groups SET ';
		foreach ($fields as $field) {
			$sql .= "{$field} = :{$field}, ";
			$param[$field] = $data[$field];
		}
		$sql .= "WHERE id = :id";
		$sql = str_replace(", WHERE", " WHERE", $sql);
		$update =  $this->db->execute($sql, $param);
		return $update;
	}


	public function deleteUser($UID)
	{	global $functions;$functions[] = get_class($this).'->'.__FUNCTION__;
			$delete = $this->db->delete('DELETE FROM users WHERE id = :id', array("id"=>$UID));
			return $delete;
	}

	public function deleteGroup($GID)
	{	global $functions;$functions[] = get_class($this).'->'.__FUNCTION__;
		return $this->db->delete('DELETE FROM groups WHERE id = :id', array("id"=>$GID));
	}

	public function deleteGroupByName($name)
	{	global $functions;$functions[] = get_class($this).'->'.__FUNCTION__;
		return $this->db->delete('DELETE FROM groups WHERE name = :name', array("name"=>$name));
	}


}
