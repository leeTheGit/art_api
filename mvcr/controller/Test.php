<?php
namespace mvcr\controller;


use mvcr\router\Request;
use mvcr\controller;

class Test extends \PHPUnit\Framework\TestCase
{
	public function __construct(Request $request)
	{

		// $this->assertArrayHasKey('Pagemasters', $get->portal, $method);

		require_once 'Testdata.php';
		$this->db 				= $request->db;
		$this->mc 				= $request->mc;
		$this->auth_user        = $request->auth_user;

		$this->request 			= $request;
		$this->cacheRequest     = clone $this->request;

		$this->plantLoadId = '';
		// $this->testMeeting  	= ''; #'6913dec6-6e3e-4329-a50c-8ec9cd82ae2d';
		// $this->testRace1     	= ''; #'0f5ff287-f78c-445b-9d96-0eb263c26f7d';
		// $this->testRace2     	= ''; #'0f5ff287-f78c-445b-9d96-0eb263c26f7d';
		// $this->testRace3    	= ''; #'0f5ff287-f78c-445b-9d96-0eb263c26f7d';
		// $this->testRace5    	= ''; #'0f5ff287-f78c-445b-9d96-0eb263c26f7d';

		// $this->testRunner   	= ''; #'7cf443e7-f825-4b24-b87b-c9b161a5dd3d';
		// $this->testRunner2 		= '';

		// $this->testJockey		= '';
		// $this->testJockey2		= '';

		// $this->testTrainer 		= '';
		// $this->testTrainer2		= '';

		// $this->testSupplier 	= 'd863242c-ae5f-4397-a5f5-2d804fd5b8a2'; // UBET

		// $this->testTrack   		= '';
		// $this->testGroup 		= '';
		// $this->testUser 		= '';
		// $this->testOutput 		= '';
		// $this->testPublication 	= '';
		$this->testMock         = $testPlant;
		// $this->testMock2        = $testMeeting2;

		// $this->meetingLoadId  	= '';

		// $this->testDivsUbet		= $testUbetDivs;
		// $this->testDivsTab   	= $testTabDivs;
		// $this->ubetDivsId		= '';
		// $this->tabDivsId		= '';

		// $this->portalHorses 	= [];

		// $this->testAuth_news 	= $testAuth_news;
		// $this->testAuth_news2 	= $testAuth_news2;
		// $this->testAuth_fairfax = $testAuth_fairfax;
		// $this->testSteve 		= $testSteve;
		// $this->testLee 			= $testLee;
		// $this->testIndesign 	= $testIndesign;

		// $this->testImportData 	= $testImportData;

	}

	public function Get()
	{
		logThis('clear');

		// $this->testLogin();

		// $this->ClearTestData();

		$this->testPostPlant();

		$this->testgetPlants();

		$this->testgetPlant();

		$this->testDeletePlant();


		$this->finish();

		exit;
    }

    private function error($e)
    {
	    $text = '';
	    $caller = debug_backtrace();
	    if (!empty($caller)) {
	        $line = (isset($caller[0]['line'])) ? $caller[0]['line'] : '';
	        $text = $line . ': ' . $text . ' ';
	        $text = $text . PHP_EOL;
	    }
		echo '<p style="color:red;margin:0;">'.$text. $e->getMessage()."</p>";
    }

    private function pass($method, $request)
    {
		echo '<ul style="margin:0 0 5px 0;"><li style="color:grey;margin:0;">'.$request.'</li><li style="color:green;margin:0">'.$method.'</li></ul>';
    	$this->log($request);
    }

	private function log($url)
	{
		$sql = "UPDATE uri_log set tested = now() WHERE uri = :uri";
		$param = array('uri' => $url);
		$this->db->execute($sql, $param);
	}



	// private function ClearTestData()
	// {
	// 	echo '<h3 style="margin: 10px 0 0 0">DELETE - Clear Old Data</h3>';

	// 	$request 	= "delete: /group/:id/:name";
	// 	$this->request->parts	= array("racing-api/", "group", "id", "AAAAAAAAAAAAAA");
	// 	try {
	// 		$group = $this->request->di->create('mvcr\controller\Group');
	// 		$delete = $group->delete();
	// 		$this->log($request);

	// 	} catch (\Exception $e) {
	// 		$this->error($e);
	// 	}

	// 	$request 	= "delete: /track/:id/:name";
	// 	$this->request->parts	= array("racing-api/", "track", "id", "A1A Carwash");
	// 	try {
	// 		$group = $this->request->di->create('mvcr\controller\Track');
	// 		$delete = $group->delete();
	// 		$this->log($request);

	// 	} catch (\Exception $e) {
	// 		$this->error($e);
	// 	}

	// 	$this->request->parts	= array("racing-api/", "publication", "id", "Aaaaaaargh Real Monsters");
	// 	try {
	// 		$group = $this->request->di->create('mvcr\controller\Publication');
	// 		$delete = $group->delete();
	// 		$this->log($request);

	// 	} catch (\Exception $e) {
	// 		$this->error($e);
	// 	}

	// }


	// private function testLogin()
	// {
	// 	echo '<h3 style="margin: 10px 0 0 0">GET - Login</h3>';
	// 	$params 	= $this->db;

	// 	try {
	// 		$log = $this->request->di->create('mvcr\controller\Login');
	// 		$get = $load->get($params);

	// 		$login = new \mvcr\controller\Login($this->request);

	// 		$auth_user = $login->auth('lneenan', 'radb');

	// 	} catch (\Exception $e) {
	// 		$this->error($e);
	// 	}
	// }
	private function testPostPlant()
	{
		echo '<h3 style="margin: 10px 0 0 0">POST - Plants</h3>';

		$request 	= "post: /plant/";
		$method 	= "plant::create():";
		$this->request->parts	= array("racing-api/", "plant");
		$params 	= $this->testMock;

		try {
			$load = $this->request->di->create('mvcr\controller\Plant');
			$post = $load->post($params);
			$this->plantLoadId = $post->id;

			$this->assertRegExp('/[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}/', $post->id);

			$this->pass($method, $request);
			$this->log($request);

		} catch (\Exception $e) {
			$this->error($e);
		}
	}


	private function testGetPlants()
	{
		echo '<h3 style="margin: 10px 0 0 0">GET - Plants</h3>';

		$request 	= "get: /plant/";
		$method 	= "plant::fetchAll():";
		$this->request->parts	= array("racing-api/", "plant");
		$params 	= [];

		try {
			$load = $this->request->di->create('mvcr\controller\Plant');
			$get = $load->get($params);
			$this->meetingLoadId = $post;

			$this->assertGreaterThan(2, count($get), $method);
			$this->pass($method, $request);
			$this->log($request);

		} catch (\Exception $e) {
			$this->error($e);
		}
	}


	private function testGetPlant()
	{
		echo '<h3 style="margin: 10px 0 0 0">GET - Plant</h3>';

		$request 	= "get: /plant/";
		$method 	= "plant::fetch():";
		$this->request->parts	= array("racing-api/", "plant", $this->plantLoadId);
		$params 	= $this->plantLoadId;

		try {
			$load = $this->request->di->create('mvcr\controller\Plant');
			$get = $load->get($params);
			$this->assertEquals('TEST_2099-01-01_13', $get->serial, $method);
			$this->pass($method, $request);
			$this->log($request);

		} catch (\Exception $e) {
			$this->error($e);
		}
	}


