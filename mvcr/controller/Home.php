<?php
namespace mvcr\controller;

// use mvcr\router\Request;

class Home
{
	protected $db;

	public function __construct(\mvcr\router\Request $request)
	{
		$this->db = $request->db;
	}


	public function get()
	{
		$datetime = new \DateTime('tomorrow');
		$date = $datetime->format('Y-m-d');


		$routes = array(
			["meeting" => [
				[
					"description"=> "Get a list of all meetings in the database",
					"url"=>API."meeting"
				],
				[
					"description"=> "Get a list of all meetings in the database for {date}",
					"url"=>API."meeting?date=".$date
				],
				[
					"description"=> "Get a list of all meetings in the database from {date} onwards",
					"url"=>API."meeting?from=".$date
				],
				[
					"description"=> "Get a list of all meetings in the database for {date} and {type}",
					"url"=>API."meeting?date=".$date."&type=R?"
				]
			],
			'race' => [
				[
					"description"=> "Get single race by {id}",
					"url"=>API."race/".$date."/"
				]
			],
			'runner' => [
				[
					"description"=> "Get single runner by {id}",
					"url"=>API."runner/".$date."/"
				]
			],
			'search' => [
				[
					"description"=> "Search for runner by name",
					"url"=>API."search/".$date."/"
				]
			]]

			// 'tips_panels' => [
			// 	[
			// 		"description"=> "Get all tips panels",
			// 		"url"=>API."tipspanel/",
			// 	],
			// ]]

		);
		return $routes;
	}


	public function put()
	{
		return false;
	}

	public function post()
	{
		return false;
	}

	public function delete()
	{
		return false;
	}
}
