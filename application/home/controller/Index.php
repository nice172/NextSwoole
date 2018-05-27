<?php

namespace application\home\controller;

use application\home\model\User;

class Index extends Base {
	
	protected function _initialize(){
		parent::_initialize();
		//echo __METHOD__;
	}
	
	public function init(){
		$user = new User();
		$data = $user->wx_userlist();
		ob_start();
		print_r($data);
		include '/opt/www/NextSwoole/application/home/view/index/index.php';
		$content = ob_get_contents();
		ob_end_clean();
		$this->response->end($content);
	}
	
	
	
}