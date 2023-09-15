<?php
/*
	[UCenter Home] (C) 2007-2008 Comsenz Inc.
	$Id: space_doing.php 12998 2009-08-05 03:29:54Z liguode $
*/

if(!defined('IN_UCHOME')) {
	exit('Access Denied');
}

//分页
$perpage = 20;
$perpage = mob_perpage($perpage);

$page = empty($_GET['page'])?0:intval($_GET['page']);
if($page<1) $page=1;
$start = ($page-1)*$perpage;

//检查开始数
ckstart($start, $perpage);

$dolist = array();
$count = 0;

if(empty($_GET['view']) && ($space['friendnum']<$_SCONFIG['showallfriendnum'])) {
	$_GET['view'] = 'all';//默认显示
}
	
//处理查询
$f_index = '';
if($_GET['view'] == 'all') {
	
	$wheresql = "1";
	$theurl = "space.php?uid=$space[uid]&do=$do&view=all";
	$f_index = 'USE INDEX(dateline)';
	$actives = array('all'=>' class="active"');
	
} else {
	
	if(empty($space['feedfriend'])) $_GET['view'] = 'me';
	
	if($_GET['view'] == 'me') {
		$wheresql = "uid='$space[uid]'";
		$theurl = "space.php?uid=$space[uid]&do=$do&view=me";
		$actives = array('me'=>' class="active"');
	} else {
		$wheresql = "uid IN ($space[feedfriend],$space[uid])";
		$theurl = "space.php?uid=$space[uid]&do=$do&view=we";
		$f_index = 'USE INDEX(dateline)';
		$actives = array('we'=>' class="active"');
	}
}

$doid = empty($_GET['doid'])?0:intval($_GET['doid']);
if($doid) {
	$count = 1;
	$f_index = '';
	$wheresql = "doid='$doid'";
	$theurl .= "&doid=$doid";
}


$doids = $clist = $newdoids = array();
if(empty($count)) {
	$count = $_SGLOBAL['db']->result($_SGLOBAL['db']->query("SELECT COUNT(*) FROM ".tname('doing')." WHERE $wheresql"), 0);
	//更新统计
	if($wheresql == "uid='$space[uid]'" && $space['doingnum'] != $count) {
		updatetable('space', array('doingnum' => $count), array('uid'=>$space['uid']));
	}
}
if($count) {
	$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('doing')." $f_index
		WHERE $wheresql
		ORDER BY dateline DESC
		LIMIT $start,$perpage");
	while ($value = $_SGLOBAL['db']->fetch_array($query)) {
		realname_set($value['uid'], $value['username']);
		$doids[] = $value['doid'];
		$value['image'] = unserialize($value['image']);	//Add By 01
		$dolist[] = $value;
	}
}

//单条处理
if($doid) {
	$dovalue = empty($dolist)?array():$dolist[0];
	if($dovalue) {
		if($dovalue['uid'] == $_SGLOBAL['supe_uid']) {
			$actives = array('me'=>' class="active"');
		} else {
			$space = getspace($dovalue['uid']);//对方的空间
			$actives = array('all'=>' class="active"');
		}
	}
}

//回复
if($doids) {
	
	include_once(S_ROOT.'./source/class_tree.php');
	$tree = new tree();
	
	$values = array();
	$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('docomment')." USE INDEX(dateline) WHERE doid IN (".simplode($doids).") ORDER BY dateline");
	while ($value = $_SGLOBAL['db']->fetch_array($query)) {
		realname_set($value['uid'], $value['username']);
		$newdoids[$value['doid']] = $value['doid'];
		if(empty($value['upid'])) {
			$value['upid'] = "do$value[doid]";
		}
		$tree->setNode($value['id'], $value['upid'], $value);
	}
}

foreach ($newdoids as $cdoid) {
	$values = $tree->getChilds("do$cdoid");
	foreach ($values as $key => $id) {
		$one = $tree->getValue($id);
		$one['layer'] = $tree->getLayer($id) * 2 - 2;
		$one['style'] = "padding-left:{$one['layer']}em;";
		if($_GET['highlight'] && $one['id'] == $_GET['highlight']) {
			$one['style'] .= 'color:red;font-weight:bold;';
		}
		$clist[$cdoid][] = $one;
	}
}

//分页
$multi = multi($count, $perpage, $page, $theurl);

