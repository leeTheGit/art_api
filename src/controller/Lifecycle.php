<?php
namespace src\controller;

use src\router\Request;
use src\service\l;


class Lifecycle extends Base_controller
{


	public function __construct(Request $request, \src\model\Lifecycle $lifecycle)
	{
		parent::__construct($request);

		$this->model = $lifecycle;
	}


	public function get(array $queryParams = [])
	{

		$resource 	= $this->getResourceFromUrl();

		$accepts = [];

		$this->set_input_defaults($accepts, $queryParams);

		if ($resource['id']) {
			return $this->model->getById($resource['id']);
		}

		if (!empty( $queryParams['name'] ) ) {
			return $this->model->getByName($queryParams['name']);
		}


		return $this->model->getAll();


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
