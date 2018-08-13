<?php
namespace src\controller;

use src\router\Request;
use src\service\l;


class Roomdata extends Base_controller
{


	public function __construct(Request $request, \src\model\Roomdata $roomdata)
	{
		parent::__construct($request);

		$this->model = $roomdata;
	}


	public function get(array $queryParams = [])
	{

		$resource 	= $this->getResourceFromUrl();

        $accepts = [
            "room_id" => false,
        ];

		$this->set_input_defaults($accepts, $queryParams);

		if ($resource['id']) {
			return $this->model->getRoomDataById($resource['id']);
		}

		if (!empty( $queryParams['room'] ) ) {
			return $this->model->getRoomDataByRoom($queryParams['room'], $queryParams);
		}


		return False;


	}


	public function put(array $input = [])
	{

		$resource 	= $this->getResourceFromUrl();
	
		$result = False;

		$accepts = [
			'room_id' => null,
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
		// l::og('posting to roomdata');
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
