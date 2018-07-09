<?php
namespace src\test;


use src\router\Request;
use src\controller;
use src\service\l;

class TestRoomData extends Test
{

	public $id = [];

    private $data = [
		"seed" => [
			["temperature" => "40", "humidity" => "23" ],
			["temperature" => "50", "humidity" => "12" ],
			["temperature" => "12", "humidity" => "34" ],  
			["temperature" => "4", "humidity"  => "2" ],  
			["temperature" => "1", "humidity"  => "89" ],  
			["temperature" => "16", "humidity" => "10" ],  
			["temperature" => "17", "humidity" => "44" ],  
			["temperature" => "23", "humidity" => "5" ],  
			["temperature" => "30", "humidity" => "6" ],  
			["temperature" => "40", "humidity" => "2" ],
		],
		"grow" => [
			["temperature" => "23", "humidity" => "76" ],
			["temperature" => "78", "humidity" => "34" ],
			["temperature" => "76", "humidity" => "54" ],  
			["temperature" => "54", "humidity" => "11" ],  
			["temperature" => "13", "humidity" => "54" ],  
			["temperature" => "90", "humidity" => "23" ],  
			["temperature" => "56", "humidity" => "65" ],  
			["temperature" => "78", "humidity" => "58" ],  
			["temperature" => "45", "humidity" => "34" ],  
			["temperature" => "34", "humidity" => "45" ],
		],
		"fun" => [
			["temperature" => "32", "humidity" => "65" ],
			["temperature" => "45", "humidity" => "67" ],
			["temperature" => "86", "humidity" => "43" ],  
			["temperature" => "34", "humidity" => "65" ],  
			["temperature" => "87", "humidity" => "23" ],  
			["temperature" => "24", "humidity" => "44" ],  
			["temperature" => "25", "humidity" => "65" ],  
			["temperature" => "75", "humidity" => "87" ],  
			["temperature" => "36", "humidity" => "63" ],  
			["temperature" => "75", "humidity" => "3" ],
		]
    ];

	public function __construct(Request $request)
	{

		$this->request = $request;
		$this->resourceName = "roomdata";
	}		


	protected function testPost($room_id, $room)
	{
		echo '<h3 style="margin: 10px 0 0 0">POST - '.$this->resourceName.'</h3>';

		$requestStr = "post: /".$this->resourceName."/";
		$method 	= $this->resourceName."::create():";
		$this->request->parts	= array(DOMAIN, strtolower( $this->resourceName) );

		$dataDate = \DateTime::createFromFormat('Y-m-d H:i:s', "2018-06-01" . '13:00:00');
		// pprint($todayDate);
		// pprint($dataDate->format('Y-m-d H:i:s'));

		try {
			$request = $this->request->di->create(NS_CONT.'\\'.$this->resourceName);
			foreach($this->data[$room] as $d) {
				$d['room_id'] = $room_id;
				$d['time'] = $dataDate->format('Y-m-d H:i:s');
				$post = $request->post($d);
				$this->id[] = $post->id;
				$this->assertRegExp(self::UUID, $post->id);
				$dataDate->modify( '+1 day' );

			}
			
			// pprint($this->id);
			$post = $request->post($this->data["seed"][0]);
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
			$this->assertEquals(12, $get->temperature, $method);
			$this->assertEquals(5, $get->humidity, $method);

			$this->pass($method, $requestStr);
			$this->log($requestStr);

		} catch (\Exception $e) {
			$this->error($e);
		}
	}

	protected function testPut()
	{
		echo '<h3 style="margin: 10px 0 0 0">PUT - '.$this->resourceName.'</h3>';

		$requestStr 	= "post: /".$this->resourceName."/";
		$method 	= $this->resourceName."::update():";
		$this->request->parts	= array(DOMAIN, strtolower( $this->resourceName), $this->id[0]);
		$params = ["temperature" => "12", "humidity" => "5" ];

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
			
			foreach($this->id as $id) {
				$this->request->parts	= array(DOMAIN, strtolower( $this->resourceName), $id);
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