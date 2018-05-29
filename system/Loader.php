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
        echo str_replace('\\', DIRECTORY_SEPARATOR, $namespacePath.$filePath)."\n\n";
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
     * 实例化验证类 格式：[模块名/]验证器名
     * @access public
     * @param  string $name         资源地址
     * @param  string $layer        验证层名称
     * @param  bool   $appendSuffix 是否添加类名后缀
     * @param  string $common       公共模块名
     * @return object|false
     * @throws ClassNotFoundException
     */
    public static function validate($name = '', $layer = 'validate', $appendSuffix = false, $common = 'common'){
    	$name = $name ?: Config::get('default_validate');
    	
    	if (empty($name)) {
    		return new Validate;
    	}
    	
    	$uid = $name . $layer;
    	if (isset(self::$instance[$uid])) {
    		return self::$instance[$uid];
    	}
    	
    	list($module, $class) = self::getModuleAndClass($name, $layer, $appendSuffix);
    	
    	if (class_exists($class)) {
    		$validate = new $class;
    	} else {
    		$class = str_replace('\\' . $module . '\\', '\\' . $common . '\\', $class);
    		
    		if (class_exists($class)) {
    			$validate = new $class;
    		} else {
    			throw new ClassNotFoundException('class not exists:' . $class, $class);
    		}
    	}
    	
    	return self::$instance[$uid] = $validate;
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
    
    /**
     * 解析应用类的类名
     * @access public
     * @param  string $module       模块名
     * @param  string $layer        层名 controller model ...
     * @param  string $name         类名
     * @param  bool   $appendSuffix 是否添加类名后缀
     * @return string
     */
    public static function parseClass($module, $layer, $name, $appendSuffix = false){
    	$array = explode('\\', str_replace(['/', '.'], '\\', $name));
    	$class = self::parseName(array_pop($array), 1);
    	$class = $class . (App::$suffix || $appendSuffix ? ucfirst($layer) : '');
    	$path  = $array ? implode('\\', $array) . '\\' : '';
    	
    	return App::$namespace . '\\' .
      	($module ? $module . '\\' : '') .
      	$layer . '\\' . $path . $class;
    }
    
}

//spl_autoload_register('\system\Loader::autoload'); // 注册自动加载