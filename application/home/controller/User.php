<?php
namespace application\home\controller;

class User extends Base {
	
	public function init(){
		$this->response->end('user index');
	}
	
}