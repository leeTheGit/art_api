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

		parent::__construct($request);

		// TEST OBJECTS
		$this->Lifecycle 	 = $this->request->di->create(NS_TEST.'\TestLifecycle');
		$this->Location  	 = $this->request->di->create(NS_TEST.'\TestLocation');
		$this->Room 	 	 = $this->request->di->create(NS_TEST.'\TestRoom');
		$this->RoomLocation  = $this->request->di->create(NS_TEST.'\TestRoomLocation');
		$this->RoomData 	 = $this->request->di->create(NS_TEST.'\TestRoomData');
		$this->Plant 	 	 = $this->request->di->create(NS_TEST.'\TestPlant');
		$this->PlantLocation = $this->request->di->create(NS_TEST.'\TestPlantLocation');
		$this->plantData 	 = $this->request->di->create(NS_TEST.'\TestPlantData');

	}

	public function Get() //RUN
	{
		l::og('clear');


		$this->ClearTestData();
		// return;
		$this->Lifecycle->testPost();
		$this->Lifecycle->testGetAll();
		$this->Lifecycle->testPut(["name" => "TEST_darkLifecycle" ]);
		$this->Lifecycle->testGet_byId();


			
		$this->Room->testPost();
		$this->Room->testGetAll();
		$this->Room->testPut();
		$this->Room->testGet_byId();
	
		


		$this->Location->testPost();
		$this->Location->testGetAll();
		$this->Location->testPut(0, [ "name" => "TEST_black" ]);
		$this->Location->testGet_byId();

		$this->Location->testPut(0, [ "room_id" => $this->Room->id[0] ]);
		$this->Location->testPut(0, [ "room_id" => $this->Room->id[1] ]);

		$this->Location->testPut(1, [ "room_id" => $this->Room->id[0] ]);
		$this->Location->testPut(1, [ "room_id" => $this->Room->id[1] ]);
		$this->Location->testPut(1, [ "room_id" => $this->Room->id[0] ]);
		
		$this->Location->testPut(2, [ "room_id" => $this->Room->id[0] ]);
		$this->Location->testPut(2, [ "room_id" => $this->Room->id[2] ]);
		$this->Location->testPut(2, [ "room_id" => $this->Room->id[1] ]);


		// $this->RoomLocation->testPost(["room_id" => $this->Room->id[0], "location_id"=> $this->Location->id[0], "created_at" => "2018-06-01"]);
		// $this->RoomLocation->testPost(["room_id" => $this->Room->id[1], "location_id"=> $this->Location->id[0], "created_at" => "2018-06-06"]);
		
		// $this->RoomLocation->testPost(["room_id" => $this->Room->id[0], "location_id"=> $this->Location->id[1], "created_at" => "2018-06-01"]);
		// $this->RoomLocation->testPost(["room_id" => $this->Room->id[1], "location_id"=> $this->Location->id[1], "created_at" => "2018-06-10"]);
		// $this->RoomLocation->testPost(["room_id" => $this->Room->id[0], "location_id"=> $this->Location->id[1], "created_at" => "2018-06-14"]);
		
		// $this->RoomLocation->testPost(["room_id" => $this->Room->id[0], "location_id"=> $this->Location->id[2], "created_at" => "2018-06-01"]);
		// $this->RoomLocation->testPost(["room_id" => $this->Room->id[2], "location_id"=> $this->Location->id[2], "created_at" => "2018-06-04"]);
		// $this->RoomLocation->testPost(["room_id" => $this->Room->id[1], "location_id"=> $this->Location->id[2], "created_at" => "2018-06-12"]);
		$this->RoomLocation->testGetAll();
		// $this->RoomLocation->testPut();
		// $this->RoomLocation->testGet_byId();



		$this->RoomData->testPost($this->Room->id[0], "seed");
		$this->RoomData->testPost($this->Room->id[1], "grow");
		$this->RoomData->testPost($this->Room->id[2], "fun");
		$this->RoomData->testPut();
		$this->RoomData->testGet_byId();




		$this->Plant->testPost();
		$this->Plant->testGetAll();
		// $this->Plant->testPut(["name" => "TEST_green" ]);
		$this->Plant->testGet_byId();



		$this->PlantLocation->testPost(["plant_id" => $this->Plant->id[0], "location_id"=> $this->Location->id[0], "created_at" => "2018-06-01"]);
		$this->PlantLocation->testPost(["plant_id" => $this->Plant->id[0], "location_id"=> $this->Location->id[1], "created_at" => "2018-06-03"]);
		$this->PlantLocation->testPost(["plant_id" => $this->Plant->id[0], "location_id"=> $this->Location->id[2], "created_at" => "2018-06-08"]);
		
		// used for testing plantData across rooms
		$this->PlantLocation->testPost(["plant_id" => $this->Plant->id[1], "location_id"=> $this->Location->id[2], "created_at" => "2018-06-02"]);
		$this->PlantLocation->testPost(["plant_id" => $this->Plant->id[1], "location_id"=> $this->Location->id[1], "created_at" => "2018-06-05"]);
		$this->PlantLocation->testPost(["plant_id" => $this->Plant->id[1], "location_id"=> $this->Location->id[2], "created_at" => "2018-06-09"]);
		$this->PlantLocation->testPost(["plant_id" => $this->Plant->id[1], "location_id"=> $this->Location->id[0], "created_at" => "2018-06-20"]);
		$this->PlantLocation->testGetAll();





		$this->plantData->testPost($this->Plant->id[1], $this->Location->id[0]);

		$this->Plant->testGet_byIdWithData(["data"=> true, 'limit' => 4]);



		// $this->Location->testDelete();
		// $this->Plant->testDelete();
		// $this->Room->testDelete();
		// $this->RoomLocation->testDelete();
		// $this->RoomData->testDelete();
		// $this->Lifecycle->testDelete();



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


			$plants = ["TEST_2099-01-01_13", "TEST_2099-01-01_14", "TEST_2099-01-01_15"];
			foreach( $plants as $plant) {

				$this->request->parts	= array("racing-api/", "plant");
				$resource = $this->request->di->create('src\controller\Plant');
				$get = $resource->get(["serial" => $plant]);
				if ($get) {
					$this->request->parts	= array("racing-api/", "plant", $get->id);
					$resource = $this->request->di->create('src\controller\Plant');
					$get = $resource->delete();
				}
			}


			$rooms = ["TEST_growingroom", "TEST_darkroom", "TEST_seedroom", "TEST_funroom"];
			foreach( $rooms as $room) {
				$this->request->parts	= array("racing-api/", "room");
				$resource = $this->request->di->create('src\controller\Room');
				$get = $resource->get(["name" => $room]);
				if ($get) {
					$this->request->parts	= array("racing-api/", "room", $get->id);
					$resource = $this->request->di->create('src\controller\Room');
					$resource->delete();
				}
			}


			$locations = ["TEST_green", "TEST_red", "TEST_orange", "TEST_black"];
			foreach( $locations as $loc) {
				$this->request->parts	= array("racing-api/", "location");
				$resource = $this->request->di->create('src\controller\Location');
				$get = $resource->get(["name" => $loc]);
				if ($get) {
					$this->request->parts	= array("racing-api/", "location", $get->id);
					$resource = $this->request->di->create('src\controller\Location');
					$get = $resource->delete();
				}
			}

			



			$lifes = ["TEST_seed", "TEST_darkLifecycle", "TEST_youngin", "TEST_oldy", "TEST_dead"];
			foreach( $lifes as $life) {
				$this->request->parts	= array("racing-api/", "lifecycle");
				$resource = $this->request->di->create(NS_CONT.'\\'.'Lifecycle');
				$get = $resource->get(["name" => $life]);
				if ($get) {
					$this->request->parts	= array("racing-api/", "lifecycle", $get->id);
					$resource = $this->request->di->create(NS_CONT.'\\'.'Lifecycle');
					$get = $resource->delete();
				}
			}	





		} catch (\Exception $e) {
			$this->error($e);
		}



	}


    protected function finish()
    {
    	echo "<div>";
		echo 	'<img src="flag.gif" style="margin: 10px 0 0 0;"></img>';
		echo 	'<h2 style="display:inline-block;margin: 10px 0 0 0;color:green;background:yellow">FINISHED!!</h2>';
		echo 	'<img src="flag.gif" style="margin: 10px 0 0 0;"></img>';
		echo 	'<br />';
		echo 	'<img src="digital_counter.gif" style="margin: 10px 0 0 0;"></img>';
		echo "</div>";
	}


}
