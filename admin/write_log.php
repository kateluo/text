<?php
/**    写入数据库
 *
 * 注意 理解上级的级目录的使用  __FILE__ __DIR__ dirnaem()
 * isset 利用三元判断 判断的步骤不能少
 * 利用INSET INTO 写入数据库
 * 最后初始化stmt_init()子类
 * 创建sql语句啊，用？号占位啊。。
 *
 *
**/
include dirname(dirname(__FILE__)).'/inti.php';  //等价于../上下级目录
var_dump(dirname(dirname(__FILE__)).'/inti.php');
echo '<br/>**./同级目录   *<br?>';
var_dump(dirname(__FILE__));
echo '<br/>*** ../ 上下级目录<br?>';
var_dump(dirname(__DIR__));
echo '<br/>*** .. 上下级目录 <br?>';
var_dump(dirname(dirname(__FILE__)));
echo '<br/>***<br?>';
var_dump($_POST);
header("content-type:text/html;charset='utf-8");



if (is_post( )&&!isset($_GET['ID'])) {
  //insst 判断步骤不能少
   $title=isset($_POST['title'])? $_POST['title']:'';
   $content=isset($_POST['content'])? $_POST['content']:'';
   $excerpt=isset($_POST['excerpt'])? $_POST['excerpt']:'';
   //获取时间戳，时间不合法使用系统时间
   $postdate=isset($_POST['postdate'])? strtotime($_POST['postdate']):time();
   //如果格式化失败也用系统时间
   $postdate=$postdate? $postdate:time();


     $stmt=$db->stmt_init();//初始化stmt 子类

     $sql='INSERT INTO `logo`(`title`,`content`,`excerpt`,`postdate`) VALUES (?,?,?,?)';
     //创建sql模板，用？占位。注。多少个值，用多少个？占位
     if (!$stmt->prepare($sql)) {//预处理sql语句，用于判断sql语句中的表，字段是否存在
         die($stmt->error);
     }
      if (!$stmt->bind_param('sssi',$title,$content,$excerpt,$postdate)) {
        //绑定参数，两个参数。第一个为？的占位符数量，从左到右以此填写。第二个为，？占位符所对应的变量  
          die($stmt->error);
      }
      if (!$stmt->execute()) {//执行sql语句，返回true,false
        die($stmt->error);
      }
     if ($db->affected_rows>0) {  var_dump($db);//

         echo "添加成功";
     }else{
         echo "添加失败";
 }
   var_dump($stmt);

 }
if (isset( $_GET['ID'])){
    $sql='SELECT * FROM `logo` WHERE ID='.$_GET['ID'];
    $resout=$db->query($sql);
    $arr=$resout->fetch_assoc();
    if (isset($_POST['title'])){
        $sql2="UPDATE `logo` SET `title`='{$_POST['title']}',`content`='{$_POST['content']}',`excerpt`='{$_POST['excerpt']}',`postdate`=".strtotime($_POST['postdate']).
       " WHERE `ID`={$_GET['ID']}";
        var_dump($sql2);
        $arr=$db->query($sql2);
    }

    if ($arr){
        echo '修改成功';
    }else{
        echo '修改失败';
    }
    include 'views/write_amend_log.html';
}else {
    include 'views/write_log.html';
}
    ?>