<?php
include "wechat.class.php";
$options = array(
		'token'=>'tokenaccesskey', //填写你设定的key
        'encodingaeskey'=>'5lLtUYtyRLDM5zCuG43TxcHpKSK5ydENbozq78XhIOi' //填写加密用的EncodingAESKey，如接口为明文模式可忽略
);

$weObj = new Wechat($options);
$weObj->valid();//明文或兼容模式可以在接口验证通过后注释此句，但加密模式一定不能注释，否则会验证失败


// 获取菜单操作:
$menu = $weObj->getMenu ();
// 设置菜单
$newmenu =  array(
   "button"=>
    	array(
            array(
                'name'=>'医院信息',
                'sub_button' => array (
					array (
						"type"=>"view",
						'name' => '医院主页',
						"url"=>"http://www.blkqyy.com/"
					),  
					array (
						"type"=>"click",
						'name' => '门诊时间',
						"key"=>"time"
					),
					array (
						"type"=>"view",
						'name' => '医院地址',
						"url"=>"http://blkqyy.com/wap.php/index-map.html"
					),  
					array (
						"type"=>"view",
						'name' => '专业团队',
						"url"=>"http://mp.weixin.qq.com/s?__biz=MzA3NDEyMTcxMw==&mid=202512101&idx=1&sn=478061005d164632ccbb3a79b36d5e85#rd"
					),                       
				) 
            ),
            array(
                'name'=>'就医导航',
                'sub_button' => array (
					array (
						'type'=>'click',
						'name' => '常见咨询',
						'key'=>'quest'
					), 
                    array (
						'type'=>'click',
						'name' => '我要留言',
						'key'=>'liuyan'
					), 
                    array (
						'type'=>'view',
						'name' => '来院导航',
						'url'=>'http://map.wap.soso.com/x/index.jsp?welcomeChange=1&sid=AfYicfAV0b1upF4O4-Lnzj_z&welcomeClose=1&hideAdvert=hide&type=infowindow&open=1&address=中国浙江省宁波市北仑区星中路7号&name=宁波市北仑口腔&referer=weixinmp_profile&g_ut=3&Y=29.910579&X=121.84133&Z=16&from=singlemessage&'
					), 
                    array (
						'type'=>'view',
						'name' => '在线咨询',
						'url'=>'http://dx.zoosnet.net/lrserver/LR/Chatpre.aspx?id=LZS32497012'
					), 
                    
				) 
            ),
            array(
                'name'=>'爱牙知识',
                'sub_button' => array (
					array (
						'type'=>'view',
						'name' => '成人宣教视频',
						'url'=>'http://mp.weixin.qq.com/s?__biz=MzA3NDEyMTcxMw==&mid=200521821&idx=1&sn=1272b4da141a38ab66bfab0d1e64b4b4#rd'
					),
                    array (
						'type'=>'view',
						'name' => '儿童宣教视频',
						'url'=>'http://mp.weixin.qq.com/s?__biz=MzA3NDEyMTcxMw==&mid=200276316&idx=1&sn=8695db64330bd7129786e2b4f769c32c#rd'
					),   
                   array (
						'type'=>'view',
						'name' => '爱牙知识讲堂',
						'url'=>'http://mp.weixin.qq.com/s?__biz=MzA3NDEyMTcxMw==&mid=203297479&idx=1&sn=dd41afb15383148c9b353e61e210d394#rd'
					),   
                   array (
						'type'=>'view',
						'name' => '最新资讯',
						'url'=>'http://mp.weixin.qq.com/s?__biz=MzA3NDEyMTcxMw==&mid=203379412&idx=1&sn=39fc677860d67d5fea82ff924cde88a4#rd'
					),   
				) 
            ),
 		)
  	);

$result = $weObj->createMenu ( $newmenu );


$type = $weObj->getRev()->getRevType();
$revfrom = $weObj->getRev()->getRevFrom ();
$getrevto = $weObj->getRev()->getRevTo ();

