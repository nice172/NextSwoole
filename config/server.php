<?php
$config['server']['host'] = '0.0.0.0';
$config['server']['port'] = 9501;
$config['server']['worker_num'] = 2;
$config['server']['task_worker_num'] = 2;
$config['server']['max_request'] = 10000;
$config['server']['daemonize'] = 0;

//配置静态文件根目录，与enable_static_handler配合使用。
$config['server']['document_root'] = '/opt/www/NextSwoole/public';
$config['server']['enable_static_handler'] = true;

//数据库连接池
$config['server']['server_num'] = 5;

return $config;