<?php

/**
 * 自定义绑定数据库链接类
 * $a = new Mysqlim();
 * if (!$a->bind_query('SELECT title,id FROM `article` WHERE 1 LIMIT %d,%d', array(0, 25))) {
 *     var_dump($a->get_stmt_error());
 * }
 * var_dump($a->fetch());
 */
class Mysqlim
{
    /**
     * [$mysql 实例化数据库赋值的变量]
     * @var null
     */
    private $mysql = null;

    /**
     * [$stmt 默认stmt]
     * @var null
     */
    private static $stmt = null;

    public function __construct(
        $db_host = DB_HOST,
        $db_user = DB_USER,
        $db_pass = DB_PASS,
        $db_name = DB_DATANAME,
        $db_port = DB_PORT,
        $db_char = DB_CHAR
    )
    {
        //首先检查类是否存在
        class_exists('Mysqli', false) || zmMsg('PHP服务器不支持Mysqli数据库连接');
        $this->mysql = @new Mysqli($db_host, $db_user, $db_pass, $db_name, $db_port);
        //判断数据库连接状态
        // $this->mysql->connect_errno && $this->error($this->mysql->connect_errno);
        if ($this->mysql->connect_errno) {
            $this->error($this->mysql->connect_errno);
        }
        //设置数据库编码
        $this->mysql->set_charset($db_char);
        method_exists($this->mysql, 'stmt_init') || zmMsg('Mysqli不支持stmt_init方法');
        self::$stmt = $this->mysql->stmt_init();
    }
    /**
     * [bind_query 绑定执行sql]
     * @Author    ZiShang520@gmail.com
     * @DateTime  2016-05-20T15:15:49+0800
     * @copyright (c)                      ZiShang520   All           Rights Reserved
     * @param     [type]                   $querystring [sql语句]
     * @param     [type]                   $prepares    [绑定值数组]
     * @return    [type]                                [布尔]
     */
    public function bind_query($querystring, $prepares = array())
    {
        //配置
        $map = array('%s' => 's', '%d' => 'i', '%f' => 'd', '%b' => 'b');
        // fdi%b
        $expr = '/(' . implode('|', array_keys($map)) . ')/'; //:/(%s|%d|%f|%b)/
        //匹配数据
        if (preg_match_all($expr, $querystring, $matches)) {
            if (count($matches[0]) !== count($prepares)) {
                //var_dump($matches,$prepares);
                zmMsg('SQL传入参数不符合');
            }
            $types = implode('', $matches[0]);
            $types = strtr($types, $map);
            //替换原始sql中的类型为为问好
            $query = preg_replace($expr, '?', $querystring);
            //预处理sql
            if (!self::$stmt->prepare($query)) {
                return false;
            }
            array_unshift($prepares, $types);
            $params = array();
            //解决stmt引用的问题
            foreach ($prepares as $key => $value) {
                $params[$key] = &$prepares[$key];
            }
            //使用回调函数动态传参
            if (!call_user_func_array(array(self::$stmt, 'bind_param'), $params)) {
                return false;
            }
            //执行sql语句
            return self::$stmt->execute(); //true or false
        } else {
            if (!self::$stmt->prepare($querystring)) {
                return false;
            }
            return self::$stmt->execute(); //true or false
        }
    }
    /**
     * [fetch 取出数据]
     * @Author    ZiShang520@gmail.com
     * @DateTime  2016-05-20T15:53:57+0800
     * @copyright (c)                      ZiShang520 All           Rights Reserved
     * @param     boolean                  $status    [判断是否获取一条数据]
     * @return    [type]                              [description]
     */
    public function fetch($status = false)
    {
        //取出数据缓存
        if (!self::$stmt->store_result()) {
            return false;
        }
        //判断是否存在数据
        if (self::$stmt->num_rows <= 0) {
            return null; //因为执行成功，但是并没有数据，和执行出现错误需要区分开
        }
        $result = array();
        //获取数据库连接相关
        $fd = self::$stmt->result_metadata();
        $params = array();
        //获取字段
        while ($field = $fd->fetch_field()) {
            $params[] = &$result[$field->name];
        }
        //调用回调函数
        if (!call_user_func_array(array(self::$stmt, 'bind_result'), $params)) {
            return false;
        }
        //判断是否给予参数用于只获取一条数据
        if ($status) {
            self::$stmt->fetch();
            return $result;
        } else {
            $arr = array();
            while (self::$stmt->fetch()) {
                $data = array();
                //解决一个由于引用带来的问题
                foreach ($result as $key => $value) {
                    $data[$key] = $value;
                }
                $arr[] = $data;
            }
            return $arr; //返回结果
        }
    }
    /**
     * [get_stmt_error 获取错误信息]
     * @Author    ZiShang520@gmail.com
     * @DateTime  2016-05-20T15:09:21+0800
     * @copyright (c)                      ZiShang520    All Rights Reserved
     * @return    [type]                   [description]
     */
    public function get_stmt_error()
    {
        return self::$stmt->error;
    }

