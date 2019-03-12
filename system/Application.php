<?php
namespace system;

define('APP_DEBUG', true);
define('ROOT_PATH', dirname(dirname(__FILE__)));

date_default_timezone_set('PRC');

require_once 'Loader.php';

class Application {
    
    private static $instance = null;
    private $server = null;
    private $objectPool = [];
    
    private function __construct() {
        // 注册加载器
        Loader::register();
    }
    
    private function __clone() {}
    
    public static function getInstance(){
        if (!(self::$instance instanceof self)){
            echo 'create app'.PHP_EOL;
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function run($args = ''){
        if (php_sapi_name() != 'cli'){
            echo "仅允许在命令行模式下运行".PHP_EOL;
            exit;
        }
        if (version_compare(PHP_VERSION, '7.1','<')){
            echo "PHP版本必须大于等于7.1，当前版本:".PHP_VERSION.PHP_EOL;
            exit;
        }
        if (!extension_loaded('swoole')){
            echo "必须安装Swoole扩展".PHP_EOL;
            exit;
        }
        if (version_compare(swoole_version(), '2.2', '<')) {
            echo "Swoole版本必须大于等于2.2，当前版本:".swoole_version().PHP_EOL;
            exit;
        }
        $option = array('start','restart','reload','stop');
        if (empty($args) || !in_array($args, $option)) {
            echo "Usage:".PHP_EOL;
            echo "  php http.php [start|restart|reload|stop]".PHP_EOL;
            exit;
        }
        
        $config = Config::getInstance()->getConfig();
        switch ($args) {
            case 'start':
                $this->server = Server::getInstance();
                $this->server->setConfig($config);
                $this->server->run();
                break;
            case 'restart':
                
                break;
            case 'reload':
                
                break;
            case 'stop':
                
                break;
        }
    }
    
    public function http(\swoole_http_server $server,\swoole_http_request $request, \swoole_http_response $response){
        
        Response::getInstance()->setResponse($response);
        $route = Route::getInstance()->setRequest($request)->parseRoute();
        
        $controllerName = $route['controllerName'];
        
        Loader::bindModule($route['moduleName']);
        
        if (!isset($this->objectPool[$controllerName])){
            $filename = ROOT_PATH.str_replace('\\', '/', $controllerName).'.php';
            if (file_exists($filename)){
                if (class_exists($controllerName)){
                    $controllerInstance = new $controllerName();
                    $this->objectPool[$controllerName] = $controllerInstance;
                }
            }
        }
        $method = $route['methodName'];
        if (isset($this->objectPool[$controllerName]) && method_exists($this->objectPool[$controllerName], $method)){
            $this->objectPool[$controllerName]->$method();
            return;
        }
        $response->status(404);
        $response->end('<h2>404 NOT FOUND...</h2>');
    }
    
    public function websocket(){
        
    }
    
}