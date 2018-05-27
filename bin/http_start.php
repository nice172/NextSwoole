<?php

use system\HttpServer;

$config = require dirname(__DIR__).'/config/config.php';
require $config['basePath'].'/system/HttpServer.php';

HttpServer::getInstance()->run($config);

