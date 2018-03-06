<?php
namespace mvcr\controller;

use mvcr\router\Request;
use \mvcr\service\l;


class Room extends Base_controller
{

	protected $resourceArray = ['domain', 'class', 'id'];
	protected $users = null;

	public function __construct(Request $request, \mvcr\model\Room $room)
	{
		parent::__construct($request);

		$this->model = $room;
	}


	public function get($input)
	{

		$resource 	= $this->getResourceFromUrl();

		$accepts = [];

		$this->set_input_defaults($accepts, $input);
		
		if ($resource['id']) {
			return $this->model->getRoomById($resource['id']);
		}

		return $this->model->getRooms();


	}


	public function put($input)
	{

		$resource 	= $this->getResourceFromUrl();

		throw new Exception($error);
		
		return "You're not allowed to do that.";

	}

	public function post($input)
	{
		// \mvcr\service\l::og($input);
		// $resource 	= $this->getResourceFromUrl();

		// $defaults = [
		// 	'serial'    => false,
		// 	'name' 	=> false
		// ];

		// $this->set_input_defaults($defaults, $input);


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