	private function testDeletePlant()
	{
		echo '<h3 style="margin: 10px 0 0 0">DELETE - Plant</h3>';

		$request 	= "delete: /plant/";
		$method 	= "plant::delete():";
		$this->request->parts	= array("racing-api/", "plant", $this->plantLoadId);
		$params 	= $this->plantLoadId;

		try {
			$load = $this->request->di->create('mvcr\controller\Plant');
			$del = $load->delete($params);
			pprint($del);

			$this->IsFalse($del, $method);
			$this->pass($method, $request);
			$this->log($request);

		} catch (\Exception $e) {
			$this->error($e);
		}
	}



// 	private function testPostLoadRedraw()
// 	{
// 		echo '<h3 style="margin: 10px 0 0 0">POST - Load Mock</h3>';

// 		$request 	= "get: /load/?date=:date";
// 		$method 	= "load::loadMockMeeting():";
// 		$this->request->parts	= array("racing-api/", "load");
// 		$params 	= $this->testMock2;

// 		try {
// 			$load = $this->request->di->create('mvcr\controller\Load');
// 			$post = $load->post($params);

// 			$this->meetingLoadId = $post;

// 			// $this->assertEquals('2099-01-01', $get[0]->date, $method);
// 			$this->pass($method, $request);


// 		} catch (\Exception $e) {
// 			$this->error($e);
// 		}
// 	}


// 	private function testPostMockDivs()
// 	{
// 		echo '<h3 style="margin: 10px 0 0 0">POST - Load DIVS</h3>';

// 		$request 	= "get: /divs/";
// 		$method 	= "load::loadMockDivs():";
// 		$this->request->parts	= array("racing-api/", "divs");
// 		$params1 	= $this->testDivsUbet;
// 		$params2	= $this->testDivsTab;
// 		$params1->meetingid = $this->testMeeting;
// 		$params2->meetingid = $this->testMeeting;


// 		try {
// 			$divs = $this->request->di->create('mvcr\controller\Divs');
// 			$this->ubetDivsId = $post = $divs->post($params1);
// 			$this->tabDivsId = $post = $divs->post($params2);
// 			// $this->pass($method, $request);

// 		} catch (\Exception $e) {
// 			$this->error($e);
// 		}
// 	}

// 	private function testDeleteMockDivs()
// 	{
// 		echo '<h3 style="margin: 10px 0 0 0">DELETE - DIVS</h3>';

// 		$request 	= "get: /divs/";
// 		$method 	= "load::deleteByMeeting():";
// 		$this->request->parts	= array("racing-api/", "divs", "", $this->testMeeting);
// 		$params 	= [];


// 		try {
// 			$divs = $this->request->di->create('mvcr\controller\Divs');
// 			$delete = $divs->delete();

// 		} catch (\Exception $e) {
// 			$this->error($e);
// 		}
// 	}

// 	private function testMatchTrackForDivs()
// 	{
// 		echo '<h3 style="margin: 10px 0 0 0">PUT - RACE/DIVS</h3>';

// 		$request 	= "get: /race/";
// 		$method 	= "race::confirmTrackMatch():";
// 		$this->request->parts	= array("racing-api/", "race");
// 		$params1 	= ['matchDiv' => $this->ubetDivsId->id];
// 		$params2 	= ['matchDiv' => $this->tabDivsId->id];

// 		try {
// 			$race = $this->request->di->create('mvcr\controller\Race');
// 			$put = $race->Put($params1);
// 			$put = $race->Put($params2);

// 		} catch (\Exception $e) {
// 			$this->error($e);
// 		}
// 	}



// /**

// 	LOAD

// */


//     public function testGetLoad()
//     {
// 		echo '<h3 style="margin: 10px 0 0 0">GET - Load</h3>';

// 		$request 	= "get: /load/?date=:date";
// 		$method 	= "load::getLoadByDate():";
// 		$this->request->parts	= array("racing-api/", "load");
// 		$params 	= array("date" => "2099-01-01");

// 		try {
// 			$load = $this->request->di->create('mvcr\controller\Load');
// 			$get = $load->get($params);
// 			$this->assertEquals('2099-01-01', $get[0]->date, $method);
// 			$this->pass($method, $request);


// 		} catch (\Exception $e) {
// 			$this->error($e);
// 		}
// 	}




//     public function testPostLoad()
//     {
// 		echo '<h3 style="margin: 10px 0 0 0">POST - Load</h3>';

// 		$request 	= "post: /load/:load";
// 		$method 	= "load::loadRunnerData():";
// 		$this->request->parts	= array("racing-api/", "load", $this->meetingLoadId);

// 		try {
// 			$load = $this->request->di->create('mvcr\controller\Load');
// 			$post = $load->post(['feed'=>'race']);
// 			$this->assertRegExp('/[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}/', $post);
// 			$this->testMeeting = $post;
// 			$this->pass($method, $request);


// 		} catch (\Exception $e) {
// 			$this->error($e);
// 			$this->testDeleteMeeting();
// 		}
// 	}


//     public function testPutLoad()
//     {
// 		echo '<h3 style="margin: 10px 0 0 0">PUT - Load</h3>';

// 		$request 	= "put: /load/:load";
// 		$method 	= "load::reloadRunnerData():";
// 		$this->request->parts	= array("racing-api/", "load", $this->meetingLoadId);

// 		try {
// 			$load = $this->request->di->create('mvcr\controller\Load');
// 			$put = $load->put(['Runners'=>true, 'Market'=>true, "Distance" => true]);
// 		} catch (\Exception $e) {
// 			$this->testDeleteMeeting();
// 			$this->error($e);
// 		}

// 	}




//     public function testDeleteLoad()
//     {
// 		echo '<h3 style="margin: 10px 0 0 0">DELETE - Load</h3>';

// 		$request 	= "delete: /load/:load";
// 		$method 	= "load::loadRunnerData():";
// 		$this->request->parts	= array("racing-api/", "load", $this->meetingLoadId);

// 		try {
// 			$load = $this->request->di->create('mvcr\controller\Load');
// 			$delete = $load->delete();
// 			$this->assertEquals(1, $delete, $method);
// 			$this->pass($method, $request);


// 		} catch (\Exception $e) {
// 			$this->testDeleteMeeting();
// 			$this->error($e);
// 		}
// 	}



// /**

// 	MEETINGS

// */

//     public function testPutMeeting()
//     {
// 		echo '<h3 style="margin: 10px 0 0 0">PUT - Meeting</h3>';
// 		echo '<h4 style="margin: 5px 0 0 40px; color:blue">Disable meeting</h4>';

// 		$request 	= "put: /meeting/:uuid";
// 		$method 	= "meeting::updateMeeting():";
// 		$this->request->parts	= array("racing-api/", "meeting", $this->testMeeting);
// 		$params 	= array("disabled" => "false");

// 		try {
// 			$meeting = $this->request->di->create('mvcr\controller\Meeting');
// 			$put = $meeting->put($params);
// 			$this->assertEquals(1, $put, $method);
// 			$this->pass($method, $request);


// 		} catch (\Exception $e) {
// 			$this->error($e);
// 		}

// 		try {
// 			echo '<h4 style="margin: 5px 0 0 40px; color:blue">Import feed (from client)</h4>';

// 			$this->request->parts	= array("racing-api/", "meeting", $this->testMeeting);

// 			$meeting = $this->request->di->create('mvcr\controller\Meeting');

// 			$params = array("type" => "market", "data" => $this->testImportData, "feed" => "feed");

// 			$put = $meeting->put($params);
// 			$this->assertEquals(1, $put, $method);
// 			$this->pass($method, $request);

// 			// check it actually updated horse
// 			$this->request->parts	= array("racing-api/", "runner", $this->testRunner);

// 			try {
// 				$runner = $this->request->di->create('mvcr\controller\Runner');
// 				$get = $runner->get([]);

// 				$this->assertEquals("137.00", $get[0]->market, $method);
// 				$this->pass($method, $request);
// 			} catch (\Exception $e) {
// 				$this->error($e);
// 			}

// 		} catch (\Exception $e) {
// 			$this->testDeleteMeeting();
// 			$this->error($e);
// 		}


// 		echo '<h4 style="margin: 5px 0 0 40px; color:orange">Sync & unsync</h4>';

// 	}



//     public function testGetTestMeeting1()
//     {
// 		echo '<h3 style="margin: 10px 0 0 0">GET - Meetings</h3>';

// 		$request 	= "get: /meeting/:uuid";
// 		$method 	= "meeting::getMeetingById():";
// 		$this->request->parts	= array("racing-api/", "meeting", $this->testMeeting);

// 		try {
// 			$meeting = $this->request->di->create('mvcr\controller\Meeting');
// 			$get = $meeting->get([]);
// 			// $this->assertEquals('loaded', $get->status[0]->status, $method);
// 			// $this->assertEquals('loaded', $get->status[1]->status, $method);
//             $this->assertEquals($this->testMeeting, $get->id, $method);
// 			$this->pass($method, $request);
// 			//

// 		} catch (\Exception $e) {
// 			$this->error($e);
// 		}
// 	}


//     public function testGetTestMeeting2()
//     {
// 		echo '<h3 style="margin: 10px 0 0 0">GET - Meeting after redraw</h3>';

// 		$request 	= "get: /meeting/:uuid";
// 		$method 	= "meeting::getMeetingById():";
// 		$this->request->parts	= array("racing-api/", "meeting", $this->testMeeting);

// 		try {
// 			$meeting = $this->request->di->create('mvcr\controller\Meeting');
// 			$get = $meeting->get([]);
// 			// $this->assertEquals('loaded', $get->status[0]->status, $method);
// 			// $this->assertEquals('loaded', $get->status[1]->status, $method);
//             $this->assertEquals($this->testMeeting, $get->id, $method);
// 			$this->pass($method, $request);
// 			//

// 		} catch (\Exception $e) {
// 			$this->error($e);
// 		}
// 	}


//     public function testGetMeetingByDate()
//     {

// 		$request 	= "get: /meeting/?date=:date";
// 		$method 	= "meeting::getMeetingByDate():";
// 		$this->request->parts	= array("racing-api/", "meeting");
// 		$params 	= array('date'=> "2099-01-01");

// 		try {
// 			$meeting = $this->request->di->create('mvcr\controller\Meeting');
// 			$get = $meeting->get($params);
// 			$this->assertEquals($this->testMeeting, $get[0]->id, $method);
// 			$this->pass($method, $request);


// 		} catch (\Exception $e) {
// 			$this->error($e);
// 		}



// 		$request 	= "get: /meeting/?date=:date&data=all";
// 		$method 	= "meeting::getMeetingByDate_allData():";
// 		$this->request->parts	= array("racing-api/", "meeting");

// 		$params 	= array('date'=> "2099-01-01", "data" => "all");

// 		try {
// 			$meeting = $this->request->di->create('mvcr\controller\Meeting');
// 			$get = $meeting->get($params);

// 			$this->assertEquals($this->testMeeting, $get[0]->id, $method);
// 			$this->pass($method, $request);


// 		} catch (\Exception $e) {
// 			$this->error($e);
// 		}
// 	}

//     public function testGetMeetingByRaces_allRaces()
//     {
// 		$request 	= "get: /meeting/?data=races";
// 		$method 	= "meeting::getMeetingById_allRaces():";
// 		$this->request->parts	= array("racing-api/", "meeting", $this->testMeeting);
// 		$params 	= array('data' => "races");

// 		try {
// 			$meeting = $this->request->di->create('mvcr\controller\Meeting');
// 			$get = $meeting->get($params);
// 			$this->assertEquals('Race 3 mosey', $get->races[2]->name, $method);
// 			$this->testRace1 = $get->races[0]->id;
// 			$this->testRace2 = $get->races[1]->id;
// 			$this->testRace3 = $get->races[2]->id;
// 			$this->testRace4 = $get->races[3]->id;
// 			$this->testRace5 = $get->races[4]->id;
// 			$this->pass($method, $request);


// 		} catch (\Exception $e) {
// 			$this->error($e);
// 		}

// 	}

//     public function testGetMeetingByRaces_allData()
//     {
// 		$request 	= "get: /meeting/:uuid/?data=all";
// 		$method 	= "meeting::getMeetingById_AllData():";
// 		$this->request->parts	= array("racing-api/", "meeting", $this->testMeeting);
// 		$params 	= array('data' => "all");

// 		try {
// 			$meeting = $this->request->di->create('mvcr\controller\Meeting');
// 			$get = $meeting->get($params);
// 			$this->assertEquals('Howdee Doodee', $get->races[0]->runners[3]->name, $method);

// 			$this->assertEquals('WA', $get->state, $method);

// 			// deprecating: favouritism no longer set by API (c50ff9b283830206867886f9862b813729170fee)
// 			// $this->assertEquals('2.35', $get->races[0]->favourite, $method);

// 			$this->assertEquals('2099-01-01 12:00:00', $get->races[0]->timezones->local, $method);
// 			$this->assertEquals('2099-01-01 14:30:00', $get->races[0]->timezones->sa, $method);


// 			$this->testRunner 	= $get->races[0]->runners[2]->id;
// 			$this->testRunner2 	= $get->races[0]->runners[3]->id;

// 			$this->testJockey 	= $get->races[0]->runners[2]->jockeyid;
// 			$this->testJockey2 	= $get->races[0]->runners[3]->jockeyid;

// 			$this->testTrainer 	= $get->races[0]->runners[2]->trainerid;
// 			$this->testTrainer2 = $get->races[0]->runners[3]->trainerid;

// 			$this->pass($method, $request);

// 		} catch (\Exception $e) {
// 			$this->error($e);
// 		}

//     }

//     public function testGetMeetingSilks()
//     {

// 		echo '<h3 style="margin: 10px 0 0 0">GET - Silks</h3>';
// 		$request 	= "get: /meeting/:uuid?data=silks_req&races=:num";
// 		$method	 	= "meeting::getMeetingById_Silks():";
// 		$this->request->parts	= array("racing-api/", "meeting", $this->testMeeting);
// 		$params 	= array('data' => "silks_req", 'races' => '1');

// 		try {
// 			$meeting = $this->request->di->create('mvcr\controller\Meeting');
// 			$get = $meeting->get($params);


// 			$this->assertEquals('Ironic Hipster Sweater With Coffee Stains', $get->races[0]->runners[0]->livery, $method);

// 			$this->pass($method, $request);


// 		} catch (\Exception $e) {
// 			$this->error($e);
// 		}
// 	}



//     public function testDeleteMeeting()
//     {
// 		echo '<h3 style="margin: 10px 0 0 0">DELETE - Meeting</h3>';
// 		$request = "delete: /meeting/:uuid";
// 		$method = "meeting::deleteMeeting():";
// 		$this->request->parts	= array("racing-api/", "meeting", $this->testMeeting);

// 		// try {
// 			$meeting = $this->request->di->create('mvcr\controller\Meeting');
// 			$delete = $meeting->delete();
// 			$this->assertEquals(1, $delete, $method);
// 			$this->pass($method, $request);


// 		// } catch (\Exception $e) {
// 		// 	$this->error($e);
// 		// }
// 	}



// /**

// 	PORTAL

// */
//     public function testGetMeeting_withTipsPanel()
//     {
// 		echo '<h3 style="margin: 10px 0 0 0">GET - Tips Panel</h3>';
// 		$request 	= "get: /meeting/:uuid/?data=races&portal=true&panel=:uuid&poll=:num";
// 		$method 	= "meeting::getMeetingById_portal():";
// 		$this->request->parts	= array("racing-api/", "meeting", $this->testMeeting);

// 		try {  // call for external portal clients

// 			$this->request->auth_user = $this->testAuth_news;


// 			$meeting = $this->request->di->create('mvcr\controller\Meeting');
// 			$this->request->auth_user = $this->cacheRequest->auth_user;

// 			// logThis($this->testPanel);
// 			$get = $meeting->get(['data' => 'races', 'portal' => true, 'panel'=>$this->testPanel, 'poll'=>5]); //'data'=>'all'  "group" => PAGEMASTERS, "user"=>'da2a87e1-886d-4b75-8b42-2449a6cc6e14'

// 			$this->assertEquals('testuserA', $get->panel->panel->tipsters[0]->tipster, $method);
// 			$this->assertEquals('Big Horse', $get->panel->panel->tipsters[0]->paneltips[0]['racetips'][0]['runnername'], $method);
// 			$this->assertEquals('1', $get->panel->panel->tipsters[0]->paneltips[0]['racetips'][0]['rank'], $method);

// 			$this->assertEquals('Hostile Force', $get->panel->panel->tipsters[0]->paneltips[0]['racetips'][1]['runnername'], $method);
// 			$this->assertEquals('2', $get->panel->panel->tipsters[0]->paneltips[0]['racetips'][1]['rank'], $method);

// 			$this->assertEquals('Howdee Doodee', $get->panel->panel->tipsters[0]->paneltips[0]['racetips'][2]['runnername'], $method);
// 			$this->assertEquals('3', $get->panel->panel->tipsters[0]->paneltips[0]['racetips'][2]['rank'], $method);

// 			$this->assertEquals('Slow Guy', $get->panel->panel->tipsters[0]->paneltips[0]['racetips'][3]['runnername'], $method);
// 			$this->assertEquals('4', $get->panel->panel->tipsters[0]->paneltips[0]['racetips'][3]['rank'], $method);

// 			$this->assertEquals('Big Horse', $get->panel->poll[0]['poll'][0]->selection, $method);
// 			$this->assertEquals('15', $get->panel->poll[0]['poll'][0]->total, $method);

// 			$this->assertEquals('Hostile Force', $get->panel->poll[0]['poll'][1]->selection, $method);
// 			$this->assertEquals('12', $get->panel->poll[0]['poll'][1]->total, $method);

// 			$this->assertEquals('Howdee Doodee', $get->panel->poll[0]['poll'][2]->selection, $method);
// 			$this->assertEquals('9', $get->panel->poll[0]['poll'][2]->total, $method);

// 			$this->assertEquals('Slow Guy', $get->panel->poll[0]['poll'][3]->selection, $method);
// 			$this->assertEquals('6', $get->panel->poll[0]['poll'][3]->total, $method);

// 			$this->assertEquals(4, count($get->panel->panel->tipsters[1]->paneltips[0]['racetips']));

// 			$this->pass($method, $request);


// 		} catch (\Exception $e) {
// 			$this->error($e);
// 		}
// 	}

//     public function testGetMeeting_withPortal()
//     {

// 		echo '<h3 style="margin: 10px 0 0 0">GET - Portal Data</h3>';

// 		$request 	= "get: /meeting/:uuid/?data=all&portal=true";
// 		$method 	= "meeting::getMeetingById_portal():";
// 		$this->request->parts	= array("racing-api/", "meeting", $this->testMeeting);

// 		$this->request->auth_user = $this->testAuth_news;


// 		try {  // call for external portal clients
// 			$meeting = $this->request->di->create('mvcr\controller\Meeting');
// 			$this->request->auth_user = $this->cacheRequest->auth_user;

// 			$get = $meeting->get(['data' => 'all', 'portal' => true]);


// 			$this->assertEquals('test News', $get->portal['bests'][0]['tipster'], $method);
// 			$this->assertEquals('Daily Telegraph', $get->races[0]->portal['comment']->publication, $method);
// 			$this->assertEquals('Race 1 test comment pagemasters', $get->races[0]->portal['comment']->comment, $method);
// 			$this->assertEquals('test_News', $get->races[0]->portal['tips'][1]->tipster, $method);
// 			$this->assertEquals(4, count($get->races[0]->portal['tips']), $method);

// 			$this->assertEquals('Daily Telegraph', $get->races[0]->runners[0]->portal->publication, $method);

// 			$this->pass($method, $request);


// 		} catch (\Exception $e) {
// 			$this->error($e);
// 		}



// 		$request 	= "get: /meeting/:uuid/?data=all&portal=true&publication=:pub&tipster=:tipster";
// 		$method 	= "meeting::getMeetingById():";
// 		$this->request->parts	= array("racing-api/", "meeting", $this->testMeeting);

// 		$this->request->auth_user = $this->testIndesign;


// 		try {  // call for external Indesign
// 			$meeting = $this->request->di->create('mvcr\controller\Meeting');
// 			$this->request->auth_user = $this->cacheRequest->auth_user;

// 			$get = $meeting->get(['data' => 'all', 'portal' => true, "group" => NEWS, "user" => $this->testAuth_news->userid]); //'data'=>'all'  "group" => PAGEMASTERS, "user"=>'da2a87e1-886d-4b75-8b42-2449a6cc6e14'

// 			$this->assertEquals('test News', $get->portal['bests'][0]['tipster'], $method);

// 			$this->assertEquals('Daily Telegraph', $get->races[0]->portal['comment']->publication, $method);
// 			$this->assertEquals('Race 1 test comment pagemasters', $get->races[0]->portal['comment']->comment, $method);
// 			$this->assertEquals('test_News', $get->races[0]->portal['tips'][1]->tipster, $method);

// 			$this->assertEquals('Daily Telegraph', $get->races[0]->runners[0]->portal->publication, $method);
// 			$this->assertEquals(4, count($get->races[0]->portal['tips']), $method);

// 			$this->pass($method, $request);


// 		} catch (\Exception $e) {
// 			$this->error($e);
// 		}


// 		$request 	= "get: /meeting/:uuid/?data=all&portal=true&publication=:pub";
// 		$method 	= "meeting::getMeetingById():";
// 		$this->request->parts	= array("racing-api/", "meeting", $this->testMeeting);

// 		$this->request->auth_user = $this->testLee;


// 		try {  // call for external Indesign
// 			$meeting = $this->request->di->create('mvcr\controller\Meeting');
// 			$this->request->auth_user = $this->cacheRequest->auth_user;

// 			$get = $meeting->get(['data' => 'all', 'portal' => true, "group" => PAGEMASTERS]); //'data'=>'all'  "group" => PAGEMASTERS, "user"=>'da2a87e1-886d-4b75-8b42-2449a6cc6e14'


// 			$this->assertEquals('Lee Neenan', $get->portal['bests'][0]['tipster'], $method);

// 			$this->assertEquals('Pagemasters', $get->races[0]->portal['comment']->publication, $method);
// 			$this->assertEquals('Race 1 test comment pagemasters', $get->races[0]->portal['comment']->comment, $method);
// 			$this->assertEquals('Lee_Neenan', $get->races[0]->portal['tips'][1]->tipster, $method);

// 			$this->assertEquals('Pagemasters', $get->races[0]->runners[0]->portal->publication, $method);
// 			$this->assertEquals(4, count($get->races[0]->portal['tips']), $method);

// 			$this->pass($method, $request);


// 		} catch (\Exception $e) {
// 			$this->error($e);
// 		}


// 		$request 	= "get: /meeting/:uuid/?data=all&portal=true&publication=:pub&tipster=:tipster";
// 		$method 	= "meeting::getMeetingById():";
// 		$this->request->parts	= array("racing-api/", "meeting", $this->testMeeting);

// 		$this->request->auth_user = $this->testLee;


// 		try {  // call for external Indesign
// 			$meeting = $this->request->di->create('mvcr\controller\Meeting');
// 			$this->request->auth_user = $this->cacheRequest->auth_user;

// 			$get = $meeting->get(['data' => 'all', 'portal' => true, "group" => PAGEMASTERS, "user"=> $this->testSteve->userid]); //'data'=>'all'  "group" => PAGEMASTERS, "user"=>'da2a87e1-886d-4b75-8b42-2449a6cc6e14'

// 			$this->assertEquals('Steve Davison', $get->portal['bests'][0]['tipster'], $method);

// 			$this->assertEquals('Pagemasters', $get->races[0]->portal['comment']->publication, $method);
// 			$this->assertEquals('Race 1 test comment pagemasters', $get->races[0]->portal['comment']->comment, $method);
// 			$this->assertEquals('Steve_Davison', $get->races[0]->portal['tips'][1]->tipster, $method);

// 			$this->assertEquals('Pagemasters', $get->races[0]->runners[0]->portal->publication, $method);
// 			$this->assertEquals(4, count($get->races[0]->portal['tips']), $method);

// 			$this->pass($method, $request);


// 		} catch (\Exception $e) {
// 			$this->error($e);
// 		}



// 		$request 	= "get: /meeting/:uuid/?data=portal&portal=all";
// 		$method 	= "meeting::getMeetingById():";
// 		$this->request->parts	= array("racing-api/", "meeting", $this->testMeeting);

// 		$this->request->auth_user = $this->testLee;

// 		try {  // call for external Indesign
// 			$meeting = $this->request->di->create('mvcr\controller\Meeting');
// 			$this->request->auth_user = $this->cacheRequest->auth_user;

// 			$get = $meeting->get(['data' => 'portal', 'portal' => 'all']); //'data'=>'all'  "group" => PAGEMASTERS, "user"=>'da2a87e1-886d-4b75-8b42-2449a6cc6e14'
// 			$this->assertArrayHasKey('Pagemasters', $get->portal, $method);
// 			$this->assertArrayHasKey('Lee_Neenan', $get->portal['Pagemasters'], $method);
// 			$this->assertArrayHasKey('Steve_Davison', $get->portal['Pagemasters'], $method);
// 			$this->assertEquals(3, count($get->portal['Pagemasters']['Lee_Neenan']['bests']), $method);
// 			$this->assertEquals(3, count($get->portal['Pagemasters']['Steve_Davison']['bests']), $method);

// 			$this->assertArrayHasKey('Daily_Telegraph', $get->portal, $method);
// 			$this->assertArrayHasKey('test_News', $get->portal['Daily_Telegraph'], $method);
// 			$this->assertEquals(3, count($get->portal['Daily_Telegraph']['test_News']['bests']), $method);

// 			$this->assertArrayHasKey('Pagemasters', $get->races[0]->portal, $method);
// 			$this->assertArrayHasKey('Lee_Neenan', $get->races[0]->portal['Pagemasters']['tips'], $method);
// 			$this->assertArrayHasKey('Steve_Davison', $get->races[0]->portal['Pagemasters']['tips'], $method);
// 			$this->assertEquals(4, count($get->races[0]->portal['Pagemasters']['tips']['Lee_Neenan']), $method);
// 			$this->assertEquals(4, count($get->races[0]->portal['Pagemasters']['tips']['Steve_Davison']), $method);

// 			$this->assertArrayHasKey('Pagemasters', $get->races[0]->portal, $method);
// 			$this->assertArrayHasKey('test_News', $get->races[0]->portal['Daily_Telegraph']['tips'], $method);
// 			$this->assertEquals(4, count($get->races[0]->portal['Daily_Telegraph']['tips']['test_News']), $method);

// 			pprint($get->races[0]->runners[0]->portal);

// 			$this->assertArrayHasKey('Pagemasters', $get->races[0]->runners[0]->portal, $method);
// 			$this->assertArrayHasKey('Daily_Telegraph', $get->races[0]->runners[0]->portal, $method);

// 			$this->pass($method, $request);


// 		} catch (\Exception $e) {
// 			$this->error($e);
// 		}


// 		$request 	= "get: /meeting/:uuid/?data=portal&portal=all&publication=:pub";
// 		$method 	= "meeting::getMeetingById():";
// 		$this->request->parts	= array("racing-api/", "meeting", $this->testMeeting);

// 		$this->request->auth_user = $this->testLee;

// 		try {  // call for external Indesign
// 			$meeting = $this->request->di->create('mvcr\controller\Meeting');
// 			$this->request->auth_user = $this->cacheRequest->auth_user;

// 			$get = $meeting->get(['data' => 'portal', 'portal' => 'all', "group" => PAGEMASTERS]); //'data'=>'all'  "group" => PAGEMASTERS, "user"=>'da2a87e1-886d-4b75-8b42-2449a6cc6e14'

// 			$this->assertArrayHasKey('Pagemasters', $get->portal, $method);
// 			$this->assertArrayHasKey('Lee_Neenan', $get->portal['Pagemasters'], $method);
// 			$this->assertArrayHasKey('Steve_Davison', $get->portal['Pagemasters'], $method);
// 			$this->assertEquals(3, count($get->portal['Pagemasters']['Lee_Neenan']['bests']), $method);
// 			$this->assertEquals(3, count($get->portal['Pagemasters']['Steve_Davison']['bests']), $method);


// 			$this->assertArrayHasKey('Pagemasters', $get->races[0]->portal, $method);
// 			$this->assertArrayHasKey('Lee_Neenan', $get->races[0]->portal['Pagemasters']['tips'], $method);
// 			$this->assertArrayHasKey('Steve_Davison', $get->races[0]->portal['Pagemasters']['tips'], $method);
// 			$this->assertEquals(4, count($get->races[0]->portal['Pagemasters']['tips']['Lee_Neenan']), $method);
// 			$this->assertEquals(4, count($get->races[0]->portal['Pagemasters']['tips']['Steve_Davison']), $method);

// 			$this->assertArrayHasKey('Pagemasters', $get->races[0]->portal, $method);
// 			$this->assertArrayHasKey('Pagemasters', $get->races[0]->runners[0]->portal, $method);

// 			$this->pass($method, $request);


// 		} catch (\Exception $e) {
// 			$this->error($e);
// 		}

// 		$request 	= "get: /meeting/:uuid/?data=portal&portal=all&publication=:pub&tipster=:tipster";
// 		$method 	= "meeting::getMeetingById():";
// 		$this->request->parts	= array("racing-api/", "meeting", $this->testMeeting);

// 		$this->request->auth_user = $this->testLee;


// 		try {  // call for external Indesign
// 			$meeting = $this->request->di->create('mvcr\controller\Meeting');
// 			$this->request->auth_user = $this->cacheRequest->auth_user;

// 			$get = $meeting->get(['data' => 'portal', 'portal' => 'all', "group" => PAGEMASTERS, "user"=>$this->testLee->userid]); //'data'=>'all'  "group" => PAGEMASTERS,

// 			$this->assertArrayHasKey('Pagemasters', $get->portal, $method);
// 			$this->assertArrayHasKey('Lee_Neenan', $get->portal['Pagemasters'], $method);
// 			$this->assertArrayNotHasKey('Steve_Davison', $get->portal['Pagemasters'], $method);
// 			$this->assertEquals(3, count($get->portal['Pagemasters']['Lee_Neenan']['bests']), $method);



// 			$this->assertArrayHasKey('Pagemasters', $get->races[0]->portal, $method);
// 			$this->assertArrayHasKey('Lee_Neenan', $get->races[0]->portal['Pagemasters']['tips'], $method);
// 			$this->assertArrayNotHasKey('Steve_Davison', $get->races[0]->portal['Pagemasters']['tips'], $method);
// 			$this->assertEquals(4, count($get->races[0]->portal['Pagemasters']['tips']['Lee_Neenan']), $method);

// 			$this->assertArrayHasKey('Pagemasters', $get->races[0]->portal, $method);
// 			$this->assertArrayHasKey('Pagemasters', $get->races[0]->runners[0]->portal, $method);

// 			$this->pass($method, $request);


// 		} catch (\Exception $e) {
// 			$this->error($e);
// 		}

// 		// check that scratchings are properly removed from portal tips & other horses promoted
// 		try {  // call for external portal clients
// 			$this->request->parts	= array("racing-api/", "meeting", $this->testMeeting);
// 			$this->request->auth_user = $this->testAuth_news;
// 			$meeting = $this->request->di->create('mvcr\controller\Meeting');
// 			$this->request->auth_user = $this->cacheRequest->auth_user;

// 			$this->request->parts	= array("racing-api/", "runner", $this->testRunner2);
// 			$runner = $this->request->di->create('mvcr\controller\Runner');
// 			$horse = $runner->put(array("scratched"=>true));

// 			$get = $meeting->get(['data' => 'all', 'portal' => true]);

// 			$this->assertEquals(3, count($get->races[0]->portal['tips']), $method);
// 			$this->assertEquals('Slow Guy', $get->races[0]->portal['tips'][2]->name, $method);

// 			$this->pass($method, $request);

// 		} catch (\Exception $e) {
// 			$this->error($e);
// 		}

// 	}



// 	public function testGetMeetingforQLDGuide()
// 	{
// 		echo '<h3 style="margin: 10px 0 0 0">GET - QLD GUIDE</h3>';

// 		$request 	= "get: /meeting";
// 		$method 	= "meeting::getMeetingById():";
// 		$this->request->parts	= array("racing-api/", "meeting", $this->testMeeting);
// // meeting/24036649-74f2-42a1-8ce7-e94172c75d26?data=all&poll=4&sort=&tz=Australia/Brisbane&form=true&runs=2&races=1,2,3,4,5,6,7,8&portal=true&publication=452eadb1-9134-42a7-80f8-76f466181598&tipster=ca7f442c-1994-4fc7-8285-ae402f30c9ed
// 		try {
// 			$meeting = $this->request->di->create('mvcr\controller\Meeting');


// 			$get = $meeting->get(['tz' => 'Australia/Brisbane', 'data'=>'all', 'poll'=>'4']);

// 			$this->assertEquals('2099-01-01 14:00:00', $get->races[0]->racetime, $method);
// 			$this->pass($method, $request);

// 		} catch (\Exception $e) {
// 			$this->error($e);
// 		}


// 	}




//     public function testGetMeeting_withPortal_redraw()
//     {

// 		echo '<h3 style="margin: 10px 0 0 0">GET - Portal Data redraw</h3>';

// 		$request 	= "get: /meeting/:uuid/?data=all&portal=true";
// 		$method 	= "meeting::getMeetingById_portal():";
// 		$this->request->parts	= array("racing-api/", "meeting", $this->testMeeting);


// 		$this->request->auth_user = $this->testAuth_news;


// 		try {  // call for external portal clients

// 			$meeting = $this->request->di->create('mvcr\controller\Meeting');
// 			$this->request->auth_user = $this->cacheRequest->auth_user;

// 			$get = $meeting->get(['data' => 'all', 'portal' => true]); //'data'=>'all'  "group" => PAGEMASTERS, "user"=>'da2a87e1-886d-4b75-8b42-2449a6cc6e14'
// 			$this->assertEquals('Big Bishop', 	 $get->races[1]->portal['tips'][1]->name, $method);
// 			$this->assertEquals('2', 		  	 $get->races[1]->portal['tips'][1]->rank, $method);
// 			$this->assertEquals('4', 		  	 $get->races[1]->portal['tips'][1]->number, $method);
// 			// exit;

// 			$this->assertEquals('Howdee Bishop', $get->races[1]->portal['tips'][3]->name, $method);
// 			$this->assertEquals('4', 		  	 $get->races[1]->portal['tips'][3]->rank, $method);
// 			$this->assertEquals('2', 		  	 $get->races[1]->portal['tips'][3]->number, $method);


// 			$runners = array_values(array_filter($get->races[1]->runners, function($runner) {
// 				return is_null($runner->load_discrepancy);
// 			}));

// 			$this->assertEquals('Howdee Bishop', $runners[1]->name, $method);
// 			$this->assertEquals('4',	    	 $runners[1]->portal->tip, $method);
// 			$this->assertEquals('7.00', 		 $runners[1]->market, $method);

// 			$this->assertEquals('Big Bishop', 	 $runners[3]->name, $method);
// 			$this->assertEquals('2', 		  	 $runners[3]->portal->tip, $method);
// 			$this->assertEquals('322', 		 	 $runners[3]->market, $method);

// 			$this->pass($method, $request);
// 			// exit;


// 		} catch (\Exception $e) {
// 			$this->error($e);
// 			// exit;
// 		}

// 	}











// /**

// 	RACES

// */


//     public function testGetRaces()
//     {
// 		echo '<h3 style="margin: 10px 0 0 0">GET - Races</h3>';
// 		$request 	= "get: /race/:uuid";
// 		$method	 	= "race::getRaceById():";
// 		$this->request->parts	= array("racing-api/", "race", $this->testRace3);

// 		try {
// 			$race = $this->request->di->create('mvcr\controller\Race');
// 			$get = $race->get([]);
// 			$this->assertEquals('Race 3 mosey', $get[0]->name, $method);
// 			$this->pass($method, $request);


// 		} catch (\Exception $e) {
// 			$this->error($e);
// 		}





// 		$request 	= "get: /race/?meeting=:uuid";
// 		$method 	= "race::getMeetingRaces():";
// 		$this->request->parts	= array("racing-api/", "race", "");
// 		$params 	= array("meeting"=> $this->testMeeting);

// 		try {
// 			$race = $this->request->di->create('mvcr\controller\Race');
// 			$get = $race->get($params);

// 			$this->assertEquals('Race 3 mosey', $get[2]->name, $method);

// 			$this->assertEquals('2099-01-01 12:00:00', $get[0]->racetime, $method);
// 			$this->assertEquals('2099-01-01 15:00:00', $get[0]->vic_time, $method);
// 			$this->assertEquals('2099-01-01 17:00:00', $get[0]->nz_time, $method);
// 			$this->assertEquals('2099-01-01 12:00:00', $get[0]->hk_time, $method);
// 			$this->pass($method, $request);


// 		} catch (\Exception $e) {
// 			$this->error($e);
// 		}
//     }



//     public function testPutRaces()
//     {
// 		echo '<h3 style="margin: 10px 0 0 0">PUT - Races</h3>';
// 		$request 	= "put: /race/:uuid";
// 		$method 	= "race::updateRace():";
// 		$this->request->parts	= array("racing-api/", "race", $this->testRace3);
// 		$params 	= array(	"name2"	=>"Pagemasters test",
// 								"name"	=>"New Race 2 Name",
// 								"distance" => '3',
// 								"racetime" => '2099-01-01 13:27:00'
// 								);

// 		try {
// 			$race = $this->request->di->create('mvcr\controller\Race');
// 			$put = $race->put($params);
// 			$this->assertEquals(1, $put, $method);
// 			$this->pass($method, $request);


// 		} catch (\Exception $e) {
// 			$this->error($e);
// 		}
// 	}









// /**

// 	RUNNERS

// */

//     public function testGetRunner()
//     {

// 		echo '<h3 style="margin: 10px 0 0 0">GET - Runners</h3>';
// 		$request 	= "get: /runner/:uuid";
// 		$method	 	= "runner::getRunnerById():";
// 		$this->request->parts	= array("racing-api/", "runner", $this->testRunner2);

// 		try {
// 			$runner = $this->request->di->create('mvcr\controller\Runner');
// 			$get = $runner->get([]);

// 			$this->assertEquals("Howdee Doodee", $get[0]->name, $method);

// 			$this->assertEquals("RA", $get[0]->type, $method);
// 			$this->pass($method, $request);


// 		} catch (\Exception $e) {
// 			$this->error($e);
// 		}
// 	}


//     public function testGetRunnersByRace1()
//     {
// 		$request 	= "get: /runner/?race=:uuid";
// 		$method 	= "runner::getRunnersByRace():";
// 		$this->request->parts	= array("racing-api/", "runner", '');
// 		$params 	= array("race"=> $this->testRace1);

// 		try {
// 			$runner = $this->request->di->create('mvcr\controller\Runner');
// 			$get = $runner->get($params);

// 			foreach($get as $horse) {
// 				$this->portalHorses[] = $horse->id;
// 			}

// 			$this->pass($method, $request);

// 		} catch (\Exception $e) {
// 			$this->error($e);
// 		}
// 	}


//     public function testGetRunnersByRace2()
//     {
// 		$request 	= "get: /runner/?race=:uuid";
// 		$method 	= "runner::getRunnersByRace():";
// 		$this->request->parts	= array("racing-api/", "runner", '');
// 		$params 	= array("race"=> $this->testRace2);

// 		try {
// 			$runner = $this->request->di->create('mvcr\controller\Runner');
// 			$get = $runner->get($params);
// 			foreach($get as $horse) {

// 				$this->portalHorses[] = $horse->id;
// 			}


// 		} catch (\Exception $e) {
// 			$this->error($e);
// 		}
// 	}

//     public function testGetRunnersByRace2_redraw()
//     {
// 		$request 	= "get: /runner/?race=:uuid";
// 		$method 	= "runner::getRunnersByRace():";
// 		$this->request->parts	= array("racing-api/", "runner", '');
// 		$params 	= array("race"=> $this->testRace2);

// 		try {
// 			$runner = $this->request->di->create('mvcr\controller\Runner');
// 			$get = $runner->get($params);


// 			// $this->assertEquals("Howdee Bishop", $get[1]->name, $method);
// 			// $this->assertEquals("2", $get[1]->number, $method);
// 			// $this->assertEquals("Big Bishop", $get[3]->name, $method);
// 			// $this->assertEquals("4", $get[3]->number, $method);
// 		} catch (\Exception $e) {
// 			$this->error($e);
// 		}
// 	}
//     public function testGetRunnersByRace3()
//     {
// 		$request 	= "get: /runner/?race=:uuid";
// 		$method 	= "runner::getRunnersByRace():";
// 		$this->request->parts	= array("racing-api/", "runner", '');
// 		$params 	= array("race"=> $this->testRace3);

// 		try {
// 			$runner = $this->request->di->create('mvcr\controller\Runner');
// 			$get = $runner->get($params);
// 			$this->assertEquals("Howdee Cardinal", $get[3]->name, $method);

// 			foreach($get as $horse) {
// 				$this->portalHorses[] = $horse->id;
// 			}

// 			$this->pass($method, $request);


// 		} catch (\Exception $e) {
// 			$this->error($e);
// 		}
// 	}

//     public function testGetRunnersByRace3_redraw()
//     {
// 		$request 	= "get: /runner/?race=:uuid";
// 		$method 	= "runner::getRunnersByRace():";
// 		$this->request->parts	= array("racing-api/", "runner", '');
// 		$params 	= array("race"=> $this->testRace3);

// 		try {
// 			$runner = $this->request->di->create('mvcr\controller\Runner');
// 			$get = $runner->get($params);

// 			$this->assertEquals("Howdee Cardinal", $get[3]->name, $method);

// 			// foreach($get as $horse) {
// 			// 	$this->portalHorses[] = $horse->id;
// 			// }

// 			$this->pass($method, $request);


// 		} catch (\Exception $e) {
// 			$this->error($e);
// 		}
// 	}

//     public function testGetRunnersByRace4()
//     {
// 		$request 	= "get: /runner/?race=:uuid";
// 		$method 	= "runner::getRunnersByRace():";
// 		$this->request->parts	= array("racing-api/", "runner", '');
// 		$params 	= array("race"=> $this->testRace4);

// 		try {
// 			$runner = $this->request->di->create('mvcr\controller\Runner');
// 			$get = $runner->get($params);

// 			$this->assertEquals("Ruthers Hadlow", $get[1]->name, $method);

// 			foreach($get as $horse) {
// 				$this->portalHorses[] = $horse->id;
// 			}

// 			$this->pass($method, $request);


// 		} catch (\Exception $e) {
// 			$this->error($e);
// 		}
// 	}

//     public function testGetRace5_redraw()
//     {
// 		$request 	= "get: /race/:uuid";
// 		$method 	= "runner::getRunnersByRace():";
// 		$this->request->parts	= array("racing-api/", "race", $this->testRace5);
// 		$params 	= array();

// 		try {
// 			$race = $this->request->di->create('mvcr\controller\Race');
// 			$get = $race->get($params);

// 			$this->assertEquals("12", $get[0]->distance, $method);
// 			$this->pass($method, $request);

// 		} catch (\Exception $e) {
// 			$this->error($e);
// 		}
// 	}

//     public function testGetRunnersByRace5_redraw()
//     {
// 		$request 	= "get: /runner/?race=:uuid";
// 		$method 	= "runner::getRunnersByRace():";
// 		$this->request->parts	= array("racing-api/", "runner", '');
// 		$params 	= array("race"=> $this->testRace5);

// 		try {
// 			$runner = $this->request->di->create('mvcr\controller\Runner');
// 			$get = $runner->get($params);
// 			$this->assertEquals("12.65", $get[2]->market, $method);
// 			$this->assertEquals("100", $get[0]->market, $method);
// 			$this->assertEquals("T O Benny", $get[0]->jockey, $method);
// 			$this->assertEquals($this->testJockey2, $get[0]->jockeyid, $method);
// 			$this->assertEquals("3", $get[0]->rating, $method);

// 			// foreach($get as $horse) {
// 			// 	$this->portalHorses[] = $horse->id;
// 			// }

// 			$this->pass($method, $request);


// 		} catch (\Exception $e) {
// 			$this->error($e);
// 		}
// 	}

//     public function testGetRunnersByMeeting()
//     {

// 		$request 	= "get: /runner/?meeting=:uuid";
// 		$method 	= "runner::getRunnersByMeeting():";
// 		$this->request->parts	= array("racing-api/", "runner", '');
// 		$params 	= array("meeting"=> $this->testMeeting);

// 		try {
// 			$runner = $this->request->di->create('mvcr\controller\Runner');
// 			$get = $runner->get($params);

// 			$this->assertEquals("Howdee Doodee", $get[3]->name, $method);
// 			$this->pass($method, $request);


// 		} catch (\Exception $e) {
// 			$this->error($e);
// 		}

// 	}


//     public function testGetRunnerWithForm()
//     {

// 		$request 	= "get: /runner/:uuid?form=true";
// 		$method 	= "runner::getRunnersById():";
// 		$this->request->parts	= array("racing-api/", "runner", $this->testRunner2);
// 		$params 	= array("form"=> "true");

// 		try {
// 			$runner = $this->request->di->create('mvcr\controller\Runner');
// 			$get = $runner->get($params);

// 			// LATEST FORM
// 			echo $get[0]->name;
// 			$this->assertEquals("Howdee Doodee", $get[0]->name, $method);
			
// 			echo $get[0]->latest_form[1]->jockey;
// 			$this->assertEquals("R Hutchings", $get[0]->latest_form[1]->jockey, $method);

// 			echo $get[0]->latest_form[1]->barrier;
// 			$this->assertEquals("11", $get[0]->latest_form[1]->barrier, $method);
			
// 			echo $get[0]->latest_form[1]->position;
// 			$this->assertEquals("3RD", $get[0]->latest_form[1]->position, $method);
			
// 			echo $get[0]->latest_form[1]->track;
// 			$this->assertEquals("Warwick Farm", $get[0]->latest_form[1]->track, $method);

// 			echo $get[0]->latest_form[1]->racetime.'!';
// 			$this->assertEquals("1:38.56", $get[0]->latest_form[1]->racetime, $method);
			
// 			echo $get[0]->latest_form[9]->date;
// 			$this->assertEquals("2015-09-12", $get[0]->latest_form[9]->date, $method);
// 			// Howdee Doodee
// 			// R Hutchings
// 			// 11
// 			// 3RD
// 			// Warwick Farm

// 			// BARRIER TRIAL
// 			echo $get[0]->latest_barrier[1]->position;
// 			$this->assertEquals("WON", $get[0]->latest_barrier[1]->position, $method);
			
// 			echo $get[0]->latest_barrier[1]->track;
// 			$this->assertEquals("Warwick", $get[0]->latest_barrier[1]->track, $method);
			
// 			echo $get[0]->latest_barrier[1]->racetime;
// 			$this->assertEquals("45.96 secs", $get[0]->latest_barrier[1]->racetime, $method);
			
// 			echo $get[0]->latest_barrier[2]->date;
// 			$this->assertEquals("2015-11-10", $get[0]->latest_barrier[2]->date, $method);


// 			$this->pass($method, $request);


// 		} catch (\Exception $e) {
// 			$this->error($e);
// 		}



// 		$request 	= "get: /runner/?race=:uuid&form=true";
// 		$method 	= "runner::getRunnersByRace():";
// 		$this->request->parts	= array("racing-api/", "runner", '');
// 		$params 	= array("race"=> $this->testRace3, "form"=> "true");

// 		try {
// 			$runner = $this->request->di->create('mvcr\controller\Runner');
// 			$get = $runner->get($params);

// 			$this->assertEquals("Howdee Cardinal", $get[3]->name, $method);
// 			$this->pass($method, $request);


// 		} catch (\Exception $e) {
// 			$this->error($e);
// 		}



// 		$request 	= "get: /runner/?meeting=:uuid&form=true";
// 		$method 	= "runner::getRunnersByMeeting():";
// 		$this->request->parts	= array("racing-api/", "runner", '');
// 		$params 	= array("meeting"=> $this->testMeeting, "form"=> "true");

// 		try {
// 			$runner = $this->request->di->create('mvcr\controller\Runner');
// 			$get = $runner->get($params);
// 			$this->assertEquals("Howdee Doodee", $get[3]->name, $method);
// 			$this->pass($method, $request);


// 		} catch (\Exception $e) {
// 			$this->error($e);
// 		}


// 	}




//     public function testPutRunners()
//     {

// 		echo '<h3 style="margin: 10px 0 0 0">PUT - Runners</h3>';
// 		$request 	= "put: /runner/:uuid";
// 		$method 	= "runner::updateRunner():";
// 		$this->request->parts	= array("racing-api/", "runner", $this->testRunner);
// 		$params 	= array("jockey"=>"Test Jockey",
// 							"trainer" => "NEW horse trainer",
// 							"comment" => "blah",
// 							"market" => "100",
// 							"weight"=>"10");

// 		try {
// 			$runner = $this->request->di->create('mvcr\controller\Runner');
// 			$put = $runner->put($params);
// 			$this->assertEquals(1, $put, $method);
// 			$this->pass($method, $request);


// 		} catch (\Exception $e) {
// 			$this->error($e);
// 		}


// 		echo '<h3 style="margin: 10px 0 0 0">PUT - Runner Tips & Scratchings</h3>';
// 		$request 	= "put: /runner/:uuid";
// 		$method 	= "runner::updateRunner():";

// 		$this->request->parts	= array("racing-api/", "runner", $this->testRunner);
// 		$request2 = clone $this->request;
// 		$request2->parts 	= array("racing-api/", "runner", $this->testRunner2);

// 		$params 	= array("tip"=>true);

// 		try {
// 			$runner = $this->request->di->create('mvcr\controller\Runner');
// 			$runner2 = $this->request->di->create('mvcr\controller\Runner', [$request2]);

// 			// tip first horse
// 			$get = $runner->get($params);
// 			$this->assertEquals(NULL, $get[0]->tip, $method); // first check horse has not been tipped
// 			$this->assertEquals(false, $get[0]->scratched, $method); // first check horse has not been scratched

// 			$put = $runner->put($params); // tip the first horse  tip = 1
// 			$this->assertEquals(1, $put, $method);

// 			$get = $runner->get($params);
// 			$this->assertEquals(1, $get[0]->tip, $method);  // check first horse is tipped as 1

// 			// tip second horse
// 			$get = $runner2->get($params);
// 			$this->assertEquals(NULL, $get[0]->tip, $method); // first check horse has not been tipped

// 			$put = $runner2->put($params);
// 			$this->assertEquals(1, $put, $method);

// 			$get = $runner2->get($params);
// 			$this->assertEquals(2, $get[0]->tip, $method); // tip increments as first horse is in same race as second horse

// 			// scratch first horse
// 			$put = $runner->put(array("scratched"=>true));
// 			$this->assertEquals(1, $put, $method);

// 			$get = $runner->get($params);
// 			$this->assertEquals(NULL, $get[0]->tip, $method); // tip resets to NULL on scratching
// 			$this->assertEquals(true, $get[0]->scratched, $method);

// 			// attempt to tip scratched first horse
// 			$put = $runner->put($params);
// 			$this->assertEquals(false, $put, $method);

// 			// check second horse -- tips should have escalated
// 			$get = $runner2->get($params);
// 			$this->assertEquals(1, $get[0]->tip, $method);

// 			// unscratch first horse
// 			$put = $runner->put(array("scratched"=>false));
// 			$this->assertEquals(1, $put, $method);

// 			$get = $runner->get($params);
// 			$this->assertEquals(NULL, $get[0]->tip, $method); // tip resets to NULL on scratching
// 			$this->assertEquals(NULL, $get[0]->scratched, $method);

// 			// attempt to tip  first horse
// 			$put = $runner->put($params);
// 			$this->assertEquals(true, $put, $method);

// 			$get = $runner->get($params);
// 			$this->assertEquals(2, $get[0]->tip, $method);

// 			$this->pass($method, $request);


// 		} catch (\Exception $e) {
// 			$this->error($e);
// 		}


// 	}


// /** JOCKEYS, TRAINERS **/

//     public function testGetJockey()
//     {

// 		echo '<h3 style="margin: 10px 0 0 0">GET - Jockeys</h3>';
// 		$request 	= "get: /jockey/:uuid";
// 		$method	 	= "jockey::getJockeyById():";
// 		$this->request->parts	= array("racing-api/", "jockey", $this->testJockey);

// 		try {
// 			$jockey = $this->request->di->create('mvcr\controller\Jockey');
// 			$get = $jockey->get([]);

// 			$this->assertEquals('Tazer O Hara', $get->fullname);

// 			$this->pass($method, $request);


// 		} catch (\Exception $e) {
// 			$this->error($e);
// 		}
// 	}

//     public function testUpdateRunnerJockey()
//     {

// 		echo '<h3 style="margin: 10px 0 0 0">PUT - Runner (Jockey)</h3>';
// 		$request 	= "put: /runner/:uuid";
// 		$method	 	= "runner::updateRunner():";
// 		$this->request->parts	= array("racing-api/", "runner", $this->testRunner2);

// 		try {
// 			$runner = $this->request->di->create('mvcr\controller\Runner');
// 			$put = $runner->put(array("jockey2"=>'Tazer O Benny'));
// 			$this->assertEquals(true, $put);

// 			$get = $runner->get([]);
// 			$this->assertEquals($this->testJockey2, $get[0]->jockeyid);


// 			$put = $runner->put(array("jockey2"=>'time is a flat circle'));
// 			$this->assertEquals(true, $put);

// 			$get = $runner->get([]);
// 			$this->assertEquals(NULL, $get[0]->jockeyid); // when updating to jockeys that don't exist, null jockeyid

// 			$this->pass($method, $request);


// 		} catch (\Exception $e) {
// 			$this->error($e);
// 		}
// 	}


//     public function testGetTrainer()
//     {

// 		echo '<h3 style="margin: 10px 0 0 0">GET - Trainers</h3>';
// 		$request 	= "get: /trainer/:uuid";
// 		$method	 	= "trainer::gettrainerById():";
// 		$this->request->parts	= array("racing-api/", "trainer", $this->testTrainer);

// 		try {
// 			$trainer = $this->request->di->create('mvcr\controller\Trainer');
// 			$get = $trainer->get([]);

// 			$this->assertEquals('Sir Minni Calcium', $get->fullname);

// 			$this->pass($method, $request);


// 		} catch (\Exception $e) {
// 			$this->error($e);
// 		}
// 	}

//     public function testUpdateRunnerTrainer()
//     {

// 		echo '<h3 style="margin: 10px 0 0 0">PUT - Runner (trainer)</h3>';
// 		$request 	= "put: /runner/:uuid";
// 		$method	 	= "runner::updateRunner():";
// 		$this->request->parts	= array("racing-api/", "runner", $this->testRunner2);

// 		try {
// 			$runner = $this->request->di->create('mvcr\controller\Runner');
// 			$put = $runner->put(array("trainer2"=>'Sir Targood Hue'));
// 			$this->assertEquals(true, $put);

// 			$get = $runner->get([]);
// 			$this->assertEquals($this->testTrainer2, $get[0]->trainerid);

// 			$put = $runner->put(array("trainer2"=>'time is a flat circle'));
// 			$this->assertEquals(true, $put);

// 			$get = $runner->get([]);

// 			$this->assertEquals(NULL, $get[0]->trainerid); // when updating to trainers that don't exist, null trainerid

// 			$this->pass($method, $request);


// 		} catch (\Exception $e) {
// 			$this->error($e);
// 		}
// 	}




// /**

// 	RUNNER MARKETS

// **/

//     public function testUpdateRunnerMarket()
//     {

// 		echo '<h3 style="margin: 10px 0 0 0">PUT - Runner (Market)</h3>';
// 		$request 	= "put: /runner/:uuid";
// 		$method	 	= "runner::updateRunner():";
// 		$this->request->parts	= array("racing-api/", "runner", $this->testRunner2);

// 		try {
// 			$runner = $this->request->di->create('mvcr\controller\Runner');

// 			// start with sanity check for markets
// 			$get = $runner->get([]);
// 			$this->assertEquals('3.0', $get[0]->market);		// from mock data, check base state is sane

// 			$put = $runner->put(array("market"=>'2'));			// update market to 2. do not specify supplier -- should use SUPPLIER_DEFAULT
// 			$this->assertEquals(true, $put);

// 			$get = $runner->get([]);
// 			$this->assertEquals('2.0', $get[0]->market);		// assert market is set

// 			$get = $runner->get(array("supplier"=>SUPPLIER_DEFAULT));
// 			$this->assertEquals('2.0', $get[0]->market);		// market is retrieved when supplier is explicitly passed

// 			$get = $runner->get(array("supplier"=>$this->testSupplier));
// 			$this->assertEquals('2.0', $get[0]->market);		// there is no market for testSupplier, so return default case

// 			// update market for UBET (d863242c-ae5f-4397-a5f5-2d804fd5b8a2)


// 			$put = $runner->put(array("market"=>'75', "supplier"=>$this->testSupplier));

// 			$this->assertEquals(true, $put);					// update market to 75 using testSupplier

// 			$get = $runner->get([]);
// 			$this->assertEquals('2.0', $get[0]->market);	// when not explicitly passing supplier, market defaults to SUPPLIER_DEFAULT, which should remain unchanged

// 			$get = $runner->get(array("supplier"=>$this->testSupplier));

// 			$this->assertEquals('75.0', $get[0]->market);		// when supplier is explicitly passed, return new value set above

// 			$this->pass($method, $request);


// 		} catch (\Exception $e) {
// 			$this->error($e);
// 		}
// 	}







// /**

// 	PORTAL

// */


//     public function testPostPortal()
//     {

// 		$this->race1data     = array(
// 	        "race"              => "1",
// 	        "meeting_best_1"    => $this->portalHorses[3],
// 	        "meeting_best_2"    => $this->portalHorses[1],
// 	        "meeting_best_3"    => $this->portalHorses[2],
// 	        "race_comment"      => "Race 1 test comment pagemasters",

// 	        "runnerid_1"        => $this->portalHorses[0],
// 	        "rating_1"          => "10",
// 	        "class_1"           => "U",
// 	        "market_1"          => "1.00",
// 	        "selection_1"       => "2",
// 	        "comment_1"         => "This horse might win",

// 	        "runnerid_2"        => $this->portalHorses[1],
// 	        "rating_2"          => "3",
// 	        "class_2"           => "S",
// 	        "market_2"          => "77",
// 	        "selection_2"       => "1",
// 	        "comment_2"         => "horse 2 comment",

// 	        "runnerid_3"        => $this->portalHorses[2],
// 	        "rating_3"          => "100",
// 	        "class_3"           => "D",
// 	        "market_3"          => "63.10",
// 	        "selection_3"       => "4",
// 	        "comment_3"         => "some comment for horse 3",

// 	        "runnerid_4"        => $this->portalHorses[3],
// 	        "rating_4"          => "67",
// 	        "class_4"           => "10",
// 	        "market_4"          => "2.00",
// 	        "selection_4"       => "3",
// 	        "comment_4"         => "This 4th horse should come third"
// 		);
// 		$this->race2data     = array(
//             "race"              => "2",
//             "meeting_best_1"    => $this->portalHorses[3],
//             "meeting_best_2"    => $this->portalHorses[1],
//             "meeting_best_3"    => $this->portalHorses[2],
//             "race_comment"      => "Race 2 test comment from steve",

//             "runnerid_1"        => $this->portalHorses[4],
//             "rating_1"          => "10",
//             "class_1"           => "U",
//             "market_1"          => "20.05",
//             "selection_1"       => "1",
//             "comment_1"         => "Bishop Wings might win",

//             "runnerid_2"        => $this->portalHorses[5],
//             "rating_2"          => "23",
//             "class_2"           => "S",
//             "market_2"          => "322",
//             "selection_2"       => "2",
//             "comment_2"         => "Big Bishop is also running",

//             "runnerid_3"        => $this->portalHorses[6],
//             "rating_3"          => "8",
//             "class_3"           => "S",
//             "market_3"          => "97.60",
//             "selection_3"       => "3",
//             "comment_3"         => "Slow Bishop won once",
//             "runnerid_4"        => $this->portalHorses[7],

//             "rating_4"          => "80",
//             "class_4"           => "10",
//             "market_4"          => "7.00",
//             "selection_4"       => "4",
//             "comment_4"         => "Howdee Bishop has never won"
// 		);
// 		$this->race3data     = array(
// 		    "race"              => "3",
// 		    "meeting_best_1"    => $this->portalHorses[3],
// 		    "meeting_best_2"    => $this->portalHorses[1],
// 		    "meeting_best_3"    => $this->portalHorses[2],
// 		    "race_comment"      => "Race 3 test comment",

// 		    "runnerid_1"        => $this->portalHorses[10],
// 		    "rating_1"          => "54",
// 		    "class_1"           => "D",
// 		    "market_1"          => "3.05",
// 		    "selection_1"       => "2",
// 		    "comment_1"         => "This horse might win",

// 		    "runnerid_2"        => $this->portalHorses[11],
// 		    "rating_2"          => "3",
// 		    "class_2"           => "S",
// 		    "market_2"          => "77",
// 		    "selection_2"       => "1",
// 		    "comment_2"         => "fds",

// 		    "runnerid_3"        => $this->portalHorses[9],
// 		    "rating_3"          => "100",
// 		    "class_3"           => "D",
// 		    "market_3"          => "63.10",
// 		    "selection_3"       => "4",
// 		    "comment_3"         => "some comment",

// 		    "runnerid_4"        => $this->portalHorses[8],
// 		    "rating_4"          => "67",
// 		    "class_4"           => "10",
// 		    "market_4"          => "2.00",
// 		    "selection_4"       => "3",
// 		    "comment_4"         => "This horse should come third"
// 		);

// 		$this->race4data     = array(
// 		    "race"              => "4",
// 		    "meeting_best_1"    => $this->portalHorses[3],
// 		    "meeting_best_2"    => $this->portalHorses[1],
// 		    "meeting_best_3"    => $this->portalHorses[2],
// 		    "race_comment"      => "Race 4 test comment NEWS",

// 		    "runnerid_1"        => $this->portalHorses[12],
// 		    "rating_1"          => "54",
// 		    "class_1"           => "D",
// 		    "market_1"          => "3.05",
// 		    "selection_1"       => "2",
// 		    "comment_1"         => "Horse 1 NEWS",

// 		    "runnerid_2"        => $this->portalHorses[13],
// 		    "rating_2"          => "3",
// 		    "class_2"           => "S",
// 		    "market_2"          => "77",
// 		    "selection_2"       => "1",
// 		    "comment_2"         => "Horse 2 NEWS",

// 		    "runnerid_3"        => $this->portalHorses[15],
// 		    "rating_3"          => "100",
// 		    "class_3"           => "D",
// 		    "market_3"          => "63.10",
// 		    "selection_3"       => "4",
// 		    "comment_3"         => "Horse 3 NEWS",

// 		    "runnerid_4"        => $this->portalHorses[14],
// 		    "rating_4"          => "67",
// 		    "class_4"           => "10",
// 		    "market_4"          => "2.00",
// 		    "selection_4"       => "3",
// 		    "comment_4"         => "Horse 4 NEWS"
// 		);

// 		echo '<h3 style="margin: 10px 0 0 0">POST - Portal</h3>';

// 		$request 	= "post: /portal/";
// 		$method 	= "portal::save():";
// 		$this->request->parts	= array("racing-api/", "portal", $this->testMeeting);

// 		$this->request->auth_user = (object) ['userid'=>$this->testUser2, 'groupid'=>$this->testGroup, 'access'=>'portal'];


// 		try {
// 			$portal = $this->request->di->create('mvcr\controller\Portal');
// 			$this->request->auth_user = $this->cacheRequest->auth_user;

// 			$post = $portal->post($this->race1data);
// 			$post = $portal->post($this->race2data);
// 			$post = $portal->post($this->race3data);
// 			$post = $portal->post($this->race4data);

// 			$this->assertEquals(true, $post, $method);
// 			$this->pass($method, $request);


// 		} catch (\Exception $e) {
// 			$this->error($e);
// 		}




// 		$request 	= "post: /portal/:uuid";
// 		$method 	= "portal::save():";
// 		$this->request->parts	= array("racing-api/", "portal", $this->testMeeting);

// 		$this->request->auth_user = (object) ['userid'=> $this->testUser3, 'groupid'=>$this->testGroup, 'access'=>'portal'];

// 		try {
// 			$portal = $this->request->di->create('mvcr\controller\Portal');
// 			$this->request->auth_user = $this->cacheRequest->auth_user;

// 			$post = $portal->post($this->race1data);
// 			$post = $portal->post($this->race2data);
// 			$post = $portal->post($this->race3data);
// 			$post = $portal->post($this->race4data);

// 			$this->assertEquals(true, $post, $method);
// 			$this->pass($method, $request);


// 		} catch (\Exception $e) {
// 			$this->error($e);
// 		}


// 		$request 	= "post: /portal/:uuid";
// 		$method 	= "portal::save():";
// 		$this->request->parts	= array("racing-api/", "portal", $this->testMeeting);

// 		$this->request->auth_user = (object) ['userid'=>$this->testUser4, 'groupid'=>$this->testGroup, 'access'=>'portal'];

// 		try {
// 			$portal = $this->request->di->create('mvcr\controller\Portal');
// 			$this->request->auth_user = $this->cacheRequest->auth_user;
// 			$post = $portal->post($this->race1data);
// 			$post = $portal->post($this->race2data);
// 			$post = $portal->post($this->race3data);
// 			$post = $portal->post($this->race4data);

// 			$this->assertEquals(true, $post, $method);
// 			$this->pass($method, $request);


// 		} catch (\Exception $e) {
// 			$this->error($e);
// 		}


// 		$request 	= "post: /portal/:uuid";
// 		$method 	= "portal::save():";
// 		$this->request->parts	= array("racing-api/", "portal", $this->testMeeting);

// 		$this->request->auth_user = $this->testLee;


// 		try {
// 			$portal = $this->request->di->create('mvcr\controller\Portal');
// 			$this->request->auth_user = $this->cacheRequest->auth_user;

// 			$post = $portal->post($this->race1data);
// 			$post = $portal->post($this->race2data);
// 			$post = $portal->post($this->race3data);
// 			$post = $portal->post($this->race4data);

// 			$this->assertEquals(true, $post, $method);
// 			$this->pass($method, $request);


// 		} catch (\Exception $e) {
// 			$this->error($e);
// 		}
// 		$request 	= "post: /portal/:uuid";
// 		$method 	= "portal::save():";
// 		$this->request->parts	= array("racing-api/", "portal", $this->testMeeting);

// 		$this->request->auth_user = $this->testIndesign;


// 		try {
// 			$portal = $this->request->di->create('mvcr\controller\Portal');
// 			$this->request->auth_user = $this->cacheRequest->auth_user;

// 			$post = $portal->post($this->race1data);
// 			$post = $portal->post($this->race2data);
// 			$post = $portal->post($this->race3data);
// 			$post = $portal->post($this->race4data);

// 			$this->assertEquals(true, $post, $method);
// 			$this->pass($method, $request);


// 		} catch (\Exception $e) {
// 			$this->error($e);
// 		}
// 		$request 	= "post: /portal/:uuid";
// 		$method 	= "portal::save(): user testAuth_news";
// 		$this->request->parts	= array("racing-api/", "portal", $this->testMeeting);

// 		$this->request->auth_user = $this->testAuth_news;

// 		try {

// 			$portal = $this->request->di->create('mvcr\controller\Portal');
// 			$this->request->auth_user = $this->cacheRequest->auth_user;

// 			$post = $portal->post($this->race1data);
// 			$post = $portal->post($this->race2data);
// 			$post = $portal->post($this->race3data);
// 			$post = $portal->post($this->race4data);

// 			$this->assertEquals(true, $post, $method);
// 			$this->pass($method, $request);


// 		} catch (\Exception $e) {
// 			$this->error($e);
// 		}


// 		$request 	= "post: /portal/:uuid";
// 		$method 	= "portal::save():";
// 		$this->request->parts	= array("racing-api/", "portal", $this->testMeeting);

// 		$this->request->auth_user = $this->testSteve;

// 		try {
// 			$portal = $this->request->di->create('mvcr\controller\Portal');
// 			$this->request->auth_user = $this->cacheRequest->auth_user;

// 			$post = $portal->post($this->race1data);
// 			$post = $portal->post($this->race2data);
// 			$post = $portal->post($this->race3data);
// 			$post = $portal->post($this->race4data);

// 			$this->assertEquals(true, $post, $method);
// 			$this->pass($method, $request);


// 		} catch (\Exception $e) {
// 			$this->error($e);
// 		}

// 	}

















// /**

// 	TRACKS

// */

//     public function testPostTrack()
//     {

// 		echo '<h3 style="margin: 10px 0 0 0">POST - Track</h3>';
// 		$request 	= "post: /track";
// 		$method 	= "track::createTrack():";
// 		$this->request->parts	= array("racing-api/", "track");
// 		$params 	= array("name"=>"A1A Carwash", "timezone"=>"Australia/Melbourne", "code"=>"aaa");

// 		try {
// 			$track = $this->request->di->create('mvcr\controller\Track');
// 			$post = $track->post($params);
// 			$this->assertRegExp('/[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}/', $post->id);
// 			$this->pass($method, $request);
// 			$this->testTrack = $post->id;

// 		} catch (\Exception $e) {
// 			$this->error($e);
// 		}
// 	}


//     public function testGetTrack()
//     {

// 		echo '<h3 style="margin: 10px 0 0 0">GET - Track</h3>';
// 		$request 	= "get: /track";
// 		$method 	= "track::getAllTracks():";
// 		$this->request->parts	= array("racing-api/", "track");

// 		try {
// 			$track = $this->request->di->create('mvcr\controller\Track');
// 			$get = $track->get([]);
// 			$this->assertEquals('A1A Carwash', $get[0]->name, $method);
// 			$this->pass($method, $request);


// 		} catch (\Exception $e) {
// 			$this->error($e);
// 		}



// 		$request = "get: /track/:uuid";
// 		$method = "track::getTrackById():";
// 		$this->request->parts	= array("racing-api/", "track", $this->testTrack);

// 		try {
// 			$track = $this->request->di->create('mvcr\controller\Track');
// 			$get = $track->get([]);
// 			$this->assertEquals("A1A Carwash", $get->name, $method);
// 			$this->pass($method, $request);


// 		} catch (\Exception $e) {
// 			$this->error($e);
// 		}
// 	}



//     public function testPutTrack()
//     {
// 		echo '<h3 style="margin: 10px 0 0 0">PUT - Track</h3>';
// 		$request 	= "put: /track/:uuid";
// 		$method 	= "track::updateTrack():";
// 		$this->request->parts	= array("racing-api/", "track", $this->testTrack);
// 		$params 	= array("code"=>"www");

// 		try {
// 			$track = $this->request->di->create('mvcr\controller\Track');
// 			$put = $track->put($params);
// 			$this->assertEquals(1, $put, $method);
// 			$this->pass($method, $request);


// 		} catch (\Exception $e) {
// 			$this->error($e);
// 		}
// 	}



//     public function testDeleteTrack()
//     {
// 		echo '<h3 style="margin: 10px 0 0 0">DELETE - Track</h3>';
// 		$request 	= "delete: /track/:uuid";
// 		$method 	= "track::deleteTrack():";
// 		$this->request->parts	= array("racing-api/", "track", $this->testTrack);

// 		try {
// 			$track = $this->request->di->create('mvcr\controller\Track');
// 			$delete = $track->delete();
// 			$this->assertEquals(1, $delete, $method);
// 			$this->pass($method, $request);


// 		} catch (\Exception $e) {
// 			$this->error($e);
// 		}
// 	}






















// /**

// 	GROUPS

// */

//     public function testPostGroup()
//     {

// 		echo '<h3 style="margin: 10px 0 0 0">POST - Group</h3>';
// 		$request 	= "post: /group";
// 		$method 	= "group::createGroup():";
// 		$this->request->parts 	= array("racing-api/", "group");
// 		$params 	= array("name"=>"AAAAA", "access"=>"full");

// 		try {
// 			$group = $this->request->di->create('mvcr\controller\Group');
// 			$post = $group->post($params);
// 			$this->assertRegExp('/[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}/', $post->id);
// 			$this->pass($method, $request);

// 			$this->testGroup = $post->id;

// 		} catch (\Exception $e) {
// 			$this->error($e);
// 		}
// 	}


//     public function testGetGroup()
//     {

// 		echo '<h3 style="margin: 10px 0 0 0">GET - Group</h3>';
// 		$request 	= "get: /group";
// 		$method 	= "group::getGroup():";
// 		$this->request->parts	= array("racing-api/", "group");

// 		try {
// 			$group = $this->request->di->create('mvcr\controller\Group');
// 			$get = $group->get([]);
// 			$this->assertEquals('AAAAA', $get[0]->groupname, $method);
// 			$this->pass($method, $request);


// 		} catch (\Exception $e) {
// 			$this->error($e);
// 		}



// 		$request	= "get: /group/:uuid";
// 		$method 	= "group::getGroupById():";
// 		$this->request->parts	= array("racing-api/", "group", $this->testGroup);

// 		try {
// 			$group = $this->request->di->create('mvcr\controller\Group');
// 			$get = $group->get([]);
// 			$this->assertEquals("AAAAA", $get->groupname, $method);
// 			$this->pass($method, $request);


// 		} catch (\Exception $e) {
// 			$this->error($e);
// 		}
// 	}

//     public function testPutGroup()
//     {
// 		echo '<h3 style="margin: 10px 0 0 0">PUT - Groups</h3>';
// 		$request 	= "put: /group/:uuid";
// 		$method 	= "group::updateGroup():";
// 		$this->request->parts	= array("racing-api/", "group", $this->testGroup);
// 		$params 	= array("name"=>"AAAAAAAAAAAAAA");

// 		try {
// 			$group = $this->request->di->create('mvcr\controller\Group');
// 			$put = $group->put($params);
// 			$this->assertEquals(1, $put, $method);
// 			$this->pass($method, $request);


// 		} catch (\Exception $e) {
// 			$this->error($e);
// 		}
// 	}

//     public function testDeleteGroup()
//     {
// 		echo '<h3 style="margin: 10px 0 0 0">DELETE - Group</h3>';
// 		$request 	= "delete: /group/:uuid";
// 		$method 	= "group::deleteGroup():";
// 		$this->request->parts	= array("racing-api/", "group", $this->testGroup);

// 		try {
// 			$group = $this->request->di->create('mvcr\controller\Group');
// 			$delete = $group->delete();
// 			$this->assertEquals(1, $delete, $method);
// 			$this->pass($method, $request);


// 		} catch (\Exception $e) {
// 			$this->error($e);
// 		}
// 	}

















// /**

// 	USERS

// */


//     public function testPostUser()
//     {

// 		echo '<h3 style="margin: 10px 0 0 0">POST - User</h3>';
// 		$request	= "post: /user";
// 		$method 	= "users::createUser():";
// 		$this->request->parts	= array("racing-api/", "user");

// 		try {
// 			$user = $this->request->di->create('mvcr\controller\User');
			
// 			$params 	= array("name"=>"aaa", "password"=>"qwerty", "group" => $this->testGroup);
// 			$post = $user->post($params);
// 			$this->assertRegExp('/[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}/', $post->id);
// 			$this->testUser = $post->id;

// 			$params 	= array("name"=>"testuserA", "password"=>"qwerty", "group" => $this->testGroup);
// 			$post = $user->post($params);
// 			$this->testUser2 = $post->id;

// 			$params 	= array("name"=>"testuserB", "password"=>"qwerty", "group" => $this->testGroup);
// 			$post = $user->post($params);
// 			$this->testUser3 = $post->id;

// 			$params 	= array("name"=>"testuserC", "password"=>"qwerty", "group" => $this->testGroup);
// 			$post = $user->post($params);
// 			$this->testUser4 = $post->id;

// 			$this->pass($method, $request);

// 		} catch (\Exception $e) {
// 			$this->error($e);
// 		}
// 	}

//     public function testGetUser()
//     {
// 		echo '<h3 style="margin: 10px 0 0 0">GET - User</h3>';

// 		$request 	= "get: /user";
// 		$method 	= "users::getUser():";
// 		$this->request->parts	= array("racing-api/", "user");

// 		try {
// 			$user = $this->request->di->create('mvcr\controller\User');
// 			$get = $user->get([]);

// 			$this->assertEquals($this->testUser, $get[0]->userid, $method);
// 			$this->pass($method, $request);


// 		} catch (\Exception $e) {
// 			$this->error($e);
// 		}



// 		$request 	= "get: /user/:uuid";
// 		$method 	= "users::getUser():";
// 		$this->request->parts	= array("racing-api/", "group", $this->testUser);

// 		try {
// 			$user = $this->request->di->create('mvcr\controller\User');
// 			$get = $user->get([]);

// 			$this->assertEquals($this->testUser, $get[0]->userid, $method);
// 			$this->pass($method, $request);


// 		} catch (\Exception $e) {
// 			$this->error($e);
// 		}



// 		$request 	= "get: /group/:uuid/?data=all";
// 		$method 	= "group::getAllUsers():";
// 		$this->request->parts	= array("racing-api/", "group", $this->testGroup);
// 		$params 	= array("data"=>"all");

// 		try {
// 			$group = $this->request->di->create('mvcr\controller\Group');
// 			$get = $group->get($params);
// 			$this->assertEquals($this->testUser, $get->users[0]->userid, $method);
// 			$this->pass($method, $request);


// 		} catch (\Exception $e) {
// 			$this->error($e);
// 		}

// 	}

//     public function testPutUser()
//     {

// 		echo '<h3 style="margin: 10px 0 0 0">PUT - User</h3>';
// 		$request 	= "put: /user/:uuid";
// 		$method 	= "user::updateUser():";
// 		$this->request->parts	= array("racing-api/", "user", $this->testUser);
// 		$params 	= array("name"=>"Test User");

// 		try {
// 			$user = $this->request->di->create('mvcr\controller\User');
// 			$put = $user->put($params);
// 			$this->assertEquals(1, $put, $method);
// 			$this->pass($method, $request);


// 		} catch (\Exception $e) {
// 			$this->error($e);
// 		}
// 	}



//     public function testDeleteUser()
//     {
// 		echo '<h3 style="margin: 10px 0 0 0">DELETE - User</h3>';
// 		$request 	= "delete: /user/:uuid";
// 		$method 	= "user::deleteUser():";
// 		$this->request->parts	= array("racing-api/", "user", $this->testUser);

// 		try {
// 			$user = $this->request->di->create('mvcr\controller\User');
// 			$delete = $user->delete();
// 			$this->assertEquals(1, $delete, $method);
// 			$this->pass($method, $request);


// 		} catch (\Exception $e) {
// 			$this->error($e);
// 		}
// 	}

















// /**

// 	PUBLICATIONS

// */


//     public function testPostPublication()
//     {

// 		echo '<h3 style="margin: 10px 0 0 0">POST - Publication</h3>';
// 		$request 	= "post: /publication";
// 		$method 	= "publication::createPublication():";
// 		$this->request->parts	= array("racing-api/", "publication");
// 		$params 	= array("name"=>"Aaaaaaargh Real Monsters");

// 		try {
// 			$publication = $this->request->di->create('mvcr\controller\Publication');
// 			$post = $publication->post($params);

// 			$this->assertRegExp('/[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}/', $post->id);
// 			$this->pass($method, $request);

// 			$this->testPublication = $post->id;

// 		} catch (\Exception $e) {
// 			$this->error($e);
// 		}
// 	}

//     public function testPutPublication()
//     {

// 		echo '<h3 style="margin: 10px 0 0 0">PUT - Publication</h3>';
// 		$request 	= "put: /publication/:uuid";
// 		$method 	= "publication::updatePublication():";
// 		$this->request->parts	= array("racing-api/", "publication", $this->testPublication);
// 		$params 	= array("timezone"=>"Australia/Melbourne");

// 		try {
// 			$publication = $this->request->di->create('mvcr\controller\Publication');
// 			$put = $publication->put($params);
// 			$this->assertEquals(1, $put, $method);
// 			$this->pass($method, $request);


// 		} catch (\Exception $e) {
// 			$this->error($e);
// 		}
// 	}

//     public function testGetPublication()
//     {
// 		echo '<h3 style="margin: 10px 0 0 0">GET - Publication</h3>';
// 		$request 	= "get: /publication";
// 		$method 	= "publication::getPublications():";
// 		$this->request->parts	= array("racing-api/", "publication");

// 		try {
// 			$publication = $this->request->di->create('mvcr\controller\Publication');
// 			$get = $publication->get([]);
// 			$this->assertEquals($this->testPublication, $get[0]->id, $method);
// 			$this->pass($method, $request);


// 		} catch (\Exception $e) {
// 			$this->error($e);
// 		}



// 		$request 	= "get: /publication/:uuid";
// 		$method 	= "publication::getPublicationById():";
// 		$this->request->parts	= array("racing-api/", "publication");
// 		$params 	= array("id"=>$this->testPublication);

// 		try {
// 			$publication = $this->request->di->create('mvcr\controller\Publication');
// 			$get = $publication->get([]);
// 			$this->assertEquals($this->testPublication, $get[0]->id, $method);
// 			$this->pass($method, $request);


// 		} catch (\Exception $e) {
// 			$this->error($e);
// 		}



// 		$request 	= "get: /publication/?data=all";
// 		$method	 	= "publication::getPublications_AllData():";
// 		$this->request->parts	= array("racing-api/", "publication");
// 		$params 	= array("data"=>"all");

// 		try {
// 			$publication = $this->request->di->create('mvcr\controller\Publication');
// 			$get = $publication->get($params);
// 			$this->assertEquals($this->testOutput, $get[0]->outputs[0]->id, $method);
// 			$this->pass($method, $request);


// 		} catch (\Exception $e) {
// 			$this->error($e);
// 		}



// 		$request 	= "get: /publication/:uuid/?data=all";
// 		$method 	= "publication::getPublicationById_AllData():";
// 		$this->request->parts	= array("racing-api/", "publication", $this->testPublication);
// 		$params 	= array("data"=>"all");

// 		try {
// 			$publication = $this->request->di->create('mvcr\controller\Publication');
// 			$get = $publication->get($params);
// 			$this->assertEquals($this->testOutput, $get->outputs[0]->id, $method);
// 			$this->pass($method, $request);


// 		} catch (\Exception $e) {
// 			$this->error($e);
// 		}
// 	}


//     public function testDeletePublication()
//     {
// 		echo '<h3 style="margin: 10px 0 0 0">DELETE - Publication</h3>';
// 		$request = "delete: /publication/:uuid";
// 		$method = "publication::deletePublication():";
// 		$this->request->parts	= array("racing-api/", "publication", $this->testPublication);

// 		try {
// 			$publication = $this->request->di->create('mvcr\controller\Publication');
// 			$delete = $publication->delete();
// 			$this->assertEquals(1, $delete, $method);
// 			$this->pass($method, $request);


// 		} catch (\Exception $e) {
// 			$this->error($e);
// 		}
// 	}













// /**

// 	TIPSPANEL

// */

//     public function testPostTipsPanel()
//     {
// 		echo '<h3 style="margin: 10px 0 0 0">POST - Tips Panel</h3>';
// 		$request = "post: /tipspanel/";
// 		$method = "tipspanel::deletePublication():";
// 		$this->request->parts	= array("racing-api/", "tipspanel");

// 		try {
// 			$tipspanel = $this->request->di->create('mvcr\controller\Tipspanel');
// 			$post = $tipspanel->post(['group'=>$this->testGroup, "name"=>"test"]);

// 			$this->assertRegExp('/[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}/', $post->id);
// 			$this->pass($method, $request);
// 			$this->testPanel = $post->id;

// 		} catch (\Exception $e) {
// 			$this->error($e);
// 		}
//     }

//     public function testGetTipsPanel()
//     {
// 		echo '<h3 style="margin: 10px 0 0 0">GET - Tips Panel</h3>';
// 		$request = "get: /tipspanel/";
// 		$method = "tipspanel::deletePublication():";
// 		$this->request->parts	= array("racing-api/", "tipspanel");

// 		try {
// 			$tipspanel = $this->request->di->create('mvcr\controller\Tipspanel');
// 			$get = $tipspanel->get(['group'=>$this->testGroup]);
// 			$this->assertEquals('test',      $get[0]->name, $method);
// 			$this->assertEquals('testuserA', $get[0]->tipsters[0]->tipster, $method);
// 			$this->assertEquals('testuserB', $get[0]->tipsters[1]->tipster, $method);
// 			$this->assertEquals('testuserC', $get[0]->tipsters[2]->tipster, $method);
// 			$this->pass($method, $request);

// 		} catch (\Exception $e) {
// 			$this->error($e);
// 		}
//     }



//     public function testPostTipstersToPanel()
//     {
// 		echo '<h3 style="margin: 10px 0 0 0">POST - Tipsters To Panel</h3>';
// 		$request = "get: /tipspanel/";
// 		$method = "tipspanel::deletePublication():";
// 		$this->request->parts	= array("racing-api/", "tipspanel", $this->testPanel);

// 		try {
// 			$tipspanel = $this->request->di->create('mvcr\controller\Tipspanel');
// 			$post = $tipspanel->post(['user'=>'testuserA']);
// 			$post = $tipspanel->post(['user'=>'testuserB']);
// 			$post = $tipspanel->post(['user'=>'testuserC']);
// 			$this->assertRegExp('/[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}/', $post->id);
// 			$this->pass($method, $request);

// 		} catch (\Exception $e) {
// 			$this->error($e);
// 		}
//     }









// /**

// 	OUTPUTS

// */


//     public function testPostOutput()
//     {
// 		echo '<h3 style="margin: 10px 0 0 0">POST - Output</h3>';
// 		$request 	= "post: /output";
// 		$method 	= "output::createOutput():";
// 		$this->request->parts	= array("racing-api/", "output");
// 		$params 	= array("name"=>"Aaaaaaargh Main Fields", "publication"=>$this->testPublication);

// 		try {
// 			$output = $this->request->di->create('mvcr\controller\Output');
// 			$post = $output->post($params);
// 			$this->assertRegExp('/[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}/', $post->id);
// 			$this->pass($method, $request);

// 			$this->testOutput = $post->id;

// 		} catch (\Exception $e) {
// 			$this->error($e);
// 		}
// 	}

//     public function testPutOutput()
//     {
// 		echo '<h3 style="margin: 10px 0 0 0">PUT - Output</h3>';
// 		$request 	= "put: /output/:uuid";
// 		$method 	= "output::updateOutput():";
// 		$this->request->parts	= array("racing-api/", "output", $this->testOutput);
// 		$params 	= array("name"=>"Aaaaaaargh New Fields");

// 		try {
// 			$output = $this->request->di->create('mvcr\controller\Output');
// 			$put = $output->put($params);
// 			$this->assertEquals(1, $put, $method);
// 			$this->pass($method, $request);


// 		} catch (\Exception $e) {
// 			$this->error($e);
// 		}
// 	}

//     public function testGetOutputs()
//     {
// 		echo '<h3 style="margin: 10px 0 0 0">GET - Output</h3>';
// 		$request 	= "get: /output";
// 		$method 	= "output::getAllOutputs():";
// 		$this->request->parts	= array("racing-api/", "output", $this->testOutput);

// 		try {
// 			$output = $this->request->di->create('mvcr\controller\Output');
// 			$get = $output->get([]);
// 			$this->assertEquals("Aaaaaaargh New Fields", $get->name, $method);
// 			$this->pass($method, $request);


// 		} catch (\Exception $e) {
// 			$this->error($e);
// 		}



// 		$request 	= "get: /output/:uuid";
// 		$method 	= "output::getOutputById():";
// 		$this->request->parts	= array("racing-api/", "output", $this->testOutput);

// 		try {
// 			$output = $this->request->di->create('mvcr\controller\Output');
// 			$get = $output->get([]);
// 			$this->assertEquals($this->testOutput, $get->id, $method);
// 			$this->pass($method, $request);


// 		} catch (\Exception $e) {
// 			$this->error($e);
// 		}



// 		$request 	= "get: /output/?pid=:uuid";
// 		$method 	= "output::getOutputByPublicationId():";
// 		$this->request->parts	= array("racing-api/", "output");
// 		$params 	= array("pid"=>$this->testPublication);

// 		try {
// 			$output = $this->request->di->create('mvcr\controller\Output');
// 			$get = $output->get($params);
// 			$this->assertEquals($this->testOutput, $get[0]->id, $method);
// 			$this->pass($method, $request);


// 		} catch (\Exception $e) {
// 			$this->error($e);
// 		}
// 	}

//     public function testDeleteOutput()
//     {
//     	# this test will always fail as deletePublication also deletes the publications outputs
// 		echo '<h3 style="margin: 10px 0 0 0">DELETE - Output</h3>';
// 		$request 	= "delete: /output/:uuid";
// 		$method 	= "output::deleteOutput():";
// 		$this->request->parts	= array("racing-api/", "output", $this->testOutput);

// 		try {
// 			$output = $this->request->di->create('mvcr\controller\Output');
// 			$delete = $output->delete();
// 			$this->assertEquals(1, $delete, $method);
// 			$this->pass($method, $request);


// 		} catch (\Exception $e) {
// 			$this->error($e);
// 		}
// 	}


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
