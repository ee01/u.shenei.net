<?php
/*
	[UCenter Home] (C) 2007-2008 Comsenz Inc.
	$Id: cp_doing.php 13245 2009-08-25 02:01:40Z liguode $
*/

if(!defined('IN_UCHOME')) {
	exit('Access Denied');
}

$doid = empty($_GET['doid'])?0:intval($_GET['doid']);
$id = empty($_GET['id'])?0:intval($_GET['id']);
if(empty($_POST['refer'])) $_POST['refer'] = "space.php?do=doing&view=me";

if(submitcheck('addsubmit')) {

	$add_doing = 1;
	if(empty($_POST['spacenote'])) {
		if(!checkperm('allowdoing')) {
			ckspacelog();
			showmessage('no_privilege');
		}
		
		//实名认证
		ckrealname('doing');
		
		//视频认证
		ckvideophoto('doing');
		
		//新用户见习
		cknewuser();
	
		//验证码
		if(checkperm('seccode') && !ckseccode($_POST['seccode'])) {
			showmessage('incorrect_code');
		}
	
		//判断是否操作太快
		$waittime = interval_check('post');
		if($waittime > 0) {
			showmessage('operating_too_fast', '', 1, array($waittime));
		}
	} else {
		if(!checkperm('allowdoing')) {
			$add_doing = 0;
		}

		//实名
		if(!ckrealname('doing', 1)) {
			$add_doing = 0;
		}
		//视频
		if(!ckvideophoto('doing', array(), 1)) {
			$add_doing = 0;
		}
		//新用户
		if(!cknewuser(1)) {
			$add_doing = 0;
		}
		$waittime = interval_check('post');
		if($waittime > 0) {
			$add_doing = 0;
		}
	}
	
	//获取心情
	$mood = 0;
	preg_match("/\[em\:(\d+)\:\]/s", $_POST['message'], $ms);
	$mood = empty($ms[1])?0:intval($ms[1]);

	$message = getstr($_POST['message'], 200, 1, 1, 1);

	//替换表情
	//微博表情	Add By 01
	if ($_POST['sina'] || $_POST['qq'] || $_POST['facebook']) {
		$match='/\[em:(\d+):]/isU';
		if (preg_match_all($match,$message,$arr)) {
			$text_sina = $message;
			$text_qq = $message;
			$text_fb = $message;
			for ($i=0;$i<count($arr[1]);$i++) {
				$query = $_SGLOBAL['db']->query("SELECT sina,qq FROM ".tname('face')." WHERE id='{$arr[1][$i]}' ORDER BY level");
				$face = $_SGLOBAL['db']->fetch_array($query);
				$face_text = '[em:'.$arr[1][$i].':]';
				if ($_POST['sina'] && $face['sina']) {
					$arr2 = explode($face_text, $text_sina);
					$face_sina = '['.$face['sina'].']';
					$text_sina = $arr2[0].$face_sina.$arr2[1];
				}
				if($_POST['facebook'] && $face['sina']) {
					$arr2 = explode($face_text, $text_fb);
					$face_fb = '['.$face['sina'].']';
					$text_fb = $arr2[0].$face_fb.$arr2[1];
				}
				if($_POST['qq'] && $face['qq']) {
					$arr2 = explode($face_text, $text_qq);
					$face_qq = '/'.$face['qq'];
					$text_qq = $arr2[0].$face_qq.$arr2[1];
				}
			}
		}else{
			$text_sina = $message;
			$text_qq = $message;
			$text_fb = $message;
		}
	}
	//系统表情替换
	$message = preg_replace("/\[em:(\d+):]/is", "<img src=\"image/face/\\1.gif\" class=\"face\">", $message);
	$message = preg_replace("/\<br.*?\>/is", ' ', $message);
	
	if(strlen($message) < 1) {
		showmessage('should_write_that');
	}
	
	if($add_doing) {
		$setarr = array(
			'uid' => $_SGLOBAL['supe_uid'],
			'username' => $_SGLOBAL['supe_username'],
			'dateline' => $_SGLOBAL['timestamp'],
			'message' => $message,
			'mood' => $mood,
			'ip' => getonlineip()
		);
		//入库
		$newdoid = inserttable('doing', $setarr, 1);
	}
	
	//更新空间note
	$setarr = array('note'=>$message);
	$credit = $experience = 0;
	if(!empty($_POST['spacenote'])) {
		$reward = getreward('updatemood', 0);
		$setarr['spacenote'] = $message;
	} else {
		$reward = getreward('doing', 0);
	}
	updatetable('spacefield', $setarr, array('uid'=>$_SGLOBAL['supe_uid']));
	
	if($reward['credit']) {
		$credit = $reward['credit'];
	}
	if($reward['experience']) {
		$experience = $reward['experience'];
	}
	$setarr = array(
		'mood' => "mood='$mood'",
		'updatetime' => "updatetime='$_SGLOBAL[timestamp]'",
		'credit' => "credit=credit+$credit",
		'experience' => "experience=experience+$experience",
		'lastpost' => "lastpost='$_SGLOBAL[timestamp]'"
	);
	if($add_doing) {
		if(empty($space['doingnum'])) {//第一次
			$doingnum = getcount('doing', array('uid'=>$space['uid']));
			$setarr['doingnum'] = "doingnum='$doingnum'";
		} else {
			$setarr['doingnum'] = "doingnum=doingnum+1";
		}
	}
	$_SGLOBAL['db']->query("UPDATE ".tname('space')." SET ".implode(',', $setarr)." WHERE uid='$_SGLOBAL[supe_uid]'");
	
	//事件feed
	if($add_doing && ckprivacy('doing', 1)) {
		$feedarr = array(
			'appid' => UC_APPID,
			'icon' => 'doing',
			'uid' => $_SGLOBAL['supe_uid'],
			'username' => $_SGLOBAL['supe_username'],
			'dateline' => $_SGLOBAL['timestamp'],
			'title_template' => cplang('feed_doing_title'),
			'title_data' => saddslashes(serialize(sstripslashes(array('message'=>$message)))),
			'body_template' => '',
			'body_data' => '',
			'id' => $newdoid,
			'idtype' => 'doid'
		);
		$feedarr['hash_template'] = md5($feedarr['title_template']."\t".$feedarr['body_template']);//喜好hash
		$feedarr['hash_data'] = md5($feedarr['title_template']."\t".$feedarr['title_data']."\t".$feedarr['body_template']."\t".$feedarr['body_data']);//合并hash
		inserttable('feed', $feedarr);
	}

	//统计
	updatestat('doing');
	
	//同步微博	Add By 01
	if ($_POST['sina']) {
//		include_once(S_ROOT.'./source/OAuth.php');
		include_once(S_ROOT.'./source/OAuth10a.php');
		include_once(S_ROOT.'./source/function_sina_t.php');
		$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('api')." WHERE uid='$_SGLOBAL[supe_uid]' and app='sina'");
		$api_sina = $_SGLOBAL['db']->fetch_array($query);
		$accesskey_sina = unserialize($api_sina['accesskey']);
		
		$sina = new WeiboClient( WB_AKEY , WB_SKEY , $accesskey_sina['oauth_token'] , $accesskey_sina['oauth_token_secret'] );
		$text = siconv($text_sina." 【From】#舍内网#", "utf-8", "gbk");
		$sina_r = $sina->update( $text );
		if (!$sina_r['text']) {
			showmessage('新浪微博同步失败！也许是存在不允许字符。<br><br>但已成功更新到舍内网！', $_POST['refer'], 3);
		}
	}
	if ($_POST['qq']) {
//		include_once(S_ROOT.'./source/OAuth.php');
		include_once(S_ROOT.'./source/OAuth10.php');
		include_once(S_ROOT.'./source/function_qq_t.php');
		$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('api')." WHERE uid='$_SGLOBAL[supe_uid]' and app='qq'");
		$api_qq = $_SGLOBAL['db']->fetch_array($query);
		$accesskey_qq = unserialize($api_qq['accesskey']);
		
		$qq = new MBApiClient( MB_AKEY , MB_SKEY , $accesskey_qq['oauth_token'] , $accesskey_qq['oauth_token_secret']  );
		$text = siconv($text_qq." 【From】#舍内网#", "utf-8", "gbk");
		$post = array(
			'c' => $text,
			'ip' => $_SERVER['REMOTE_ADDR'], 
			'j' => '',
			'w' => ''
		);
		$qq_r = $qq->postOne( $post );
		if ($qq_r['msg']!="ok") {
			showmessage('腾讯微博同步失败！也许是存在不允许字符。<br><br>但已成功更新到舍内网！', $_POST['refer'], 3);
		}
	}
	if ($_POST['facebook']) {
		include_once(S_ROOT.'./source/function_facebook.php');
		$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('api')." WHERE uid='$_SGLOBAL[supe_uid]' and app='facebook'");
		$api_fb = $_SGLOBAL['db']->fetch_array($query);
		$accesskey_fb = unserialize($api_fb['accesskey']);
		
		$facebook = new Facebook(array(
		  'appId'  => FB_APPID,
		  'secret' => FB_SECRET,
		  'cookie' => true,
		));
		$facebook->setSession($accesskey_fb);
		$text = siconv($text_fb."\n From【舍内网】", "utf-8", "gbk");
		$act_name = siconv("@".$_SN[$_SGLOBAL[supe_uid]]." On 舍内网", "utf-8", "gbk");
		$post = array(
			'message' => $text,
			'actions' => array(
				'name' => $act_name,
				'link' => $_SC[siteurl]."viewspace.php?uid=".$_SGLOBAL['supe_uid']
			)
		);
		$fb_r = $facebook->api('/me/feed', "POST", $post);
		if (!$fb_r['id']) {
			showmessage('Facebook同步失败！也许是存在不允许字符。<br><br>但已成功更新到舍内网！', $_POST['refer'], 3);
		}
	}
	
	showmessage('do_success', $_POST['refer'], 0);

} elseif (submitcheck('commentsubmit')) {
	
	if(!checkperm('allowdoing')) {
		ckspacelog();
		showmessage('no_privilege');
	}
	
	//实名认证
	ckrealname('doing');
	
	//新用户见习
	cknewuser();
	
	//判断是否操作太快
	$waittime = interval_check('post');
	if($waittime > 0) {
		showmessage('operating_too_fast', '', 1, array($waittime));
	}
	
	$message = getstr($_POST['message'], 200, 1, 1, 1);
	//替换表情
	$message = preg_replace("/\[em:(\d+):]/is", "<img src=\"image/face/\\1.gif\" class=\"face\">", $message);
	$message = preg_replace("/\<br.*?\>/is", ' ', $message);
	if(strlen($message) < 1) {
		showmessage('should_write_that');
	}
	
	$updo = array();
	if($id) {
		$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('docomment')." WHERE id='$id'");
		$updo = $_SGLOBAL['db']->fetch_array($query);
	}
	if(empty($updo) && $doid) {
		$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('doing')." WHERE doid='$doid'");
		$updo = $_SGLOBAL['db']->fetch_array($query);
	}
	if(empty($updo)) {
		showmessage('docomment_error');
	} else {
		//黑名单
		if(isblacklist($updo['uid'])) {
			showmessage('is_blacklist');
		}
	}
	
	$updo['id'] = intval($updo['id']);
	$updo['grade'] = intval($updo['grade']);
	
	$setarr = array(
		'doid' => $updo['doid'],
		'upid' => $updo['id'],
		'uid' => $_SGLOBAL['supe_uid'],
		'username' => $_SGLOBAL['supe_username'],
		'dateline' => $_SGLOBAL['timestamp'],
		'message' => $message,
		'ip' => getonlineip(),
		'grade' => $updo['grade']+1
	);
	
	//最多层级
	if($updo['grade'] >= 3) {
		$setarr['upid'] = $updo['upid'];//更母一个级别
	}

	$newid = inserttable('docomment', $setarr, 1);
	
	//更新回复数
	$_SGLOBAL['db']->query("UPDATE ".tname('doing')." SET replynum=replynum+1 WHERE doid='$updo[doid]'");
	
	//通知
	if($updo['uid'] != $_SGLOBAL['supe_uid']) {
		$note = cplang('note_doing_reply', array("space.php?do=doing&doid=$updo[doid]&highlight=$newid"));
		notification_add($updo['uid'], 'doing', $note);
		//奖励积分
		getreward('comment',1, 0, 'doing'.$updo['doid']);
	}
	
	//统计
	updatestat('docomment');
		
	showmessage('do_success', $_POST['refer'], 0);

}

