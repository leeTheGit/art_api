<?php
namespace src\model;
use \Memcached;


class Cache  {

	private $config = null;
	private $mc = null;


	public function __construct($config, \Memcached $cache)
	{
		$this->config = $config;
		$this->mc = $cache;
		$this->connect();
	}

	private function connect()
	{
		// $this->mc = new \Memcached();


		$servers = $this->mc->getServerList();
		if(is_array($servers)) {
			foreach ($servers as $server) {
				if($server['host'] == $this->config['host'] and $server['port'] == 11211) {
					return true;
				}
			}
		}
		$con = $this->mc->addServer($this->config['host'], 11211);

	}

	public function set($key, $data)
	{
		if (!$this->config['write_enabled']) {return false;}


		$this->mc->set($key, $data);
	}

	public function get($key)
	{
		if (!$this->config['read_enabled']) {return false;}


		return $this->mc->get($key);
	}

	public function delete($key)
	{
		if (!$this->config['write_enabled']) {return false;}

		return $this->mc->delete($key);
	}


}
