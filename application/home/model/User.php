<?php
namespace application\home\model;
use system\Model;

class User extends Model{
	
	protected $table = 'wl_wx_users';
	
	public function wx_userlist(){
		return $this->where(['id' => rand(3,13)])->find();
	}
	
}