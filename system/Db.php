<?php
namespace system;

class Db {
    
    private static $instance = null;
    
    public static $MySqlPool = [];
    
    private function __construct($linkId=null){
        $this->connect($linkId);
    }
    
    private function __clone(){}
    
    public static function getInstance(){
        file_put_contents('connect.txt', "getInstance\r\n",FILE_APPEND);
        if (!(self::$instance instanceof self)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function connect($linkId=null){
        $mysqli = mysqli_connect('localhost','root','123456','weili');
        if (mysqli_connect_error()){
            return false;
        }
        if ($linkId !== null){
            self::$MySqlPool[$linkId] = $mysqli;
        }else{
            self::$MySqlPool[] = $mysqli;
        }
        return true;
    }
    
}