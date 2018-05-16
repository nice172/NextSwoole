<?php

 /**
 * 获取配置参数
 * @param string $name
 * @param string $value
 * @return string|string[]|array
 */
function C($name='',$value=''){
    static $config = [];
    if (is_array($name) && $value == ''){
        $config = array_merge($config,array_change_key_case($name,CASE_UPPER));
    }elseif (is_string($name) && $value != ''){
        $config[strtoupper($name)] = $value;
    }elseif ($name != ''){
        $name = strtoupper($name);
        return isset($config[$name]) ? $config[$name] : '';
    }else{
        return $config;
    }
}
$config = array(
   'db_host' => 'localhost',
   'DB_PWD' => 123456,
   'DB_USER' => 'admin'
);
C($config);
