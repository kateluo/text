<?php

/**
 * 截取编码为utf8的字符串
 *
 * @param string $strings 预处理字符串
 * @param int $start 开始处 eg:0
 * @param int $length 截取长度
 */
function subString($strings, $start, $length)
{
    if (function_exists('mb_substr') && function_exists('mb_strlen')) {
        $sub_str = mb_substr($strings, $start, $length, 'utf8');
        return mb_strlen($sub_str, 'utf8') < mb_strlen($strings, 'utf8') ? $sub_str . '...' : $sub_str;
    }
    $str = substr($strings, $start, $length);
    $char = 0;
    for ($i = 0; $i < strlen($str); $i++) {
        if (ord($str[$i]) >= 128) {
            $char++;
        }

    }
    $str2 = substr($strings, $start, $length + 1);
    $str3 = substr($strings, $start, $length + 2);
    if ($char % 3 == 1) {
        if ($length <= strlen($strings)) {
            $str3 = $str3 .= '...';
        }
        return $str3;
    }
    if ($char % 3 == 2) {
        if ($length <= strlen($strings)) {
            $str2 = $str2 .= '...';
        }
        return $str2;
    }
    if ($char % 3 == 0) {
        if ($length <= strlen($strings)) {
            $str = $str .= '...';
        }
        return $str;
    }
}

/**
 * 从可能包含html标记的内容中萃取纯文本摘要
 *
 * @param string $data
 * @param int $len
 */
function extractHtmlData($data, $len, $status = false)
{
    $data = $status ? htmlspecialchars_decode($data) : $data;
    $data = subString(strip_tags($data), 0, $len + 30);
    $search = array("/([\r\n])[\s]+/", // 去掉空白字符
        "/&(quot|#34);/i", // 替换 HTML 实体
        "/&(amp|#38);/i",
        "/&(lt|#60);/i",
        "/&(gt|#62);/i",
        "/&(nbsp|#160);/i",
        "/&(iexcl|#161);/i",
        "/&(cent|#162);/i",
        "/&(pound|#163);/i",
        "/&(copy|#169);/i",
        "/\"/i",
    );
    $replace = array(" ", "\"", "&", " ", " ", "", chr(161), chr(162), chr(163), chr(169), "");
    $data = trim(subString(preg_replace($search, $replace, $data), 0, $len));
    return $data;
}




 /**页面跳转
  * @param $url  跳转的地址
  */

 function go($url )
 {
     if (!empty($url)) {
         header('location:' . $url);
         exit();
     }


 }






    /** 自动加载类
 * @param $class
 */
function autoload($class)
{
    if (file_exists(__DIR__ . '/lib/' . $class . '.class.php')) {
        require __DIR__ . '/lib/' . $class . '.class.php';
    } else if (file_exists(__DIR__ . '/Model/' . $class . '.class.php')) {
        require __DIR__ . '/Model/' . $class . '.class.php';
    } else {
        zmMsg($class . '类加载失败');
    }
}

spl_autoload_register('autoload');//执行自动加载


/** is_post 判断post请求
 * @return bool
 */

function is_post()
{
    return (isset($_SERVER['REQUEST_METHOD']) && ('POST' == $_SERVER['REQUEST_METHOD']));
}


/**
 * [zmMsg 显示消息的function]
 * @Author   ZiShang520
 * @DateTime 2016-02-24T17:05:59+0800
 * @param    [type]                   $msg      [description]
 * @param    string $url [description]
 * @param    boolean $isAutoGo [description]
 * @return   [type]                             [description]
 */
function zmMsg($msg, $url = 'javascript:history.back(-1);', $isAutoGo = false)
{
    if ('404' == $msg) {
        header("HTTP/1.1 404 Not Found");
        $msg = '抱歉，你所请求的页面不存在！';
    }
    echo <<<EOT
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

EOT;
    if ($isAutoGo) {
        echo "<meta http-equiv=\"refresh\" content=\"2;url=$url\" />";
    }
    echo <<<EOT
    <title>提示信息</title>
    <style type="text/css">
        body { background-color:#F7F7F7; font-family: Arial; font-size: 12px; line-height:150%; } .main { background-color:#FFFFFF; font-size: 12px; color: #666666; width:650px; margin:60px auto 0px; border-radius: 10px; padding:30px 10px; list-style:none; border:#DFDFDF 1px solid; } .main p { line-height: 18px; margin: 5px 20px; }
    </style>
</head>

<body>
    <div class="main">
        <p>$msg</p>

EOT;
    if ('none' != $url) {
        echo '        <p><a href="' . $url . '">&laquo;点击返回</a></p>';
    }
    echo <<<EOT

    </div>
</body>

</html>
EOT;
    exit;
}

?>