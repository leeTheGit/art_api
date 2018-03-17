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


	public function get($input)
	{

		$resource 	= $this->getResourceFromUrl();

		$accepts = [];

		$this->set_input_defaults($accepts, $input);
		
		if ($resource['id']) {
			return $this->model->getPlantById($resource['id']);
		}

		return $this->model->getPlantdata();


	}


	public function put($input)
	{

		$resource 	= $this->getResourceFromUrl();
		return "You're not allowed to do that.";

	}

	public function post($input)
	{
		
		l::og($input);

		$default_values = [
			"plant_id"		=> false,
			"height"		=> false,
			"location"		=> false,
			"user_id"		=> false,
			"notes"			=> false,
			"ph"			=> false,
			"conductivity"	=> false,
			"temperature"	=> false,
			"humidity"		=> false,
			"lux"			=> false,
			"light_hours"	=> false,
			"health"		=> false,
		];

		$this->set_input_defaults($default_values, $input);

		l::og($input);
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
