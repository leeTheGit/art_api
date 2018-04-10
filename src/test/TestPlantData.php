<?php
namespace src\test;


use src\router\Request;
use src\controller;
use src\service\l;

class TestPlantData extends Test
{

	public $id;

	public function __construct(Request $request)
	{
		$this->request = $request;
		$this->resourceName = "Plantdata";
	}		


	protected function testPost($plantId, $locationId, $data)
	{
		echo '<h3 style="margin: 10px 0 0 0">POST - '.$this->resourceName.'</h3>';


		$requestStr = "post: /".$this->resourceName."/";
		$method 	= $this->resourceName."::create():";

		$this->request->parts	= array(DOMAIN, strtolower( $this->resourceName));

		$data['plant_id'] = $plantId;
		$data['location'] = $locationId;

		try {
			$load = $this->request->di->create(NS_CONT.'\\'.$this->resourceName);
			$post = $load->post($data);
			$this->id = $post->id;

			$this->assertRegExp(self::UUID, $post->id);

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