<?php
$config['server']['type'] = 'swoole_http_server';
$config['server']['host'] = '0.0.0.0';
$config['server']['port'] = 9501;
$config['server']['reactor_num'] = 1;
$config['server']['worker_num'] = 1;
$config['server']['task_worker_num'] = 1;
$config['server']['max_request'] = 10000;
$config['server']['daemonize'] = 0;

//配置静态文件根目录，与enable_static_handler配合使用。
$config['server']['document_root'] = '/opt/www/NextSwoole/public';
$config['server']['enable_static_handler'] = true;

return $config;