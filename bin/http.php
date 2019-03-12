<?php
namespace system;
require '../system/Application.php';
$args = isset($argv[1]) ? $argv[1] : '';
Application::getInstance()->run($args);