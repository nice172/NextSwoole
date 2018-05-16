<?php
class Base {
	
	protected $request;
	protected $response;
	protected static $config = [];
	protected static $mysqlPool = null;
	
	public function __construct(swoole_http_response $response, $mysqlPool = null){
	    $this->setResponse($response);
	    self::$mysqlPool = $mysqlPool;
	}
	
	public function init(){
		$this->response->end('easyswoole');
	}
	
	public function setResponse($response){
	    $this->response = $response;
	    $this->response->header('Content-Type', 'text/html;charset=utf-8');
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
	    echo 'response null';
	    $this->request = null;
	    $this->response = null;
	}
	
}