//删除
if($_GET['op'] == 'delete') {
	
	if(submitcheck('deletesubmit')) {
		if($id) {
			$allowmanage = checkperm('managedoing');
			$query = $_SGLOBAL['db']->query("SELECT dc.*, d.uid as duid FROM ".tname('docomment')." dc, ".tname('doing')." d WHERE dc.id='$id' AND dc.doid=d.doid");
			if($value = $_SGLOBAL['db']->fetch_array($query)) {
				if($allowmanage || $value['uid'] == $_SGLOBAL['supe_uid'] || $value['duid'] == $_SGLOBAL['supe_uid'] ) {
					//更新内容
					updatetable('docomment', array('uid'=>0, 'username'=>'', 'message'=>''), array('id'=>$id));
					if($value['uid'] != $_SGLOBAL['supe_uid'] && $value['duid'] != $_SGLOBAL['supe_uid']) {
						//扣除积分
						getreward('delcomment', 1, $value['uid']);
					}
				}
			}
		} else {
			include_once(S_ROOT.'./source/function_delete.php');
			deletedoings(array($doid));
		}
		
		showmessage('do_success', $_POST['refer'], 0);
	}
	
} elseif ($_GET['op'] == 'getcomment') {
	
	include_once(S_ROOT.'./source/class_tree.php');
	$tree = new tree();
	
	$list = array();
	$highlight = 0;
	$count = 0;
	
	if(empty($_GET['close'])) {
		$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('docomment')." WHERE doid='$doid' ORDER BY dateline");
		while ($value = $_SGLOBAL['db']->fetch_array($query)) {
			realname_set($value['uid'], $value['username']);
			$tree->setNode($value['id'], $value['upid'], $value);
			$count++;
			if($value['authorid'] = $space['uid']) $highlight = $value['id'];
		}
	}
	
	if($count) {
		$values = $tree->getChilds();
		foreach ($values as $key => $vid) {
			$one = $tree->getValue($vid);
			$one['layer'] = $tree->getLayer($vid) * 2;
			$one['style'] = "padding-left:{$one['layer']}em;";
			if($one['id'] == $highlight && $one['uid'] == $space['uid']) {
				$one['style'] .= 'color:red;font-weight:bold;';
			}
			$list[] = $one;
		}
	}
	
	realname_get();
	
}

include template('cp_doing');

?>