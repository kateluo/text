<?php
/**
 *   加法减发验证码。。。
 * Created by PhpStorm.
 * User: 骆－剑
 * Date: 2016/5/12
 * Time: 10:22
 */
session_start();
function op()
{       //将运算符包装成函数，用op调用
    $arr = ['+', '-',];
    $a = ($arr[mt_rand(0, 1)]);
    return $a;
}

function add($n1, $n2, $op)
{  //调用add运算
    switch ($op) {
        case'+';
            $t = $n1 + $n2;
            break;
        case '-';
            $t = $n1 - $n2;
            break;
    }
    return $t;

}

$n = range(1, 20);//建立数组
shuffle($n);//打乱
$op1 = op(); //调用运算符
$op2 = op();

$a = $n[1] . $op1 . $n[2] . $op2 . $n[3] . '=' . '?'; //var_dump($a); //运算式
$b = add(add($n[1], $n[2], $op1), $n[3], $op2); //运算结果
//echo $b;
$_SESSION['SB'] = $b;    //用SESSION保存值的结果
header('content-type:image/png');
$img = imagecreatefromjpeg('2.jpg'); //或得图片资源
$bg = imagecreatetruecolor(300, 100);//创造个画板
imagecopyresampled($bg, $img, 0, 0, 150, 160, 300, 100, 400, 200);
//(背景,图片,背景的起始x,背景起始y,图片的起始x,图片起始y,背景的最终x,背景的最终y,图片的最终x,图片的最终y）；
$color = imagecolorallocate($bg, mt_rand(0, 255), mt_rand(100, 255), mt_rand(90, 255));//字体颜色
imagettftext($bg, 40, 0, 30, 70, $color, 'simhei.ttf', $a);//向图像写入文本
imagepng($bg);