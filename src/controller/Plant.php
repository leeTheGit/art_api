<?php
namespace src\controller;

use src\router\Request;
use src\service\l;


class Plant extends Base_controller
{

	protected $resourceArray = ['domain', 'class', 'id'];
	protected $users = null;

	public function __construct(Request $request, \src\model\Plant $plant, \src\model\Plantlocation $location)
	{
		parent::__construct($request);

		$this->model = $plant;
		$this->plantLocation = $location;
	}


	public function get(array $params = [])
	{
		$resource 	= $this->getResourceFromUrl();

		$accepts = [
			"location" 		=> NULL,
			"section"		=> False,
			"serial"        => False,
			"offset"  		=> NULL,
			"limit"			=> 20,
			"room" 			=> False,
			"data"          => False,
		];

		$this->set_input_defaults($accepts, $params);

		if ($resource['id']) {

			$plant = $this->model->getPlantById($resource['id']);

		}  else if (!empty( $params['serial'] ) ) {
			
			$plant = $this->model->getPlantBySerial($params);
	

		}  else if (!empty( $params['location'] ) ) {
			
			$plant = $this->model->getPlantByLocation($params);

		} else {

			$plant = $this->model->getPlants();
		}

		if (!empty($params['data']) && $params['data'] == true) {
			
			$plant = $this->model->getPlantData($plant, $params);

			return $plant;
		
		} 
		
		return $plant;

	}


	public function put(array $input)
	{

		$resource 	= $this->getResourceFromUrl();

		$result = False;

		$accepts = [
			'serial' 		=> null,
			'mortality'		=> null,
			"manager_id"	=> null,
			"life_cycle" 	=> null,
			"location"      => null,
		];

		$accepts = array_intersect_key($accepts, $input);
		$this->set_input_defaults($accepts, $input);
		if (!empty($input)) {
			$result = $this->model->update($resource['id'], $input);
		}


		// if changing location, store the history of the change in plantlocations
		// which is used to match stats of plant data with location data over time.
		if (!empty($input['location'])) {
			$this->plantLocation->create(["location_id" => $input['location'], "plant_id"=> $resource['id']]);
		}

		return $result;

	}

	public function post(array $input)
	{

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