    /**
     * [get_stmt_errno 获取错误编号]
     * @Author    ZiShang520@gmail.com
     * @DateTime  2016-05-20T15:12:09+0800
     * @copyright (c)                      ZiShang520    All Rights Reserved
     * @return    [type]                   [description]
     */
    public function get_stmt_errno()
    {
        return self::$stmt->errno;
    }

    /**
     * [get_num_rows 获取返回数据条数]
     * @Author    ZiShang520@gmail.com
     * @DateTime  2016-05-20T16:04:54+0800
     * @copyright (c)                      ZiShang520    All Rights Reserved
     * @return    [type]                   [description]
     */
    public function get_num_rows()
    {
        return self::$stmt->num_rows;
    }

    /**
     * [get_affected_rows 获取响应行数]
     * @Author    ZiShang520@gmail.com
     * @DateTime  2016-05-20T16:06:29+0800
     * @copyright (c)                      ZiShang520    All Rights Reserved
     * @return    [type]                   [description]
     */
    public function get_affected_rows()
    {
        return self::$stmt->affected_rows;
    }

    /**
     * [free_result 销毁数据]
     * @Author    ZiShang520@gmail.com
     * @DateTime  2016-05-20T16:09:03+0800
     * @copyright (c)                      ZiShang520    All Rights Reserved
     * @return    [type]                   [description]
     */
    public function free_result()
    {
        self::$stmt->free_result();
    }
    /**
     * [reset 重置stmt预处理]
     * @Author    ZiShang520@gmail.com
     * @DateTime  2016-05-20T16:13:38+0800
     * @copyright (c)                      ZiShang520    All Rights Reserved
     * @return    [type]                   [description]
     */
    public function reset()
    {
        self::$stmt->reset();
    }
    /**
     * [stmt_close 关闭stmt处理线程]
     * @Author    ZiShang520@gmail.com
     * @DateTime  2016-05-20T16:10:29+0800
     * @copyright (c)                      ZiShang520    All Rights Reserved
     * @return    [type]                   [description]
     */
    public function stmt_close()
    {
        return self::$stmt->close();
    }

    /**
     * [mysql_close 关闭数据库连接]
     * @Author    ZiShang520@gmail.com
     * @DateTime  2016-05-20T16:12:28+0800
     * @copyright (c)                      ZiShang520    All Rights Reserved
     * @return    [type]                   [description]
     */
    public function mysql_close()
    {
        return $this->mysql->close();
    }
    /**
     * [error 数据库连接失败提示]
     * @Author   ZiShang520
     * @DateTime 2016-04-07T14:14:32+0800
     */
    private function error($connect_errno)
    {
        switch ($connect_errno) {
            case 1044:
            case 1045:
                zmMsg('连接数据库失败，数据库用户名或密码错误');
                break;

            case 1049:
                zmMsg('连接数据库失败，未找到您填写的数据库');
                break;

            case 2003:
                zmMsg('连接数据库失败，数据库端口错误');
                break;

            case 2005:
                zmMsg('连接数据库失败，数据库地址错误或者数据库服务器不可用');
                break;

            case 2006:
                zmMsg('连接数据库失败，数据库服务器不可用');
                break;

            default:
                zmMsg('连接数据库失败，请检查数据库信息。错误编号：' . $connect_errno);
                break;
        }
    }
}
