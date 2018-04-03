<?php
namespace src\test;


use src\router\Request;
use src\controller;
use src\service\l;

class TestLifeCycle extends Test
{

	public $id;

	public function __construct(Request $request)
	{

		$this->request = $request;
		$this->resourceName = "Lifecycle";
	}		


	protected function testPost($data)
	{
		echo '<h3 style="margin: 10px 0 0 0">POST - '.$this->resourceName.'</h3>';

		$requestStr = "post: /".$this->resourceName."/";
		$method 	= $this->resourceName."::create():";
		$this->request->parts	= array(DOMAIN, strtolower( $this->resourceName) );

		try {
			$request = $this->request->di->create(NS_CONT.'\\'.$this->resourceName);
			
			$post = $request->post($data[0]);
			$this->id = $post->id;
			$this->assertRegExp(self::UUID, $post->id);

			$post = $request->post($data[1]);
			$this->id2 = $post->id;
			$this->assertRegExp(self::UUID, $post->id);
			
			$post = $request->post($data[2]);
			$this->id3 = $post->id;
			$this->assertRegExp(self::UUID, $post->id);

			$post = $request->post($data[3]);
			$this->id4 = $post->id;
			$this->assertRegExp(self::UUID, $post->id);


			$post = $request->post($data[0]);
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
		$this->request->parts	= array(DOMAIN, strtolower( $this->resourceName), $this->id);

		try {
			$request = $this->request->di->create(NS_CONT.'\\'.$this->resourceName);

			$get = $request->get();

			$this->assertEquals('TEST_darkLifecycle', $get->name, $method);

			$this->pass($method, $requestStr);
			$this->log($requestStr);

		} catch (\Exception $e) {
			$this->error($e);
		}
	}

	protected function testPut($data)
	{
		echo '<h3 style="margin: 10px 0 0 0">PUT - '.$this->resourceName.'</h3>';

		$requestStr 	= "post: /".$this->resourceName."/";
		$method 	= $this->resourceName."::update():";
		$this->request->parts	= array(DOMAIN, strtolower( $this->resourceName), $this->id);

		
		try {
			$request = $this->request->di->create(NS_CONT.'\\'.$this->resourceName);
			$put = $request->put($data);

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
			$this->request->parts	= array(DOMAIN, strtolower( $this->resourceName), $this->id);
			$request = $this->request->di->create(NS_CONT.'\\'.$this->resourceName);
			$del = $request->delete();
			$this->IsFalse($del, $method);



			$this->request->parts	= array(DOMAIN, strtolower( $this->resourceName), $this->id2);
			$request = $this->request->di->create(NS_CONT.'\\'.$this->resourceName);
			$del = $request->delete();
			$this->IsFalse($del, $method);



			$this->request->parts	= array(DOMAIN, strtolower( $this->resourceName), $this->id3);
			$request = $this->request->di->create(NS_CONT.'\\'.$this->resourceName);
			$del = $request->delete();
			$this->IsFalse($del, $method);


			$this->request->parts	= array(DOMAIN, strtolower( $this->resourceName), $this->id3);
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