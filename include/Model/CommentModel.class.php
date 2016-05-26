<?php
/**
 * Created by PhpStorm.
 * User: 骆－剑
 * Date: 2016/5/25
 * Time: 14:46
 */
class CommentModel
{

    //数据库连接对象
    private $mysql;

    public function __construct()
    {
        $this->mysql = new Mysqlim();
    }

    public function comartlist($atcid)
    {
        $sql = 'SELECT * FROM `comment` WHERE `atcid`=%d';
        if (!$this->mysql->bind_query($sql, array($atcid))) {
            zmMsg($this->mysql->get_stmt_error());
        }
        if (($rows = $this->mysql->fetch()) === false) {
            zmMsg($this->mysql->get_stmt_error());
        }
        return $rows;
    }
        /**
         * 插入的评论
         */
        public function insertion($atcid,$name, $coment, $postdate)
        {
            $sql = 'INSERT INTO `comment` (`atcid`, `name`,`coment`, `postdate`)VALUES(%d,%s,%s,%d)';
          
            if (!$this->mysql->bind_query($sql, array($atcid,$name, $coment, $postdate))) {
                zmMsg($this->mysql->get_stmt_error());
            }
            return true;
        }


    



}


