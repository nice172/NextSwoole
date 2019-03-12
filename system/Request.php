<?php
namespace system;

class Request {
    
	private static $instance = null;
    private $request = null;
    private $header = [];
    private $server = [];
    private $get = [];
    private $post = [];
    private $cookie = [];
    private $files = [];
    private $rawContent = null;
    
    private function __construct(){
        
    }
    
    private function __clone(){}
    
    public static function getInstance(){
    	if (!(self::$instance instanceof self)){
    		self::$instance = new self();
    	}
    	return self::$instance;
    }
    
    public function setRequest(\swoole_http_request $request){
    	$this->request = $request;
    	$this->get = $request->get;
    	$this->post = isset($request->post) ? $request->post : [];
    }
    
    public function getRequest(){
    	return $this->request;
    }
    
    public function get(){
    	return $this->get;
    }
    
    public function post(){
    	return $this->post;
    }
    
    public function files(){
    	
    }
    
    public function header(){
    	
    }
    
    public function server(){
    	
    }
    
    public function cookie(){
    	
    }
    
    public function rawContent(){
    	
    }
    
}