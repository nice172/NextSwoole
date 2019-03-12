<?php
namespace system;

class Response {
 
    private static $instance = null;
    
    private $response = null;
    
    private function __construct(){}
    
    private function __clone(){}
    
    public static function getInstance(){
        if(!(self::$instance instanceof self)){
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function setResponse(\swoole_http_response $response){
        $this->response = $response;
    }
    
    public function getResponse(){
        return $this->response;
    }
    
}