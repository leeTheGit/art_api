<?php
namespace src\controller;

use src\router\Request;
use src\service\l;


class Plantlocation extends Base_controller
{


	public function __construct(Request $request, \src\model\Plantlocation $model)
	{
		parent::__construct($request);

		$this->model = $model;
	}


	public function get(array $queryParams = [])
	{

		$resource 	= $this->getResourceFromUrl();

		$accepts = [];

		$this->set_input_defaults($accepts, $queryParams);

		if ($resource['id']) {
			return $this->model->getPlantLocationById($resource['id']);
		}

		if (!empty( $queryParams['plant'] ) ) {
			return $this->model->getPlantLocationByPlantId($queryParams['plant']);
		}
		// if (!empty( $queryParams['serial'] ) ) {
		// 	return $this->model->getPlantLocationBySerial($queryParams['serial']);
		// }


		return $this->model->getPlantLocations();


	}


	public function put(array $input = [])
	{

		$resource 	= $this->getResourceFromUrl();
	
		$result = False;

		$accepts = [
			'plant_id' 		=> null,
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
		// l::og('posting to plant location');
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
