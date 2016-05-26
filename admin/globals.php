<?php
/**
 * Created by PhpStorm.
 * User: 骆－剑
 * Date: 2016/5/24
 * Time: 14:54
 */

include dirname(dirname(__FILE__)) . '/inti.php';
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';
$abc = new Login();
var_dump($abc->check_login());
if (!$abc->check_login()) {

    $abc->login();
//    echo 'sb';
}
//退出
if (isset($_REQUEST['logout'])){
    $abc->logout();
}