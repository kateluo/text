<?php
/**
 * Created by PhpStorm.
 * User: 骆－剑
 * Date: 2016/5/24
 * Time: 17:02
 */
//
include 'inti.php';
$artice = new ArticleModel();
$com = new CommentModel();
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';
var_dump($action);
if ($action == 'add') {
    var_dump($_GET);
    var_dump($_POST);
    $actid = isset($_GET['actid']) ? intval($_GET['actid']) : 0;
    var_dump($actid);
    if ($actid != 0) {
        $name = isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '';
        $comment = isset($_POST['content']) ? htmlspecialchars($_POST['content']) : '';
        $postdate = time();
        if ($com->insertion($actid, $name, $comment, $postdate) > 0) {
            go('index.php?add' . $actid);
        } else {
            go('index.php?add' . $actid);
        }

    }
} else {
    if (isset($_REQUEST['RID'])) {
        echo 1;
        $id = !empty($_REQUEST['RID']) ? intval($_REQUEST['RID']) : 0;
        var_dump($id);
        if ($id == 0) {
            zmMsg('你查看文章不存在');
        }
        $row = $artice->select($id);
        //var_dump($row);
        if (empty($row)) {
            zmMsg('你查看的文章不存在');
        }

        $com_list = $com->comartlist($id);
        var_dump($com_list);
        include "connect/view/read.html";
    } else {
        $info = $artice->artlist(5, 5);
        $rows = $info['list'];
        $show = $info['show'];
        include "connect/view/list.html";
    }

}

