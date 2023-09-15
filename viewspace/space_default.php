<?php
/*
	[UCenter Home] (C) 2007-2008 Comsenz Inc.
	$Id: space_index.php 10118 2008-11-25 07:21:33Z liguode $
*/

if(!defined('IN_UCHOME')) {
	exit('Access Denied');
}




$uid = $space[uid];

if($_REQUEST['faststyle']!='')
{
$u = $_SGLOBAL['supe_uid'];
$faststyle=$_POST['faststyle'];
$fastbg=$_POST['fastbg'];
$fastbgheight=$_POST['fastbgheight'];
$Cfg=new Viewcfg();

if($faststyle=="blue")
{
$cfgstyle=$Cfg->cfgblue;
}
if($faststyle=="green")
{
$cfgstyle=$Cfg->cfggreen;
}
if($faststyle=="pink")
{
$cfgstyle=$Cfg->cfgpink;
}
if($faststyle=="white")
{
$cfgstyle=$Cfg->cfgwhite;
}
if($faststyle=="yellow")
{
$cfgstyle=$Cfg->cfgyellow;
}
if($faststyle=="black")
{
$cfgstyle=$Cfg->cfgblack;
}

if($fastbg!="")
{
$fastbgheight='height:'.$fastbgheight.'px';
$fastbg='background-image:url('.$fastbg.')';
$blockk=unserialize($cfgstyle[block]);
$blockk[banner]['']['height']="$fastbgheight";
$blockk[banner]['']['background-image']="$fastbg";
}
else
{
$blockk=unserialize($cfgstyle[block]);
}



$style=array(
'effectall'=>unserialize($cfgstyle[effectall]),
'block'=>$blockk,

);



$css=outframeeffectall($style,1);
$css.=outframeblock($style,1);
if(@$fp = fopen('viewspace/css/'.get_userURL1($u), 'w')) {
fwrite($fp, $css);
fclose($fp);
} else {
	exit('Can not write to cache files, please check directory ./forumdata/ and ./'.get_userURL1($u).'/ .');
}



$time=GmtToUnix(date("Y-m-d H:i:s"));
global $_SGLOBAL;
$_SGLOBAL['db']->query( "update ".tname('app_viewspace_suser')." set status=0 where uid=$u and status=1" );
$arrsuser = array(
		"uid" => $u,
		"frame_set" =>$cfgstyle[frame_set],
		"allframe" =>$cfgstyle[allframe],
		"effectall" =>$cfgstyle[effectall],
		"blockname" =>$cfgstyle[blockname],
		"block" =>serialize($blockk),
		"cursor" =>$cfgstyle[cursor],
		"date" =>$time,
		"status" =>1,
);
inserttable( "app_viewspace_suser", $arrsuser );
echo "<script>location.href='viewspace.php'</script>";
}







if($_REQUEST['diystyle']!='')
{
$tyle =stripslashes($_POST['diystyle']);
$frame_set=frame_set1(unserialize($tyle),1);
$allframe=serialize(allframe1(unserialize($tyle),1));
$effectall=serialize(effectall11(unserialize($tyle),1));
$blockname=serialize(blockname1(unserialize($tyle),1));
$music=serialize(music1(unserialize($tyle),1));
$block=serialize(block11(unserialize($tyle),1));
$cursor=serialize(cursor1(unserialize($tyle),1));
$u = $_SGLOBAL['supe_uid'];
$time=GmtToUnix(date("Y-m-d H:i:s"));




$coun=susercount($u);
if($coun=='0')
{
$arrsuser = array(
		"uid" => $u,
		"frame_set" =>$frame_set,
		"allframe" =>$allframe,
		"effectall" =>$effectall,
		"blockname" =>$blockname,
		"block" =>$block,
		"cursor" =>$cursor,
		"date" =>$time,
		"status" =>1,
);
inserttable( "app_viewspace_suser", $arrsuser );
global $_SGLOBAL;
if($music=='b:0;')
{
$music='a:0:{}';
}
$_SGLOBAL['db']->query( "update ".tname('space')." set smusic='$music' where uid=$u" );
}
else
{
global $_SGLOBAL;
$_SGLOBAL['db']->query( "update ".tname('app_viewspace_suser')." set frame_set='$frame_set',allframe='$allframe',effectall='$effectall',blockname='$blockname',block='$block',`cursor`='$cursor',date=$time where uid=$u and status=1" );
if($music=='b:0;')
{
$music='a:0:{}';
}
$_SGLOBAL['db']->query( "update ".tname('space')." set smusic='$music' where uid=$u" );
}

$css=outframeeffectall(unserialize($tyle),1);
$css.=outframeblock(unserialize($tyle),1);
$css.=outframecursor(unserialize($tyle),1);
if(@$fp = fopen('viewspace/css/'.get_userURL1($u), 'w')) {
fwrite($fp, $css);
fclose($fp);
} else {
	exit('Can not write to cache files, please check directory ./forumdata/ and ./'.get_userURL1($u).'/ .');
}
echo "<script>location.href='viewspace.php'</script>";
}

if($space['self'])
{
$styname=stylecount($_SGLOBAL['supe_uid']);
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
$usercss = new Usercssinfo($uid,$_SGLOBAL['supe_uid']);
$allframe=$usercss -> allframe;
$tempstyle=$usercss -> tempstyle;
$defaultset=$usercss -> defaultset;
$wrap=$usercss -> wrap;
$scss='<link type="text/css" rel="stylesheet" href="viewspace/css/'.get_userURL2($uid).'?'.$usercss->date.'" />';
$_GET['view'] = 'me';



//最近访客记录
if(!$space['self'] && $_SGLOBAL['supe_uid']) {
global $_SGLOBAL;
	$query = $_SGLOBAL['db']->query("SELECT dateline FROM ".tname('visitor')." WHERE uid='$space[uid]' AND vuid='$_SGLOBAL[supe_uid]'");
	$visitor = $_SGLOBAL['db']->fetch_array($query);
	if(empty($visitor['dateline'])) {
		$setarr = array(
			'uid' => $space['uid'],
			'vuid' => $_SGLOBAL['supe_uid'],
			'vusername' => $_SGLOBAL['supe_username'],
			'dateline' => $_SGLOBAL['timestamp']
		);
		inserttable('visitor', $setarr, 0, true);
		show_credit();//竞价排名
	} else {
		if($_SGLOBAL['timestamp'] - $visitor['dateline'] >= 300) {
			updatetable('visitor', array('dateline'=>$_SGLOBAL['timestamp']), array('uid'=>$space['uid'], 'vuid'=>$_SGLOBAL['supe_uid']));
		}
		if($_SGLOBAL['timestamp'] - $visitor['dateline'] >= 3600) {
			show_credit();//1小时后竞价排名
		}
	}
}

//访问统计
if(!$space['self']) {
	$_SGLOBAL['db']->query("UPDATE ".tname('space')." SET viewnum=viewnum+1 WHERE uid='$space[uid]'");
}


include template('viewspace_default');


//竞价排名
function show_credit() {
	global $_SGLOBAL, $space;
	$showcredit = getcount('show', array('uid'=>$space['uid']), 'credit');
	if($showcredit>0) {
		$_SGLOBAL['db']->query("UPDATE ".tname('show')." SET credit=credit-1 WHERE uid='$space[uid]'");
	}
}


?>
