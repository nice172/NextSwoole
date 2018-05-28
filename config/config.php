<?php

return [
    'basePath' => dirname(__DIR__),
    'module' => ['home','admin'], // 模块
    'default_module' => 'home', // 默认模块
    'default_controller' => 'Index', // 默认控制器
    'default_method' => 'init', // 默认方法
    'app_debug' => true,
    'runtime_path' => '/runtime',
    'log_path' => '/runtime/log',
    // 数据库配置
	'database' => [
		// 数据库类型
		'type'            => 'mysql',
		// 服务器地址
		'hostname'        => '127.0.0.1',
		// 数据库名
		'database'        => 'weili_shenzhen',
		// 用户名
		'username'        => 'root',
		// 密码
		'password'        => '123456',
		// 端口
		'hostport'        => '',
		// 连接dsn
		'dsn'             => '',
		// 数据库连接参数
		'params'          => [],
		// 数据库编码默认采用utf8
		'charset'         => 'utf8',
		// 数据库表前缀
		'prefix'          => 'wl_',
		// 数据库调试模式
		'debug'           => true,
		// 数据库部署方式:0 集中式(单一服务器),1 分布式(主从服务器)
		'deploy'          => 0,
		// 数据库读写是否分离 主从式有效
		'rw_separate'     => false,
		// 读写分离后 主服务器数量
		'master_num'      => 1,
		// 指定从服务器序号
		'slave_no'        => '',
		// 是否严格检查字段是否存在
		'fields_strict'   => true,
		// 数据集返回类型
		'resultset_type'  => 'array',
		// 自动写入时间戳字段
		'auto_timestamp'  => false,
		// 时间字段取出后的默认时间格式
		'datetime_format' => 'Y-m-d H:i:s',
		// Query类
		'query'           => '\system\db\Query',
		// 是否需要进行SQL性能分析
		'sql_explain'     => false,
	]
];





