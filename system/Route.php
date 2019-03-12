<?php
namespace system;

class Route {
    
    private static $instance = null;
    
    private $request = null;
    
    private function __construct(){}
    
    private function __clone(){}
    
    public static function getInstance(){
        if(!(self::$instance instanceof self)){
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function setRequest($request){
        $this->request = $request;
        return $this;
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
        $controllerName = '\application\\'.$default_module.'\controller\\'.ucfirst($default_controller).'Controller';
        $methodName = 'index';
        if (isset($extension['extension'])){
            
            //读取配置路由文件
            
            return ['moduleName' => $default_module,
                'controllerName' => $controllerName, 'methodName' => $methodName];
        }
        if (count($pathinfo) >= 3){
            $moduleName = trim($pathinfo[0]); //获取当前模块
            if (isset($pathinfo[1]) && !empty($pathinfo[1])){
                $controllerName = '\application\\'.$default_module.'\controller\\'.ucfirst($pathinfo[1]).'Controller';
            }
            if (isset($pathinfo[2]) && !empty($pathinfo[2])){
                $methodName = $pathinfo[2];
            }
        }else{
            //执行默认模块
            if (isset($pathinfo[0]) && !empty($pathinfo[0])){
                $controllerName = '\application\\'.$default_module.'\controller\\'.ucfirst($pathinfo[0]).'Controller';
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