<?php
/**
 * Created by PhpStorm.
 * User: 骆－剑
 * Date: 2016/5/23
 * Time: 10:52
   文章模型
 */
class ArticleModel
{
    //数据库连接对象
    private $mysql;

    public function __construct()
    {
        $this->mysql = new Mysqlim();
    }

    /**artlist  取出文章列表
     *
     */

    public function artlist($num = 10, $button = 10)
    {

        if (!$this->mysql->bind_query('SELECT count(ID) AS num FROM `logo`WHERE 1')) {
            zmMsg($this->mysql->get_stmt_error());
        }
        if (($row = $this->mysql->fetch(true)) === false) {
            zmMsg($this->mysql->get_stmt_error);
        }
//获取文章
        $page = new Page($row['num'], $num , $button);
        if (!$this->mysql->bind_query('SELECT * FROM `logo` WHERE 1 GROUP BY `ID` DESC LIMIT %d,%d', array($page->first_rows, $page->page_num_rows))) {
            zmMsg($this->mysql->get_stmt_error);
        }
        if (($rows = $this->mysql->fetch()) === false) {
            zmMsg($this->mysql->get_stmt_error());
        }
        $this->mysql->free_result();//销毁数据
        $this->mysql->stmt_close();//关闭stmt线程
        $this->mysql->mysql_close();//关闭数据库


        return array('list' => $rows, 'show' => $page->show(), 'row' => $row);

    }

    /**insertinto 插入方法
     * @param $title
     * @param $content
     * @param $excerpt
     * @param $postdate
     * @return bool
     */

    public function insertinto($title, $content, $excerpt, $postdate)
    {

        //$arr=array($title,$content,$excerpt,$postdate);
        var_dump($title, $content, $excerpt, $postdate);
        if (!$this->mysql->bind_query('INSERT INTO `logo`(`title`,`content`,`excerpt`,`postdate`) VALUES (%s,%s,%s,%d)', array($title, $content, $excerpt, $postdate))) {
            zmMsg($this->mysql->get_stmt_error());
        }
        return true;

    }

    /**updateset  修改方法
     * @param $title
     * @param $content
     * @param $excerpt
     * @param $postdate
     * @param $id
     * @return bool
     */

    public function updateset($title, $content, $excerpt, $postdate, $id)
    {

        if (!$this->mysql->bind_query('UPDATE `logo` SET `title`=%s,`content`=%s,`excerpt`=%s,`postdate`=%d WHERE `ID`=%d', array($title, $content, $excerpt, $postdate, $id))) {
            zmMsg($this->mysql->get_stmt_error());
        }
        return true;


    }

    /**
     * @param $id 查询id方法
     * @return array|bool
     */

    public function select($id)
    {
        if (!$this->mysql->bind_query('SELECT * FROM `logo` WHERE `ID`=%d LIMIT 1', array($id))) {
            zmMsg($this->mysql->get_stmt_error());
        }
        $article = $this->mysql->fetch(true);
        return $article;
    }

    /**
     * 删除方法
     */

    public function delete($ids = array())
    {
        $where = implode(',', array_pad(array(), count($ids), '%d'));
        $sql = 'DELETE FROM `logo` WHERE `ID` IN (' . $where . ')';
        if (!$this->mysql->bind_query($sql, $ids)) {
            zmMsg($this->mysql->get_stmt_error());
        }
        $affected = $this->mysql->get_affected_rows();//响应行数
//        $this->mysql->free_result();//销毁数据
//        $this->mysql->stmt_close();//关闭stmt线程
//        $this->mysql->mysql_close();//关闭数据库
        return $affected;

    }

    /**页面跳转
     * @param $url  跳转的地址
     */

    function go($url)
    {
        if (!empty($url)) {
            header('location:' . $url);
            exit();
        }


    }







}
