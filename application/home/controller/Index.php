<?php

namespace application\home\controller;

use application\home\model\User;
use system\Db2;

class Index extends Base {
	private $db = null;
	protected function _initialize(){
		parent::_initialize();
		//echo __METHOD__;
		
	}
	
	public function init(){
	    new User();
	    
	    $this->db = mysqli_connect('localhost','root','123456','weili_shenzhen');
	    if (mysqli_connect_error()){
	        return false;
	    }
	    $id = rand(1,62);
	    $sql = "select * from wl_cosp_goods_info where id=$id";
	    $result = $this->db->query($sql);
	    foreach ($result as $data){
	        
	    }
		// 页面缓存
		ob_start();
		ob_implicit_flush(0);
		include '/opt/www/NextSwoole/application/home/view/index/index.php';
		$content = ob_get_clean();
		$this->response->end($content);
	}
	
	
	
}