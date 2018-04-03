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

		require_once 'Testdata.php';

		$this->db 				= $request->db;
		parent::__construct($this->db);
		// $this->mc 				= $request->mc;
		$this->auth_user        = $request->auth_user;
		$this->request 			= $request;
		// $this->cacheRequest     = clone $this->request;



		// TEST OBJECTS
		$this->TestLocation  = $this->request->di->create(NS_TEST.'\TestLocation');
		$this->TestPlant 	 = $this->request->di->create(NS_TEST.'\TestPlant');
		$this->TestRoom 	 = $this->request->di->create(NS_TEST.'\TestRoom');
		$this->TestPlantData = $this->request->di->create(NS_TEST.'\TestPlantData');
		$this->TestLifecycle = $this->request->di->create(NS_TEST.'\TestLifecycle');


		// TEST DATA
		$this->testLocations	= $locations;
		$this->testRooms		= $rooms;
		$this->testPlants       = $plants;
		$this->testPlantData    = $plantData;
		$this->lifecycles       = $lifecycles;
	}

	public function Get() //RUN
	{
		l::og('clear');


		$this->ClearTestData();

		$this->TestLocation->testPost($this->testLocations);
		$this->TestLocation->testGetAll();
		$this->TestLocation->testPut(["name" => "TEST_green" ]);
		$this->TestLocation->testGet_byId();


		$this->TestRoom->testPost($this->testRooms);
		$this->TestRoom->testGetAll();
		$this->TestRoom->testPut(["name" => "TEST_darkroom" ]);
		$this->TestRoom->testGet_byId();


		$this->TestLifecycle->testPost($this->lifecycles);
		$this->TestLifecycle->testGetAll();
		$this->TestLifecycle->testPut(["name" => "TEST_darkLifecycle" ]);
		$this->TestLifecycle->testGet_byId();

		$this->TestPlant->testPost($this->testPlants);
		$this->TestPlant->testGetAll();
		// $this->TestPlant->testPut(["name" => "TEST_green" ]);
		$this->TestPlant->testGet_byId();


		$this->TestPlantData->testPost($this->TestPlant->id[1], $this->TestLocation->id, $this->testPlantData[0]);
		$this->TestPlantData->testPost($this->TestPlant->id[1], $this->TestLocation->id, $this->testPlantData[1]);




		$this->TestLocation->testDelete();
		$this->TestPlant->testDelete();
		$this->TestRoom->testDelete();
		$this->TestLifecycle->testDelete();



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
