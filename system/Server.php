<?php
namespace system;
//创建一个异步非阻塞的http服务器
//查看进程命令： ps aux | grep my_swoole_http

//热加载代码不需要重启服务
//创建一个：reload.sh
//输入：#!/usr/bin/env bash
//ps aux | grep my_swoole_http_master | awk '{print $2}' | xargs kill -USR1

class Server {
    
    private $server = null;
    private static $instance = null;
    private $config = [];
    
    private function __construct(){}
    
    private function __clone(){}

    public static function getInstance(){
        if (!(self::$instance instanceof self)){
           self::$instance = new self(); 
        }
        return self::$instance;
    }
    
    public function setConfig($config){
        $this->config = $config;
    }
    
    public function run(){
        /**
         * * onStart * onShutdown * onWorkerStart * onWorkerStop * onTimer * onConnect * onReceive
         * * onClose * onTask * onFinish * onPipeMessage * onWorkerError * onManagerStart
         * * onManagerStop WebSocket * onOpen * onHandshake * onMessage
         */
        $this->welcome();
        
        $serverClass = isset($this->config['server']['type']) && !empty($this->config['server']['type']) ? "\\".$this->config['server']['type'] : "\swoole_http_server";        
        $this->server = new $serverClass($this->config['server']['host'], $this->config['server']['port']);
        $this->setServerConfig();
        
        $this->server->on('start', [$this,'onStart']);
        $this->server->on('workerStart', [$this,'onWorkerStart']);
        $this->server->on('workerStop', [$this, 'onWorkerStop']);
        $this->server->on('managerStart', [$this, 'onManagerStart']);
        $this->server->on('request', [$this, 'onRequest']);
        $this->server->on('task', [$this, 'onTask']);
        $this->server->on('finish', [$this, 'onFinish']);
        
        $this->server->start();
    }
    
    private function setServerConfig(){
        $this->server->set([
            'reactor_num' => $this->config['server']['reactor_num'],
            'worker_num' => $this->config['server']['worker_num'],
            'max_request' => $this->config['server']['max_request'],
            'task_worker_num' => $this->config['server']['task_worker_num'],
            'pid_file' => ROOT_PATH.'/bin/server.pid',
            'daemonize' => $this->config['server']['daemonize'],
            'document_root' => $this->config['server']['document_root'],
            'enable_static_handler' => $this->config['server']['enable_static_handler']
        ]);
    }
    
    public function onStart($server){
        //设置进程名称
        swoole_set_process_name('swoole_master_name');
    }
    
    public function onWorkerStart(){
        swoole_set_process_name('swoole_worker_name');
    }
    
    public function onWorkerStop(){
        
    }
    
    public function onManagerStart(){
        swoole_set_process_name('swoole_manager_name');
    }

    // http://localhost.com/home/index
    public function onRequest(\swoole_http_request $request, \swoole_http_response $response){
        if ($request->server['path_info'] == '/favicon.ico') {
            $response->end();return;
        }        
        Application::getInstance()->http($this->server, $request, $response);
    }
    
    private function onManagerStop(){}
    
    private function onTimer(){}
    
    private function onShutdown(){}
    
    public function onTask(\swoole_http_server $serv, $task_id, $src_worker_id, $data){
        
    }
    
    public function onFinish(\swoole_http_server $serv, $task_id, $data){
        
    }
    
    // 欢迎信息
    private function welcome(){
    	$swooleVersion = swoole_version();
    	$phpVersion = PHP_VERSION;
    	self::write("NextSwoole");
    	self::write('Server    Name: next-swoole-Http');
    	self::write("PHP    Version: {$phpVersion}");
    	self::write("Swoole Version: {$swooleVersion}");
    	self::write("Listen Address: ".$this->config['server']['host']);
    	self::write("Listen    Port: ".$this->config['server']['port']);
    }
    
    // 发送至屏幕
    private static function write($msg){
    	$time = date('Y-m-d H:i:s');
    	echo "[{$time}] " . $msg . PHP_EOL;
    }
    
    private function onClose(){}
    
    private function onPipeMessage(){}
        
}
