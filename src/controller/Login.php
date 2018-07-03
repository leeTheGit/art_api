<?php
namespace src\controller;

use src\model\Database;
use src\service\l;

// UPDATE users SET "password" = crypt('new password', gen_salt('bf')) where name = 'test';
// insert into users(name, access, password) values ('bob', 'admin', crypt('new password', gen_salt('bf')))
// SELECT "password" = crypt('new password', "password") success, id FROM users

class Login
{
	protected $server = null;
	protected $db;

	public function __construct(Database $db = NULL)
	{

		$this->server = $_SERVER;
		if (!isset($_GET['token'])) {
			if (!isset($this->server['PHP_AUTH_PW'])) {
				header('WWW-Authenticate: Basic realm="Pagemasters Racing API"');
				header('HTTP/1.0 401 Unauthorized');
				print "Login failed!\n";
				exit;
			}
		}
		$this->db = $db;
	}

	public function auth()
	{
		if (isset($_GET['token'])) {
			$sql = "SELECT  users.id userid,
						access_tokens.access,
						access_tokens.panel,
						users.username,
						groups.id groupid,
						groups.access groupaccess,
						groups.data_access
					FROM access_tokens
					LEFT JOIN users ON users.id = access_tokens.user
					LEFT JOIN groups ON groups.id = users.usergroup
					WHERE access_tokens.id = :token";

			$params = array("token"=>$_GET['token']);
		} else {
			$sql = "SELECT  users.id userid,
						users.access,
						users.username,
						groups.id groupid,
						groups.access groupaccess,
						groups.data_access
						-- groups.name groupname
					FROM users
					LEFT JOIN groups ON groups.id = users.usergroup
					WHERE users.password = crypt(:password, users.password)
					AND users.username = :user";
			$params = ["password" => $this->server['PHP_AUTH_PW'], "user" => $this->server['PHP_AUTH_USER']];
		}

		$user = $this->db->fetch($sql, $params);

		if ( !$user ) {
			header('WWW-Authenticate: Basic realm="Pagemasters Racing API"');
			header('HTTP/1.0 401 Unauthorized');
			exit(json_encode(array('message'=>"You aren't logged in")));
		}

		if ($user->data_access) {
			$user->data_access = json_decode($user->data_access);
		}

		if (isset($user->panel)) {
			$_GET['panel'] = $user->panel;
		}

		return $user;

	}
}

	// [userid] => da2a87e1-886d-4b75-8b42-2449a6cc6e14
	// [access] => admin
	// [username] => Lee
	// [publicationid] =>
	// [name] =>
	// [groupid] => 39c02f98-d579-415f-8da7-a952ade3668a
	// [groupaccess] => full
	// [groupname] => Pagemasters
