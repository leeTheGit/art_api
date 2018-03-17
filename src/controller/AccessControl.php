<?php
namespace src\controller;

/**

*group privs is either 'full' for internal/trusted users or 'self' for external,
*anything else is access denied
*
*user privs is 'read' for read only, 'user' or 'admin', anything else is access denied
*
*the access level you have is a combination of group and user access.
*External admins basically can only add/edit users of their group,
*they can't edit the listings in any way.
*External users can also only see channels/list channels that we say they can. They can also only see their own group.
*At present external user and external read are the same. They can only edit their own login details.
*
*internal admins can do whatever.
*internal users see anything, but can only edit listing and programme data, (eg, none of the scripting/channels/regions stuff) and their own login details
*internal read only can only edit their login details but can still see everything

*/

class AccessControl
{
	protected $user;
	protected $request;

	public function __construct(\src\router\Request $request, $user)
	{
		$this->user = $user;
		$this->request = $request;
	}

	function checkAuthLevel()
	{
		$access = array();

		//checks a request against the current users access and retursn true or false
		// if ($credentials->groupaccess == 'full') { //internal users
		switch ($this->user->access) {
			case 'admin':
				return true;
			case 'editor':
				$access = $this->internalEditor();
				break;
			case 'user':
				$access = $this->internalUser();
				break;
			case 'read':
				$access = $this->internalRead();
				break;
			case 'read_all_meetings':
				$access = $this->internalRead();
				break;
			case 'portal':
				$access = $this->internalPortal();
				break;
			case 'selections':
				$access = $this->meetingOnly();
				break;
		}
		// } elseif ($credentials->groupaccess == 'self') {
		// 	switch ($credentials->access) {
		// 		case 'admin':
		// 			$access = externalAdmin($request->method);
		// 			break;
		// 		case 'user':
		// 			$access = externalUser($request->method);
		// 			break;
		// 		case 'read':
		// 			$access = externalRead($request->method);
		// 			break;
		// 		case 'read_all_meetings':
		// 			$access = externalRead($request->method);
		// 			break;
		// 	}
		// }

		$retval = false;
		foreach ($access as $a) {
			//preg using | as things because its grepping urls whiach all have / in them

			if (!$retval && preg_match('|'.$a.'|',$this->request->uri) != 0) {
				$retval = true;
			}
		}

		if (!$retval) {
			header("Content-Type: application/json; charset=utf-8");
			header('HTTP/1.1 550 Permission Denied');
			exit(json_encode(array('message'=>"You don't have permission for that task: ". $request->method . ' ' . $request->uri)));
		}

		// $this->check_data_access();

		return $retval;
	}


	private function check_data_access()
	{
		foreach ($this->user->data_access as $access_key=>$v) {

			if (array_key_exists($access_key, $this->request->data)) {

				switch ($access_key) {

					case 'form':
						if ($v == 'false') {
							unset($this->request->data['form']);
						}
						break;

					case 'runs':
						if (intval($this->request->data['runs'] > intval($v) )) {
							$this->request->data['runs'] = intval($v);
						}
						break;
				}
			}
		}
	}


	private function internalEditor()
	{
		switch ($this->request->method) {
		case 'get':
			return array(".+");
		case 'post':
			return array("/meeting.*",
						"/race.*",
						"/track.*",
						"/load.*",
						"/output.*",
						"/publication.*",
						"/runner.*",
						"/portal.*",
						"/tipspanel.*",
						);
		case 'put':
			return array("/meeting.*",
						"/race.*",
						"/track.*",
						"/load.*",
						"/output.*",
						"/publication.*",
						"/runner.*",
						"/user.*",
						"/tipspanel.*",
						);
		case 'delete':
			return array();
		}

		return array();
	}

	private function internalUser()
	{
		switch ($this->request->method) {
		case 'get':
			return array(".+");
		case 'post':
			return array("/meeting.*",
						"!(load)/race.*",
						"/track.*");
		case 'put':
			return array(
						"/race.*",
						"/runner.*",
						"/output.*",
						"/user.*");
		case 'delete':
			return array();
		}

		return array();
	}

	private function internalRead()
	{
		switch ($this->request->method) {
		case 'get':
			return array( ".+");
		case 'post':
			return array("/user/self.*",
						 "/user/".$this->user->userid.".*");
		case 'put':
			return array("/user/self.*",
						 "/user/".$this->user->userid.".*");
		case 'delete':
			return array();
		}

		return array();
	}

	private function internalPortal()
	{
		switch ($this->request->method) {
		case 'get':
			return array("/meeting.*",
						 "/race.*",
						 "/runner.*",
						 "/portal.*",
						 "/user/".$this->user->userid.".*",
						 "/user/self"
						);
		case 'post':
			return array("/user/self.*",
						 "/user/".$this->user->userid.".*",
						 "/portal.*",
						 );
		case 'put':
			return array();
		case 'delete':
			return array();
		}

		return array();
	}

	private function meetingOnly()
	{
		switch ($this->request->method) {
		case 'get':
			return array("/meeting.*",
						 "/user/".$this->user->userid.".*",
						 "/user/self"
						);
		case 'post':
			return array();
		case 'put':
			return array();
		case 'delete':
			return array();
		}

		return array();
	}

	// function externalAdmin ($request)
	// {
	// 	switch ($request->method) {
	// 		case 'get':
	// 			return array(	"/",
	// 							"/meeting.*",
	// 							"/races.*",
	// 							"/track.*");
	// 			break;
	// 		case 'post':
	// 			return array("/meeting.*",
	// 						"/races.*",
	// 						"/track.*");
	// 			break;
	// 		case 'put':
	// 			return array("/meeting.*",
	// 						"/races.*",
	// 						"/track.*");
	// 			break;
	// 		case 'delete':
	// 			return array("/user.*");
	// 			break;
	// 	}

	// 	return array();
	// }

	// function externalUser ($request) //is this even a sensible thing? its the same as read only
	// {
	// 	switch ($request->method) {
	// 		case 'get':
	// 			return array(	"/",
	// 							"/meeting.*",
	// 							"/races.*",
	// 							"/track.*",
	// 							"/portal.*");
	// 			break;
	// 		case 'post':
	// 			return array("/user/self.*",
	// 						 "/portal.*");
	// 			break;
	// 		case 'put':
	// 			return array("/user/self.*");
	// 			break;
	// 		case 'delete':
	// 			return array();
	// 			break;
	// 		}

	// 	return array();
	// }

	// function externalRead ($request)
	// {
	// 	switch ($request->method) {
	// 		case 'get':
	// 			return array(	"/",
	// 							"/meeting.*",
	// 							"/races.*",
	// 							"/track.*");
	// 			break;
	// 		case 'post':
	// 			return array("/user/self.*");
	// 			break;
	// 		case 'put':
	// 			return array("/user/self.*");
	// 			break;
	// 		case 'delete':
	// 			return array();
	// 			break;
	// 	}

	// 	return array();
	// }

}
