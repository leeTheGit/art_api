<?php
namespace src\controller;

use src\router\Request;
use src\service\l;


class Roomlocation extends Base_controller
{


	public function __construct(Request $request, \src\model\Roomlocation $room)
	{
		parent::__construct($request);

		$this->model = $room;
	}


	public function get(array $queryParams = [])
	{
		l::og($queryParams);

		$resource 	= $this->getResourceFromUrl();

		$accepts = [
			"room" => false
		];

		$this->set_input_defaults($accepts, $queryParams);

		if ($resource['id']) {
			return $this->model->getRoomLocationById($resource['id']);
		}

		if (!empty( $queryParams['room'] ) ) {
			return $this->model->getRoomLocationByRoomId($queryParams['room']);
		}


		return $this->model->getRoomLocations();


	}


	public function put(array $input = [])
	{

		$resource 	= $this->getResourceFromUrl();
	
		$result = False;

		$accepts = [
			'room_id' 		=> null,
			'location_id'   => null,
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
		// l::og('posting to room location');
		// l::og($input);
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
