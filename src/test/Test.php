<?php
namespace src\test;


use src\router\Request;
use src\controller;
use src\service\l;


// 			$this->assertArrayHasKey('Pagemasters', $get->portal, $method);
// 			$this->assertArrayNotHasKey('Steve_Davison', $get->portal['Pagemasters'], $method);


class Test extends Basetest
{
	public function __construct(Request $request)
	{

		$this->db 				= $request->db;
		parent::__construct($this->db);
		// $this->mc 				= $request->mc;
		$this->auth_user        = $request->auth_user;
		$this->request 			= $request;
		// $this->cacheRequest     = clone $this->request;



		// TEST OBJECTS
		$this->Location  	 = $this->request->di->create(NS_TEST.'\TestLocation');
		$this->Plant 	 	 = $this->request->di->create(NS_TEST.'\TestPlant');
		$this->Room 	 	 = $this->request->di->create(NS_TEST.'\TestRoom');
		$this->RoomData 	 = $this->request->di->create(NS_TEST.'\TestRoomData');
		$this->plantData 	 = $this->request->di->create(NS_TEST.'\TestPlantData');
		$this->Lifecycle 	 = $this->request->di->create(NS_TEST.'\TestLifecycle');

	}

	public function Get() //RUN
	{
		l::og('clear');


		$this->ClearTestData();

		$this->Location->testPost();
		$this->Location->testGetAll();
		$this->Location->testPut(["name" => "TEST_green" ]);
		$this->Location->testGet_byId();


		$this->Room->testPost();
		$this->Room->testGetAll();
		$this->Room->testPut(["name" => "TEST_darkroom" ]);
		$this->Room->testGet_byId();


		$this->RoomData->testPost($this->Room->roomId);
		// $this->RoomData->testGetAll();
		$this->RoomData->testPut(["temperature" => "12", "humidity" => "5" ]);
		$this->RoomData->testGet_byId();



		$this->Lifecycle->testPost();
		$this->Lifecycle->testGetAll();
		$this->Lifecycle->testPut(["name" => "TEST_darkLifecycle" ]);
		$this->Lifecycle->testGet_byId();

		$this->Plant->testPost($this->plants);
		// $this->Plant->testGetAll();
		// $this->Plant->testPut(["name" => "TEST_green" ]);
		$this->Plant->testGet_byId();


		$this->plantData->testPost($this->Plant->id[1], $this->Location->id, 0);
		$this->plantData->testPost($this->Plant->id[1], $this->Location->id, 1);
		$this->plantData->testPost($this->Plant->id[1], $this->Location->id, 2);
		$this->plantData->testPost($this->Plant->id[1], $this->Location->id, 3);
		$this->plantData->testPost($this->Plant->id[1], $this->Location->id, 4);
		$this->plantData->testPost($this->Plant->id[1], $this->Location->id, 5);

		$this->Plant->testGet_byIdWithData(["data"=> true, 'limit' => 4]);



		$this->Location->testDelete();
		$this->Plant->testDelete();
		$this->Room->testDelete();
		$this->Lifecycle->testDelete();



		$this->finish();

		exit;
    }




	private function ClearTestData()
	{
		echo '<h3 style="margin: 10px 0 0 0">DELETE - Clear Old Data</h3>';
		
		// *******************************************************************
		// *						CLEARING LOCATIONS 						 *
		// *******************************************************************
		$request 	= "delete: /location/:name";
		try {

			$this->request->parts	= array("racing-api/", "plant");
			$plant = $this->request->di->create('src\controller\Plant');
			$get = $plant->get(["serial" => "TEST_2099-01-01_13"]);

			$this->request->parts	= array("racing-api/", "plant", $get->id);
			$plant = $this->request->di->create('src\controller\Plant');
			$get = $plant->delete();



			$rooms = ["TEST_growingroom", "TEST_darkroom", "TEST_seedroom"];
			foreach( $rooms as $room) {
				$this->request->parts	= array("racing-api/", "room");
				$roomModel = $this->request->di->create('src\controller\Room');
				$get = $roomModel->get(["name" => $room]);

				$this->request->parts	= array("racing-api/", "room", $get->id);
				$roomModel = $this->request->di->create('src\controller\Room');
				$roomModel->delete();
			}


			$this->request->parts	= array("racing-api/", "location");
			$location = $this->request->di->create('src\controller\Location');
			$get = $location->get(["name" => "TEST_green"]);

			$this->request->parts	= array("racing-api/", "location", $get->id);
			$location = $this->request->di->create('src\controller\Location');
			$get = $location->delete();


			$this->request->parts	= array("racing-api/", "location");
			$location = $this->request->di->create('src\controller\Location');
			$get = $location->get(["name" => "TEST_red"]);

			$this->request->parts	= array("racing-api/", "location", $get->id);
			$location = $this->request->di->create('src\controller\Location');
			$get = $location->delete();


			$this->request->parts	= array("racing-api/", "location");
			$location = $this->request->di->create('src\controller\Location');
			$get = $location->get(["name" => "TEST_orange"]);

			$this->request->parts	= array("racing-api/", "location", $get->id);
			$location = $this->request->di->create('src\controller\Location');
			$get = $location->delete();





			$this->request->parts	= array("racing-api/", "lifecycle");
			$plant = $this->request->di->create(NS_CONT.'\\'.'Lifecycle');
			$get = $plant->get(["name" => "TEST_darkLifecycle"]);

			$this->request->parts	= array("racing-api/", "lifecycle", $get->id);
			$plant = $this->request->di->create(NS_CONT.'\\'.'Lifecycle');
			$get = $plant->delete();


			$this->request->parts	= array("racing-api/", "lifecycle");
			$plant = $this->request->di->create(NS_CONT.'\\'.'Lifecycle');
			$get = $plant->get(["name" => "TEST_youngin"]);

			$this->request->parts	= array("racing-api/", "lifecycle", $get->id);
			$plant = $this->request->di->create(NS_CONT.'\\'.'Lifecycle');
			$get = $plant->delete();


			$this->request->parts	= array("racing-api/", "lifecycle");
			$plant = $this->request->di->create(NS_CONT.'\\'.'Lifecycle');
			$get = $plant->get(["name" => "TEST_oldy"]);

			$this->request->parts	= array("racing-api/", "lifecycle", $get->id);
			$plant = $this->request->di->create(NS_CONT.'\\'.'Lifecycle');
			$get = $plant->delete();



			$this->request->parts	= array("racing-api/", "lifecycle");
			$plant = $this->request->di->create(NS_CONT.'\\'.'Lifecycle');
			$get = $plant->get(["name" => "TEST_dead"]);

			$this->request->parts	= array("racing-api/", "lifecycle", $get->id);
			$plant = $this->request->di->create(NS_CONT.'\\'.'Lifecycle');
			$get = $plant->delete();



		} catch (\Exception $e) {
			$this->error($e);
		}



	}


    protected function finish()
    {
    	echo "<div>";
		echo '<img src="flag.gif" style="margin: 10px 0 0 0;"></img>';
		echo '<h2 style="display:inline-block;margin: 10px 0 0 0;color:green;background:yellow">FINISHED!!</h2>';
		echo '<img src="flag.gif" style="margin: 10px 0 0 0;"></img>';
		echo '<br />';
		echo '<img src="digital_counter.gif" style="margin: 10px 0 0 0;"></img>';
		echo "</div>";
	}


}
