<?php
/**查询取出数据
 * Created by PhpStorm.
 * User: 骆－剑
 * Date: 2016/5/11
 * Time: 9:12
 */
include __DIR__.'/globals.php';
//include dirname(dirname(__FILE__)).'/inti.php';
$action=isset($_REQUEST['action'])?$_REQUEST['action']:'';
$article=new ArticleModel();
if ($action=='operate_log') {
    var_dump($_POST);

    $operate = isset($_POST['operate']) ? $_POST['operate'] :'';
    switch ($operate) {
        case 'del';


            $ids = isset($_POST['blog']) ? array_map('intval', $_POST['blog']) : array();
            if ($article->delete($ids)>0){
                $article->go('read_log.php?status=2');
            }else{
                $article->go('read_log.php?status=-2');
            }

            break;
        default:
            break;
    }
}
    $article=$article->artlist();
    $rows=$article['list'];
    $row=$article['row'];
    $show=$article['show'];
    var_dump($row);

    include 'views/read_log.html';























//$mysql=new Mysqlim();
//if (!$mysql->bind_query('SELECT count(ID) AS num FROM `logo`WHERE 1')){
//    zmMsg($mysql->get_stmt_error());
//}
//if (!$row=$mysql->fetch(true)){
//    zmMsg($mysql->get_stmt_error);
//}
////获取文章
//$page=new Page($row['num'],1);
//if (!$mysql->bind_query('SELECT * FROM `logo` WHERE 1 LIMIT %d,%d',array($page->first_rows,$page->page_num_rows))){
//    zmMsg($mysql->get_stmt_error);
//}
//if (!$rows=$mysql->fetch()){
//    zmMsg($mysql->get_stmt_error());
//}
//$mysql->free_result();//销毁数据
//$mysql->stmt_close();//关闭stmt线程
//$mysql->mysql_close();//关闭数据库




//var_dump($row);
//var_dump($rows);






//$row=$db->query('SELECT count(ID) FROM `logo`WHERE 1')->fetch_row();
//var_dump($row);
//$page=new Paging($row[0],1);
//var_dump($page);
//
//$sql='SELECT * FROM `logo` WHERE 1 LIMIT ?,?';   //var_dump($sql);
//  $stmt=$db->stmt_init();   //初始化子类
//if (!$stmt->prepare($sql)){
//    die($stmt->error);
//}
//$stmt->bind_param('ii',$page->first_rows,$page->page_num_rows);
//
//  $stmt->execute();//直接执行sql ，不需要绑定？（因为不用绑定传参）
////取出数据的三个步骤
////取出数据缓存到内存中
//$stmt->store_result();//传递数据
//if ($stmt->num_rows>0){
//
//}
// //对查询字段进行绑定
////绑定变量到准备好的语句结果储存
//  $stmt->bind_result($i,$title,$content,$excerpt,$postdate);
////传递参数数量 ，为查询到的字段数量
//  $arr=array();
//while ($stmt->fetch()){//取出数据，遍历取出数据，如果只有一条不用while
//    $arr[]=array($i,$title,$content,$excerpt,$postdate);
//}
//var_dump($arr);
//var_dump($stmt);













