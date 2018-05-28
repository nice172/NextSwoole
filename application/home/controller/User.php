<?php

namespace application\home\controller;
use application\home\model\User as userModel;

class User extends Base {
	
	public function init(){

	    $userModel = new userModel();
		
		//$this->response->end('user index');
		
	}
	
}