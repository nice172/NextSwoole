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
        if (empty(self::$vendorMap)) {
            self::$vendorMap = [
                'application' => ROOT_PATH.DIRECTORY_SEPARATOR.'application',
                'system' => ROOT_PATH.DIRECTORY_SEPARATOR.'system'
            ];
        }
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
    
    
    /**
     * 字符串命名风格转换
     * type 0 将Java风格转换为C的风格 1 将C风格转换为Java的风格
     * @param string  $name 字符串
     * @param integer $type 转换类型
     * @param bool    $ucfirst 首字母是否大写（驼峰规则）
     * @return string
     */
    public static function parseName($name, $type = 0, $ucfirst = true){
    	if ($type) {
    		$name = preg_replace_callback('/_([a-zA-Z])/', function ($match) {
    			return strtoupper($match[1]);
    		}, $name);
    			return $ucfirst ? ucfirst($name) : lcfirst($name);
    	} else {
    		return strtolower(trim(preg_replace("/[A-Z]/", "_\\0", $name), "_"));
    	}
    }
    
    /**
     * 解析模块和类名
     * @access protected
     * @param  string $name         资源地址
     * @param  string $layer        验证层名称
     * @param  bool   $appendSuffix 是否添加类名后缀
     * @return array
     */
    protected static function getModuleAndClass($name, $layer, $appendSuffix){
    	if (false !== strpos($name, '\\')) {
    		$module = Request::instance()->module();
    		$class  = $name;
    	} else {
    		if (strpos($name, '/')) {
    			list($module, $name) = explode('/', $name, 2);
    		} else {
    			$module = Request::instance()->module();
    		}
    		
    		$class = self::parseClass($module, $layer, $name, $appendSuffix);
    	}
    	
    	return [$module, $class];
    }
    
    // 注册加载器
    public static function register(){
        self::addNamespace();
        spl_autoload_register('\system\Loader::autoload'); // 注册自动加载
    }
    
}