<?php
/**
 * Created by PhpStorm.
 * User: 骆－剑
 * Date: 2016/5/24
 * Time: 9:27
 */

function check_status($status){
    switch ($status){
        case 1 :
            return'<span calss="alert alert-success">添加成功 </span>';
       break;
        case -1:
            return'<span calss="alert alert-danger">添加失败 </span>';

        case 2:
            return'<span calss="alert alert-success">删除成功</span>';
            break;
        case -2:
            return'<span calss="alert alert-danger">删除失败 </span>';


        case 3 :
            return'<span calss="alert alert-success">修改成功 </span>';
            break;
        case -3:
            return'<span calss="alert alert-danger">修改失败 </span>';

            

    }
}
