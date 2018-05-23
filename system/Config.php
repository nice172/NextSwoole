<?php
namespace system;

class Config{
    
    private static $instance = null;
    
    private function __construct(){
        $paths = scandir(ROOT_PATH.'/config');
        foreach ($paths as $file){
            if ($file != '.' && $file != '..'){
                self::getConfig(require_once ROOT_PATH.'/config/'.$file);
            }
        }
    }
    
    private function __clone(){}
 
    public static function getInstance(){
        if (!(self::$instance instanceof self)){
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * 获取配置参数
     * @param string $name
     * @param string $value
     * @return string|string[]|array
     */
    public function getConfig($name='',$value=''){
        static $config = [];
        if (is_array($name) && $value == ''){
            $config = array_merge($config,array_change_key_case($name,CASE_UPPER));
        }elseif (is_string($name) && $value != ''){
            $config[strtoupper($name)] = $value;
        }elseif ($name != ''){
            $name = strtoupper($name);
            return isset($config[$name]) ? $config[$name] : '';
        }else{
            return $config;
        }
    }
    
}

