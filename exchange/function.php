<?php

if (!defined('IN_UCHOME')) {
	exit('Access Denied');
} 

//检测插件是否关闭
function checkAPP($isAdmin){
global $_SGLOBAL, $_SC, $SConfig; 
if(!$isAdmin){

//插件关闭
if ($SConfig["APP_OFF"] == 1 ) showmessage($SConfig["APP_TITLE"] . '暂时关闭，请和管理员联系。', 'index.php', 5);

//自动开启关闭插件
$opendate = explode(",", $SConfig["OPENDATE"]);
$_opentime = explode(":", $SConfig["OPENTIME"]);
$_closetime = explode(":", $SConfig["CLOSETIME"]);

$nowdate=gmdate("d",$_SGLOBAL['timestamp']);
$_nowhour=gmdate("H",$_SGLOBAL['timestamp']);
$_nowminute=gmdate("i",$_SGLOBAL['timestamp']);

//$h=str_split($_nowhour);
$h=array();
$h[0]=substr($_nowhour,0,1);
$h[1]=substr($_nowhour,1,2);
if($h[0]>0){
	$nowhour=$_nowhour;
}else{
	$nowhour=$h[1];
}
//$m=str_split($_nowminute);
$m=array();
$m[0]=substr($_nowminute,0,1);
$m[1]=substr($_nowminute,1,2);

if($m[0]>0){
	$nowminute=($_nowminute);
}else{
	$nowminute=($m[1]);
}
$nowtime=($nowhour)*60+($nowminute);
$opentime = ($_opentime[0])*60+($_opentime[1]);
$closetime =  ($_closetime[0])*60+($_closetime[1]);

if($SConfig["APP_OFF"]==2){
		if(!in_array($nowdate ,$opendate)) showmessage($SConfig["APP_TITLE"] . '暂时关闭，今天是'.$nowdate.'号。    开放日期:<br><br>'.$SConfig["OPENDATE"].'<br><br>感谢支持!', 'index.php', 10);			
		if($nowtime<$opentime ) showmessage('距离 '.$SConfig["APP_TITLE"].' 开放时间 '.$SConfig["OPENTIME"]. '还差'.($opentime-$nowtime). '分钟,请准备好哦。', 'index.php', 10);	
		if($nowtime > $closetime) showmessage('已经超过 '.$SConfig["APP_TITLE"].' 开放时间 '.$SConfig["OPENTIME"]. ' -'.($closetime-$nowtime). '分钟，请下次再来！', 'index.php', 10);	
}

}
}

//检测师是否需要登录
function checkislogin(){
global $_SGLOBAL;
if (empty($_SGLOBAL['supe_uid'])) showmessage('to_login', 'do.php?ac=login');
}

//检查注册时间是否到达兑奖限定最低时间
function checkregtime($needregtime){
	global $_SGLOBAL;	


	if($_SGLOBAL['my']['isamdin'] || $needregtime=="0") return true;
	if($_SGLOBAL['timestamp'] >= $_SGLOBAL['my']['dateline']+intval($needregtime)) return true;

	return false;
}

//检查限次兑换
function checkrobnumber($robnumber,$gid){
	global $_SGLOBAL;	

	if($_SGLOBAL['my']['isamdin']||$robnumber<1) return true;	

     $count=0;
     $query = $_SGLOBAL['db'] -> query("SELECT gid,data FROM " . tname('exchange_orderform') . " WHERE gid=".$gid ); 
    
	 while ($value = $_SGLOBAL['db'] -> fetch_array($query)) {
		  if(gmdate("Y-m-d",$_SGLOBAL['timestamp']) != gmdate("Y-m-d",$value['data'])) $count ++;
	  }

    if($count<$robnumber) return true;

	return false;
}


