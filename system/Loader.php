<?php
namespace system;

class Loader {
    
    //路径映射
    private static $vendorMap = [];
    
    private static $moduleName = '';
    
    /**
     * 绑定模块
     * @param string $module
     */
    public static function bindModule($module){
        self::$moduleName = $module;
    }
    
    /**
     * 添加命名空间基础目录
     */
    public static function addNamespace(Array $namespace = []){
        self::$vendorMap = [
            'application' => ROOT_PATH.DIRECTORY_SEPARATOR.'application',
            'system' => ROOT_PATH.DIRECTORY_SEPARATOR.'system'
        ];
        if (!empty($namespace)){
            self::$vendorMap = array_merge(self::$vendorMap,$namespace);
        }
    }
    
    /**
     * 自动加载器
     */
    public static function autoload($className){
        $file = self::findFile($className);
        if (file_exists($file)) {
            echo $file."\n";
            self::includeFile($file);
        }
    }
    
    /**
     * 解析文件路径
     */
    public static function findFile($className){
        $namespace = substr($className, 0, strpos($className, '\\')); // 顶级命名空间
        $namespacePath = self::$vendorMap[$namespace]; // 文件基目录
        $className = str_replace('\\', DIRECTORY_SEPARATOR, $className);
        $filePath = substr($className, strlen($namespace)).'.php'; // 文件相对路径
        return str_replace('\\', DIRECTORY_SEPARATOR, $namespacePath.$filePath); // 文件标准路径
    }
    
    /**
     * 引入文件
     */
    private static function includeFile($file){
        if (is_file($file)) {
            return include_once $file;
        }
    }
    
}

//spl_autoload_register('\system\Loader::autoload'); // 注册自动加载