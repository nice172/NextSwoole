<?php
define('AUTO_RELOAD', true);

date_default_timezone_set('PRC');

$path = dirname(__DIR__);

if (!AUTO_RELOAD) return;

function get_file($path, $clear = false){
    static $files = [];
    if($clear) {
        $files = [];
        return;
    }
    $fp = opendir($path);
    while (($file = readdir($fp)) !== false){
        if ($file != '.' && $file != '..'){
            if (is_dir($path.'/'.$file)){
                $files = array_merge($files,get_file($path.'/'.$file));
            }else{
                $files[] = [
                    'file' => $path.'/'.$file,
                    'last_time' => filectime($path.'/'.$file)
                ];
            }
        }
    }
    closedir($fp);
    return $files;
}

$autoload = [
    $path.'/application',
    $path.'/common',
    $path.'/config',
    $path.'/system'
];

$defaultFiles = array();
foreach ($autoload as $dir){
    $defaultFiles = get_file($dir);
}

swoole_timer_tick(1000, function($timer_id,$params) use ($path, $autoload, &$defaultFiles) {
    $pidFile = __DIR__.'/server.pid';
    $server_pid = 0;
    if (file_exists($pidFile)){
        $server_pid = file_get_contents(__DIR__.'/server.pid');
    }
    $flag = false;
    if ($server_pid) {
        
        get_file('', true);
        $newFiles = array();
        foreach ($autoload as $dir){
            $newFiles = get_file($dir); //每次都获取最新文件
        }
        
        if (count($newFiles) != count($defaultFiles)) {
            $defaultFiles = $newFiles;
            $flag = true;
        }
        
        if ($flag == false){
            foreach ($defaultFiles as $oldKey => $oldFile){
                foreach ($newFiles as $newKey => $newFile){
                    if ($oldFile['file'] == $newFile['file'] && $oldFile['last_time'] < $newFile['last_time']){
                        $defaultFiles = $newFiles;
                        $flag = true;
                        break;
                    }
                }
                if ($flag == true) break;
            }
        }
        
        // 重启所有worker进程   kill -USR1 主进程PID
        if ($flag == true){
            $flag = false;
            echo "SwooleServer正在重新启动...\n";
            //重启日志
            exec("kill -USR1 ".$server_pid);
        }
    }

},[]);