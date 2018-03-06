<?php
namespace mvcr\controller;


use \mvcr\service\l;

Abstract class BaseTest extends \PHPUnit\Framework\TestCase
{

    protected function error($e)
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

    protected function pass($method, $request)
    {
		echo '<ul style="margin:0 0 5px 0;"><li style="color:grey;margin:0;">'.$request.'</li><li style="color:green;margin:0">'.$method.'</li></ul>';
    	$this->log($request);
    }

	protected function log($url)
	{
		$sql = "UPDATE uri_log set tested = now() WHERE uri = :uri";
		$param = array('uri' => $url);
		$this->db->execute($sql, $param);
	}
}