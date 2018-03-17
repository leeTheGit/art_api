<?php
namespace src\model;

use src\router\Request;

class Logger extends Base_model
{
	protected $table 	= 'log_load';

	public function __construct(Request $request)
	{
		parent::__construct($request);
	}

	protected $data_view = array(
		'edit' => [
			"time", "user", "type", "message", "meetingid"
		],
		'default' => [
			"time", "user", "type", "message", "meetingid"
		]
	);

	public function getMeetingLogs($id)
	{	global $functions;$functions[] = get_class($this).'->'.__FUNCTION__;

		$sql = 'SELECT load_log.id, load_log.time, load_log.user as userid, users.firstname, users.lastname, load_log.type, load_log.message, load_log.meetingid
				FROM load_log
				LEFT JOIN users
					on load_log.user = users.id
				WHERE load_log.meetingid = :id ORDER BY load_log.time';
		$params = array("id"=>$id);
		$logs = $this->db->query($sql, $params);

		return $logs;
	}

	public function load_log($data)
	{	global $functions;$functions[] = get_class($this).'->'.__FUNCTION__;

		$sql = 'INSERT INTO load_log ("user", type, message, meetingid) VALUES (:user, :type, :message, :meetingid)';
		return $this->db->insert($sql, $data);
	}
}
