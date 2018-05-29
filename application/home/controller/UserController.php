<?php

namespace application\home\controller;

use application\home\model\UserModel;

class UserController extends BaseController {
	
	public function init(){

	    $userModel = new UserModel();
	    $data = $userModel->wx_userlist();
	    print_r($data['id']);
		$this->response->end('user index');
		
	}
	
}