// 添加礼品
function gif_post($POST, $olds = array()) {
	global $_SGLOBAL, $_SC, $SConfig; 
    
	// 标题
	$POST['subject'] = getstr($POST['subject'], 80, 1, 1, 1);
	if (strlen($POST['subject']) < 1) $POST['subject'] = sgmdate('Y-m-d'); 
	// 内容
	$POST['message'] = checkhtml($POST['message']);
	$POST['message'] = getstr($POST['message'], 0, 1, 0, 1, 0, 1);
	$POST['message'] = preg_replace("/\<div\>\<\/div\>/i", '', $POST['message']);
	$message = $POST['message']; 
	// 奖品分类
	if (empty($olds['classid']) || $POST['classid'] != $olds['classid']) {
		if (!empty($POST['classid']) && substr($POST['classid'], 0, 4) == 'new:') {
			// 分类名
			$classname = shtmlspecialchars(trim(substr($POST['classid'], 4)));
			$classname = getstr($classname, 0, 1, 1, 1);
			if (empty($classname)) {
				$classid = 0;
			} else {
				$classid = getcount('exchange_class', array('classname' => $classname), 'classid');
				if (empty($classid)) {
					$setarr = array('classname' => $classname
						);
					$classid = inserttable('exchange_class', $setarr, 1);
				} 
			} 
		} else {
			$classid = intval($POST['classid']);
		} 
	} else {
		$classid = $olds['classid'];
	} 
	if ($classid && empty($classname)) {
		$classname = getcount('exchange_class', array('classid' => $classid), 'classname');
		if (empty($classname)) $classid = 0;
	} 
	$expiration = $POST['expiration'] ? strtotime($POST['expiration']) : '';
	$begin = $POST['begin'] ? strtotime($POST['begin']) : ''; 
	$needregtime=$POST['needregtime'] ? ($POST['needregtime']) : '0';
	$robnumber=$POST['robnumber'] ? ($POST['robnumber']) : '0';
	// 如果是自动发货，自动更新库存数目
	if (($POST['autokey']==1) && $olds['id']) {
		$POST['num'] = $_SGLOBAL['db'] -> result($_SGLOBAL['db'] -> query("SELECT COUNT(*) FROM " . tname('exchange_keys') . " WHERE gifts_id=" . $olds['id']));
	} 
	// 主表
	$blogarr = array('title' => $POST['subject'],
		'rank' => $POST['rank'],
		'body' => $message,
		'type' => $classid,
		'num' => $POST['num']?intval($POST['num']):0,
		'price' => $POST['price']?intval($POST['price']):0,
		'sale' => $POST['sale']?intval($POST['sale']):0,
		'ac' => $POST['ac'],
		'autokey' => $POST['autokey']?$POST['autokey']:0,
		'url' => $POST['url'],
		'buynum' => $POST['buynum'],
		'robnumber'=>$POST['robnumber'],
		'begin' => $begin,
		'expiration' => $expiration ,
		'needregtime'=>$needregtime ,
		'uid' => $POST['uid']?$POST['uid']:0
		); 


	// 标题图片
	$titlepic = ''; 
	// 获取上传的图片
	$uploads = array();
	if (!empty($POST['picids'])) {
		$picids = array_keys($POST['picids']);
		$query = $_SGLOBAL['db'] -> query("SELECT * FROM " . tname('pic') . " WHERE picid IN (" . simplode($picids) . ") AND uid='$_SGLOBAL[supe_uid]'");
		while ($value = $_SGLOBAL['db'] -> fetch_array($query)) {
			if (empty($titlepic) && $value['thumb']) {
				$titlepic = $value['filepath'] . '.thumb.jpg';
				$blogarr['picflag'] = $value['remote']?2:1;
			} 
			$uploads[$POST['picids'][$value['picid']]] = $value;
		} 
		if (empty($titlepic) && $value) {
			$titlepic = $value['filepath'];
			$blogarr['picflag'] = $value['remote']?2:1;
		} 
	} 
	// 插入文章
	if ($uploads) {
		preg_match_all("/\<img\s.*?\_uchome\_localimg\_([0-9]+).+?src\=\"(.+?)\"/i", $message, $mathes);
		if (!empty($mathes[1])) {
			$searchs = $idsearchs = array();
			$replaces = array();
			foreach ($mathes[1] as $key => $value) {
				if (!empty($mathes[2][$key]) && !empty($uploads[$value])) {
					$searchs[] = $mathes[2][$key];
					$idsearchs[] = "_uchome_localimg_$value";
					$replaces[] = mkpicurl($uploads[$value], 0);
					unset($uploads[$value]);
				} 
			} 
			if ($searchs) {
				$message = str_replace($searchs, $replaces, $message);
				$message = str_replace($idsearchs, 'uchomelocalimg[]', $message);
			} 
		} 
		// 未插入文章
		foreach ($uploads as $value) {
			$picurl = mkpicurl($value, 0);
			$message .= "<div class=\"uchome-message-pic\"><img src=\"$picurl\"><p>$value[title]</p></div>";
		} 
	} 
	// 出现问题
	if (empty($message) || empty($POST['subject']) || empty($POST['classid']) || empty($POST['price']) || empty($POST['num'])) {
		return false; //没有任何内容
	} 
	// 添加slashes
	$message = addslashes($message);
	$blogarr['body'] = $message;
	$blogarr['pic'] = $picurl; 
	// 从内容中读取图片
	if (empty($titlepic)) {
		$titlepic = getmessagepic($message);
		$blogarr['picflag'] = 0;
		$blogarr['pic'] = $titlepic;
	} 

	if ($olds['id']) {
		// 更新
		$id = $olds['id'];
		updatetable('exchange_gifts', $blogarr, array('id' => $id));
		Return $id;
	} else {

		$blogarr['dateline'] = empty($POST['dateline'])?$_SGLOBAL['timestamp']:$POST['dateline'];
		$blogid = inserttable('exchange_gifts', $blogarr, 1); 
		
		// 添加自动发货（游戏卡列表）
		$keys = trim($POST['keys']);
		$list = explode("\n", $keys);

		if ($list && $blogid) {
			$count = 0;
			for($i = 0;$i < count($list);$i++) {
				if (strlen($list[$i]) > 0) {
					$keyid = inserttable('exchange_keys', array('gifts_id' => $blogid, 'keys' => $list[$i], 'keyuid' => 0, 'keyac' => 1), 1);
					$count++;
				} 
			} 

			// 更新库存数
			if ($POST['autokey']==1 && $blogid) {			
			updatetable('exchange_gifts', array('num' => $count), array('id' => $blogid));
			}

		} 
		// 事件推动
		$feedmessage = "<B>[<a href=".$_SC['siteurl']."exchange.php>" . $SConfig["APP_TITLE"] . "</a>]</B> <a href=".$_SC['siteurl'] ."space.php?uid=" . $space[uid] . " target=_black>" . $space[username] . "</a> 新增了一个兑奖礼品《<a href=exchange.php?op=detailcontent&id=" . $blogid . ">" . $POST['subject'] . "</a>》";
		feed_add('exchange', $feedmessage);

   	return $blogid;
	} 
	

} 
// 获取礼品图片
function getmessagepic($message) {
	$pic = '';
	$message = stripslashes($message);
	$message = preg_replace("/\<img src=\".*?image\/face\/(.+?).gif\".*?\>\s*/is", '', $message); //移除表情符
	preg_match("/src\=[\"\']*([^\>\s]{25,105})\.(jpg|gif|png)/i", $message, $mathes);
	if (!empty($mathes[1]) || !empty($mathes[2])) {
		$pic = "{$mathes[1]}.{$mathes[2]}";
	} 
	return addslashes($pic);
} 
// 屏蔽html
function checkhtml($html) {
	$html = stripslashes($html);
	if (!checkperm('allowhtml')) {
		$html = preg_replace("/\<script.*?\>.*?\<\/script\>/is", '', $html); //去掉script
		preg_match_all("/\<([^\<]+)\>/is", $html, $ms);
		$searchs = $replaces = array();
		if ($ms[1]) {
			$allowtags = 'img|a|font|div|table|tbody|caption|tr|td|br|p|b|strong|i|u|em|span|ol|ul|li|blockquote|object|param|embed'; //允许的标签
			$ms[1] = array_unique($ms[1]);
			foreach ($ms[1] as $value) {
				$value = shtmlspecialchars($value);
				$searchs[] = "&lt;" . $value . "&gt;";
				$value = str_replace(array('\\', '/*'), array('.', '/.'), $value);
				$value = preg_replace(array("/(javascript|script|eval|behaviour|expression)/i", "/(\s+|&quot;|')on/i"), array('.', ' .'), $value);
				if (!preg_match("/^[\/|\s]?($allowtags)(\s+|$)/is", $value)) {
					$value = '';
				} 
				$replaces[] = empty($value)?'':"<" . str_replace('&quot;', '"', $value) . ">";
			} 
		} 
		$html = shtmlspecialchars($html);
		if ($searchs) {
			$html = str_replace($searchs, $replaces, $html);
		} 
		$html = preg_replace("/\&amp\;lt\;(.*?)\&amp\;gt\;/is", '&lt;\\1&gt;', $html); //恢复<>显示
	} 
	// $html = preg_replace("/\<([^\>]*?)width([=|:].*?(\s|\>|\'|\"|;))/is", '<\\1!width\\2', $html);
	$html = addslashes($html);

	return $html;
} 
// 检查是否已经购买过自动发放的卡
function check_buyer($POST) {
	global $_SGLOBAL, $_SC, $SConfig;
	$id = $POST['id']?$POST['id']:"0"; 
	// 检查是否已经领取过了
	$is_have = $_SGLOBAL['db'] -> result($_SGLOBAL['db'] -> query("SELECT * FROM " . tname('exchange_keys') . " WHERE gifts_id=" . $id . " AND keyuid=" . $_SGLOBAL['supe_uid']));

	Return $is_have;
} 
// 购买礼品
function buy_gif($POST) {
	global $_SGLOBAL, $_SC, $SConfig; 

   //确定登录
	checkislogin();

	// 确定物品存在
	$id = intval($POST['id'])?intval($POST['id']):0;
	$uid = intval($POST['uid'])?intval($POST['uid']):0;
	$autokey = trim($POST['autokey']);
	$num = intval($POST['amount']); 
	$price=intval($POST['price']); 
    $buynum=intval($POST['buynum']); 
	//输入过滤
	if(!eregi('^[0-9]+$',$num)||($num<1))   showmessage("请输入正确数量");

	//读取物品信息，价格等（防止post作弊）
    $sql="SELECT * FROM " . tname('exchange_gifts') . " WHERE id=" . $id . " AND ac='1' LIMIT 1" ;
	$query = $_SGLOBAL['db']->query($sql);
	$gifs= $_SGLOBAL['db']->fetch_array($query);
	if($gifs){
    $price=intval($gifs['price']); 
	$buynum=intval($gifs['buynum']); 
	$autokey=intval($gifs['autokey']); 
    $robnumber=intval($gifs['robnumber']); 

	if(!checkregtime($gifs['needregtime'])) showmessage('兑奖受限：此物品管理员设定了会员注册时间大于 '.$gifs['needregtime'].' 分钟后方可兑奖。');

     if(!checkrobnumber($robnumber,$id)) showmessage('兑奖受限：今天兑换名额已满，请明天早点来抢兑。<br><br>每天限兑: '.$robnumber.' 次。');

	}else{
	showmessage("此奖品已经关闭，请和管理联系");
	}

	// 自动发货
	if ($autokey) {

		//限制领取数
		if($buynum!=0){		

		//计算库存数
		// 检查是否已经领取过了
		$sql="SELECT COUNT(*) FROM " . tname('exchange_keys') . " WHERE gifts_id=" . $id . " AND keyuid=" . $_SGLOBAL['supe_uid'];
		$is_have = $_SGLOBAL['db']->result($_SGLOBAL['db']->query($sql), 0);
	
		if ($is_have>=$buynum) showmessage("您已经兑换过此物品 ".$is_have."件，请到收件箱查看。");
		}

		$query = $_SGLOBAL['db'] -> query("SELECT * FROM " . tname('exchange_keys') . " WHERE gifts_id=" . $id . " AND keyac=1 AND keyuid=0  LIMIT ".$num); 
        
		$keylist=array();
		$total_key=0;

         while ($value = $_SGLOBAL['db'] -> fetch_array($query)) {
				 $keylist[]=$value;
				 $total_key++;
		 }

		if ($total_key>=$num) {		

			//检查客户积分
			$total = $num * $price; //	
            if ($_SGLOBAL['my']['credits'] < $total) showmessage("您的积分不足，请返回。"); 

			// 扣除金币
			$_SGLOBAL['db'] -> query("UPDATE " . tname('space') . " SET credit=credit-$total WHERE uid=" . $_SGLOBAL['supe_uid']);

			//增加金币
			if ($uid) {
				$_SGLOBAL['db'] -> query("UPDATE " . tname('space') . " SET credit=credit+$total WHERE uid=" . $uid);
			}

			$keycount=0;
			$keymessage="";

			foreach($keylist as  $key=>$val){
			
			$keycount++;
			//加入订单
			$setarr = array('uid' => $_SGLOBAL['supe_uid'],
				'gid' => $id,
				'keyid' =>  $val['keyid'],
				'amount' => $_POST['amount'],
				'status' => 3,
				'data' => $_SGLOBAL['timestamp']
				);

			$adid = inserttable('exchange_orderform', $setarr, 1); 

			// 标记已售 ac=0 无效 ac=1 可用 ac=2 已售
			updatetable('exchange_keys', array('keyuid' => $_SGLOBAL['supe_uid'], 'keyac' => 2, 'dateline' => $_SGLOBAL['timestamp']), array('keyid' => $val['keyid'])); 				
               
			$temStr=str_replace('：',':',trim($val['keys'])); 
			$temStr = explode(":", $temStr); 
            
			if($total_key>1){
				$keymessage .="#  第 ".$keycount." 份 <br>";
			}else{
				$keymessage .="<br>";
			}

			if ($temStr[1]) {
			$keymessage .= "『卡      号』" . $temStr[0] . "<br>";
			$keymessage .= "『密      码』" . $temStr[1]. "<br>";
			} else {
			$keymessage .= "『密      钥』" . trim($val['keys'])."<br>";
			} 
			$keymessage .="--------------------------------------------------------------------------<br>";
            
			}//foreach($value as  $val)

			// 发站内通知
			$subbject = "您兑换领取的『" . $POST['title'] . "』帐号信息(系统自动发放)";
			$message = "";
			$message .=  "<br>";
			$message .= "感谢您使用我们的" . $SConfig["APP_TITLE"] . "自动发放服务,以下是你兑换的详细信息：<br><br> ";

			$message .= "========================================<br>";
			$message .= "『品      名』" . $POST['title'] . "<br>";
			$message .= "========================================<br>";
			$message .= "<br>";			
			$message .=$keymessage;
			$message .=  "<br>";

			if(strlen($POST['url'])>6) 	{
			 $message .="『官方网站』".$POST['url'];	
			}

			$message .= "<br>";
			$message .= "========================================<br>";
			$message .= "帐单明细 " . $num . " 份 (单价" . $price . ") 共消耗 " . $total . " 积分<br> ";
			$message .= "========================================<br>";
			$message .= "<br><br>";
			$message .= "注：如果卡号中间有冒号或者分号，则表示这些符号前面是帐号，后面是密码。没有则直接输入即可。<br>";
			$message .= "为了确保资源有效性， 请您在领取后48小时内使用。 如果出现激活码错误等信息，请到官方提交。谢谢合作";
			$message .= "<br>";
			$message .= "声明：此信息请妥善保管，丢失不可补发！如有疑问请PM管理员。";
			$message .= "<br><br>";
			$message .= "再次谢谢！";

			include_once S_ROOT . './uc_client/client.php';
			uc_pm_send($uid?$uid:$SConfig ['ADMINUID'], $_SGLOBAL['supe_uid'], $subbject , str_replace("<br>", "\n", $message), 1, 0, 0);		
			
			//短消息送给赞助商
			if ($uid) {
				$subbject_uid = "您的商品『" . $POST['title'] . "』已被兑换！";
				$message_uid  = "您在【兑换中心】的商品『" . $POST['title'] . "』已成功被 " . $_SGLOBAL['my']['username'] . " 兑换！<br>";
				$message_uid .= "帐单明细 " . $num . " 份 (单价" . $price . ") 共获得 " . $total . " 积分";
				uc_pm_send($_SGLOBAL['supe_uid'], $uid, $subbject_uid , str_replace("<br>", "\n", $message_uid), 1, 0, 0);	
			}
			
			if($SConfig["EMAIL_MOD"] && $_SGLOBAL['my']['email'] && $_SGLOBAL['my']['emailcheck']  ){
			//发站外邮件通知
			$mail_subject=$subbject;
			$mail_message=$temStr=$message; 
			include_once(S_ROOT.'./source/function_cp.php');
	        smail(0, $_SGLOBAL['my']['email'], $mail_subject, $mail_message);
	        $mail_syssubject="网站有会员兑换礼品";
	        smail(0, $SConfig[emailinfo], $mail_syssubject, $mail_message);			
			}

			// 更新库存数
			$_SGLOBAL['db'] -> query("UPDATE " . tname('exchange_gifts') . " SET num=num-$num,sale=sale+$num WHERE id=$id");

			// 事件推送
			$msg = "兑换奖品 <a href=".$_SC['siteurl'] ."exchange.php?op=detailcontent&id=".$gifs['id']."><b>" . $POST['title'] . "</b></a> " . $num . " 份 (单价" . $price . ") 合计消耗 " . $total . " 积分。 ";
			feed_add('exchange', "<a href=".$_SC['siteurl'] ."space.php?uid=" . $_SGLOBAL['supe_uid'] . " target=_blank>" . $_SGLOBAL['my']['username'] . "</a>: " . $msg);

			Return $keycount;
		} else {

			//通知管理员，库存不足
			$subbject="～库存不足～";
			$message ="";	
			$message .="敬爱的管理员，『".$POST['title']." 』库存不足，麻烦请补货。\n";
			$message .="\n\n";
			$message .="补好货后，请PM我。谢谢，您老辛苦了！";
			include_once S_ROOT . './uc_client/client.php';
			uc_pm_send( $_SGLOBAL['supe_uid'],$SConfig ['ADMINUID'], $subbject , $message, 1, 0, 0);

			showmessage("卡号库存不足，系统已经通知管理员。感谢支持！");
		} 
	} else {

		//限制领取数
		if($buynum!=0){
		//计算库存数
		// 检查是否已经领取过了
		$is_have = $_SGLOBAL['db'] -> result($_SGLOBAL['db'] -> query("SELECT * FROM " . tname('exchange_orderform') . " WHERE gid=" . $id . " AND uid=" . $_SGLOBAL['supe_uid']));
   
		if ($is_have>=$buynum) showmessage("您已经兑换过此物品，请到我的订单查看。");
		}

		// 手工发货
		$query = $_SGLOBAL['db'] -> query("SELECT * FROM " . tname('exchange_gifts') . " WHERE id=" . $id . " AND ac=1");
		$value = $_SGLOBAL['db'] -> fetch_array($query) ;

		if ($value['num'] < 1 || $value['num'] < $num) {

			//通知管理员，库存不足
			$subbject="～库存不足～";
			$message ="";	
			$message .="敬爱的管理员，『".$value['title']." 』库存不足，麻烦请补货。\n";
			$message .="\n\n";
			$message .="补好货后，请PM我。谢谢，您老辛苦了！";
			include_once S_ROOT . './uc_client/client.php';
			uc_pm_send( $_SGLOBAL['supe_uid'],$SConfig ['ADMINUID'], $subbject , $message, 1, 0, 0);

			showmessage("库存不足，系统已经通知管理员。感谢支持！");
		}

		if ($value && $value['num'] >= $num) {
			$total = $num * $value['price']; //	
			
			if ($_SGLOBAL['my']['credits'] < $total) showmessage("您的积分不足，请返回。"); 
			// 扣除金币
			$_SGLOBAL['db'] -> query("UPDATE " . tname('space') . " SET credit=credit-$total WHERE uid=" . $_SGLOBAL['supe_uid']);

			$setarr = array('uid' => $_SGLOBAL['supe_uid'],
				'gid' => $id,
				'amount' => $_POST['amount'],
				'status' => 1,
				'data' => $_SGLOBAL['timestamp']
				);

			$adid = inserttable('exchange_orderform', $setarr, 1); 
			// 更新礼品数量和销售数
			$_SGLOBAL['db'] -> query("UPDATE " . tname('exchange_gifts') . " SET num=num-$num,sale=sale+$num WHERE id=$id"); 
			// 事件推送
			$msg = "兑换奖品<a href=".$_SC['siteurl'] ."exchange.php?op=detailcontent&id=".$value['id']."><b>" . $value['title'] . "</b></a> " . $_POST['amount'] . " 份 (单价" . $value['price'] . ") 合计消耗 " . $total . " 积分。 ";
            feed_add('exchange', "<a href=".$_SC['siteurl'] ."space.php?uid=" . $_SGLOBAL['supe_uid'] . " target=_blank>" . $_SGLOBAL['my']['username'] . "</a>: " . $msg);
			
			$pmmsg = "兑换奖品" . $value['title'] . " " . $_POST['amount'] . " 份 (单价" . $value['price'] . ") 合计消耗 " . $total . " 积分 ";

			//附加个人收货信息
            $info=getinfo($_SGLOBAL['supe_uid']);
		   //发送消息
		    $subbject="您的订单已经提交（手工发货）。";
			$message="以下是您订单详情: \n".$pmmsg."\n\n 温馨提示：\n 1.请再次检查您的收货地址是否正确，以免耽误您收货。在《收货信息》可重新填写。\n 2.您可到我的订单跟踪订单处理进程。";
			$message.=str_replace("<br>", "\n", $info);
			$mailbody="以下是您订单详情: <br>".$msg."<br><br> 温馨提示：<br> 1.请再次检查您的收货地址是否正确，以免耽误您收货。在《收货信息》可重新填写。<br> 2.您可到我的订单跟踪订单处理进程。";
			$mailbody.=$info;
			include_once S_ROOT . './uc_client/client.php';
			uc_pm_send( $SConfig['ADMINUID'],$_SGLOBAL['supe_uid'], $subbject , $message, 1, 0, 0);

            //if($SConfig["EMAIL_MOD"] && $_SGLOBAL['my']['email'] && $_SGLOBAL['my']['emailcheck']  ){
			if($SConfig["EMAIL_MOD"] && $_SGLOBAL['my']['email'] ){
			//发站外邮件通知
			$mail_subject="您的积分兑换礼品订单详情";
			$mail_message=$mailbody;
			include_once(S_ROOT.'./source/function_cp.php');
	        smail(0, $_SGLOBAL['my']['email'], $mail_subject, $mail_message);
	        $mail_syssubject="网站有会员兑换礼品";
	        smail(0, $SConfig[emailinfo], $mail_syssubject, $mail_message);			
			}			

			Return $adid;
		} 
	} //end else autokey
} 
// 删除订单（会员级操作）
function del_orderform($ids) {
	global $_SGLOBAL, $_SC, $SConfig;

	   //确定登录
	checkislogin();

	foreach ($_POST['ids'] as $id => $value) {
		$query = $_SGLOBAL['db'] -> query("SELECT * FROM " . tname('exchange_gifts') . " a LEFT JOIN " . tname('exchange_orderform') . " b ON(a.id=b.gid)  WHERE b.uid=" . $_SGLOBAL['supe_uid'] . " AND b.status=1 AND b.id=" . $id);

		while ($value = $_SGLOBAL['db'] -> fetch_array($query)) {
			if ($value) {
				$num = $value['amount'];

				$coin = $value['amount'] * $value['price'];

				$gid = $value['gid'];

				$_SGLOBAL['db'] -> query("DELETE FROM " . tname('exchange_orderform') . " WHERE id=" . $id . " AND (status=1 OR status=4)"); 
				// 添加日志
				$msg = "清除订单 id=" . $id . " status=1 OR status=4 <br> SQL: " . "DELETE FROM " . tname('exchange_orderform') . " WHERE id=" . $id . " AND (status=1 OR status=4)";

				if ($value['status'] == 1) {
					$_SGLOBAL['db'] -> query("UPDATE " . tname('space') . " SET credit=credit+" . $coin . " WHERE uid=" . $_SGLOBAL['supe_uid']);
				} 
				// 更新礼品数量和销售数
				$_SGLOBAL['db'] -> query("UPDATE " . tname('exchange_gifts') . " SET num=num+$num,sale=sale-$num WHERE id=" . $gid);
			} 
		} 
	} 
	Return $ids;
} 
// 删除礼品
function del_gif($ids) {
	global $_SGLOBAL, $_SC, $SConfig;

	$_SGLOBAL['db'] -> query("DELETE FROM " . tname('exchange_gifts') . " WHERE id IN (" . simplode($ids) . ")");

	Return $ids;
} 
// 更新礼品
function up_gif($ids) {
	global $_SGLOBAL;
	foreach ($ids as $id => $value) {
		$setstr = "";
		if (!empty($_POST['rank'][$id])) {
			$setstr .= "rank = '" . $_POST['rank'][$id] . "', ";
		} else {
			$setstr .= "rank = '0', ";
		} 
		if (!empty($_POST['price'][$id])) {
			$setstr .= "price = '" . $_POST['price'][$id] . "', ";
		} else {
			$setstr .= "price = '0', ";
		} 
			// 如果是自动发货，自动更新库存数目
	if ($_POST['autokey'][$id]==1) {
		$_POST['num'][$id] = $_SGLOBAL['db'] -> result($_SGLOBAL['db'] -> query("SELECT COUNT(*) FROM " . tname('exchange_keys') . " WHERE gifts_id=" . $id));
	} 

		if (!empty($_POST['num'][$id])) {
			$setstr .= "num = '" . $_POST['num'][$id] . "', ";
		} else {
			$setstr .= "num = '0', ";
		} 
		if (!empty($_POST['sale'][$id])) {
			$setstr .= "sale = '" . $_POST['sale'][$id] . "', ";
		} else {
			$setstr .= "sale = '0', ";
		} 
		if (!empty($_POST['ac'][$id])) {
			$setstr .= "ac = '" . $_POST['ac'][$id] . "', ";
		} else {
			$setstr .= "ac = '0', ";
		} 

		$setstr = substr($setstr, 0, -2);
		$query = "UPDATE " . tname('exchange_gifts') . " SET $setstr WHERE id=" . $id;

		$_SGLOBAL['db'] -> query($query);
	} 
	Return $ids;
} 
// 批量添加帐号	//添加自动发货（游戏卡列表）
function add_keys($POST) {
	global $_SGLOBAL;
	$key = trim($POST['keys']);
	$list = explode("\n", $key);

	if ($list && $POST['id']) {
		for($i = 0;$i < count($list) + 1;$i++) {
			if (strlen(trim($list[$i])) > 0) $keyid = inserttable('exchange_keys', array('gifts_id' => $POST['id'], 'keys' => trim($list[$i]), 'keyuid' => 0, 'keyac' => 1), 1);
		} 
		if(count($list)){
		$count = $_SGLOBAL['db'] -> result($_SGLOBAL['db'] -> query("SELECT COUNT(*) FROM " . tname('exchange_keys') . " WHERE gifts_id=" . $POST['id'] ." AND keyuid='0' AND keyac='1'" )); 		
		// 更新库存数，并设置为自动发货
		updatetable('exchange_gifts', array('num' => $count,'autokey'=>1), array('id' => $POST['id']));
		}
	} 
	Return $keyid;
} 
// 删除帐号列表
function del_keys($ids) {
	global $_SGLOBAL, $_SC, $SConfig;

	$_SGLOBAL['db'] -> query("DELETE FROM " . tname('exchange_keys') . " WHERE keyid IN (" . simplode($ids) . ")");

foreach ($_POST['ids'] as $id => $value) {
	if (!empty($_POST['gifts_id'][$id])) {
			$lim = " AND gifts_id='" . $_POST['gifts_id'][$id]."'" ;
			$uplim=$_POST['gifts_id'][$id];
		} else {
			$lim = "";
	} 

	// 更新库存数，并设置为自动发货
	 $count = $_SGLOBAL['db'] -> result($_SGLOBAL['db'] -> query("SELECT COUNT(*) FROM " . tname('exchange_keys') . " WHERE keyuid='0'".$lim)); 		
	  if($uplim) updatetable('exchange_gifts', array('num' => $count,'autokey'=>1), array('id' => $uplim));
}
	Return $ids;
} 
// 更新帐号列表
function up_keys($ids) {
	global $_SGLOBAL;

	foreach ($_POST['ids'] as $id => $value) {
		$setstr = "";
		if (!empty($_POST['keys'][$id])) {
			$setstr .= "`keys` = '" . trim($_POST['keys'][$id]) . "', ";
		} else {
			$setstr .= "`keys` = '0', ";
		} 
		/*
		if (!empty($_POST['keyuid'][$id])) {
			$setstr .= "`keyuid` = '" . $_POST['keyuid'][$id] . "', ";
		} else {
			$setstr .= "`keyuid` =0, ";
		} 
		*/
		if (!empty($_POST['ac'][$id])) {
			$setstr .= "`keyac` = '" . $_POST['ac'][$id] . "', ";
		} else {
			$setstr .= "`keyac` = 0, ";
		} 
		if (!empty($_POST['gifts_id'][$id])) {
			$lim = " AND gifts_id='" . $_POST['gifts_id'][$id]."'" ;
			$uplim=$_POST['gifts_id'][$id];
		} else {
			$lim = "";
		} 

		$setstr = substr($setstr, 0, -2);
		$query = "UPDATE `" . tname('exchange_keys') . "` SET " . $setstr . " WHERE `keyid`='" . $id . "' AND keyuid='0'";
		$_SGLOBAL['db'] -> query($query);

		// 更新库存数，并设置为自动发货
	    $count = $_SGLOBAL['db'] -> result($_SGLOBAL['db'] -> query("SELECT COUNT(*) FROM " . tname('exchange_keys') . " WHERE keyuid='0'".$lim)); 		
	    if($uplim) updatetable('exchange_gifts', array('num' => $count,'autokey'=>1), array('id' => $uplim));
	} 



	Return $ids;
} 
// 更新个人信息
function up_myinfo($olds) {
	global $_SGLOBAL, $_SC, $SConfig; 

	//确定登录
	checkislogin();

	// 主表
	$arr = array('uid' => $_SGLOBAL['supe_uid'],
		'name' => $_POST['name'],
		'address' => $_POST['address'],
		'zipcode' => $_POST['zipcode'],
		'phone' => $_POST['phone'],
		'mobile' => $_POST['mobile'],
		'city' => $_POST['city'],
		'state' => $_POST['state'],
		'country' => $_POST['country'],
		'qq' => $_POST['qq'],
		'msn' => $_POST['msn'],
		'skype' => $_POST['skype'],
		'email' => $_POST['email']
		);

	if ($olds) {
		// 更新
		$uid = $olds['uid'];
		updatetable('exchange_user', $arr, array('uid' => $uid));
	} else {
		$uid = inserttable('exchange_user', $arr, 1);
	} 
	Return $uid;
} 
// 保存配置
function saveConfig($confItem, $newVal) {
	global $_SC, $SConfig; 
	// 配置文件是否可写
	if (is_writable("exchange/config.php")) {
		$newLine = "\$SConfig[\"" . strtoupper($confItem) . "\"] = ";

		if (is_numeric($newVal)) $newLine .= $newVal;
		elseif (is_bool($newVal)) $newLine .= ($newVal?"true":"false");
		else $newLine .= "\"" . str_replace("\"", "\\\"", stripslashes($newVal)) . "\"";

		$newLine .= ";";

		$configBody = file_get_contents("exchange/config.php");

		$configBody = preg_replace('/\\$SConfig\\["' . preg_quote(strtoupper($confItem)) . '"\\].*/',
			$newLine,
			$configBody
			);

		ignore_user_abort(true);
		if ($handle = fopen("exchange/config.php", "w")) {
			flock($handle, LOCK_EX);
			fwrite($handle, $configBody);
			flock($handle, LOCK_UN);
			fclose($handle);
			ignore_user_abort(false);

			$SConfig[$confItem] = $newVal;

			return true;
		} 
	} else return false;
} 

