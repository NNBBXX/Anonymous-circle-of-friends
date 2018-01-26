<?php
require_once('db/util.php');
$mysql = new Util();
//获取参数
$id = $_POST['id'];
$openid = $_POST['openid'];
$type = $_POST['type'];

if($type=="" ||$openid=="" ||$id==""){
	echo "没有数据输入";
	return;
}
//获取留言者id
$fid = intval(getField('id',"openid='".$openid."'")['id']);

//点赞
$message = $mysql->updateZan($id,$fid);
echo json_encode($message);

function getField($filed,$where){
	global $mysql;
	$sql = "select ".$filed." from tb_user where ".$where;
    return $mysql->getFieldBySql($sql);
}
?>