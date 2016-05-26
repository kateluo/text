<?php
/**
 * Created by PhpStorm.
 * User: 骆－剑
 * Date: 2016/5/17
 * Time: 18:09
 */

include __DIR__.'/globals.php';
//include dirname(__DIR__).'/inti.php';
//$action=isset($_REQUEST['action'])?$_REQUEST['action']:'';

var_dump($_REQUEST);
switch ($action){
    case 'edit';
        $id=isset($_GET['ID'])?intval($_GET['ID']):0;
        
        $article=new ArticleModel();
        $article=$article->select($id);
          var_dump($article);

//    $sql='SELECT * FROM `logo`WHERE `ID`=? LIMIT 1';
//    $stmt=$db->stmt_init();//初始化子类
//    if (!$stmt->prepare($sql)){
//        zmMsg($stmt->error);
//    }
//    $stmt->bind_param('i',$id);//绑定
//    $stmt->execute();//执行
//    $stmt->store_result();//取出
//   if (1==$stmt->num_rows){
//       $stmt->bind_result($a,$b,$c,$d,$e);
//       $stmt->fetch();
//       $stmt->free_result();//销毁数据
//   }else{
//       zmMsg('你没权限编辑该文章或者文章不存在');
//   }
    //获取信息，输出信息
    include 'views/deit_log.html';
    break;
    default:


        include 'views/write_log.html';

   break;
}
