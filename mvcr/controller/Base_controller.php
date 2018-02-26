<?php
namespace mvcr\controller;

class Base_controller
{
	protected $_resourceParts;
	protected $db;
	protected $auth_user;
	protected $mc;
	protected $request;
	protected $resourceArray;

	public function __construct(\mvcr\router\Request $request)
	{
		$this->request 			= $request;
		$this->db               = $request->db;
		$this->mc               = $request->mc;
		$this->auth_user        = $request->auth_user;
		$this->mc               = $request->mc;
		$this->_resourceParts   = $request->parts;
	}

	protected function getResourceFromUrl()
	{
		$resource = array();

		foreach($this->resourceArray as $i=>$part) {

			$resource[$part]  = (!empty($this->_resourceParts[$i])) ? $this->_resourceParts[$i] : False;

		}

		return $resource;
	}


	protected function input_init($input_list, &$input)
	{
		foreach ($input_list as $param => $default) {

			if (is_callable($default)) {

				$input[$param] = empty( $input[$param] ) ? NULL : call_user_func($default, $input[$param]);

			} else {

				$input[$param] = !empty( $input[$param] ) ? $input[$param] : $default;
			}

		}

		if (!empty($input['tipster'])) {
			$input['user'] = $input['tipster'];
		}

		if (!empty($input['publication'])) {
			$input['group'] = $input['publication'];
		}
	}

	protected function intersector($arr, $func) {
		return function($arr2) use ($arr, $func) {
			return $this->$func->intersect( $arr2, $arr);
		};
	}

}
