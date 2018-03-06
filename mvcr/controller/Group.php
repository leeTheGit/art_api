<?php
namespace mvcr\controller;

use mvcr\router\Request;

class Group extends Base_controller {

	protected $resourceArray = ['domain', 'class', 'id', 'name'];
	protected $users;

	public function __construct(Request $request, \mvcr\model\Users $users)
	{
		parent::__construct($request);

		$this->users = $users;
	}



	public function get($input) {

		$resource = $this->getResourceFromUrl();

		$accepts = ['users' => "true", 'panels' => "false"];
		$this->set_input_defaults($accepts, $input);

		if (($this->auth_user->access == 'admin') || ($this->auth_user->groupid == PAGEMASTERS)) {


			if (empty($resource['id'])) {

				return $this->users->getAllGroups($input);

			} else {

				return $this->users->getGroupById($resource['id'], $input);

			}

		} else {

			if (empty($resource['id'])) {
				return $this->users->getGroupById($this->auth_user->groupid, $input);

			}
		}


	}


	public function put($input)
	{

		$resource   = $this->getResourceFromUrl();

		$accepts = ['group' => '', 'access' => ''];

		$this->set_input_defaults($accepts, $input);

		if ($this->auth_user->access == 'admin') {

			$columns = array('access', 'name');

			return $this->users->updateGroup($resource['id'], $input, $columns);
		}

		return "You're not allowed to do that.";
	}





	public function post($input)
	{

		$accepts = ['group' => '', 'access' => ''];

		$this->set_input_defaults($accepts, $input);

		if ($this->auth_user->access == 'admin') {
			return $this->users->createGroup($input);
		}

		return false;
	}




	public function delete()
	{
		$resource = $this->getResourceFromUrl();

		if ($this->auth_user->access == 'admin') {
			$name = (!empty($resource['name']) ? $resource['name'] : False);

			if ($name) {
				return $this->users->deleteGroupByName($name);
			}

			if (!empty($resource['id'])) {
				return $this->users->deleteGroup($resource['id']);
			}			
		}

		return False;
	}

}
