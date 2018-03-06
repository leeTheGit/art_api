<?php
// phpinfo();return;

require_once dirname(__FILE__) . '/../vendor/autoload.php' ;
require_once dirname(__FILE__) .'/../config/config.php';
require_once dirname(__FILE__) .'/../config/globals.php';
include_once DIR_MODEL.'/functions.php';
set_error_handler("error_handler");

$di = new \Dice\Dice;

// Allow authorized cross origin requests;
// $origin = $di->create('mvcr\model\Origins');

\mvcr\service\l::og('in the index');


$rule = [         'shared' => true,
         'constructParams' => [dbConfig_pg()]
];
$di->addRule('mvcr\model\Database', $rule);



$rule = [		  'shared' => true,
         'constructParams' => [cacheConfig()]
];
$di->addRule('mvcr\model\Cache', $rule);


// $mc = $di->create('mvcr\model\Cache');


// $redis = new Predis\Client();
// $redis->set("hot jockey", "bob terwilliger");
// $hot_jock = $redis->get("hot jockey");
// logThis($hot_jock);
$redis = "redis placeholder";


try { // real hard little API!!

	// used to log all called functions throughout the request;
	$functions = [];


		/**
			Login the user
		*/

	\mvcr\service\l::og('creating login');

	$login = $di->create('mvcr\controller\Login');
	\mvcr\service\l::og('created login');

		/**
			Authorise the user for request
		*/

	\mvcr\service\l::og('authing user');

	$auth_user = $login->auth();

	
	$rule = [		  'shared' => true,
	         'constructParams' => [$auth_user, $redis, $di]];

	$di->addRule('mvcr\router\Request', $rule);

	$request = $di->create('mvcr\router\Request');



	$rule = ['constructParams' => [$auth_user]];

	$di->addRule('mvcr\controller\AccessControl', $rule);

	$auth = $di->create('mvcr\controller\AccessControl');

	$auth->checkAuthLevel();  // if not authed will return with code 550



	$reqLog = $di->create('mvcr\model\requestLogger');


	/**
		Process the request
	*/


	$request->process();




} catch( Exception $e ) {

	\mvcr\service\l::og($e->getMessage());

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
