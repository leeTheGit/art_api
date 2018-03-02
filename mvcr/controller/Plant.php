<?php
namespace mvcr\controller;

use mvcr\router\Request;
use \mvcr\service\l;


class Plant extends Base_controller
{

	protected $resourceArray = ['domain', 'class', 'id'];
	protected $users = null;

	public function __construct(Request $request, \mvcr\model\Plant $plant)
	{
		parent::__construct($request);

		$this->model = $plant;
	}


	public function get($input)
	{

		$resource 	= $this->getResourceFromUrl();

		$accepts = [];

		$this->input_init($accepts, $input);
		
		if ($resource['id']) {
			return $this->model->getPlantById($resource['id']);
		}

		return $this->model->getPlants();


	}


	public function put($input)
	{

		$resource 	= $this->getResourceFromUrl();
		$columns = array('usergroup', 'name', 'firstname', 'lastname', 'access', 'password');

		// if ($this->auth_user->access != 'admin') { //name +password for self only

		// 	$update = $this->users->updateUser($this->auth_user->userid, $input, $columns);

		// 	return $update;

		// } elseif ($resource['id'] != '') {

		// 	if ($this->auth_user->groupaccess != 'full') {

		// 		$groupid = $this->auth_user->groupid;
		// 	}
		// 	//need to add check here to see if user is in your group as only full access admins can change anybody...
		// 	//for now we will just rely on them not being able to know uids outside their group

		// 	$columns = array('usergroup', 'name', 'firstname', 'lastname', 'access', 'password');

		// 	$update = $this->users->updateUser($resource['id'], $input, $columns);

		// 	return $update;
		// }

		return "You're not allowed to do that.";

	}

	public function post($input)
	{
		// \mvcr\service\l::og($input);
		// $resource 	= $this->getResourceFromUrl();

		$default_values = [
			'serial'    => false,
			'status' 	=> false
		];

		$this->input_init($default_values, $input);


		return $this->model->create($input);

	}



	public function delete()
	{

		$resource = $this->getResourceFromUrl();

		if ($this->auth_user->access == 'admin' && !empty($resource['id'])) {

			$delete = $this->model->delete($resource['id']);

			return $delete;
		}

		return false;

	}

}
