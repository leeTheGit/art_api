<?php
namespace mvcr\controller;

use mvcr\router\Request;

class Jockey extends Base_controller
{
	protected $resourceArray = ['domain', 'class', 'id'];
	protected $jockey;

	public function __construct(Request $request, \mvcr\model\Jockey $jockey)
	{
		parent::__construct($request);

		$this->jockey = $jockey;
	}


	public function get($input)
	{
		$resource = $this->getResourceFromUrl();

		$accepts = [];

		$this->set_input_defaults($accepts, $input);

		if ($resource['id']) {
			$jockeys = $this->jockey->getById($resource['id']);
			$jockeys = $this->jockey->filterColumns($jockeys);
		} else {
			$jockeys = $this->jockey->getAll();

			foreach ($jockeys as &$jockey) {
				$jockey = $this->jockey->filterColumns($jockey);
			}
		}

		return $jockeys;
	}



	public function put($input)
	{
		$resource = $this->getResourceFromUrl();
		if (!empty($input)) {
			return $this->jockey->update($resource['id'], $input);
		}
		return False;
	}



	public function delete()
	{
		$resource   = $this->getResourceFromUrl();

		if (!empty($resource['id'])) {
			return $this->jockey->delete($resource['id']);
		}
		return False;
	}

}
