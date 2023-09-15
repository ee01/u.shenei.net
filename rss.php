<?php
/*
	[UCenter Home] (C) 2007-2008 Comsenz Inc.
	$Id: rss.php 12766 2009-07-20 04:26:21Z liguode $
*/

include_once('./common.php');

@header("Content-type: application/xml");

$pagenum = 10;
$tag = '<?';
$rssdateformat = 'D, d M Y H:i:s T';
$rsscharset = empty($_GET['charset'])?$_SC[charset]:$_GET['charset'];	//Add By 01

$siteurl = getsiteurl();
$uid = empty($_GET['uid'])?0:intval($_GET['uid']);
$list = array();

if(!empty($uid)) {
	$space = getspace($uid);
}
if(empty($space)) {
	//站点更新rss
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

//10篇最新日志
$uidsql = empty($space['uid'])?'':" AND b.uid='$space[uid]'";
$query = $_SGLOBAL['db']->query("SELECT bf.message, b.*
	FROM ".tname('blog')." b
	LEFT JOIN ".tname('blogfield')." bf ON bf.blogid=b.blogid
	WHERE b.friend='0' $uidsql
	ORDER BY dateline DESC
	LIMIT 0,$pagenum");
while ($value = $_SGLOBAL['db']->fetch_array($query)) {
	if(!empty($space['privacy']['blog'])) {
		$value['message'] = '';
	} else {
		$value['message'] = getstr($value['message'], 300, 0, 0, 0, 0, -1);
		if($value['pic']) {
			$value['pic'] = pic_cover_get($value['pic'], $value['picflag']);
			$value['message'] .= "<br /><img src=\"$value[pic]\">";
		}
		$value['message'] = fiximgurl($value['message']);	//Add By 01
	}
	realname_set($value['uid'], $value['username']);
	
	$value['dateline'] = sgmdate($rssdateformat, $value['dateline']);
	$list[] = $value;
}

realname_get();

include template('space_rss');

//图片相对地址转换	Add By 01
Function fiximgurl ($str) {
	$match='/<img[^>]+src\=\"attachment(.*)\"[^>]*>/isU';
	
	if (preg_match_all($match,$str,$arr)) {
		$arr2 = explode('"attachment'.$arr[1][0].'"', $str);
		if (count($arr[1])==count($arr2)-1) {
			$fiximgurl = $arr2[0];
			for ($i=0;$i<count($arr[1]);$i++) {
				$fiximgurl .= '"http://u.shenei.net/attachment'.$arr[1][$i].'"'.$arr2[$i+1];
			}
		}else{
			$fiximgurl = $str;
		}
	}else{
		$fiximgurl = $str;
	}
	
	return $fiximgurl;
}

?>