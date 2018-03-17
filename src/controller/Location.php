<?php
namespace src\controller;

use src\router\Request;
use src\service\l;


class Location extends Base_controller
{

	

	public function __construct(Request $request, \src\model\Location $location)
	{
		parent::__construct($request);

		$this->model = $location;
	}


	public function get(array $input = [])
	{
		$resource 	= $this->getResourceFromUrl();
		
		$accepts = [
			"name" => False,
		];

		$this->set_input_defaults($accepts, $input);
		
		if ($resource['id']) {
			return $this->model->getLocationById($resource['id']);
		}

		if (!empty( $input['name'] ) ) {
			return $this->model->getLocationByName($input['name']);
		}


		return $this->model->getLocations();


	}


	public function put(array $input = [])
	{

		$resource 	= $this->getResourceFromUrl();

		// throw new \Exception($error);
	
		$result = False;

		$accepts = [
			'name' 		=> null,
			'rank'		=> null
		];

		$accepts = array_intersect_key($accepts, $input);
		$this->set_input_defaults($accepts, $input);

		if (!empty($input)) {
			$result = $this->model->update($resource['id'], $input);
		}

		return $result;
	}

	public function post(array $input = [])
	{
		try {
			return $this->model->addLocation($input);
		}
		catch(Exception $e) {
			l::og($e->getMessage());
			return 'did not work';
		}
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
