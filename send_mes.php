<?php
/**
 * 微信扩展接口测试
 */
include("sendmsg.class.php");

function logdebug($text){
	file_put_contents('data/log.txt',$text."\n",FILE_APPEND);
};

$options = array(
	'appid'=>'wx7876787c21486f55',
	'appsecret'=>'aeaedf03b50a9adccdd2f28014a340cf'
);

$sendmsg = new SendMsg($options);
$content = $_GET['content'];
$openid = $_GET['id'];

if ($sendmsg->checkAppidAndAppsecret())
{
	//get token
	if ($sendmsg->sendMsgByOpenid($openid,$content)){
		echo "send msg success";
	}else{
		echo "send msg faile";
	}
} else {
	echo "appid or appsecret error";
}