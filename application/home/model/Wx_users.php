<?php

namespace application\home\model;

use system\Model;

class Wx_users extends Model {
    

    public function get_user(){
        $data = $this->where(['id' => 10])->find();
        print_r($data['id']);
        echo $this->getLastSql();
//         print_r($data['id']);

    }
    
}

