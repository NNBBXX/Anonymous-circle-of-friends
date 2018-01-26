<?php
require_once('db/util.php');
$mysql = new Util();
//获取参数
$book = $_POST['book'];
$openid = $_POST['openid'];

if($book=="" ||$openid==""){
	echo "没有数据输入";
	return;
}
//获取留言者id跟被留言者id
$tid  = intval(getField('id',"book='".$book."'")['id']);
$fid = intval(getField('id',"openid='".$openid."'")['id']);

//获取留言
$message = $mysql->getMessage($tid,$fid);
echo json_encode($message);

function getField($filed,$where){
	global $mysql;
	$sql = "select ".$filed." from tb_user where ".$where;
    return $mysql->getFieldBySql($sql);
}
?>