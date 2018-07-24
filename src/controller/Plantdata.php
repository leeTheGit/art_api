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
		// l::og($input);
		// throw new \Exception($error);
		

		$result = False;
		l::og($input);
		$accepts = [
			'conductivity' 	=> null,
			'temperature' 	=> null,
			"light_hours" 	=> null,
			"user_check"    => null,
			"humidity"		=> null,
			'location'		=> null,
			"user_id"		=> null,
			"health"      	=> null,
			'height' 		=> null,
			"notes" 		=> null,
			"time"  		=> null,
			"lux" 			=> null,
			"ph"      		=> null,
		];

		$accepts = array_intersect_key($accepts, $input);
		$this->set_input_defaults($accepts, $input);

		if (!empty($input['user_check'])) {
			$user = $this->model->allowCheck($resource['id'], $input['user_check']);
			if(!$user) {
				unset($input['user_check']);
			}
		}

		if (!empty($input)) {
			l::og($input);
			$result = $this->model->update($resource['id'], $input);
		}

		return $result;
	}

	public function post(array $input)
	{

		$default_values = [
			"conductivity"	=> false,
			"light_hours"	=> false,
			"temperature"	=> false,
			"plant_id"		=> false,
			"humidity"		=> false,
			"location"		=> false,
			"user_id"       => $this->auth_user->userid,
			"user_id"		=> false,
			"height"		=> false,
			"health"		=> false,
			"notes"			=> false,
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
