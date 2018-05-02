<?php
namespace src\test;


use src\router\Request;
use src\controller;
use src\service\l;

class TestPlant extends Test
{

	public $id = [];

	public function __construct(Request $request)
	{
		$this->request = $request;
		$this->resourceName = "Plant";
	}		


	protected function testPost($data)
	{
		echo '<h3 style="margin: 10px 0 0 0">POST - '.$this->resourceName.'</h3>';

		$requestStr = "post: /".$this->resourceName."/";
		$method 	= $this->resourceName."::create():";
		$this->request->parts	= array(DOMAIN, strtolower( $this->resourceName));

		try {
			$request = $this->request->di->create(NS_CONT.'\\'.$this->resourceName);
			$post = $request->post($data[0]);
			$this->id[] = $post->id;
			$this->assertRegExp(self::UUID, $post->id);

			$post = $request->post($data[1]);
			$this->id[] = $post->id;
			$this->assertRegExp(self::UUID, $post->id);

			$post = $request->post($data[2]);
			$this->id[] = $post->id;
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
		$this->request->parts	= array(DOMAIN, strtolower( $this->resourceName), $this->id[0]);

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


	protected function testGet_byIdWithData($params)
	{
		echo '<h3 style="margin: 10px 0 0 0">GET - '.$this->resourceName.'</h3>';

		$requestStr = "get: /".$this->resourceName."/";
		$method 	= $this->resourceName."::fetch([data => true, limit => 4]):";
		$this->request->parts	= array(DOMAIN, strtolower( $this->resourceName), $this->id[1]);

		try {
			$request = $this->request->di->create(NS_CONT.'\\'.$this->resourceName);
			$get = $request->get($params);

			$this->assertEquals('TEST_2099-01-01_14', $get->serial, $method);
			$this->assertEquals(6, count( $get->data ), $method);
			$this->assertEquals('small and seedy', $get->data[0]->notes, $method);
			$this->assertEquals(2, $get->data[0]->conductivity, $method);
			$this->assertEquals(3, $get->data[0]->temperature, $method);
			$this->assertEquals(4, $get->data[0]->humidity, $method);
			$this->assertEquals(5, $get->data[0]->lux, $method);
			$this->assertEquals(6, $get->data[0]->light_hours, $method);

			$this->assertEquals('Browny green color', $get->data[1]->notes, $method);
			$this->assertEquals(7.8, 	$get->data[1]->ph, $method);
			$this->assertEquals(56, 	$get->data[1]->conductivity, $method);
			$this->assertEquals(32.9, 	$get->data[1]->temperature, $method);
			$this->assertEquals(64, 	$get->data[1]->humidity, $method);
			$this->assertEquals(6.1, 	$get->data[1]->lux, $method);
			$this->assertEquals(8.4, 	$get->data[1]->light_hours, $method);

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

		try {

			for ($i=0; $i < count($this->id); $i++) {
				$this->request->parts	= array(DOMAIN, strtolower( $this->resourceName), $this->id[$i]);
				$request = $this->request->di->create(NS_CONT.'\\'.$this->resourceName);
				$del = $request->delete();
				$this->IsFalse($del, $method);	
			}

			$this->pass($method, $requestStr);
			$this->log($requestStr);

		} catch (\Exception $e) {
			$this->error($e);
		}
	}


}