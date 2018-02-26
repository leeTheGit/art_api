<?php
namespace mvcr\model;
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
		// logThis($this->mc);

		$servers = $this->mc->getServerList();
		if(is_array($servers)) {
			foreach ($servers as $server) {
				if($server['host'] == $this->config['host'] and $server['port'] == 11211) {
					return true;
				}
			}
		}
		$con = $this->mc->addServer($this->config['host'], 11211);
		// logThis($con);
	}

	public function set($key, $data)
	{
		if (!$this->config['write_enabled']) {return false;}

		// logThis('setting key: '. $key);
		$this->mc->set($key, $data);
	}

	public function get($key)
	{
		if (!$this->config['read_enabled']) {return false;}

		// logThis('getting key: '. $key);
		return $this->mc->get($key);
	}

	public function delete($key)
	{
		if (!$this->config['write_enabled']) {return false;}

		// logThis('deleting: '. $key);
		return $this->mc->delete($key);
	}


}
