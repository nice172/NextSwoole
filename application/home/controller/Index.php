<?php

namespace application\home\controller;

use system\Db;

class Index extends Base {
	
	public function init(){
// 		parent::init();
        $sql = "select id,login_name from wl_users";
        $result = self::$mysqlPool[0]->query($sql);
        if (!$result){
            echo "数据库连接池断线重连\n";
            self::$mysqlPool = null;
            Db::getInstance()->connect(0);
            self::$mysqlPool = Db::getInstance()::$MySqlPool;
            $result = self::$mysqlPool[0]->query($sql);
            $data = $result->fetch_all(MYSQLI_ASSOC);
        }else{
            $data = $result->fetch_all(MYSQLI_ASSOC);
        }
		$this->response->end(json_encode($data));
	}
	
	
	
}