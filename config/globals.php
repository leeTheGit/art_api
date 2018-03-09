<?php

define('TEST',      False);
define('DOMAIN',    "arta-api/");


define('DIR_WEB',   dirname(__FILE__).'/../public');
define('DIR_ROOT',  DIR_WEB .'/..');
define('DIR_SRC',   DIR_ROOT.'/src');
define('DIR_MODEL', DIR_SRC.'/model');
define('DIR_CTRL',  DIR_SRC.'/controller');
define('DIR_ROUT',  DIR_SRC.'/router');
define('DIR_SERV',  DIR_SRC.'/services');
define('DIR_LOG',   DIR_ROOT.'/log');

define('NS',   		'src');
define('NS_CONT',   NS.'\controller');
define('NS_MODEL',  NS.'\model');
define('NS_SERV',   NS.'\service');
define('NS_ROUT',   NS.'\router');
define('NS_TEST',   NS.'\test');


define('REQUEST_ID', substr(str_shuffle(md5(time())),0,5));

