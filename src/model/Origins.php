<?php
namespace src\model;

use src\service\l;

class Origins  {

	protected $origins = ['http://pm-import.aap.com.au', 'http://pm-railtracker.aap.com.au', 'http://localhost:3000'];

	public function __construct()
	{

		$this->getOrigin();
	}

	private function getOrigin()
	{
		$origin = ( !empty( $_SERVER['HTTP_ORIGIN']) ) ? $_SERVER['HTTP_ORIGIN'] : NULL;

		if (in_array($origin, $this->origins)) {
			header("Access-Control-Allow-Credentials:true");
			header("Access-Control-Allow-Methods:POST, GET, OPTIONS, PUT, DELETE");
			header("Access-Control-Allow-Origin: " . $origin);
			header("Access-Control-Allow-Headers: Accept, Origin, X-Requested-With, Content-Type, Authorization");
		}

		if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
			exit;
		}
	}

}
