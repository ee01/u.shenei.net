<?php
/*
	[UCenter Home] (C) 2007-2008 Comsenz Inc.
	$Id: space_album.php 10322 2008-11-28 09:44:18Z liguode $
*/

if(!defined('IN_UCHOME')) {
	exit('Access Denied');
}

$minhot = $_SCONFIG['feedhotmin']<1?3:$_SCONFIG['feedhotmin'];

$id = empty($_GET['id'])?0:intval($_GET['id']);
$picid = empty($_GET['picid'])?0:intval($_GET['picid']);
$page = empty($_GET['page'])?1:intval($_GET['page']);
if($page<1) $page=1;

//表态分类
@include_once(S_ROOT.'./data/data_click.php');
$clicks = empty($_SGLOBAL['click']['picid'])?array():$_SGLOBAL['click']['picid'];

if($space['self'])
{
$styname=stylecount($_SGLOBAL['supe_uid']);
$usercss = new Usercssabouta($uid,$_SGLOBAL['supe_uid']);
$tempstyle=$usercss -> tempstyle;
$defaultset=$usercss -> defaultset;
$wrap=$usercss -> wrap;
$scss='<link type="text/css" rel="stylesheet" href="viewspace/css/'.get_userURL2($uid).'?'.$usercss->date.'" />';
}
else
{
$value=Usercssabout($uid);
$wrap=strlen($value[frame_set])==2?wraptwo:wrap;
$scss='<link type="text/css" rel="stylesheet" href="viewspace/css/'.get_userURL2($uid).'" />';
}



$musiclist=unserialize($space[smusic]);
$mcount=0;
foreach ($musiclist as $key => $value) {
$mlist .='listURL['.$mcount.']="'.$value.'"
';
$mlist .='RadioList['.$mcount.']="'.$key.'"
';
$mcount =$mcount+1;
}
//是否好友
$space['isfriend'] = $space['self'];
if($space['friends'] && in_array($_SGLOBAL['supe_uid'], $space['friends'])) {
	$space['isfriend'] = 1;//是好友
}
//是否在线
$isonline = 0;
$isonline = $_SGLOBAL['db']->result($_SGLOBAL['db']->query("SELECT lastactivity FROM ".tname('session')." WHERE uid='$uid'"), 0);

//红包道具
$space['magiccredit'] = 0;
if($_SGLOBAL['magic']['gift'] && $_SGLOBAL['supe_uid']) {
	$query = $_SGLOBAL['db']->query('SELECT * FROM '.tname('magicuselog')." WHERE uid='$space[uid]' AND mid='gift' LIMIT 1");
	if($value = $_SGLOBAL['db']->fetch_array($query)) {
		$data = empty($value['data'])?array():unserialize($value['data']);
		if($data['left'] <= 0) {
			$_SGLOBAL['db']->query('DELETE FROM '.tname('magicuselog')." WHERE uid = '$_SGLOBAL[supe_uid]' AND mid = 'gift'");
		}
		if(!$data['receiver'] || !in_array($_SGLOBAL['supe_uid'], $data['receiver'])) {
			$space['magiccredit'] = $data['left'] >= $data['chunk'] ? $data['chunk'] : $data['left'];
		}
	}
}

