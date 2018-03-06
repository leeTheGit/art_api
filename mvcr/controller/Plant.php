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

		$this->set_input_defaults($accepts, $input);
		
		if ($resource['id']) {
			return $this->model->getPlantById($resource['id']);
		}

		return $this->model->getPlants();


	}


	public function put($input)
	{

		$resource 	= $this->getResourceFromUrl();

		throw new Exception($error);
		
		return "You're not allowed to do that.";

	}

	public function post($input)
	{
		// \mvcr\service\l::og($input);
		// $resource 	= $this->getResourceFromUrl();

		$defaults = [
			'serial'    => false,
			'status' 	=> false
		];

		$this->set_input_defaults($defaults, $input);


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