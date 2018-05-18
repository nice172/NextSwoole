<?php
namespace system;

class Request {
    
    private $request = null;
    private $header = null;
    private $server = null;
    private $get = null;
    private $post = null;
    private $cookie = null;
    private $files = null;
    private $rawContent = null;
    
    public function __construct(\swoole_http_request $request){
        $this->request = $request;
    }
    
    public function parseRoute(){
        $pathname = explode('/', $this->request->server['path_info']);
        $extension = pathinfo($this->request->server['path_info']);
        $controller = 'Index';
        $method = 'init';
        if (!isset($extension['extension'])){
            if (isset($pathname[1]) && !empty($pathname[1])){
                $controller = ucfirst($pathname[1]);
                $method = isset($pathname[2]) && !empty($pathname[2]) ? trim($pathname[2]) : 'init';
            }
        }
        return ['controllerName' => $controller, 'methodName' => $method];
    }
    
}