<?php
// phpinfo();return;

require_once dirname(__FILE__) . '/../vendor/autoload.php' ;
require_once dirname(__FILE__) .'/../config/config.php';
require_once dirname(__FILE__) .'/../config/globals.php';
include_once DIR_MODEL.'/functions.php';
set_error_handler("error_handler");

$di = new \Dice\Dice;

// Allow authorized cross origin requests;
// $origin = $di->create(NS_MODEL.'\Origins');

src\service\l::og('in the index');


$rule = [         'shared' => true,
         'constructParams' => [dbConfig_pg()]
];
$di->addRule(NS_MODEL.'\Database', $rule);



$rule = [		  'shared' => true,
         'constructParams' => [cacheConfig()]
];
$di->addRule(NS_MODEL.'\Cache', $rule);


// $mc = $di->create(NS_MODEL.'\Cache');


// $redis = new Predis\Client();
// $redis->set("hot jockey", "bob terwilliger");
// $hot_jock = $redis->get("hot jockey");

$redis = "redis placeholder";


try { // real hard little API!!

	// used to log all called functions throughout the request;
	$functions = [];


		/**
			Login the user
		*/


	$login = $di->create(NS_CONT.'\Login');

		/**
		Authorise the user for request
		*/


	$auth_user = $login->auth();

	
	$rule = [		  'shared' => true,
	         'constructParams' => [$auth_user, $redis, $di]];

	$di->addRule(NS_ROUT.'\Request', $rule);

	$request = $di->create(NS_ROUT.'\Request');



	$rule = ['constructParams' => [$auth_user]];

	$di->addRule(NS_CONT.'\AccessControl', $rule);

	$auth = $di->create(NS_CONT.'\AccessControl');

	$auth->checkAuthLevel();  // if not authed will return with code 550



	$reqLog = $di->create(NS_MODEL.'\requestLogger');


	/**
		Process the request
	*/


	$request->process();




} catch( Exception $e ) {

	// \src\service\l::og($e->getMessage());

	exit(json_encode( 
		[
			"error"   => $e->getMessage(),
			"request" => [
				"url" 	 => $request->uri,
				"params" => $request->data
			]
	    ])
	);
}
