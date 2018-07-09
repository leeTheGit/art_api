<?php
namespace src\controller;

use src\router\Request;
use src\service\l;


class Plantdata extends Base_controller
{

	protected $resourceArray = ['domain', 'class', 'id'];
	protected $users = null;

	public function __construct(Request $request, \src\model\Plantdata $plantdata)
	{
		parent::__construct($request);

		$this->model = $plantdata;
	}


	public function get(array $input)
	{

		$resource 	= $this->getResourceFromUrl();

		$accepts = [];

		$this->set_input_defaults($accepts, $input);
		
		if ($resource['id']) {
			return $this->model->getById($resource['id']);
		}

		return $this->model->getPlantdata();


	}


	public function put(array $input)
	{

		$resource 	= $this->getResourceFromUrl();
		l::og($input);
		// throw new \Exception($error);
		

		$result = False;
		
		$accepts = [
			'height' 		=> null,
			'location'		=> null,
			"user_id"		=> null,
			"notes" 		=> null,
			"ph"      		=> null,
			'conductivity' 	=> null,
			'temperature' 	=> null,
			"humidity"		=> null,
			"lux" 			=> null,
			"light_hours" 	=> null,
			"health"      	=> null,
			"time"  		=> null
		];

		$accepts = array_intersect_key($accepts, $input);
		$this->set_input_defaults($accepts, $input);
		if (!empty($input)) {
			$result = $this->model->update($resource['id'], $input);
		}

		return $result;
	}

	public function post(array $input)
	{
		l::og($this->auth_user);
		$default_values = [
			"conductivity"	=> false,
			"light_hours"	=> false,
			"location"		=> false,
			"user_id"		=> false,
			"height"		=> false,
			"notes"			=> false,
			"temperature"	=> false,
			"plant_id"		=> false,
			"humidity"		=> false,
			"user_id"       => $this->auth_user->userid,
			"health"		=> false,
			"lux"			=> false,
			"ph"			=> false,
		];

		$this->set_input_defaults($default_values, $input);

		return $this->model->create($input);

	}



	public function delete()
	{

		$resource = $this->getResourceFromUrl();

		if ($this->auth_user->access == 'admin' && !empty($resource['id'])) {

			$delete = $this->model->delete($resource['id'], true);

			return $delete;
		}

		return false;

	}

}
