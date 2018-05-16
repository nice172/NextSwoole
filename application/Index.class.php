<?php
class Index extends Base {
	
	public function init(){
// 		parent::init();
        $sql = "select id,login_name from wl_users";
        $result = self::$mysqlPool[0]->query($sql);
        if (!$result){
            echo "链接失败\n";
        }else{
            $data = $result->fetch_all(MYSQLI_ASSOC);
            print_r($data);
        }
		$this->response->end('Index init方法');
	}
	
	
	
}