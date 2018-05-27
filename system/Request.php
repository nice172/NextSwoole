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
    
    /**
     * 解析URL路由
     * http://www.example.com/home/index/init
     * @return array
     */
    public function parseRoute(){
        $pathinfo = explode('/', substr($this->request->server['path_info'], 1));
        $extension = pathinfo($this->request->server['path_info']);
        $default_module = Config::getInstance()->getConfig('default_module');
        $default_controller = ucfirst(Config::getInstance()->getConfig('default_controller'));
        
       // new \appliaction\home\controller\Index($response);
        
        $controllerName = '\application\\'.$default_module.'\controller\\'.$default_controller;
        $methodName = 'init';
        if (isset($extension['extension'])){
            return ['moduleName' => $default_module,
                'controllerName' => $controllerName, 'methodName' => $methodName];
        }
        if (count($pathinfo) >= 3){
            $moduleName = trim($pathinfo[0]); //获取当前模块
            if (isset($pathinfo[1]) && !empty($pathinfo[1])){
                $controllerName = '\application\\'.$default_module.'\controller\\'.ucfirst($pathinfo[1]);
            }
            if (isset($pathinfo[2]) && !empty($pathinfo[2])){
                $methodName = $pathinfo[1];
            }
        }else{
            if (isset($pathinfo[0]) && !empty($pathinfo[0])){
                $controllerName = '\application\\'.$default_module.'\controller\\'.ucfirst($pathinfo[0]);
            }
            if (isset($pathinfo[1]) && !empty($pathinfo[1])){
                $methodName = $pathinfo[1];
            }
        }
        if (!isset($moduleName) || $moduleName == ''){
            $moduleName = $default_module;
        }

        return ['moduleName' => $moduleName,'controllerName' => $controllerName, 'methodName' => $methodName];
    }
    
}