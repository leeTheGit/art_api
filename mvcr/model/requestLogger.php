<?php
namespace mvcr\model;

use mvcr\model\Database;
use mvcr\router\Request;

class requestLogger
{
	protected $db;
	protected $method;
	protected $uri;

	public function __construct(Database $db, Request $request)
	{
		$this->db = $db;
		$this->method = $request->method;
		$this->uri    = $request->uri;
		$this->log();
	}

	private function uuidCheck()
	{
		$this->uri = preg_replace( '/[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}/', ':uuid', strtolower($this->uri));
	}
	private function dateCheck()
	{
		$this->uri = preg_replace( '/[0-9]{4,4}-[0-9]{2,2}-[0-9]{2,2}/', ':date', $this->uri);
	}
	private function meetingCodecheck()
	{
		$this->uri = preg_replace( '/\/[a-z ]+,/', '/:meeting', $this->uri);
	}
	private function loadCodecheck()
	{   //cranbourne n,:date,ha
		$this->uri = preg_replace( '/\/:meeting:date,(ha|ra|gr)/', '/:load', $this->uri);
	}

	private function racecheck()
	{
		$this->uri = preg_replace( '/races=[0-9,]+/', 'races=:num', $this->uri);
	}
	private function othercheck()
	{
		$this->uri = preg_replace( '/(poll|runs)=[0-9]+/', '\1=:num', $this->uri);
	}
	private function tipstercheck()
	{
		$this->uri = preg_replace( '/\/tipster\/\/[a-z]+/', '/:tipster', $this->uri);
	}


	private function log()
	{	global $functions;$functions[] = get_class($this).'->'.__FUNCTION__;

		$this->uuidCheck();
		$this->dateCheck();
		$this->meetingCodecheck();
		$this->loadCodecheck();
		$this->racecheck();
		$this->othercheck();
		$this->tipstercheck();
		//
		$uri = $this->method . ': ' .$this->uri;
		$sql = "SELECT * FROM uri_log WHERE uri = :uri";
		$param = array('uri' => $uri);
		$check = $this->db->query($sql, $param);

		if (count($check) > 0 ) {
			$number = $check[0]->count;
			$number++;
			$sql = "UPDATE uri_log set count = :count WHERE id = :id";
			$param = array('count' => $number, 'id'=>$check[0]->id );
			$this->db->execute($sql, $param);

		} else {
			$sql = "INSERT INTO uri_log (uri) Values(:uri)";
			$this->db->execute($sql, $param);
		}
	}
}
