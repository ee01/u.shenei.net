<?php
/*
	[UCenter Home] (C) 2007-2008 Comsenz Inc.
	$Id: rss.php 12766 2009-07-20 04:26:21Z liguode $
*/

include_once('./common.php');

@header("Content-type: application/xml");

$pagenum = 10;
$tag = '<?';
$tag2 = '?>';
$rssdateformat = 'D, d M Y H:i:s T';

$siteurl = getsiteurl();
$uid = empty($_GET['uid'])?0:intval($_GET['uid']);
$user = $_GET['user'];
$pass = $_GET['pass'];
$list = array();

//��֤�û�
$query = $_SGLOBAL['db']->query("SELECT uid,username,password,salt FROM ucenter.uc_members WHERE username='$user'");
$value = $_SGLOBAL['db']->fetch_array($query);
$uid = $value[uid];

if(!empty($uid)) {
	$space = getspace($uid);
}
if(empty($space)) {
	//վ�����rss
	$space['username'] = $_SCONFIG['sitename'];
	$space['name'] = $_SCONFIG['sitename'];
	$space['email'] = $_SCONFIG['adminemail'];
	$space['space_url'] = $siteurl;
	$space['lastupdate'] = sgmdate($rssdateformat);
	$space['privacy']['blog'] = 0;	//Add By 01
} else {
	$space['username'] = $space['username'].'@'.$_SCONFIG['sitename'];
	$space['space_url'] = $siteurl."space.php?uid=$space[uid]";
	$space['lastupdate'] = sgmdate($rssdateformat, $space['lastupdate']);
}

if(empty($user) || empty($pass)) {
	echo $tag."xml version=\"1.0\" encoding=\"gbk\"".$tag2."\n<rss version=\"2.0\">\n<channel>\n<error>\n�������û��������룡\n</error>\n</channel>\n</rss>";
	exit;
}else{
	if($value[password] != md5(md5($pass).$value[salt])) {
		echo $tag."xml version=\"1.0\" encoding=\"gbk\"".$tag2."<rss version=\"2.0\">\n<channel>\n<error>\n�û������������\n</error>\n</channel>\n</rss>";
		exit;
	}
}

//10�����¶���Ϣ
$query = $_SGLOBAL['db']->query("SELECT pmid,msgfrom,msgfromid,msgtoid,new,dateline,message FROM ucenter.uc_pms WHERE msgtoid=$uid and delstatus=0 and related=1 ORDER BY dateline DESC LIMIT 0,$pagenum");
while ($value = $_SGLOBAL['db']->fetch_array($query)) {
	if(strlen($value['message']) >= 100) {
		$value['message'] = getstr($value['message'], 100, 0, 0, 0, 0, -1) . "����(δ��)";
	}else{
		$value['message'] = getstr($value['message'], 100, 0, 0, 0, 0, -1);
	}
	realname_set($value['msgfromid'], $value['msgfrom']);
	$value['dateline'] = sgmdate($rssdateformat, $value['dateline']);
	$list[] = $value;
}

$_SN[0] = "ϵͳ��Ϣ";
realname_get();

//	echo "<pre>";
//		print_r($list);
//	echo "</pre>";
//exit;
include template('space_rss_pm');

?>