<?php
namespace application\home\controller;

use system\Response;

class BaseController {
	
	protected $request;
	protected $response;
	protected static $config = [];
	protected static $mysqlPool = null;
	
	public function __construct(){	    
	    if (method_exists($this, '_initialize')){
	    	$this->_initialize();
	    }
	}

	protected function _initialize(){}
	
	public function service(\swoole_http_server $server, \swoole_http_request $request, \swoole_http_response $response, $route){
		$this->request = $request;
		$this->response = $response;
		$methodName = $route['methodName'];
		$this->$methodName();
	}
	
	public function init(){
		$this->response->end('easyswoole');
	}
	
	public function setResponse($response){
	    $this->response = $response;
	    $this->response->header('Content-Type', 'text/html;charset=utf-8');
	}
	
	public function setMysqlPool($mysql){
	    self::$mysqlPool = $mysql;
	}
	
	public function setConfig($config=[]){
	    self::$config = array_map(self::$config, $config);
	}
	
	protected function getConfig($key = ''){
	    
	    if ($key != ''){
	        return isset(self::$config[$key]) ? self::$config[$key] : '';
	    }
	    return self::$config;
	}
	
	public function __wakeup(){
	    echo '__wakeup';
	}
	
	public function __invoke(){
	    echo '__invoke';
	}
	
	public function __destruct(){
	    //echo 'response null';
	    $this->request = null;
	    $this->response = null;
	}
	
}