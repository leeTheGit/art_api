<?php
namespace src\controller;

use src\router\Request;
use src\service\l;


class Room extends Base_controller
{


	public function __construct(Request $request, \src\model\Room $room)
	{
		parent::__construct($request);

		$this->model = $room;
	}


	public function get(array $queryParams = [])
	{

		$resource 	= $this->getResourceFromUrl();

		$accepts = [
			"data" => False,
			"locations" => False
		];
		$this->set_input_defaults($accepts, $queryParams);
		l::og($queryParams);

		if ($resource['id']) {
			$room = $this->model->getRoomById($resource['id']);
		} else

		if (!empty( $queryParams['name'] ) ) {
			$room =  $this->model->getRoomByName($queryParams['name']);
		} else {
			l::og('getting rooms');
			$room = $this->model->getRooms();
		}

		if (!empty($queryParams['data']) && $queryParams['data'] == 'true') {
			$room = $this->model->getRoomData($room, $queryParams);
		} 


		if (!empty($queryParams['locations']) && $queryParams['locations'] == 'true') {
			l::og('getting room locations');
			// l::og($room);

			$room = $this->model->getLocationData($room, $queryParams);
		} 


		return $room;


	}


	public function put(array $input = [])
	{

		$resource 	= $this->getResourceFromUrl();
	
		$result = False;

		$accepts = [
			'name' 		=> null,
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
