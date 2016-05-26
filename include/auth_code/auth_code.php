<?php
/**  普通艳证码。。。。。
 *
 *
 * Created by PhpStorm.
 * User: 骆－剑
 * Date: 2016/5/11
 * Time: 16:43


 */

session_start();
//1.创造个画板
header('content-type:image/png');
$bg=imagecreatetruecolor(400,70);
$code='';
//2.改变画板颜色   和字体颜色  。。
$bg_colar=imagecolorallocate($bg, 255, 255, 255);
//$txt_colar=imagecolorallocate($bg, 250, 200, 0);
$txt_colar=imagecolorallocatealpha($bg, mt_rand(0,255), mt_rand(0,255), mt_rand(0,255), mt_rand( 0,127  ));
imagefilledrectangle($bg, 0, 0, 400, 100, $bg_colar);

//3.创造随机字
$num=['min'=>48,'max'=>57];
$small=['min'=>97,'max'=>122];
$big=['min'=>65,'max'=>90];
$arr=[$num,$small,$big];
//透明 $txt_colar=imagecolorallocatealpha($bg, mt_rand(0,255), mt_rand(0,255), mt_rand(0,255), mt_rand(0,200));
//循环字体放入字符  斜杠  小点
for ($i=0; $i <4 ; $i++) {
    $one=$arr[mt_rand(0,2)];
    $str=chr(mt_rand($one['min'],$one['max']));
    $code.=$str;
    //imagechar($bg, 20,50+100*$i , 20, $str, $txt_colar);
    imagettftext($bg, 30, mt_rand(0,30), 50+100*$i-mt_rand(1,10),30 ,$txt_colar, './simhei.ttf', $str);
}
for ($i=0; $i <7 ; $i++) {
    imageline($bg, mt_rand(0,400), mt_rand(0,70), mt_rand(0,400), mt_rand(0,70), $txt_colar);
}

for ($i=0; $i <300 ; $i++) {
    $point_colar=imagecolorallocate($bg, mt_rand(0,250), mt_rand(0,255), mt_rand(0,255));
    imagesetpixel($bg, rand(0,400), rand(0,70), $point_colar);
}
$_SESSION['code']= $code ;
imagepng($bg);

