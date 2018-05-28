<?php
namespace system;

require_once 'Loader.php';
require_once 'SwooleBase.php';

//创建一个异步非阻塞的http服务器
//查看进程命令： ps aux | grep my_swoole_http

//热加载代码不需要重启服务
//创建一个：reload.sh
//输入：#!/usr/bin/env bash
//ps aux | grep my_swoole_http_master | awk '{print $2}' | xargs kill -USR1

class HttpServer extends SwooleBase {
    
    private $server = null;
    private static $instance = null;
    private $mysqlPool = null;
    private $objectPool = [];
    private $redisPool = [];
    private $fileStatus = true;
    /**
     * * onStart * onShutdown * onWorkerStart * onWorkerStop * onTimer * onConnect * onReceive
     * * onClose * onTask * onFinish * onPipeMessage * onWorkerError * onManagerStart
     * * onManagerStop WebSocket * onOpen * onHandshake * onMessage
     */
    protected function __construct(){
        parent::__construct();
        $this->server = new \swoole_http_server(self::$conifg['server']['host'], self::$conifg['server']['port']);
        $this->setServerConfig();
        $this->onStart();
        $this->onShutdown();
        $this->onWorkerStart();
        $this->onWorkerStop();
        $this->onRequest();
        $this->onTimer();
        $this->onTask();
        $this->onFinish();
        $this->onPipeMessage();
        $this->onWorkerError();
        $this->onManagerStart();
        $this->onManagerStop();
        $this->onClose();
        $this->init();
    }
    
    private function __clone(){}
    
    public static function getInstance(){
        if (!(self::$instance instanceof self)){
           self::$instance = new self(); 
        }
        return self::$instance;
    }
    
    private function onStart(){
        $this->server->on('start', function($server){
            //设置进程名称
            swoole_set_process_name('ns_master');
        });
    }
    
    private function onWorkerStart(){
        $this->server->on('WorkerStart', function(\swoole_http_server $server, $worker_id){
            swoole_set_process_name('ns_worker');
        });
    }
    
    private function init(){
        \system\Loader::addNamespace();
        spl_autoload_register('\system\Loader::autoload');
        $this->mysqlPool = new \SplQueue();
        //if ($this->mysqlPool->count() <= 0){
        //    for ($i=0; $i < self::$conifg['server']['server_num']; $i++){
        //        $this->mysqlPool->enqueue(Db::getInstance());
        //    }
        //}
        define('APP_DEBUG', true);
    }
    
    private function onWorkerStop(){
        $this->server->on('WorkerStop', function(\swoole_http_server $server, $worker_id){
            
        });
    }
    
    private function onManagerStart(){
        $this->server->on('ManagerStart', function(){
            swoole_set_process_name('ns_manager');
        });
    }
    
    private function onWorkerError(){}
    
    // http://localhost.com/home/index
    private function onRequest(){
        $this->server->on('request', function(\swoole_http_request $request, \swoole_http_response $response){
           if ($request->server['path_info'] == '/favicon.ico') {
               $response->end();return;
           }           
           $request = new Request($request);
           $controller = $request->parseRoute();
           $controllerName = $controller['controllerName'];
           if (isset($this->objectPool[$controllerName]) && is_object($this->objectPool[$controllerName])){
               $controllerInstance = $this->objectPool[$controllerName];
               $controllerInstance->setResponse($response);
           }else{
               \system\Loader::bindModule($controller['moduleName']);
               \system\Loader::addNamespace();
               $this->fileStatus = file_exists(ROOT_PATH.str_replace('\\', '/', $controllerName).'.php');
               if ($this->fileStatus == true){
                   $controllerInstance = new $controllerName($response);
                   $this->objectPool[$controllerName] = $controllerInstance;
               }
           }
           
           if ($this->fileStatus == true){
               $methodName = $controller['methodName'];
               if (method_exists($controllerInstance, $methodName)){
                   $controllerInstance->setMysqlPool($mysql=[]);
                   $controllerInstance->$methodName();
                   return;
               }
           }
           $this->fileStatus = true;
           $response->status(404);
           $response->end('<h2>404 NOT FOUND...</h2>');
        });
    }
    
    private function onManagerStop(){}
    
    private function onTimer(){}
    
    private function onShutdown(){}
    
    private function onTask(){
        $this->server->on('task', function(\swoole_http_server $serv, $task_id, $src_worker_id,$data){
            
        });
    }
    
    private function onFinish(){
        $this->server->on('finish', function(\swoole_http_server $serv, $task_id, $data){
            
        });
    }
    
    // 欢迎信息
    private function welcome(){
    	$swooleVersion = swoole_version();
    	$phpVersion = PHP_VERSION;
    	self::send("NextSwoole");
    	self::send('Server    Name: NS-Http');
    	self::send("PHP    Version: {$phpVersion}");
    	self::send("Swoole Version: {$swooleVersion}");
    	self::send("Listen Address: ".self::$conifg['server']['host']);
    	self::send("Listen    Port: ".self::$conifg['server']['port']);
    }
    
    // 发送至屏幕
    private static function send($msg){
    	$time = date('Y-m-d H:i:s');
    	echo "[{$time}] " . $msg . PHP_EOL;
    }
    
    private function onClose(){}
    
    private function onPipeMessage(){}
    
    private function setServerConfig(){
        $this->server->set([
            'reactor_num' => self::$conifg['server']['reactor_num'],
            'worker_num' => self::$conifg['server']['worker_num'],
            'max_request' => self::$conifg['server']['max_request'],
            'task_worker_num' => self::$conifg['server']['task_worker_num'],
            'pid_file' => __DIR__.'/server.pid',
            'daemonize' => self::$conifg['server']['daemonize'],
            'document_root' => self::$conifg['server']['document_root'],
            'enable_static_handler' => self::$conifg['server']['enable_static_handler']
        ]);
    }
    
    public function run($config){
        self::$conifg = array_merge(self::$conifg,$config);
        $this->welcome();
        $this->server->start();
    }
    
}
