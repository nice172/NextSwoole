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
    

    
}