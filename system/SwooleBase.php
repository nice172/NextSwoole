<?php
namespace system;

class SwooleBase {
    
    protected static $conifg = [];
    
    protected function __construct(){
        define('ROOT_PATH', dirname(dirname(__FILE__)));
        date_default_timezone_set('PRC');
        $this->setConfig();
    }
    
    /**
     * 初始化配置文件
     * @param array $config
     */
    public function setConfig($config = null){
        if (is_array($config) && $config !== null){
            self::$conifg = array_merge(self::$conifg, $config);
        }else{
            // 加载配置文件
            $configFiles = [
                ROOT_PATH.'/config/mimeFile.php',
                ROOT_PATH.'/config/server.php'
            ];
            foreach ($configFiles as $file){
                if (file_exists($file)){
                    $config = require_once $file;
                    self::$conifg = array_merge(self::$conifg, $config);
                }
            }
        }
    }
    
}