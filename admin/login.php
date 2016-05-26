<?php
session_start();
//include '../function.php';
//include dirname(dirname(__FILE__)).'/function.php';
include dirname(dirname(__FILE__)) . '/inti.php';
//include __DIR__.'/globals.php';
if (is_post()) {
    var_dump($_POST);
    $u = isset($_POST['u']) ? addslashes($_POST['u']) : '';
    $p = isset($_POST['p']) ? addslashes($_POST['p']) : '';
    if ($u != '' && $p != '') {

        //初始化stmt子类
        $stmt = $db->stmt_init();
        //var_dump($stmt);
        $sql = 'SELECT * FROM `table1` WHERE `name`=? and `pass`=?';
        var_dump($sql);
        //创建SQL模板？问号是占位符，注意，如果要绑定的值是字符串，都不用加引号包裹占位符
        //预处理sql语句，用于判断sql语句中的表，字段，是否存在
        if (!$stmt->prepare($sql)) {
            //如果预处理失败。输出错误信息
            zmMsg($stmt->error);
        }

        //绑定参数
        if (!$stmt->bind_param('ss', $u, $p)) {
            //如果绑定失败，输出错误信息
            zmMsg($stmt->error());
        }
        if (!$stmt->execute()) {
            zmMsg($stmt->error);
        }

        $stmt->bind_result($i, $name, $pass);
        $arr = array();
        while ($stmt->fetch()) {
            $arr[] = array($i, $name, $pass);
        }
        var_dump($arr);
        if (isset($arr) && $arr != null) {//判断如果数组有值并且不能为空那么久跳转，否则找号密码错误


            if (isset($_SESSION['SB']) && $_SESSION['SB'] != null) {
                if (($_SESSION['SB']) == ($_POST['code'])) {
                    if (isset($_POST['check'])) {
                        setcookie('u', $u, time() + 3600);
                        setcookie('p', $p, time() + 3600);
                    } else {

                        if (isset($_COOKIE['user']) && isset($_COOKIE['pass'])) {
                            setcookie('u', $u, time() - 1);
                            setcookie('p', $p, time() - 1);
                        }

                    }
                    header('location:w_log.php');
                } else {
                    echo '验证码错误';
                }


            } else {
                echo '账号密码错误';
            }
        }
    }


} else {
    echo '账号或密码不能空';


}


include 'views/login.html';
