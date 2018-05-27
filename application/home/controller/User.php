<?php

namespace application\home\controller;

use application\home\model\User;

class User extends Base {
	
	public function init(){

		$userModel = new User();
		
		//$this->response->end('user index');
		
	}
	
}