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
	$space['space_url'] = $siteurl."viewspace.php?uid=$space[uid]";
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
/*
		$value['message'] = getstr($value['message'], 300, 0, 0, 0, 0, -1);
		if($value['pic']) {
			$value['pic'] = pic_cover_get($value['pic'], $value['picflag']);
			$value['message'] .= "<br /><img src=\"$value[pic]\">";
		}
*/
		$value['message'] = fiximgurl($value['message']);
		$value['message'] = fixmediaurl($value['message']);
		$value['message'] .= addsourse($value['uid'],$value['blogid']);
	}
	realname_set($value['uid'], $value['username']);
	
	$value['dateline'] = sgmdate($rssdateformat, $value['dateline']);
	$list[] = $value;
}

realname_get();

include template('space_rss');

Function addsourse ($uid, $blogid) {
	global $_SCONFIG,$space,$siteurl;
	$blogurl_space  = $siteurl . 'viewspace.php?uid=' . $uid . '&do=blog&id=' . $blogid;
	$blogurl_viewspace = $siteurl . 'viewspace.php?uid=' . $uid . '&do=blog&id=' . $blogid;
	$blogurl_html = $siteurl . 'space-' . $uid . '-do-blog-id-' . $blogid . '.html';
	
	//插入源地址
	$addsourse  = '<p style="margin:50px 0 0 30px;"><strong><font color="gray">此日志由<a href="';
	$addsourse .= $space['space_url'];
	$addsourse .= '"><font color="orangered">';
	$addsourse .= $space['name'];
	$addsourse .= '</font></a>在【<a href="';
	$addsourse .= $siteurl;
	$addsourse .= '"><font color="limegreen">';
	$addsourse .= $_SCONFIG[sitename];
	$addsourse .= '</font></a>】发布~<br></font></strong>';
	$addsourse .= '　<strong><font color="fuchsia">需评论请到日志源地址操作！</font></strong><strong><font color="gray">否则</font></strong><strong><font color="orangered">';
	$addsourse .= $space['name'];
	$addsourse .= '</font></strong><strong><font color="gray">也许</font></strong><strong><font color="gray">无法及时回复。。。<br></font></strong>';
	$addsourse .= '　　<strong><font color="silver">原文地址：<a href="';
	$addsourse .= $blogurl_viewspace;
	$addsourse .= '">';
	$addsourse .= $blogurl_html;
	$addsourse .= '</a>（记得要回看评论回复噢~</font></strong><br></p>';
	
	return $addsourse;
}

Function fiximgurl ($str) {
	$match='/<img[^>]+src\=\"attachment(.*)\"[^>]*>/isU';
	
	if (preg_match_all($match,$str,$arr)) {
		$fiximgurl = $str;
		for ($i=0;$i<count($arr[1]);$i++) {
			$arr2 = explode('"attachment'.$arr[1][$i].'"', $fiximgurl);
			$fiximgurl = $arr2[0].'"http://u.shenei.net/attachment'.$arr[1][$i].'"'.$arr2[1];
		}
	}else{
		$fiximgurl = $str;
	}
	
	return $fiximgurl;
}

Function fixmediaurl ($str) {
	$fixmediaurl = $str;
	$fixmediaurl = preg_replace( "/\\[music\\=?(auto)*\\](.+?)\\[\\/music\\]/ie","blog_music(\"\\2\", \"\\1\")",$fixmediaurl);
	$fixmediaurl = preg_replace("/\[flash\=?(media|real)*\](.+?)\[\/flash\]/ie", "blog_flash('\\2', '\\1')", $fixmediaurl);
	return $fixmediaurl;
}
function blog_music( $music_url, $state )
{
    $optauto = "";
	$geshi = substr($music_url,-3,3);
	if ( $geshi == 'mp3') {
		if ( $state == "auto" )
		{
			$optauto = "&autostart=yes";
		}
   		$html = '<script language="JavaScript" src="http://u.shenei.net/image/audio-player.js"></script><object type="application/x-shockwave-flash" data="http://u.shenei.net/image/player.swf" height="24" width="290"><param name="movie" value="http://u.shenei.net/image/player.swf" /><param name="FlashVars" value="bg=0xCDDFF3&leftbg=0x357DCE&lefticon=0xF2F2F2&rightbg=0xF06A51&rightbghover=0xAF2910&righticon=0xF2F2F2&righticonhover=0xFFFFFF&text=0x357DCE&slider=0x357DCE&track=0xFFFFFF&border=0xFFFFFF&loader=0xAF2910&soundFile='.$music_url.$optauto.'" /><param name="quality" value="high" /><param name="menu" value="false" /><param name="wmode" value="transparent" /></object>';
	} else {
		if ( $state == "auto" )
		{
			$optauto = "1";
		} else {
			$optauto = "0";
		}
		$html ='<object height="64" width="290" data="'.$music_url.'" type="audio/x-ms-wma"><param value="'.$music_url.'" name="src"/><param value="'.$optauto.'" name="autostart"/><param value="true" name="controller"/></object>';
	}
    return $html;
}
function blog_flash($swf_url, $type='') {
	$width = '520';
	$height = '390';
	if ($type == 'media') {
		$html = '<object classid="clsid:6bf52a52-394a-11d3-b153-00c04f79faa6" width="'.$width.'" height="'.$height.'">
			<param name="autostart" value="0">
			<param name="url" value="'.$swf_url.'">
			<embed autostart="false" src="'.$swf_url.'" type="video/x-ms-wmv" width="'.$width.'" height="'.$height.'" controls="imagewindow" console="cons"></embed>
			</object>';
	} elseif ($type == 'real') {
		$html = '<object classid="clsid:cfcdaa03-8be4-11cf-b84b-0020afbbccfa" width="'.$width.'" height="'.$height.'">
			<param name="autostart" value="0">
			<param name="src" value="'.$swf_url.'">
			<param name="controls" value="Imagewindow,controlpanel">
			<param name="console" value="cons">
			<embed autostart="false" src="'.$swf_url.'" type="audio/x-pn-realaudio-plugin" width="'.$width.'" height="'.$height.'" controls="controlpanel" console="cons"></embed>
			</object>';
	} else {
		$html = '<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" width="'.$width.'" height="'.$height.'">
			<param name="movie" value="'.$swf_url.'">
			<param name="allowscriptaccess" value="always">
			<embed src="'.$swf_url.'" type="application/x-shockwave-flash" width="'.$width.'" height="'.$height.'" allowfullscreen="true" allowscriptaccess="always"></embed>
			</object>';
	}
	return $html;
}

?>