<?php
namespace src\controller;

use src\router\Request;

class Search extends Base_controller
{
	protected $resourceArray = ['domain', 'class', 'search'];
	protected $search;

	public function __construct(Request $request, \src\model\Search $search)
	{
		parent::__construct($request);

		$this->search = $search;
	}


	public function Get($input)
	{
		$resource  = $this->getResourceFromUrl();

		if (!empty($resource['search'])) {
			return $this->search->getSearch($resource['search']);
		}
		return False;
	}

}
