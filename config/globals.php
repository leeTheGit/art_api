<?php

define('TEST',      False);
define('DIR_WEB',   dirname(__FILE__).'/../public');
define('DIR_ROOT',  DIR_WEB .'/..');
define('DIR_MVCR',  DIR_ROOT.'/mvcr');
define('DIR_MODEL', DIR_MVCR.'/model');
define('DIR_CTRL',  DIR_MVCR.'/controller');
define('DIR_ROUT',  DIR_MVCR.'/router');
define('DIR_SERV',  DIR_MVCR.'/services');
define('DIR_LOG',   DIR_ROOT.'/log');

define('REQUEST_ID', substr(str_shuffle(md5(time())),0,5));

