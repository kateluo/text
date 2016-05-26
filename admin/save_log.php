<?php
/**
 * Created by PhpStorm.
 * User: 骆－剑
 * Date: 2016/5/17
 * Time: 17:59
 */
 include __DIR__.'/globals.php';
//$action=isset($_REQUEST['action'])?$_REQUEST['action']:'';
if (is_post()){
    $id=isset($_GET['ID'])?$_GET['ID']:'';
//    $id=isset($_REQUEST['ID'])?intval($_REQUEST['ID']):0;var_dump($id);
    $title=isset($_POST['title'])? $_POST['title']:'';
    $content=isset($_POST['content'])? $_POST['content']:'';
    $excerpt=isset($_POST['excerpt'])? $_POST['excerpt']:'';
    //获取时间戳，时间不合法使用系统时间
    $postdate=isset($_POST['postdate'])? strtotime($_POST['postdate']):time();
    //如果格式化失败也用系统时间
    $postdate = $postdate ? $postdate : time();
    $article = new ArticleModel();//实例化对象
    //下面直接调用
    $stmt = $db->stmt_init();//初始化stmt 子类
    if (isset($_GET['ID'])) {
        if ($article->updateset($title, $content, $excerpt, $postdate, $id) > 0) {
            $article->go('read_log.php?status=3');
        } else {
            $article->go('read_log.php?status=-3');
        }
    } else {
        if ($article->insertinto($title, $content, $excerpt, $postdate) > 0) {
            $article->go('read_log.php?status=1');
        } else {
            $article->go('read_log.php?status=-1');
        }
    }
}
include 'views/write_log.html';