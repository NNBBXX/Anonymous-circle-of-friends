<?php
  require_once "jssdk.php";
$url = $_POST['url'];
$code = $_POST['code'];
$jssdk = new JSSDK("你的appid", "你的秘钥",$url,$code);
$signPackage = $jssdk->GetSignPackage();
$data = array("data"=>array("appId"=>"你的appid","timeStamp"=>$signPackage["timestamp"],"nonceStr"=>$signPackage["nonceStr"],"signature"=>$signPackage["signature"],"openid"=>$signPackage["openid"],"nickname"=>$signPackage["nickname"],"sex"=>$signPackage["sex"],"headimgurl"=>$signPackage["headimgurl"]),"err"=>0,"msg"=>null);
 echo json_encode($data);
?>