<?php
namespace system;
class Log {
    
    private static $log_path = '';
    
    private static $logfile = '';
    
    /**
     * 检查目录是否存在并创建日志文件名
     */
    private static function checkPath(){
        $basePath = Config::getInstance()->getConfig('basePath');
        self::$log_path = $basePath.Config::getInstance()->getConfig('log_path');
        self::$logfile = self::$log_path.DIRECTORY_SEPARATOR.date('Ymd').'.log';
        $runtimePath = $basePath.Config::getInstance()->getConfig('runtime_path');
        if (!is_dir($runtimePath)){
            mkdir($runtimePath,0777);
        }
        if (!is_dir(self::$log_path)){
            mkdir(self::$log_path,0777);
        }
    }
    
    public static function record($msg,$type=''){
        self::checkPath();
        self::bakFile();
        $fp = fopen(self::$logfile, 'a+'); // 打开文件或创建文件
        if (!is_writable(self::$logfile)) exit('日志文件不可写');       
        flock($fp, LOCK_EX); // 锁定文件
        fwrite($fp, "[".date('Y-m-d H:i:s')."] ".$msg."\r\n\r\n");
        flock($fp, LOCK_UN); // 解锁文件
        fclose($fp); // 关闭文件
    }
    
    /**
     * 判断文件大小并备份
     */
    private static function bakFile(){
        if (file_exists(self::$logfile) && filesize(self::$logfile)/1024/1024 >= 2){
            rename(self::$logfile, self::$logfile.'.bak');
        }
    }
 
}