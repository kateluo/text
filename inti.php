<?php 
//设置市区
date_default_timezone_set('Asia/shanghai');

//引入配置常量的文件。。
include dirname(__FILE__).'/config.php';
//var_dump(dirname(__FILE__));


//引入自定义函数文件
include dirname(__FILE__).'/include/function.base.php';//等价于./同级目录



//实例化数据库连接
 $db=@new mysqli(DB_HOST,DB_USER,DB_PASS,DB_DATANAME,DB_PORT);
 $db->set_charset('utf8');  //设置数据库编码

if ($db->connect_errno) {
    	die($db->connect_error);
    }
 
 

?>