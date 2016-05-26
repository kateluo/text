<?php
// 连接数据库函数
// var_dump($_POST);
// $h='127.0.0.1';
// $u='root';
// $p='';
// $db='log_in';
// sql_connect($h, $u, $p, $db);
function sql_connect($h, $u, $p, $db) {
	$link = mysqli_connect ( $h, $u, $p, $db );
	mysqli_set_charset ( $link, 'utf8' );
	// var_dump($link);
	return $link;
}
// 查询一条数据
// $field='id';
// $table='log_in_table';
// $where="user_id='{$_POST['user_id']}' && user_pass='{$post_md5}'";
// var_dump ( $sql )
/**
 * @param $link
 * @param $field
 * @param $table
 * @param $where
 * @return array|bool
 */
function sql_select_one($link, $field, $table, $where) {
	$sql = "SELECT $field FROM $table WHERE $where";
	 var_dump($sql);
	$res = mysqli_query ( $link, $sql );
	$sql_arr = [ ];
	while ( $res1 = mysqli_fetch_assoc ( $res ) ) {
		$sql_arr [] = $res1;
		if (count ( $sql_arr ) == 1 && $sql_arr != null) {
			return $sql_arr;
		} else {
			return false;
		}
	}
}

// 去空格
function trim_str($str) {
	$str = trim ( $str );
	$str = addslashes ( $str );
	if (! empty ( $str )) {
		return $str;
	}
	return false;
}

// 字符串长度判断
function str_len($str, $min, $max) {
	$str_len = mb_strlen ( $str );
	if ($str_len >= $min && $str_len <= $max) {
		return $str;
	} else {
		return false;
	}
}

// 数据库插入数据
function insertinto($link, $table, $field, $values) {
	$sql = "INSERT INTO $table($field) VALUES ($values)";
	 //echo ($sql);
	$sql1 = mysqli_query ( $link, $sql ); // var_dump($sql);
	                                 // var_dump($sql1);
	if ($sql1) {
		// var_dump($sql1);
		return true;
	}
	return false;
}

// 查所有询数据取出几条
function sql_select_all($link, $field, $table, $where,$page,$num) {
	$sql = "SELECT $field FROM $table WHERE $where ORDER BY `u_time` DESC LIMIT $page,$num";
	//var_dump ( $sql );
	$res = mysqli_query ( $link, $sql );
	$sql_arr = [ ];
	while ( $res1 = mysqli_fetch_assoc ( $res ) ) {
		$sql_arr [] = $res1;
	}
	if (! empty ( $sql_arr )) {
		return $sql_arr;
	}
	return false;
}

//删除数据
function sql_delete($link,$table,$where=0){
	$sql="DELETE FROM $table WHERE $where";
	$res = mysqli_query ( $link, $sql );
	if ($res){
		return true;
	}
	return false;
}

//修改数据
function sql_update($link,$table,$value,$where){
	$sql="UPDATE $table SET $value WHERE $where";
	var_dump($sql);
	$res = mysqli_query ( $link, $sql );
	if ($res){
		return true;
	}
	return false;
	}


//多表查询
function sql_join_one($link,$id){
	$sql="SELECT * FROM user_info WHERE `user_id`=$id";
	//var_dump($sql);
	$res=mysqli_query ( $link, $sql );
	$sql_arr = [ ];
	while ( $res1 = mysqli_fetch_assoc ( $res ) ) {
		$sql_arr [] = $res1;
	}
	if (! empty ( $sql_arr )) {
		return $sql_arr;
	}
	return false;
	}

//插入一条
	function insert_one($link, $table, $field, $values,$where) {
		$sql = "INSERT INTO $table($field) VALUES ($values) WHERE user_id=$where";
		echo ($sql);
		$sql1 = mysqli_query ( $link, $sql ); // var_dump($sql);
		// var_dump($sql1);
		if ($sql1) {
			// var_dump($sql1);
			return true;
		}
		return false;
	}
	
//查询所有
	function select_all($link, $field, $table, $where) {
		$sql = "SELECT $field FROM $table WHERE $where ";
		var_dump ( $sql );
		$res = mysqli_query ( $link, $sql );
		$sql_arr = [ ];
		while ( $res1 = mysqli_fetch_assoc ( $res ) ) {
			$sql_arr [] = $res1;
		}
		if (! empty ( $sql_arr )) {
			return $sql_arr;
		}
		return false;
	}

/**
 * @return bool
 */
function is_post(){
	return (isset($_SERVER['REQUEST_METHOD'])&&('POST'==$_SERVER['REQUEST_METHOD']));
}


