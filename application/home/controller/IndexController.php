<?php

namespace application\home\controller;

use application\home\model\UserModel;

class IndexController extends BaseController {
	private $db = null;
	protected function _initialize(){
		parent::_initialize();
	}
	
	public function init(){
	    $userModel = new UserModel();
	    $data = $userModel->wx_userlist();
	    $this->response->end(json_encode($data));
	}
	
}