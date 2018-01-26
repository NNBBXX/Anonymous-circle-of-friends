<?php
require_once('db/util.php');
$mysql = new Util();
//获取参数
$txt = $_POST['txt'];
$book = $_POST['book'];
$openid = $_POST['openid'];

if($txt=="" || $book=="" ||$openid==""){
	echo "没有数据输入";
	return;
}
//获取留言者id跟被留言者id
$tid  = intval(getField('id',"book='".$book."'")['id']);
$fid = intval(getField('id',"openid='".$openid."'")['id']);
//插入
$fields = array('txt'=>$txt, 'tid'=>$tid,'fid'=> $fid);
$txtid = $mysql->addData("tb_txt",$fields); // 功能同上，返回 last_insert_id（插入的增长id）
echo json_encode($txtid);
//$m->Insert("tb_zan", null, array($txtid, $fid, 0)); // 拼接方法：往`user`表中添加一条数据，返回值为数据库影响的行数*/

function getField($filed,$where){
	global $mysql;
	$sql = "select ".$filed." from tb_user where ".$where;
    return $mysql->getFieldBySql($sql);
}

?>