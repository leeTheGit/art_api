<?php
namespace src\controller;

use src\router\Request;
use src\service\l;


class Plant extends Base_controller
{

	protected $resourceArray = ['domain', 'class', 'id'];
	protected $users = null;

	public function __construct(Request $request, \src\model\Plant $plant)
	{
		parent::__construct($request);

		$this->model = $plant;
	}


	public function get($queryParams)
	{

		$resource 	= $this->getResourceFromUrl();

		$accepts = [
			"location" 		=> NULL,
			"serial"        => False,
			"room" 			=> False,
			"section"		=> False,
			"limit"			=> 20,
			"data"          => False,
		];

		$this->set_input_defaults($accepts, $queryParams);

		if ($resource['id']) {
			return $this->model->getPlantById($resource['id']);
		}


		if (!empty( $queryParams['serial'] ) ) {
			return $this->model->getPlantBySerial($queryParams['serial']);
		}

		return $this->model->getPlants();

	}


	public function put($input)
	{

		$resource 	= $this->getResourceFromUrl();

		// throw new \Exception($error);
		

		$result = False;

		$accepts = [
			'serial' 		=> null,
			'mortality'		=> null,
			"manager_id"	=> null,
			"life_cycle" 	=> null
		];

		$accepts = array_intersect_key($accepts, $input);
		$this->set_input_defaults($accepts, $input);

		if (!empty($input)) {
			$result = $this->model->update($resource['id'], $input);
		}

		return $result;

	}

	public function post($input)
	{
		// \src\service\l::og($input);
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