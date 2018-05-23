<?php
namespace system;

class Config{
    
    private static $instance = null;
    private static $config = [];
    
    private function __construct(){
        $paths = scandir(ROOT_PATH.'/config');
        foreach ($paths as $file){
            if ($file != '.' && $file != '..'){
                self::getConfig(require ROOT_PATH.'/config/'.$file);
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
        if (is_array($name) && $value == ''){
            self::$config = array_merge(self::$config,array_change_key_case($name,CASE_UPPER));
        }elseif (is_string($name) && $value != ''){
            self::$config[strtoupper($name)] = $value;
        }elseif ($name != ''){
            $name = strtoupper($name);
            return isset(self::$config[$name]) ? self::$config[$name] : '';
        }else{
            return self::$config;
        }
    }
    
}

