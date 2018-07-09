<?php
namespace src\test;


use src\router\Request;
use src\controller;
use src\service\l;

class TestPlantData extends Test
{

	public $id = [];

	private $data = [

		[
			"height"        => "80",
			"location"      => "",
			"plant_id"      => "",
			"notes"         => "Fast growing seed",
			"ph"            => "1",
			"conductivity"  => "4",
			"temperature"   => "34.9",
			"humidity"      => "2",
			"lux"           => '2.3',
			"light_hours"   => '6.4',
		],
		[
			"height"        => "30",
			"location"      => "",
			"plant_id"      => "",
			"notes"         => "Slow growing seed",
			"ph"            => "2",
			"conductivity"  => "3",
			"temperature"   => "24.4",
			"humidity"      => "5",
			"lux"           => '1.3',
			"light_hours"   => '4.4',
		],
		[
			"height"        => "4",
			"location"      => "",
			"plant_id"      => "",
			"notes"         => "Weird green seed",
			"ph"            => "1",
			"conductivity"  => "2",
			"temperature"   => "4.4",
			"humidity"      => "5",
			"lux"           => '3.3',
			"light_hours"   => '2.4',
		],
		[
			"height"        => "120",
			"location"      => "",
			"plant_id"      => "",
			"notes"         => "Came in a nice pouch",
			"ph"            => "5",
			"conductivity"  => "4",
			"temperature"   => "34.4",
			"humidity"      => "6",
			"lux"           => '6.3',
			"light_hours"   => '2.4',
		],
		[
			"height"        => "159",
			"location"      => "",
			"plant_id"      => "",
			"notes"         => "Browny green color",
			"ph"            => "7.8",
			"conductivity"  => "56",
			"temperature"   => "32.9",
			"humidity"      => "64",
			"lux"           => '6.1',
			"light_hours"   => '8.4',
		],
		[
			"height"        => "1462",
			"location"      => "",
			"plant_id"      => "",
			"notes"         => "small and seedy",
			"ph"            => "1",
			"conductivity"  => "2",
			"temperature"   => "3",
			"humidity"      => "4",
			"lux"           => '5',
			"light_hours"   => '6',
		]
	];


	public function __construct(Request $request)
	{
		$this->request = $request;
		$this->resourceName = "Plantdata";
	}		


	protected function testPost($plantId, $locationId)
	{
		echo '<h3 style="margin: 10px 0 0 0">POST - '.$this->resourceName.'</h3>';

		$requestStr = "post: /".$this->resourceName."/";
		$method 	= $this->resourceName."::create():";

		$this->request->parts	= array(DOMAIN, strtolower( $this->resourceName));


		try {
			$request = $this->request->di->create(NS_CONT.'\\'.$this->resourceName);
			
			foreach($this->data as $d) {
				$d['plant_id'] = $plantId;
				$d['location'] = $locationId;
		
				$post = $request->post($d);
				$this->id[] = $post->id;
				$this->assertRegExp(self::UUID, $post->id);
			}
		

			$this->pass($method, $requestStr);
			$this->log($requestStr);

		} catch (\Exception $e) {
			$this->error($e);
		}
	}


	protected function testGetAll()
	{
		echo '<h3 style="margin: 10px 0 0 0">GET - '.$this->resourceName.'</h3>';


		$requestStr = "get: /".$this->resourceName."/";
		$method 	= $this->resourceName."::fetchAll():";
		$this->request->parts	= array(DOMAIN, strtolower( $this->resourceName));
		$params 	= [];

		try {
			$request = $this->request->di->create(NS_CONT.'\\'.$this->resourceName);
			$get = $request->get($params);
			
			$this->assertGreaterThan(2, count($get), $method);
			$this->pass($method, $requestStr);
			$this->log($requestStr);

		} catch (\Exception $e) {
			$this->error($e);
		}
	}




	protected function testput($data)
	{
		echo '<h3 style="margin: 10px 0 0 0">PUT - '.$this->resourceName.'</h3>';

		$requestStr = "put: /".$this->resourceName."/";
		$method 	= $this->resourceName."::update():";
		$this->request->parts	= array(DOMAIN, strtolower( $this->resourceName));

		try {
			$request = $this->request->di->create(NS_CONT.'\\'.$this->resourceName);
			$put = $request->put($data);

			$this->assertGreaterThan(2, count($put), $method);
			$this->pass($method, $requestStr);
			$this->log($requestStr);

		} catch (\Exception $e) {
			$this->error($e);
		}
	}



	protected function testGet_byId()
	{
		echo '<h3 style="margin: 10px 0 0 0">GET - '.$this->resourceName.'</h3>';

		$requestStr = "get: /".$this->resourceName."/";
		$method 	= $this->resourceName."::fetch():";
		$this->request->parts	= array(DOMAIN, strtolower( $this->resourceName), $this->plantLoadId);

		try {
			$request = $this->request->di->create(NS_CONT.'\\'.$this->resourceName);
			$get = $request->get($params);
			$this->assertEquals('TEST_2099-01-01_13', $get->serial, $method);
			$this->pass($method, $requestStr);
			$this->log($requestStr);

		} catch (\Exception $e) {
			$this->error($e);
		}
	}


	protected function testDelete()
	{
		echo '<h3 style="margin: 10px 0 0 0">DELETE - '.$this->resourceName.'</h3>';

		$requestStr = "delete: /".$this->resourceName."/";
		$method 	= $this->resourceName."::delete():";
		$this->request->parts	= array(DOMAIN, strtolower( $this->resourceName), $this->plantLoadId);

		try {
			$request = $this->request->di->create(NS_CONT.'\\'.$this->resourceName);
			$del = $request->delete();

			$this->IsFalse($del, $method);
			$this->pass($method, $requestStr);
			$this->log($requestStr);

		} catch (\Exception $e) {
			$this->error($e);
		}
	}


}