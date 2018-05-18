<?php
namespace appliaction\controller;

class User extends Base {
	
	public function init(){
		$this->response->end('user index');
	}
	
}