<?php

namespace application\home\controller;

use application\home\model\User;

class Index extends Base {
	
	public function init(){
// 		parent::init();

		$user = new User();
		$data = $user->wx_userlist();
		print_r($data);
		$this->response->end(json_encode($data));

		
		
	}
	
	
	
}