function saveCONF($POST) {
	$saveList = array("APP_OFF",
		"TIME_LIMIT",
		"VIEW_MOD",
		"EMAIL_MOD",
		"OPENDATE",
		"OPENTIME",
		"CLOSETIME",
		"PERPAGE",
		"PERPAGE_ODER",
		"PERPAGE_ADMIN",
		"PERPAGE_KEYS",
		"PERPAGE_ORDERFORM",
		"TITLE_LIMIT",
		"BODY_LIMIT",
		"APP_TITLE",
		"APP_GROUPID",
		"HELP_BODY",
		);

	$i = 0;

	foreach ($saveList as $saveEntry) {
		if (isset($POST[$saveEntry])) {
			if ($saveEntry == "HELP_BODY") {
				$POST['message'] = $POST[$saveEntry];
				$POST['message'] = checkhtml($POST['message']);
				$POST['message'] = getstr($POST['message'], 0, 1, 0, 1, 0, 1);
				$POST['message'] = preg_replace("/\<div\>\<\/div\>/i", '', $POST['message']);

				$message = $POST['message'];
				$POST[$saveEntry] = base64_encode($message);
			} 
          if ($saveEntry == "OPENDATE") {
			  $POST[$saveEntry]=implode(",",$POST[$saveEntry]);
		  }

			saveConfig($saveEntry, $POST[$saveEntry]);
		} else saveConfig($saveEntry, 0);
		$i++;
	} 
	Return $i;
} 
// 更新订单状态（订单处理）
function up_orderform($POST) {
	global $_SGLOBAL, $_SC, $SConfig;

	if ($POST['status'] <= 1) {
		Return;
	} 

	$query = $_SGLOBAL['db'] -> query("SELECT * FROM " . tname('exchange_gifts') . " a LEFT JOIN " . tname('exchange_orderform') . " b ON(a.id=b.gid)  WHERE b.uid>0  AND b.id=" . $POST['id']);

	while ($value = $_SGLOBAL['db'] -> fetch_array($query)) {
		if ($value) {
			$coin = $value['amount'] * $value['price'];

			if ($value['status'] != 4 && $POST['status'] == 4) {
				$_SGLOBAL['db'] -> query("UPDATE " . tname('space') . " SET credit=credit+" . $coin . " WHERE uid=" . $value['uid']);
			} 

			updatetable('exchange_orderform', array('status' => $POST['status']), array('id' => $POST['id']));

			//发站内短信
			$subbject="～订单状态更新通知～";
			$message ="";	
			$message .="您的订单状态有更新。\n";
			$message .="\n\n";
			$message .="请到我的订单进行查阅！";
			include_once S_ROOT . './uc_client/client.php';
			uc_pm_send( $SConfig ['ADMINUID'],$value['uid'], $subbject , $message, 1, 0, 0);

        	//发站外邮件通知
            $tomail=getemailbyuid($value['uid']);
			if($SConfig["EMAIL_MOD"] && $tomail  ){		
			$mail_subject="您的积分兑换礼品订单有新操作";
			$mail_message="请登陆网站的订单管理查看订单状态。";
			include_once(S_ROOT.'./source/function_cp.php');
			
	        if($tomail) smail(0, $tomail , $mail_subject, $mail_message);			
			}

		} 
	} 
	Return $POST['id'];
} 
// 删除订单（管理员级操作）
function admin_del_orderform($ids) {
	global $_SGLOBAL,$SConfig;
	foreach ($_POST['ids'] as $id => $value) {

		//确认物品存在
		$query = $_SGLOBAL['db'] -> query("SELECT * FROM " . tname('exchange_gifts') . " a LEFT JOIN " . tname('exchange_orderform') . " b ON(a.id=b.gid)  WHERE b.uid>0 AND (b.status=1 OR b.status=4) AND b.id=" . $id);

		while ($value = $_SGLOBAL['db'] -> fetch_array($query)) {
			if ($value) {
				$num = $value['amount'] ;
				$gid = $value['gid'] ;
				$coin = $value['amount'] * $value['price'];
				$autokey=$value['autokey'];
				
				$_SGLOBAL['db'] -> query("DELETE FROM " . tname('exchange_orderform') . " WHERE id=" . $id . " AND (status=1 OR status=4)"); 

				// 更新礼品数量和销售数
				$_SGLOBAL['db'] -> query("UPDATE " . tname('exchange_gifts') . " SET num=num+$num,sale=sale-$num WHERE id=$gid");
				
				//只有申请中积分
				if ($value['status' == 1]) {
					$_SGLOBAL['db'] -> query("UPDATE " . tname('space') . " SET credit=credit+" . $coin . " WHERE uid=" . $value['uid']);
				} 

			//发站内短信
			$subbject="～订单状态更新通知～";
			$message ="";	
			$message .="您的订单状态有更新。\n";
			$message .="\n\n";
			$message .="请到我的订单进行查阅！";
			include_once S_ROOT . './uc_client/client.php';
			uc_pm_send( $SConfig ['ADMINUID'],$value['uid'], $subbject , $message, 1, 0, 0);

        	//发站外邮件通知
            $tomail=getemailbyuid($value['uid']);
			if($SConfig["EMAIL_MOD"] && $tomail  ){		
			$mail_subject="您的积分兑换礼品订单有新操作";
			$mail_message="请登陆网站订单管理查看订单状态。";
			include_once(S_ROOT.'./source/function_cp.php');
			
	        if($tomail) smail(0, $tomail , $mail_subject, $mail_message);			
			}

			} 
		} 
	} 
	Return $ids;
} 

