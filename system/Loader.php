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
     * 添加命名空间
     */
    public static function addNamespace(Array $namespace = []){
        self::$vendorMap = [
            'app' => ROOT_PATH.DIRECTORY_SEPARATOR.'application'.DIRECTORY_SEPARATOR.self::$moduleName,
            'system' => ROOT_PATH.DIRECTORY_SEPARATOR.'system'
        ];
        if (!empty($namespace)){
            self::$vendorMap = array_merge(self::$vendorMap,$namespace);
        }
    }
    
    /**
     * 自动加载器
     */
    public static function autoload($class){
        $file = self::findFile($class);
        if (file_exists($file)) {
            self::includeFile($file);
        }
    }
    
    /**
     * 解析文件路径
     */
    private static function findFile($class){
        $vendor = substr($class, 0, strpos($class, '\\')); // 顶级命名空间
        $vendorDir = self::$vendorMap[$vendor]; // 文件基目录
        $filePath = substr($class, strlen($vendor)) . '.php'; // 文件相对路径
        return str_replace('\\', '/', strtr($vendorDir . $filePath, '\\', DIRECTORY_SEPARATOR)); // 文件标准路径
    }
    
    /**
     * 引入文件
     */
    private static function includeFile($file){
        if (is_file($file)) {
            include_once $file;
        }
    }
    
}

//spl_autoload_register('\system\Loader::autoload'); // 注册自动加载