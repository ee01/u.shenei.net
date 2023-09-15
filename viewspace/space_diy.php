<?php
/*
	[UCenter Home] (C) 2007-2008 Comsenz Inc.
	$Id: space_index.php 10118 2008-11-25 07:21:33Z liguode $
*/

if(!defined('IN_UCHOME')) {
	exit('Access Denied');
}
$uid = empty($_GET['uid'])?$_SGLOBAL['supe_uid']:intval($_GET['uid']);
if($space['self'])
{
$styname=stylecount($_SGLOBAL['supe_uid']);
}
$usercss = new Usercssinfodiy($uid,$_SGLOBAL['supe_uid']);
$allframe=$usercss -> allframe;
$tempstyle=$usercss -> tempstyle;
$defaultset=$usercss -> defaultset;
$wrap=$usercss -> wrap;
$di=$usercss -> di;
$scss='<link type="text/css" rel="stylesheet" href="viewspace/css/'.get_userURL2($uid).'?'.$usercss->date.'" />';
$_GET['view'] = 'me';



//获取相册
$albums = getalbums1($_SGLOBAL['supe_uid']);

//获取音乐盒
include_once(S_ROOT.'./source/function_music.php');
$myboxlist = array();
$myboxlist = getmybox('myboxlist');
$myboxlisttc = empty($myboxlist) ?0 : count($myboxlist);
if($myboxlist[0][boxorder] == 0 && $myboxlist[($myboxlisttc-1)][boxorder] == 0){
	$isorder = 0;
}else{
	$isorder = 1;
}
foreach ($myboxlist as $key =>$value) {
	if($key<10){
		if($isorder){
			$bgmusics['music_'.($value['boxorder']+1)][i] = $value['boxorder']+1;
			$bgmusics['music_'.($value['boxorder']+1)][id] = $value['songid'];
			$bgmusics['music_'.($value['boxorder']+1)][name] = $value['songname'];
			$bgmusics['music_'.($value['boxorder']+1)][url] = $value['songurl'];
			$bgmusics['music_'.($value['boxorder']+1)][albumid] = $value['albumid'];
			$bgmusics['music_'.($value['boxorder']+1)][albumname] = $value['albumname'];
			$bgmusics['music_'.($value['boxorder']+1)][uid] = $value['userid'];
			$bgmusics['music_'.($value['boxorder']+1)][username] = $value['username'];
			$bgmusics['music_'.($value['boxorder']+1)][truename] = $value['name'];
			$bgmusics['music_'.($value['boxorder']+1)][say] = $value['usersay'];
		}else{
			$bgmusics['music_'.($key+1)][i] = $key+1;
			$bgmusics['music_'.($key+1)][id] = $value['songid'];
			$bgmusics['music_'.($key+1)][name] = $value['songname'];
			$bgmusics['music_'.($key+1)][url] = $value['songurl'];
			$bgmusics['music_'.($key+1)][albumid] = $value['albumid'];
			$bgmusics['music_'.($key+1)][albumname] = $value['albumname'];
			$bgmusics['music_'.($key+1)][uid] = $value['userid'];
			$bgmusics['music_'.($key+1)][username] = $value['username'];
			$bgmusics['music_'.($key+1)][truename] = $value['name'];
			$bgmusics['music_'.($key+1)][say] = $value['usersay'];
		}
	}
}

include template('newsp_viewspace_diy');

//获取相册
function getalbums1($uid) {
	global $_SGLOBAL;

	$albums = array();
	$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('album')." WHERE uid='$uid' ORDER BY albumid DESC");
	while ($value = $_SGLOBAL['db']->fetch_array($query)) {
		$albums[$value['albumid']] = $value;
	}
	return $albums;
}

?>