switch($type) {
	case Wechat::MSGTYPE_TEXT:
		$msg = $weObj->getRev()->getRevContent();

		switch ($msg){
			case 'openid':
				//get openid
				$openid = $weObj->getRev()->getRevFrom();
				$weObj->text('openid:'.$openid)->reply();
				break;
			
			case '预约查询':
				$newsData = array (
						$item = array (
							'Title' => '预约查询',
							'Description' => '点击查看',
							'Url' => 'www.blkqyy.com/admin.php/message/add_yuyue.html?weixin_id='.$topmsg['fakeid'] 
						)
				);
				$weObj->news ( $newsData )->reply ();
				break;

			default:
				$weObj->text("您的消息我们已经收到，感谢您的支持！")->reply();
				break;
		}

		exit;
		break;
	// 接收语音消息
	case Wechat::MSGTYPE_VOICE :
		$voice = $weObj->getRevVoice ();
		$weObj->voice ( $voice ['mediaid'] )->reply ();
		exit ();
		break;
		
	case Wechat::MSGTYPE_EVENT:
		$event = $weObj->getRevEvent ();
		switch ($event ["event"]) {
			case 'subscribe' :
				$weObj->text ( "您好，欢迎关注北仑口腔医院！\n微信预约请直接留言，客服会尽快联系您\n预约电话：0574-55128276/86830110\n预约QQ号：3155190558\n医院联系地址：北仑区星中路8号（北仑图书馆旁）\n关注北仑口腔微信，网罗口腔知识，分享生活百态！" )->reply ();
				exit ();
				break;
			case 'unsubscribe' :
				$weObj->text ( "欢迎再次订阅此服务号。" )->reply ();
				exit ();
				break;
			
			case 'LOCATION' :
				exit ();
				break;
			
			case 'CLICK' :
				switch ($event ["key"]) {
					case 'BUTTLOVE' :
                        $type1 = $weObj->getRev ()->getRevFrom ();
                        $weObj->text ( "最新消息 " . $type1 )->reply ();						
						$newsData = array (
								$item = array (
										'Title' => 'Title',
										'Description' => 'Description',
										'Url' => 'http://www.baidu.com' 
								),
								$item = array (
										'Title' => 'Title',
										'Description' => 'Description',
										'Url' => 'http://www.baidu.com'
								)
						);
						$weObj->news ( $newsData )->reply ();
						exit ();
						break;
					
					case 'button1' :
						/*
                        $userlist = $wechatext->getUserlist(0,10);                     
                        if ($wechatext->checkValid()) {      
                            foreach($userlist as $user){ 
                               $wechatext->send($user['id'],"遍历用户，群发消息测试。");    
                            }   
                        }*/
						exit ();
						break;
						
                      case "time" :
                      $weObj->text ( "夏季：08:00-11:30\n          13:30-16:30 \n冬季：08:30-11:30\n          13:00-16:30\n\n        欢迎您前来预约，我们的医生会酌情调整就诊时间。" )->reply ();	
                      exit ();
					  break;
                    
                      case "quest" :
                      $weObj->text ( "Q：你们医院可以使用医保吗？ \nA：我们医院支持医保的，具体使用方法，您可以联系我们客服给您详细解答 \nQ：我如何来你们医院就诊？ \nA：您可以选择在线预约，我们将为您安排合理的时间，为您减少不必要的等待，来院路线可以查看医院地址或者联系我们客服。" )->reply ();	
                      exit ();
					  break;
                      
                      case 'liuyan':
                      $weObj->text( "您好，这里是北仑口腔医院官方微信平台，您可以通过该平台，留下您的信息，以及需要咨询的问题，我们的医师会在第一时间回复。" )->reply();
                    
				}
				break;
		}
		break;
		
	case Wechat::MSGTYPE_IMAGE:
		break;

	default:
		$weObj->text("help info")->reply();
}