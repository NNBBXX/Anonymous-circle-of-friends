<?php
class JSSDK {
  private $appId;
  private $appSecret;
  private $url;
  private $code;

  public function __construct($appId, $appSecret,$url,$code) {
    $this->appId = $appId;
    $this->appSecret = $appSecret;
	  $this->url = $url;
	  $this->code = $code;
  }

  public function getSignPackage() {
    $jsapiTicket = $this->getJsApiTicket();
    //$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $url = $this->url;
    $timestamp = time();
    $nonceStr = $this->createNonceStr();
	  $userInfo = $this->getUserInfo();
    // 这里参数的顺序要按照 key 值 ASCII 码升序排序
    $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";

    $signature = sha1($string);

    $signPackage = array(
      "appId"     => $this->appId,
      "nonceStr"  => $nonceStr,
      "timestamp" => $timestamp,
      "url"       => $url,
      "signature" => $signature,
      "rawString" => $string,
      "openid"    => $userInfo->openid,
      "nickname"  => $userInfo->nickname,
      "sex"       => $userInfo->sex,
      "headimgurl"=> $userInfo->headimgurl,
      "userinfo" =>  $userInfo
    );
    return $signPackage; 
  }

  private function createNonceStr($length = 16) {
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $str = "";
    for ($i = 0; $i < $length; $i++) {
      $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
    }
    return $str;
  }

  private function getJsApiTicket() {
    // jsapi_ticket 应该全局存储与更新，以下代码以写入到文件中做示例
    $data = json_decode(file_get_contents("jsapi_ticket.json"));
    if ($data->expire_time < time()) {
      $accessToken = $this->getAccessToken();
      $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=$accessToken";
      $res = json_decode($this->httpGet($url));
      $ticket = $res->ticket;
      if ($ticket) {
        $data->expire_time = time() + 7000;
        $data->jsapi_ticket = $ticket;
        $fp = fopen("jsapi_ticket.json", "w");
        fwrite($fp, json_encode($data));
        fclose($fp);
      }
    } else {
      $ticket = $data->jsapi_ticket;
    }

    return $ticket;
  }

  private function getAccessToken() {
    // access_token 应该全局存储与更新，以下代码以写入到文件中做示例
    $data = json_decode(file_get_contents("access_token.json"));
    if ($data->expire_time < time()) {
      $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$this->appId&secret=$this->appSecret";
      $res = json_decode($this->httpGet($url));
      $access_token = $res->access_token;
      if ($access_token) {
        $data->expire_time = time() + 7000;
        $data->access_token = $access_token;
        $fp = fopen("access_token.json", "w");
        fwrite($fp, json_encode($data));
        fclose($fp);
      }
    } else {
      $access_token = $data->access_token;
    }
    return $access_token;
  }
  
  private function getOauth2AccessToken() {
    // oauth2_access_token 应该全局存储与更新，以下代码以写入到文件中做示例

      $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=$this->appId&secret=$this->appSecret&code=$this->code&grant_type=authorization_code";
      $res = json_decode($this->httpGet($url));
      $refresh_token = $res->refresh_token;
      return $refresh_token;
  }
  
  private function getFinalAccessToken() {
    // access_token 应该全局存储与更新，以下代码以写入到文件中做示例
    	$refreshAccessToken = $this->getOauth2AccessToken();
      $url = "https://api.weixin.qq.com/sns/oauth2/refresh_token?appid=$this->appId&grant_type=refresh_token&refresh_token=$refreshAccessToken";
      $res = json_decode($this->httpGet($url));
      $access_token = $res->access_token;
	    $openid = $res->openid;
	    $tokenAndOpenid = array('access_token'=>$access_token,'openid'=>$openid);
      return $tokenAndOpenid;
  }
  
  private function getUserInfo() {
    	$tokenAndOpenid = $this->getFinalAccessToken();
		  $access_token = $tokenAndOpenid['access_token'];
		  $openid = $tokenAndOpenid['openid'];
      $url = "https://api.weixin.qq.com/sns/userinfo?access_token=$access_token&openid=$openid&lang=zh_CN";
      $res = json_decode($this->httpGet($url));
      $user_info = $res;
      //$user_info = $access_token;
    return $user_info;
  }

  private function httpGet($url) {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_TIMEOUT, 500);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($curl, CURLOPT_URL, $url);

    $res = curl_exec($curl);
    curl_close($curl);

    return $res;
  }
}

