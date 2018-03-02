<?php
namespace mvcr\router;

use mvcr\model\Database;
use mvcr\model\Cache;

class Request
{
    public $db           = null;
    public $mc           = null;
    public $auth_user    = null;
    public $parts        = null;
    public $uri;
    public $method;
    public $date;
    public $di;
    public $redis;
    public $data;

    public function __construct(Database $db, Cache $mc, $auth_user, $redis, $di)
    {
        $this->method    = strtolower( $_SERVER['REQUEST_METHOD']);
        $this->uri       = rawurldecode( $_SERVER['REQUEST_URI'] );
        $this->data      = $this->getData();
        $this->db        = $db;
        $this->mc        = $mc;
        $this->auth_user = $auth_user;
        $this->redis     = $redis;
        $this->di        = $di;
        $this->date      = new \DateTime('now');
        $this->date->setTime(23, 59, 59);
    }


    public function getData()
    {
        switch ($this->method)
        {
            case 'get':
                return $_GET;

            case 'post':
                return $_POST;

            case 'put':
                $headers = getallheaders();
                $data = file_get_contents('php://input') ?? '';

                $result = array();
                parse_str($data, $result);
                return $result;

            case 'delete':
                return array();

            default:
                exit(header('HTTP/1.1 400 Bad request'));
        }
    }

    public function process()
    {
        $result = [];

        $urlArr = parse_url( $this->uri );

        $this->parts  = explode('/', $urlArr['path']); // seperate path elements

        $this->parts[1] = ($this->parts[1] === '') ? 'home' : $this->parts[1];

        $class = 'mvcr\controller\\'.ucfirst( $this->parts[1] );

        $time_start = microtime(true);

        if (!class_exists($class)) {
            header('HTTP/1.0 404 Not found');
            throw new \Exception('Resource does\'t exist');
        }

        $controller = $this->di->create($class);

        if (!method_exists($controller, $this->method)) {
            header('HTTP/1.0 400 Bad request');
            throw new \Exception('Action is invalid');
        }

        $result['home'] = API;

        $res = $controller->{$this->method}($this->data);

        $result['data'] = $res;
        $result['time'] = (microtime(true) - $time_start);

        $this->respond($result);
    }

    private function respond($result)
    {
        if ( isset($_GET['callback']) && \mvcr\model\callback_check::callback($_GET['callback'])) {
            header("Content-Type: application/json; charset=utf-8");
            exit("{$_GET['callback']}(".json_encode($result).");");

        } else {

            if (!DEBUG) {
                header('content-type: application/json; charset=utf-8');
            }

            exit( json_encode($result) );
        }

    }
}
