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
		l::og($input);

		$resource 	= $this->getResourceFromUrl();
		
		$accepts = [
			"name" => False,
			"room" => False
		];

		$this->set_input_defaults($accepts, $input);
		
		if ($resource['id']) {
			return $this->model->getLocationById($resource['id']);
		}
		if (!empty( $input['name'] ) ) {
			return $this->model->getLocationByName($input['name']);
		}

		if (!empty( $input['room'] ) ) {
			return $this->model->getLocationsByRoom($input['room']);
		}

		return $this->model->getLocations();


	}


	public function put(array $input = [])
	{

		$resource 	= $this->getResourceFromUrl();

		// l::og($resource);

		$result = False;

		$accepts = [
			'name' 		=> null,
			'rank'		=> null,
			'room_id'   => null,
			'old_rank'  => null
		];

		$accepts = array_intersect_key($accepts, $input);
		$this->set_input_defaults($accepts, $input);
		l::og($input);
		if (!empty($input['rank'])) {
			$result = $this->model->updateLocationRank($resource['id'], $input['rank'], $input['old_rank']);
		}

		if (!empty($input)) {
			$result = $this->model->update($resource['id'], $input);
		}

		return $result;
	}

	public function post(array $input = [])
	{
		try {
			// l::og($input);
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
