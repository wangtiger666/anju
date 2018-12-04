<?php
	/*echo "进入pano文件里面了158";die();*/
    set_time_limit(0);
    ini_set('memory_limit','1024M');
    define('APP_ROOT', str_replace("\\", '/', dirname(__FILE__)));
    define('APP_NAME', 'Yun');
    define('APP_PATH', './Yun/');
    define('APP_DEBUG', TRUE);
    require 'ThinkPHP/ThinkPHP.php';
?>
