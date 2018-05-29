<?php
namespace application\home\model;
use system\Model;

class UserModel extends Model{
	
	protected $table = 'wl_cosp_goods_info';
	
	public function wx_userlist(){
	    $id = rand(1,62);
		return $this->where(['id' => $id])->find();
	}
	
}