<?php
	//scope=snsapi_base 实例
$appid='你的appid';
$book = $_GET['book'];
$url = '';
if(!isset($book))
    $url = 'http://test.souho.net';
else
	$url = 'http://test.souho.net/share.html?book='.$book;
$redirect_uri = urlencode ( $url );
$url ="https://open.weixin.qq.com/connect/oauth2/authorize?appid=$appid&redirect_uri=$redirect_uri&response_type=code&scope=snsapi_userinfo&state=1#wechat_redirect";
header("Location:".$url);	
?>