// 检查个人信息是否完整
function checkinfo($uid){
		global $_SGLOBAL;
	if($uid<1) return 0;
		
	$query = $_SGLOBAL['db'] -> query("SELECT * FROM " . tname('exchange_user') . " where uid='" . $uid . "'");

	$row = $_SGLOBAL['db'] -> fetch_array($query) ;

	if (empty($row['name']) || empty($row['address']) || empty($row['zipcode']) || empty($row['qq']) || empty($row['mobile']) || empty($row['city']) || empty($row['state']) || empty($row['country'])) {
		return 0;
	} else {
		return 1;
	} 
}

//获取个人信息
function getinfo($uid){
		global $_SGLOBAL;
	if($uid<1) return ;
		
	$query = $_SGLOBAL['db'] -> query("SELECT * FROM " . tname('exchange_user') . " where uid='" . $uid . "'");

 $row = $_SGLOBAL['db'] -> fetch_array($query) ;
 $info= "<br>收货信息:<br>";
 $info.="收货人姓名:".$row['name']."<br>";
 $info.="收货地址:".$row['address']." (".$row['state'].$row['city']." ".$row['country'].")<br>";
 $info.="邮编:".$row['zipcode']."<br>";
 $info.="联系手机:".$row['mobile']."<br>";
 $info.="联系ＱＱ:".$row['qq']."<br>";
 $info.="---------------------------------------------------------<br>";
  $info.="以上信息如不对，请及时和网站管理员联系。<br>";

 return $info;
}

//通过uid获取邮件地址
function getemailbyuid($uid){
	global $_SGLOBAL;
	if($uid<1) return;
	$query = $_SGLOBAL['db']->query('SELECT email FROM '.tname('spacefield')."  WHERE uid='".$uid."'");
	$row = $_SGLOBAL['db']->fetch_array($query);
    return $row['email'];
}

// 页面编码转换
function utf2gbk($str) {
	if (function_exists('mb_convert_encoding')) {
		return mb_convert_encoding($str , 'GBK' , 'UTF-8');
	} 
	if (function_exists('iconv')) {
		return iconv('UTF-8' , 'GBK' , $str);
	} 
} 

function gbk2utf($str) {
	if (function_exists('mb_convert_encoding')) {
		return mb_convert_encoding($str , 'UTF-8', 'GBK');
	} 
	if (function_exists('iconv')) {
		return iconv('GBK' , 'UTF-8' , $str);
	} 
} 

?>