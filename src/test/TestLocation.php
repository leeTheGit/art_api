<?php
namespace src\test;


use src\router\Request;
use src\controller;
use src\service\l;

class TestLocation extends Test
{

	public $id = [];

	private $data = [
		["name" => "TEST_orange"],
		["name" => "TEST_red"],
		["name" => "TEST_green"],
	];
	


	public function __construct(Request $request)
	{

		$this->request = $request;
		$this->resourceName = "Location";
	}		


	protected function testPost()
	{
		echo '<h3 style="margin: 10px 0 0 0">POST - '.$this->resourceName.'</h3>';

		$requestStr = "post: /".$this->resourceName."/";
		$method 	= $this->resourceName."::create():";
		$this->request->parts	= array(DOMAIN, strtolower( $this->resourceName) );

		try {
			$request = $this->request->di->create(NS_CONT.'\\'.$this->resourceName);
			
			
			foreach($this->data as $d) {
	 			$post = $request->post($d);
				$this->id[] = $post->id;
				$this->assertRegExp(self::UUID, $post->id);
			}

			$post = $request->post($this->data[0]);
			$this->IsFalse($post, $method);

			$this->pass($method, $requestStr);
			$this->log($requestStr);

		} catch (\Exception $e) {
			$this->error($e);
		}
	}

	protected function testGet_byId()
	{
		echo '<h3 style="margin: 10px 0 0 0">GET - '.$this->resourceName.'ById</h3>';

		$requestStr 	= "post: /".$this->resourceName."/";
		$method 	= $this->resourceName."::fetch():";
		$this->request->parts	= array(DOMAIN, strtolower( $this->resourceName), $this->id[0]);

		try {
			$request = $this->request->di->create(NS_CONT.'\\'.$this->resourceName);

			$get = $request->get();
			$this->assertEquals('TEST_black', $get->name, $method);

			$this->pass($method, $requestStr);
			$this->log($requestStr);

		} catch (\Exception $e) {
			$this->error($e);
		}
	}

	protected function testPut($index, $params)
	{
		echo '<h3 style="margin: 10px 0 0 0">PUT - '.$this->resourceName.'</h3>';

		$requestStr 	= "post: /".$this->resourceName."/";
		$method 	= $this->resourceName."::update():";
		$this->request->parts	= array(DOMAIN, strtolower( $this->resourceName), $this->id[$index]);

		
		try {
			$request = $this->request->di->create(NS_CONT.'\\'.$this->resourceName);
			$put = $request->put($params);
			$this->assertEquals(1, $put, $method);
			$this->pass($method, $requestStr);
			$this->log($requestStr);

		} catch (\Exception $e) {
			$this->error($e);
		}
	}

	protected function testGetAll()
	{
		echo '<h3 style="margin: 10px 0 0 0">GET - '.$this->resourceName.'s</h3>';

		$requestStr 	= "post: /".$this->resourceName."/";
		$method 	= $this->resourceName."::fetchAll():";
		$this->request->parts	= array(DOMAIN, strtolower( $this->resourceName));

		try {
			$request = $this->request->di->create(NS_CONT.'\\'.$this->resourceName);
			$get = $request->get();

			$this->assertGreaterThan(1, count($get), $method);

			$this->pass($method, $requestStr);
			$this->log($requestStr);

		} catch (\Exception $e) {
			$this->error($e);
		}
	}

	protected function testDelete()
	{
		echo '<h3 style="margin: 10px 0 0 0">DELETE - '.$this->resourceName.'</h3>';

		$requestStr 	= "delete: /".$this->resourceName."/";
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