if($id) {
	//图片列表
	$perpage = 20;
	$perpage = mob_perpage($perpage);
	
	$start = ($page-1)*$perpage;
	
	//检查开始数
	ckstart($start, $perpage);

	//查询相册
	if($id > 0) {
		$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('album')." WHERE albumid='$id' AND uid='$space[uid]' LIMIT 1");
		$album = $_SGLOBAL['db']->fetch_array($query);
		//相册不存在
		if(empty($album)) {
			showmessage('to_view_the_photo_does_not_exist');
		}

		//检查好友权限
		ckfriend_album($album);

		//查询
		$wheresql = "albumid='$id'";
		$count = $album['picnum'];
	} else {
		//默认相册
		$wheresql = "albumid='0' AND uid='$space[uid]'";
		$count = getcount('pic', array('albumid'=>0, 'uid'=>$space['uid']));

		$album = array(
			'uid' => $space['uid'],
			'albumid' => -1,
			'albumname' => lang('default_albumname'),
			'picnum' => $count
		);
	}

	//图片列表
	$list = array();
	if($count) {
		$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('pic')." WHERE $wheresql ORDER BY dateline DESC LIMIT $start,$perpage");
		while ($value = $_SGLOBAL['db']->fetch_array($query)) {
			$value['pic'] = pic_get($value['filepath'], $value['thumb'], $value['remote']);
			$list[] = $value;
		}
	}
	//分页
	$multi = multiviewspace($count, $perpage, $page, "viewspace.php?uid=$album[uid]&do=$do&id=$id");

	include_once template("newsp_viewspace_album_view");

} elseif ($picid) {

	if(empty($_GET['goto'])) $_GET['goto'] = '';

	$eventid = intval($eventid);
	if(empty($eventid)) {
		//检索图片
		$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('pic')." WHERE picid='$picid' AND uid='$space[uid]' LIMIT 1");
		$pic = $_SGLOBAL['db']->fetch_array($query);
	}
	
	if($_GET['goto']=='up') {
		//上一张
		if($eventid) {
			$query = $_SGLOBAL['db']->query("SELECT pic.*, ep.* FROM ".tname('eventpic')." ep LEFT JOIN ".tname("pic")." pic ON ep.picid = pic.picid WHERE ep.eventid='$eventid' AND ep.picid > '$pic[picid]' ORDER BY ep.picid ASC LIMIT 0,1");
			if(!$newpic = $_SGLOBAL['db']->fetch_array($query)) {
				//到头转到最后一张
				$query = $_SGLOBAL['db']->query("SELECT pic.*, ep.* FROM ".tname('eventpic')." ep LEFT JOIN ".tname("pic")." pic ON ep.picid = pic.picid WHERE ep.eventid='$eventid' ORDER BY ep.picid ASC LIMIT 1");
				$pic = $_SGLOBAL['db']->fetch_array($query);
			} else {
				$pic = $newpic;
			}
		} else {
			$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('pic')." WHERE albumid='$pic[albumid]' AND uid='$space[uid]' AND picid>$picid ORDER BY picid LIMIT 1");
			if(!$newpic = $_SGLOBAL['db']->fetch_array($query)) {
				//到头转到最早的一张
				$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('pic')." WHERE albumid='$pic[albumid]' AND uid='$space[uid]' ORDER BY picid LIMIT 1");
				$pic = $_SGLOBAL['db']->fetch_array($query);
			} else {
				$pic = $newpic;
			}
		}
	} elseif($_GET['goto']=='down') {
		//下一张
		if($eventid) {
			$query = $_SGLOBAL['db']->query("SELECT pic.*, ep.* FROM ".tname('eventpic')." ep LEFT JOIN ".tname("pic")." pic ON ep.picid = pic.picid WHERE ep.eventid='$eventid' AND ep.picid < '$pic[picid]' ORDER BY ep.picid DESC LIMIT 0,1");
			if(!$newpic = $_SGLOBAL['db']->fetch_array($query)) {
				//到头转到第一张
				$query = $_SGLOBAL['db']->query("SELECT pic.*, ep.* FROM ".tname('eventpic')." ep LEFT JOIN ".tname("pic")." pic ON ep.picid = pic.picid WHERE ep.eventid='$eventid' ORDER BY ep.picid DESC LIMIT 1");
				$pic = $_SGLOBAL['db']->fetch_array($query);
			} else {
				$pic = $newpic;
			}
		} else {
			$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('pic')." WHERE albumid='$pic[albumid]' AND uid='$space[uid]' AND picid<$picid ORDER BY picid DESC LIMIT 1");
			if(!$newpic = $_SGLOBAL['db']->fetch_array($query)) {
				//到头转到最新的一张
				$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('pic')." WHERE albumid='$pic[albumid]' AND uid='$space[uid]' ORDER BY picid DESC LIMIT 1");
				$pic = $_SGLOBAL['db']->fetch_array($query);
			} else {
				$pic = $newpic;
			}
		}
	}
	
	$picid = $pic['picid'];

	//图片不存在
	if(empty($picid)) {
		showmessage('view_images_do_not_exist');
	}
	
	if($eventid) {
		$theurl = "viewspace.php?do=event&id=$eventid&view=pic&picid=$picid";
	} else {
		$theurl = "viewspace.php?uid=$pic[uid]&do=$do&picid=$picid";
	}

	//获取相册
	$album = array();
	if($pic['albumid']) {
		$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('album')." WHERE albumid='$pic[albumid]'");
		if(!$album = $_SGLOBAL['db']->fetch_array($query)) {
			updatetable('pic', array('albumid'=>0), array('albumid'=>$pic['albumid']));//相册丢失?
		}
	}

	if($album) {
		if($eventid) {
			//活动图片
			$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname("eventpic")." WHERE eventid='$eventid' AND picid='$picid'");
			if (!$eventpic = $_SGLOBAL['db']->fetch_array($query)) {
				showmessage('pic_not_share_to_event');// 图片没有共享到活动
			}
			$album['picnum'] = $piccount;
		} else {
			//相册好友权限
			ckfriend_album($album);	
		}
	} else {
		$album['picnum'] = getcount('pic', array('uid'=>$pic['uid'], 'albumid'=>0));
		$album['albumid'] = $pic['albumid'] = '-1';
	}
	
	if($album['picnum']) {
		//当前张数
		if($_GET['goto']=='down') {
			$sequence = empty($_SCOOKIE['pic_sequence'])?$album['picnum']:intval($_SCOOKIE['pic_sequence']);
			$sequence++;
			if($sequence>$album['picnum']) {
				$sequence = 1;
			}
		} elseif($_GET['goto']=='up') {
			$sequence = empty($_SCOOKIE['pic_sequence'])?$album['picnum']:intval($_SCOOKIE['pic_sequence']);
			$sequence--;
			if($sequence<1) {
				$sequence = $album['picnum'];
			}
		} else {
			$sequence = 1;
		}
		ssetcookie('pic_sequence', $sequence);
	}

	//图片地址
	$pic['pic'] = pic_get($pic['filepath'], $pic['thumb'], $pic['remote'], 0);
	$pic['size'] = formatsize($pic['size']);

	//图片的EXIF信息
	$exifs = array();
	$allowexif = function_exists('exif_read_data');
	if(isset($_GET['exif']) && $allowexif) {
		include_once(S_ROOT.'./source/function_exif.php');
		$exifs = getexif($pic['pic']);
	}

	//图片评论
	$perpage = 50;
	$start = ($page-1)*$perpage;
	
	//检查开始数
	ckstart($start, $perpage);

	$cid = empty($_GET['cid'])?0:intval($_GET['cid']);
	$csql = $cid?"cid='$cid' AND":'';
	$siteurl = getsiteurl();
	$list = array();
	$count = $_SGLOBAL['db']->result($_SGLOBAL['db']->query("SELECT COUNT(*) FROM ".tname('comment')." WHERE $csql id='$pic[picid]' AND idtype='picid'"),0);
	if($count) {
		$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('comment')." WHERE $csql id='$pic[picid]' AND idtype='picid' ORDER BY dateline LIMIT $start,$perpage");
		while ($value = $_SGLOBAL['db']->fetch_array($query)) {
			realname_set($value['authorid'], $value['author']);
			$list[] = $value;
		}
	}

	//分页
	$multi = multiviewspace($count, $perpage, $page, $theurl, '', 'pic_comment');

	//标题
	if(empty($album['albumname'])) $album['albumname'] = lang('default_albumname');

	//图片全路径
	$pic_url = $pic['pic'];
	if(!preg_match("/^http\:\/\/.+?/i", $pic['pic'])) {
		$pic_url = getsiteurl().$pic['pic'];
	}
	$pic_url2 = rawurlencode($pic['pic']);

	//访问统计
	if(!$space['self']) {
		inserttable('log', array('id'=>$space['uid'], 'idtype'=>'uid'));//延迟更新
	}
	
	//是否活动照片
	if(!$eventid) {
		$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname("eventpic")." ep LEFT JOIN ".tname("event")." e ON ep.eventid=e.eventid WHERE ep.picid='$picid'");
		$event = $_SGLOBAL['db']->fetch_array($query);
	}
	
	//表态
	$hash = md5($pic['uid']."\t".$pic['dateline']);
	$id = $pic['picid'];
	$idtype = 'picid';
	
	foreach ($clicks as $key => $value) {
		$value['clicknum'] = $pic["click_$key"];
		$value['classid'] = mt_rand(1, 4);
		if($value['clicknum'] > $maxclicknum) $maxclicknum = $value['clicknum'];
		$clicks[$key] = $value;
	}
	
	//点评
	$clickuserlist = array();
	$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('clickuser')."
		WHERE id='$id' AND idtype='$idtype'
		ORDER BY dateline DESC
		LIMIT 0,18");
	while ($value = $_SGLOBAL['db']->fetch_array($query)) {
		realname_set($value['uid'], $value['username']);//实名
		$value['clickname'] = $clicks[$value['clickid']]['name'];
		$clickuserlist[] = $value;
	}
	
	//热闹
	if(!$topic = topic_get($pic['topicid'])) {
		updatetable('pic', array('topicid'=>0), array('topicid'=>$blog['topicid']));
	}
	
	if(empty($eventid)) {
		//实名
		realname_get();

		include_once template("newsp_viewspace_album_pic");
	}

} else {
	//相册列表
	$perpage = 12;
	$start = ($page-1)*$perpage;
	
	//检查开始数
	ckstart($start, $perpage);

	//权限过滤
	$_GET['friend'] = intval($_GET['friend']);

	//处理查询
	$default = array();
	$f_index = '';
	$list = array();
	$pricount = 0;
	$picmode = 0;

	if(empty($_GET['view']) && ($space['friendnum']<$_SCONFIG['showallfriendnum'])) {
		$_GET['view'] = 'all';//默认显示
	}
	
	if($_GET['view'] == 'click') {
		
		//表态的图片
		$theurl = "viewspace.php?uid=$space[uid]&do=$do&view=click";
		$actives = array('click'=>' class="active"');
		
		$clickid = intval($_GET['clickid']);
		if($clickid) {
			$theurl .= "&clickid=$clickid";
			$wheresql = " AND c.clickid='$clickid'";
			$click_actives = array($clickid => ' class="current"');
		} else {
			$wheresql = '';
			$click_actives = array('all' => ' class="current"');
		}

		$picmode = 1;
		$count = $_SGLOBAL['db']->result($_SGLOBAL['db']->query("SELECT COUNT(*) FROM ".tname('clickuser')." c WHERE c.uid='$space[uid]' AND c.idtype='picid' $wheresql"),0);
		if($count) {
			$query = $_SGLOBAL['db']->query("SELECT p.*,a.albumname, a.username, c.clickid FROM ".tname('clickuser')." c
				LEFT JOIN ".tname('pic')." p ON p.picid=c.id
				LEFT JOIN ".tname('album')." a ON a.albumid=p.albumid
				WHERE c.uid='$space[uid]' AND c.idtype='picid' $wheresql
				ORDER BY c.dateline DESC LIMIT $start,$perpage");
			while ($value = $_SGLOBAL['db']->fetch_array($query)) {
				realname_set($value['uid'], $value['username']);
				$value['pic'] = pic_get($value['filepath'], $value['thumb'], $value['remote']);
				$list[] = $value;
			}
		}

	} elseif($_GET['view'] == 'all') {
		
		//大家的相册
		$theurl = "viewspace.php?uid=$space[uid]&do=$do&view=all";
		$actives = array('all'=>' class="active"');
		
		$wheresql = '1';
		
		//排序
		$orderarr = array('hot','dateline');
		foreach ($clicks as $value) {
			$orderarr[] = "click_$value[clickid]";
		}
		if(!in_array($_GET['orderby'], $orderarr)) $_GET['orderby'] = '';
		
		//时间
		$_GET['day'] = intval($_GET['day']);
		$_GET['hotday'] = 7;
		
		if($_GET['orderby'] == 'dateline') {
			
			$all_actives = array('dateline'=>' class="current"');
			$day_actives = array();
			
		} else {
			
			if ($_GET['orderby']) {
				$ordersql = 'p.'.$_GET['orderby'];
				
				$theurl = "viewspace.php?uid=$space[uid]&do=album&view=all&orderby=$_GET[orderby]";
				$all_actives = array($_GET['orderby']=>' class="current"');
				
				if($_GET['day']) {
					$_GET['hotday'] = $_GET['day'];
					$daytime = $_SGLOBAL['timestamp'] - $_GET['day']*3600*24;
					$wheresql .= " AND p.dateline>='$daytime'";
					
					$theurl .= "&day=$_GET[day]";
					$day_actives = array($_GET['day']=>' class="active"');
				} else {
					$day_actives = array(0=>' class="active"');
				}
			} else {
				$ordersql = 'p.dateline';
				$wheresql .= " AND p.hot>='$minhot'";
				
				$theurl = "viewspace.php?uid=$space[uid]&do=album&view=all";
				$all_actives = array('all'=>' class="current"');
			}
			
			$picmode = 1;
			$count = $_SGLOBAL['db']->result($_SGLOBAL['db']->query("SELECT COUNT(*) FROM ".tname('pic')." p WHERE $wheresql"),0);
			if($count) {
				$query = $_SGLOBAL['db']->query("SELECT p.*, a.username, a.albumname, a.friend, a.target_ids FROM ".tname('pic')." p
					LEFT JOIN ".tname('album')." a ON a.albumid=p.albumid
					WHERE $wheresql
					ORDER BY $ordersql DESC
					LIMIT $start,$perpage");
				while ($value = $_SGLOBAL['db']->fetch_array($query)) {
					if(ckfriend($value['uid'], $value['friend'], $value['target_ids'])) {
						realname_set($value['uid'], $value['username']);
						$value['pic'] = pic_get($value['filepath'], $value['thumb'], $value['remote']);
						$list[] = $value;
					} else {
						$pricount++;
					}
				}
			}
	
		}

	} else {
		
		if(empty($space['feedfriend'])) $_GET['view'] = 'me';
		
		if($_GET['view'] == 'me') {
		
			$wheresql = "uid='$space[uid]'";
			$theurl = "viewspace.php?uid=$space[uid]&do=$do&view=me";
			$actives = array('me'=>' class="active"');
		} else {
			
			$wheresql = "uid IN ($space[feedfriend])";
			$theurl = "viewspace.php?uid=$space[uid]&do=$do&view=we";
			$f_index = 'USE INDEX(updatetime)';
			$actives = array('we'=>' class="active"');
			
			$fuid_actives = array();
			
			//查看指定好友的
			$fusername = trim($_GET['fusername']);
			$fuid = intval($_GET['fuid']);
			if($fusername) {
				$fuid = getuid($fusername);
			}
			if($fuid && in_array($fuid, $space['friends'])) {
				$wheresql = "uid = '$fuid'";
				$theurl = "viewspace.php?uid=$space[uid]&do=$do&fuid=$fuid";
				$f_index = '';
				$fuid_actives = array($fuid=>' selected');
			}
			
			//好友列表
			$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('friend')." WHERE uid='$space[uid]' AND status='1' ORDER BY num DESC, dateline DESC LIMIT 0,500");
			while ($value = $_SGLOBAL['db']->fetch_array($query)) {
				realname_set($value['fuid'], $value['fusername']);
				$userlist[] = $value;
			}
		}
	}

	if(empty($picmode)) {
		//设置权限
		if($_GET['friend']) {
			$wheresql .= " AND friend='$_GET[friend]'";
			$theurl .= "&friend=$_GET[friend]";
		}
		
		//搜索
		if($searchkey = stripsearchkey($_GET['searchkey'])) {
			$wheresql .= " AND albumname LIKE '%$searchkey%'";
			$theurl .= "&searchkey=$_GET[searchkey]";
			cksearch($theurl);
		}
		
		$count = $_SGLOBAL['db']->result($_SGLOBAL['db']->query("SELECT COUNT(*) FROM ".tname('album')." WHERE $wheresql"),0);
		
		//更新统计
		if($wheresql == "uid='$space[uid]'" && $space['albumnum'] != $count) {
			updatetable('space', array('albumnum' => $count), array('uid'=>$space['uid']));
		}
		
		if($count) {
			$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('album')." $f_index WHERE $wheresql ORDER BY updatetime DESC LIMIT $start,$perpage");
			while ($value = $_SGLOBAL['db']->fetch_array($query)) {
				realname_set($value['uid'], $value['username']);
				if(ckfriend($value['uid'], $value['friend'], $value['target_ids'])) {
					$value['pic'] = pic_cover_get($value['pic'], $value['picflag']);
				} else {
					$value['pic'] = 'image/nopublish.gif';
				}
				$list[] = $value;
			}
		}
	}
	
	//分页
	$multi = multiviewspace($count, $perpage, $page, $theurl);

	//实名
	realname_get();

	include_once template("newsp_viewspace_album_list");
}

//检查好友权限
function ckfriend_album($album) {
	global $_SGLOBAL, $_SC, $_SCONFIG, $_SCOOKIE, $space, $_SN;

	if(!ckfriend($album['uid'], $album['friend'], $album['target_ids'])) {
		//没有权限
		include template('space_privacy');
		exit();
	} elseif(!$space['self'] && $album['friend'] == 4) {
		//密码输入问题
		$cookiename = "view_pwd_album_$album[albumid]";
		$cookievalue = empty($_SCOOKIE[$cookiename])?'':$_SCOOKIE[$cookiename];
		if($cookievalue != md5(md5($album['password']))) {
			$invalue = $album;
			include template('do_inputpwd');
			exit();
		}
	}
}

?>