<?php

/**
 * Created by PhpStorm.
 * User: 骆－剑
 * Date: 2016/5/24
 * Time: 10:49
 */
class Login
{
    private $mysql;

    /**
     * Login constructor.  析构函数
     *
     */

    public function __construct()
    {
        session_start();
        $this->mysql = new Mysqlim;//实例化数据库连接

    }

    /**
     * login 登录
     *
     */

    public function login()
    {
        if ($this->check_login()) {
            echo 'sb';
//            go('w_log.php');
        }
        if (is_post())
            echo 1;
             //var_dump(strtoupper($_SESSION['code']), strtoupper($_POST['code']));//检查$_SESSION下面的验证码 与POST提交上来的验证码
            if (!empty($_SESSION['code']) && !empty($_POST['code']) && strtoupper($_SESSION['code']) == strtoupper($_POST['code'])) {
                //账号
                $u = isset($_POST['u']) ? addslashes($_POST['u']) : '';
                //密码
                $p = isset($_POST['p']) ? addslashes($_POST['p']) : '';
                var_dump($u, $p);//检查用户名密码和账户是否有值
                if ($u != '' && $p != '') {
                    $sql = 'SELECT * FROM `table1` WHERE `name`=%s and `pass`=%s LIMIT 1';
                    echo 1;
                    if (!$this->mysql->bind_query($sql, array($u, $p))) {
                        echo 2;
                        zmMsg($this->mysql->get_stmt_error());
                    }
                    if (($row = $this->mysql->fetch(true)) === false) {
                        zmMsg($this->mysql->get_stmt_errno());
                    }

                    if (!empty($row)) {
                        //var_dump($row);
                        $info = serialize($row);
                        $_SESSION['user_info'] = $info;
                        $time=isset($_POST['ispersis'])? time()+3600*24*7 : 0 ;
                        $value=$row['name'] . '@' . md5($info);
                        setcookie('user_info',$value,$time,'/');
                        echo 'D';
                       go('w_log.php');
                    } else {
                        $msg = '用户名密码不正确';
                    }
                } else {
                    $msg = '用户密码不能为空';

                }
            } else {

                $msg='验证码错误';



        }

        include 'views/index_log.html';
        exit();
    }

    /**
     * check_login  验证登录
     *
     *
     *
     *
     */

    public function check_login(){

        //检查存在COOLIE的信息
      if (!empty($_COOKIE['user_info'])){
          //切割字符串获取授权信息
          $info=explode('@',$_COOKIE['user_info']);    var_dump($info);
          //判断切割后的值是否符合标准
          if (count($info)!=2){
              return false;
          }
          //判断是否存在session

          if (!empty($_SESSION['user_info'])){
              //判断session中的值是否与coolie的值全等
              return md5($_SESSION['user_info'])===$info[1];
          }else{
              //不存在去数据库取
              $sql = 'SELECT * FROM `table1` WHERE `name`=%s LIMIT 1 ';
              if (!$this->mysql->bind_query($sql,array($info[0]))){
                  zmMsg($this->mysql->get_stmt_error());
              }
              $row = $this->mysql->fetch(true);
              var_dump($row);

               //判断数据库是否存在对应信息
              if (empty($row)){
                  return false;
              }
              //获取数据库是否存在对应信息
              $u=serialize($row);
              //判断数据库取出的信息是否与cookie中的信息匹配
             if (md5($u)===$info[1]){
                 //如果匹配设置session
                 $_SESSION['user_info']=$u;
                 return true;
             }else{
                 return false;
             }
          }

      }else{
          return false;
      }
    }

    /**
     * 退出登录
     *
     */

    public  function logout(){

    setcookie('user_info','',-1,'/','',false,true);
    session_destroy();
        go('index.php');

    }









}






