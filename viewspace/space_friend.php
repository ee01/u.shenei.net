<?php
/*
	[UCenter Home] (C) 2007-2008 Comsenz Inc.
	$Id: space_friend.php 10530 2008-12-08 07:16:27Z zhengqingpeng $
*/

if(!defined('IN_UCHOME')) {
	exit('Access Denied');
}

//��ҳ
$perpage = 24;
$list = $ols = $fuids = array();
$count = 0;
$page = empty($_GET['page'])?0:intval($_GET['page']);
if($page<1) $page = 1;
$start = ($page-1)*$perpage;

//��鿪ʼ��
ckstart($start, $perpage);

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
$wrap='wraptwo';
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
//�Ƿ����
$space['isfriend'] = $space['self'];
if($space['friends'] && in_array($_SGLOBAL['supe_uid'], $space['friends'])) {
	$space['isfriend'] = 1;//�Ǻ���
}

//�������
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
//�Ƿ�����
$isonline = 0;
$isonline = $_SGLOBAL['db']->result($_SGLOBAL['db']->query("SELECT lastactivity FROM ".tname('session')." WHERE uid='$uid'"), 0);


if($_GET['view'] == 'online') {
	$theurl = "viewspace.php?uid=$space[uid]&do=friend&view=online";
	$actives = array('me'=>' class="active"');
	
	$wheresql = '';
	if($_GET['type']=='near') {
		$theurl = "viewspace.php?uid=$space[uid]&do=friend&view=online&type=near";
		$wheresql = " WHERE main.ip='".getonlineip(1)."'";
	} elseif($_GET['type']=='friend' && $space['feedfriend']) {
		$theurl = "viewspace.php?uid=$space[uid]&do=friend&view=online&type=friend";
		$wheresql = " WHERE main.uid IN ($space[feedfriend])";
	}
	
	$count = $_SGLOBAL['db']->result($_SGLOBAL['db']->query("SELECT COUNT(*) FROM ".tname('session')." main $wheresql"), 0);
	if($count) {
		$query = $_SGLOBAL['db']->query("SELECT f.resideprovince, f.residecity, f.note, f.sex, f.note, f.spacenote, main.uid, main.username, main.lastactivity 
			FROM ".tname('session')." main
			LEFT JOIN ".tname('spacefield')." f ON f.uid=main.uid
			$wheresql
			LIMIT $start,$perpage");
		while ($value = $_SGLOBAL['db']->fetch_array($query)) {
			if($_GET['type']=='near') {
				if($value['uid'] = $space['uid']) {
					$count = $count-1;
					continue;
				}
			}
			realname_set($value['uid'], $value['username']);
			$value['p'] = rawurlencode($value['resideprovince']);
			$value['c'] = rawurlencode($value['residecity']);
			$value['isfriend'] = ($value['uid']==$space['uid'] || ($space['friends'] && in_array($value['uid'], $space['friends'])))?1:0;
			$ols[$value['uid']] = $value['lastactivity'];
			$value['note'] = getstr($value['note'], 35, 0, 0, 0, 0, -1);
			$list[] = $value;
		}
	}
	$multi = multi($count, $perpage, $page, $theurl);

} elseif($_GET['view'] == 'visitor' || $_GET['view'] == 'trace') {

	$theurl = "viewspace.php?uid=$space[uid]&do=friend&view=$_GET[view]";
	$actives = array('me'=>' class="active"');
	
	if($_GET['view'] == 'visitor') {//�ÿ�
		$count = $_SGLOBAL['db']->result($_SGLOBAL['db']->query("SELECT COUNT(*) FROM ".tname('visitor')." main WHERE main.uid='$space[uid]'"), 0);
		$query = $_SGLOBAL['db']->query("SELECT f.resideprovince, f.residecity, f.note, f.spacenote, f.sex, main.vuid AS uid, main.vusername AS username, main.dateline
			FROM ".tname('visitor')." main
			LEFT JOIN ".tname('spacefield')." f ON f.uid=main.vuid
			WHERE main.uid='$space[uid]'
			ORDER BY main.dateline DESC
			LIMIT $start,$perpage");
	} else {//�㼣
		$count = $_SGLOBAL['db']->result($_SGLOBAL['db']->query("SELECT COUNT(*) FROM ".tname('visitor')." main WHERE main.vuid='$space[uid]'"), 0);
		$query = $_SGLOBAL['db']->query("SELECT s.username, s.name, s.namestatus, s.groupid, f.resideprovince, f.residecity, f.note, f.spacenote, f.sex, main.uid AS uid, main.dateline
			FROM ".tname('visitor')." main
			LEFT JOIN ".tname('space')." s ON s.uid=main.uid
			LEFT JOIN ".tname('spacefield')." f ON f.uid=main.uid
			WHERE main.vuid='$space[uid]'
			ORDER BY main.dateline DESC
			LIMIT $start,$perpage");
	}
	if($count) {
		while ($value = $_SGLOBAL['db']->fetch_array($query)) {
			realname_set($value['uid'], $value['username'], $value['name'], $value['namestatus']);
			$value['p'] = rawurlencode($value['resideprovince']);
			$value['c'] = rawurlencode($value['residecity']);
			$value['isfriend'] = ($value['uid']==$space['uid'] || ($space['friends'] && in_array($value['uid'], $space['friends'])))?1:0;
			$fuids[] = $value['uid'];
			$value['note'] = getstr($value['note'], 28, 0, 0, 0, 0, -1);
			$list[] = $value;
		}
	}
	$multi = multiviewspace($count, $perpage, $page, $theurl);
	
} elseif($_GET['view'] == 'blacklist') {
		
	$theurl = "viewspace.php?uid=$space[uid]&do=friend&view=$_GET[view]";
	$actives = array('me'=>' class="active"');
	
	$count = $_SGLOBAL['db']->result($_SGLOBAL['db']->query("SELECT COUNT(*) FROM ".tname('blacklist')." main WHERE main.uid='$space[uid]'"), 0);
	if($count) {
		$query = $_SGLOBAL['db']->query("SELECT s.username, s.name, s.namestatus, s.groupid, main.dateline, main.buid AS uid
			FROM ".tname('blacklist')." main
			LEFT JOIN ".tname('space')." s ON s.uid=main.buid
			WHERE main.uid='$space[uid]'
			ORDER BY main.dateline DESC
			LIMIT $start,$perpage");
		while ($value = $_SGLOBAL['db']->fetch_array($query)) {
			$value['isfriend'] = 0;
			realname_set($value['uid'], $value['username'], $value['name'], $value['namestatus']);
			$fuids[] = $value['uid'];
			$list[] = $value;
		}
	}
	$multi = multiviewspace($count, $perpage, $page, $theurl);
	
} else {
	
	//�����ѯ
	$theurl = "viewspace.php?uid=$space[uid]&do=$do";
	$actives = array('me'=>' class="active"');
	
	//���ѷ���
	$wheresql = '';
	if($space['self']) {
		$groups = getfriendgroup();
		$group = !isset($_GET['group'])?'-1':intval($_GET['group']);
		$get_key = empty($_GET['key'])?'':stripsearchkey($_GET['key']);
		if($group > -1) {
			$wheresql = "AND main.gid='$group'";
			$theurl .= "&group=$group";
		} elseif($get_key) {
			if($_SCONFIG['realname']) {
				//����ʵ��
				$uids = array();
				$query = $_SGLOBAL['db']->query("SELECT s.uid FROM ".tname('space')." s, ".tname('friend')." f
					WHERE s.name LIKE '%$get_key%' AND s.uid=f.fuid AND f.uid='$_SGLOBAL[supe_uid]' AND f.status='1'");
				while ($value = $_SGLOBAL['db']->fetch_array($query)) {
					$uids[] = $value['uid'];
				}
				$wheresql = "AND (main.fuid IN (".simplode($uids).") OR main.fusername LIKE '%$get_key%')";
			} else {
				$wheresql = "AND main.fusername LIKE '%$get_key%'";
			}
			$theurl .= "&key=$get_key";
		}
	}
	
	if($space['friendnum']) {
		if($wheresql) {
			$count = $_SGLOBAL['db']->result($_SGLOBAL['db']->query("SELECT COUNT(*) FROM ".tname('friend')." main WHERE main.uid='$space[uid]' AND main.status='1' $wheresql"), 0);
		} else {
			$count = $space['friendnum'];
		}
		if($count) {
			$query = $_SGLOBAL['db']->query("SELECT s.*, f.resideprovince, f.residecity, f.note, f.spacenote, f.sex, main.gid, main.num
				FROM ".tname('friend')." main
				LEFT JOIN ".tname('space')." s ON s.uid=main.fuid
				LEFT JOIN ".tname('spacefield')." f ON f.uid=main.fuid
				WHERE main.uid='$space[uid]' AND main.status='1' $wheresql
				ORDER BY main.num DESC, main.dateline DESC
				LIMIT $start,$perpage");
			while ($value = $_SGLOBAL['db']->fetch_array($query)) {
				realname_set($value['uid'], $value['username'], $value['name'], $value['namestatus']);
				$value['p'] = rawurlencode($value['resideprovince']);
				$value['c'] = rawurlencode($value['residecity']);
				$value['group'] = $groups[$value['gid']];
				$value['isfriend'] = 1;
				$fuids[] = $value['uid'];
				$value['note'] = getstr($value['note'], 28, 0, 0, 0, 0, -1);
				$list[] = $value;
			}
		}
		
		//��ҳ
		$multi = multiviewspace($count, $perpage, $page, $theurl);
		$friends = array();
		//ȡ100�����û���
		$query = $_SGLOBAL['db']->query("SELECT f.fusername, s.name, s.namestatus, s.groupid FROM ".tname('friend')." f
			LEFT JOIN ".tname('space')." s ON s.uid=f.fuid
			WHERE f.uid=$_SGLOBAL[supe_uid] AND f.status='1' ORDER BY f.num DESC, f.dateline DESC LIMIT 0,100");
		while ($value = $_SGLOBAL['db']->fetch_array($query)) {
			$fusername = ($_SCONFIG['realname'] && $value['name'] && $value['namestatus'])?$value['name']:$value['fusername'];
			$friends[] = addslashes($fusername);
		}
		$friendstr = implode(',', $friends);
	}
	
	if($space['self']) {
		$groupselect = array($group => 'class="active"');
	}
}

//����״̬
if($fuids) {
	$query = $_SGLOBAL['db']->query("SELECT uid, lastactivity FROM ".tname('session')." WHERE uid IN (".simplode($fuids).")");
	while ($value = $_SGLOBAL['db']->fetch_array($query)) {
		$ols[$value['uid']] = $value['lastactivity'];
	}
}

realname_get();

if(empty($_GET['view'])) $_GET['view'] = 'me';
$a_actives = array($_GET['view'].$_GET['type'] => ' class="current"');

include_once template("newsp_viewspace_friend");

?>