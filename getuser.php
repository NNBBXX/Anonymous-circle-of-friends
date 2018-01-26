<?php
require_once('db/util.php');
$mysql = new Util();
//获取参数
$book = $_POST['book'];

if($book==""){
	echo "没有数据输入";
	return;
}
//获取被留言者nickname,imgurl;
$user = getField("book='".$book."'");
$nickname  = $user['nickname'];
$imgurl  = $user['imgurl'];
$data = array("nickname"=>$nickname,"imgurl"=>$imgurl);

//获取留言
echo json_encode($data);

function getField($where){
	global $mysql;
	$sql = "select * from tb_user where ".$where;
    return $mysql->getFieldBySql($sql);
}
?>