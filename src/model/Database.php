<?php
namespace src\model;

use \src\service\l;

class Database  {

	private $PDO;
	private $config = null;

	public function __construct($config)
	{
		$this->config = $config;
		$this->connect();
	}

	private function connect()
	{
		// $this->PDO = new \PDO($this->config['connect'], $this->config['user'], $this->config['password']);
		$this->PDO = new \PDO($this->config['connect'], $this->config['user'], $this->config['password']);
		// $this->PDO = new \PDO('pgsql:user=vagrant dbname=vagrant password=vagrant');



		$this->PDO->setAttribute( \PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION );

	}

	public function query($query, $bindings = array())
	{
		try {
			$stmt = $this->prepare($query);

			$stmt->execute($bindings);
			return $stmt->fetchAll(\PDO::FETCH_OBJ);
		} catch (\PDOException $e) {
			$errormsg = $this->getErrorMsg( $e->getMessage() );
			l::og($errormsg);
			return false;
		}
	}



	public function stmt_fetch($stmt, $bindings = array())
	{
		try {
			$stmt->execute($bindings);
			return $stmt->fetch(\PDO::FETCH_OBJ);
		} catch (\PDOException $e) {
			$errormsg = $this->getErrorMsg( $e->getMessage() );
			l::og($errormsg);
			return false;
		}
	}

	public function stmt_fetchAll($stmt, $bindings = array())
	{
		try {
			$stmt->execute($bindings);
			return $stmt->fetchAll(\PDO::FETCH_OBJ);
		} catch (\PDOException $e) {
			$errormsg = $this->getErrorMsg( $e->getMessage() );
			l::og($errormsg);
			return false;
		}
	}



	public function fetch($query, $bindings = array())
	{
		try {
			$stmt = $this->prepare($query);
			$stmt->execute($bindings);
			return $stmt->fetch(\PDO::FETCH_OBJ);
		} catch (\PDOException $e) {
			$errormsg = $this->getErrorMsg( $e->getMessage() );
			l::og($errormsg);
			return false;
		}
	}

	public function fetchAll($query, $bindings = array())
	{
		try {
			$stmt = $this->prepare($query);
			$stmt->execute($bindings);
			return $stmt->fetchAll(\PDO::FETCH_OBJ);
		} catch (\PDOException $e) {
			$errormsg = $this->getErrorMsg( $e->getMessage() );
			l::og($errormsg);
			return false;
		}
	}

	public function execute($query, $bindings = array())
	{
		try {
			$stmt = $this->prepare($query);
			$stmt->execute($bindings);

			if ($stmt->rowCount() > 0) {
			  return true;
			}


			foreach ($bindings as &$b) {
				if (empty($b)) {
					$b = gettype($b);
				}
			}
			$error = [
				"sql" => $query,
				"params" => $bindings,
				"error" => $this->PDO->errorInfo()[2]
			];
			return $error;
		} catch (\PDOException $e) {
			$errormsg = $this->getErrorMsg( $e->getMessage() );
			l::og($errormsg);
			return false;
		}
	}

	public function delete($query, $bindings = array())
	{
		$stmt = $this->prepare($query);
		$stmt->execute($bindings);

		if ($stmt->rowCount() > 0) {
		  return true;
		}
		return false;
	}

	public function insert($query, $bindings = array(), $returnVal = "id")
	{
		try {
			$query .= " RETURNING " . $returnVal;
			$stmt = $this->prepare($query);
			$stmt->execute($bindings);
			return $stmt->fetch(\PDO::FETCH_OBJ);
		} catch (\PDOException $e) {
			$errormsg = $this->getErrorMsg( $e->getMessage() );
			l::og($errormsg);
			return false;
		}
	}

	public function prepare($query)
	{
		return $this->PDO->prepare($query);
	}

	public function insert_MYSQL($query, $bindings = array())
	{
		try {
			$stmt = $this->prepare($query);
			$stmt->execute($bindings);
			return $this->PDO->lastInsertId();
		} catch (\PDOException $e) {
			$errormsg = $this->getErrorMsg( $e->getMessage() );
			l::og($errormsg);
			return false;
		}
	}
	public function setTimezone($tz)
	{
		$sql = "SET Time Zone '" . $tz . "'";
		$this->execute($sql);
	}

	private function getErrorMsg( $msg )
	{
		if (strpos($msg, "Invalid text representation: 7 ERROR:  invalid input syntax for uuid") !== false) {
			return "The id you're using is not a valid UUID";
		}
		return $msg;
	}

}