//同心情的
$moodlist = array();
if($space['mood'] && empty($start)) {
	$query = $_SGLOBAL['db']->query("SELECT s.uid,s.username,s.name,s.namestatus,s.mood,s.updatetime,s.groupid,sf.note,sf.sex
		FROM ".tname('space')." s
		LEFT JOIN ".tname('spacefield')." sf ON sf.uid=s.uid
		WHERE s.mood='$space[mood]' ORDER BY s.updatetime DESC LIMIT 0,13");
	while ($value = $_SGLOBAL['db']->fetch_array($query)) {
		if($value['uid'] != $space['uid']) {
			realname_set($value['uid'], $value['username'], $value['name'], $value['namestatus']);
			$moodlist[] = $value;
			if(count($moodlist)==12) break;
		}
	}
}

$upid = 0;

//实名
realname_get();

//载入概览	Add By 01
for ($i=0; $i<10; $i++) {
	$pv['user'] = $_SN[$dolist[$i][uid]];
	$pv['message'] = getstr($dolist[$i]['message'], 120, 0, 0, 0, 0, -1);
	$pv['date'] = $dolist[$i]['dateline'];
	$pvdata[] = $pv;
}
$pvlist['title'] = '记录';
$pvlist['data'] = $pvdata;
$preview[] = $pvlist;

//外站API同步	Add By 01
if ($_SGLOBAL['supe_uid']) {
//	include_once(S_ROOT.'./source/OAuth.php');
	include_once(S_ROOT.'./source/OAuth10.php');
	include_once(S_ROOT.'./source/OAuth10a.php');
	include_once(S_ROOT.'./source/function_qq_t.php');
	include_once(S_ROOT.'./source/function_sina_t.php');
	include_once(S_ROOT.'./source/function_facebook.php');

	//新浪微博
	$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('api')." WHERE uid='$_SGLOBAL[supe_uid]' and app='sina'");
	$api_sina = $_SGLOBAL['db']->fetch_array($query);
	if ($api_sina) {
		$api_sina['sync_up_config'] = unserialize($api_sina['sync_up_config']);
	}
	//腾讯微博
	$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('api')." WHERE uid='$_SGLOBAL[supe_uid]' and app='qq'");
	$api_qq = $_SGLOBAL['db']->fetch_array($query);
	if ($api_qq) {
		$api_qq['sync_up_config'] = unserialize($api_qq['sync_up_config']);
	}
	
	//Facebook
	$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('api')." WHERE uid='$_SGLOBAL[supe_uid]' and app='facebook'");
	$api_fb = $_SGLOBAL['db']->fetch_array($query);
	if ($api_fb) {
		$api_fb['sync_up_config'] = unserialize($api_fb['sync_up_config']);
	}

	if (!$_REQUEST['oauth_verifier'] && !$_REQUEST['session']) {
		session_start();
		//新浪微博
		if (!$api_sina) {
			$oauth_sina = new WeiboOAuth( WB_AKEY , WB_SKEY  );
			$keys_sina = $oauth_sina->getRequestToken();
			$aurl_sina = $oauth_sina->getAuthorizeURL( $keys_sina['oauth_token'] , false , $_SC[siteurl].'space.php?do=doing&sync=sina' );
			$_SESSION['keys_sina'] = $keys_sina;
		}
		//腾讯微博
		if (!$api_qq) {
			$oauth_qq = new MBOpenTOAuth( MB_AKEY , MB_SKEY  );
			$keys_qq = $oauth_qq->getRequestToken( $_SC[siteurl].'space.php?do=doing&sync=qq' );
			$aurl_qq = $oauth_qq->getAuthorizeURL( $keys_qq['oauth_token'] , false , '' );
			$_SESSION['keys_qq'] = $keys_qq;
		}
		//Facebook
		if (!$api_fb) {
			$facebook = new Facebook(array(
			  'appId'  => FB_APPID,
			  'secret' => FB_SECRET,
			  'cookie' => true,
			));
			$aurl_fb = $facebook->getLoginUrl(array(
				'req_perms' => 'offline_access,
								user_about_me,user_activities,user_birthday,user_education_history,user_events,user_groups,user_hometown,user_interests,user_likes,user_location,user_notes,user_online_presence,user_photo_video_tags,user_photos,user_relationships,user_relationship_details,user_religion_politics,user_status,user_videos,user_website,user_work_history,user_checkins,
								friends_about_me,friends_activities,friends_birthday,friends_education_history,friends_events,friends_groups,friends_hometown,friends_interests,friends_likes,friends_location,friends_notes,friends_online_presence,friends_photo_video_tags,friends_photos,friends_relationships,friends_relationship_details,friends_religion_politics,friends_status,friends_videos,friends_website,friends_work_history,friends_checkins,
								email,read_friendlists,read_insights,read_mailbox,read_requests,read_stream,xmpp_login,ads_management,manage_friendlists,
								publish_stream,create_event,rsvp_event,sms,publish_checkins,
								manage_pages',
				'next' => $_SC[siteurl].'space.php?do=doing&sync=facebook',
				'cancel_url' => $_SC[siteurl].'space.php?do=doing&sync=facebook'
			));
		}
	}else{
		session_start();
		if ($_GET['sync']=='sina') {
			$sync_text = '新浪微博';
			$sync_text_id = '新浪ID';
		}elseif($_GET['sync']=='qq') {
			$sync_text = '腾讯微博';
			$sync_text_id = 'QQ账号';
		}elseif($_GET['sync']=='facebook') {
			$sync_text = 'Facebook';
			$sync_text_id = 'Facebook ID';
		}
		if ($_GET['sync']=='sina') {
			$oauth_sina = new WeiboOAuth( WB_AKEY , WB_SKEY , $_SESSION['keys_sina']['oauth_token'] , $_SESSION['keys_sina']['oauth_token_secret']  );
			$accesskey = $oauth_sina->getAccessToken(  $_REQUEST['oauth_verifier'] );
		}elseif($_GET['sync']=='qq') {
			$oauth_qq = new MBOpenTOAuth( MB_AKEY , MB_SKEY , $_SESSION['keys_qq']['oauth_token'] , $_SESSION['keys_qq']['oauth_token_secret']  );
			$accesskey = $oauth_qq->getAccessToken(  $_REQUEST['oauth_verifier'] ) ;
		}elseif($_GET['sync']=='facebook') {
			$facebook = new Facebook(array(
			  'appId'  => FB_APPID,
			  'secret' => FB_SECRET,
			  'cookie' => true,
			));
			$accesskey = $facebook->getSession();
		}

		if ($_GET['sync']=='sina' || $_GET['sync']=='qq') {
			if (!$accesskey['oauth_token'] || !$accesskey['oauth_token_secret']) {
				showmessage('连接'.$sync_text.'出错！', $_SC[siteurl].'space.php?do=doing', 5);
			}
		} elseif ($_GET['sync']=='facebook') {
			if (!$accesskey['access_token'] || !$accesskey['secret']) {
				showmessage('连接'.$sync_text.'出错！', $_SC[siteurl].'space.php?do=doing', 5);
			}
		}

		$query = $_SGLOBAL['db']->query("SELECT uid FROM ".tname('api')." WHERE accesskey='".serialize($accesskey)."' and app='$_GET[sync]'");
		$apiuser = $_SGLOBAL['db']->fetch_array($query);
		if ($apiuser['uid']) {
			showmessage('这个'.$sync_text_id.'已经被你的主号【'.$_SN[$apiuser['uid']].'】抢先连接啦！<br><br>一个号只能连接一个ID噢～', $_SC[siteurl].'space.php?do=doing', 5);
		}

		$setarr = array(
			'uid' => $_SGLOBAL['supe_uid'],
			'username' => $_SGLOBAL['supe_username'],
			'app' => $_GET['sync'],
			'accesskey' => serialize($accesskey),
			'updatetime' => 0,
			'donetime' => 0
		);
		if ( ($_GET['sync']=='sina'&&empty($api_sina)) || ($_GET['sync']=='qq'&&empty($api_qq)) || ($_GET['sync']=='facebook'&&empty($api_fb)) ) {
			inserttable('api', $setarr);
		}else{
			updatetable('api', $setarr, array('uid'=>$_SGLOBAL['supe_uid']));
		}
		echo "<script>location.href='space.php?do=doing'</script>";
	}
}else{
	$aurl_sina = "javascript:alert('请先登录！');";
	$aurl_qq = "javascript:alert('请先登录！');";
	$aurl_fb = "javascript:alert('请先登录！');";
}


$_TPL['css'] = 'doing';
include_once template("space_doing");

//同步微博	Add By 01
include_once(S_ROOT.'./sync.php');
//if (rand(0,1)) {
	sync('sina',0);
//}else{
	sync('qq',0);
//}
//echo '<iframe noscroll border="0" src="sync.php?app=sina&sync_id=0" width="0" height="0" name="stat" id="stat"></iframe>';
//echo '<iframe noscroll border="0" src="sync.php?app=qq&sync_id=0" width="0" height="0" name="stat" id="stat"></iframe>';

?>