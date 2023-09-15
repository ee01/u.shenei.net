<?php
if(!defined('IN_UCHOME')) {
exit('Access Denied');
}
include_once(S_ROOT.'./source/function_music.php');
$op = empty($_GET['op'])?'':$_GET['op'];
if($op == 'albummusic') {
$id = empty($_GET['id'])?0:intval($_GET['id']);
$start = empty($_GET['start'])?0:intval($_GET['start']);
if(empty($_SGLOBAL['supe_uid'])) {
showmessage('to_login','do.php?ac='.$_SCONFIG['login_action']);
}
$perpage = 10;
ckstart($start,$perpage);
$count = 0;
$piclist = array();
$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('pic')." WHERE albumid='$id' AND uid='$_SGLOBAL[supe_uid]' ORDER BY dateline DESC LIMIT $start,$perpage");
while ($value = $_SGLOBAL['db']->fetch_array($query)) {
$value['bigpic'] = pic_get($value['filepath'],$value['thumb'],$value['remote'],0);
$value['pic'] = pic_get($value['filepath'],$value['thumb'],$value['remote']);
$piclist[] = $value;
$count++;
}
$multi = smulti($start,$perpage,$count,"do.php?ac=ajaxm&op=albummusic&id=$id",$_GET['ajaxdiv']);
}elseif($op == 'getmyalbumlist') {
$mymusicalbumlist = array();
if(!empty($_GET['mmodel'])){
$mymusicalbumlist = getmusicalbum($_GET['uid'],-1,1,$_GET['mmodel']);
}else{
if($_GET['uid']!=-1){
$mymusicalbumlist = getmusicalbum($_GET['uid'],-1,1);
}else{
$mymusicalbumlist = getmusicalbum(-1,-1,1,-1,2);
}
}
}elseif($op == 'greatmyalbum') {
$albums = array();
$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('album')." WHERE uid='".$_SGLOBAL['supe_uid']."' ORDER BY albumid DESC");
while ($value = $_SGLOBAL['db']->fetch_array($query)) {
$albums[$value['albumid']] = $value;
}
if(!empty($_GET['albumid'])){
$editalbum = getalbuminfo($_GET['albumid']);
}
}elseif($op == 'sharetomyfriend') {
$shareolfriendlist = array();
$query = $_SGLOBAL['db']->query("SELECT s.uid as uid, s.username as username, s.name as name, s.namestatus as namestatus  FROM ".tname('friend')." as main left JOIN ".tname('space')." as s ON ( main.fuid = s.uid ) WHERE main.uid='$_SGLOBAL[supe_uid]' AND main.status='1' ORDER BY main.num DESC, main.dateline DESC LIMIT 0,100");
while ($value = $_SGLOBAL['db']->fetch_array($query)) {
realname_set($value['uid'],$value['username'],$value['name'],$value['namestatus']);
$shareolfriendlist[] = $value;
}
}elseif($op == 'getmymusiclist') {
$start = empty($_GET['start'])?0:intval($_GET['start']);
$perpage = 10;
ckstart($start,$perpage);
$count = 0;
$mymusiclist = array();
if($_GET['lastplay']=="true"){
$mymusiclist = getmusiclist($_GET['uid'],-1,-1,-1,-1,-1,-1,$start,$perpage,"",-1,-1,1);
}else{
$mymusiclist = getmusiclist($_GET['uid'],-1,-1,-1,-1,-1,-1,$start,$perpage);
}
$count = $mymusiclist[0]['returncount'];
$multi = smulti($start,$perpage,$count,"do.php?ac=ajaxm&op=getmymusiclist&uid=".$_GET['uid'],'mymusiclist_c');
}elseif($op == 'mycollectmusiclist') {
$start = empty($_GET['start'])?0:intval($_GET['start']);
$perpage = 15;
ckstart($start,$perpage);
$count = 0;
$collectmusiclist = array();
$collectmusiclist = getmusiclist(-1,-1,-1,-1,-1,-1,$_GET['uid'],$start,$perpage);
$count = $collectmusiclist[0]['returncount'];
$multi = smulti($start,$perpage,$count,"do.php?ac=ajaxm&op=mycollectmusiclist&uid=".$_GET['uid'],'mycollectmusiclist_c');
}elseif($op == 'getmusicpingfenuser') {
$iampingfen = "";
$mymusicpingfen = array();
$mymusicpingfen = getmusicguestnotes(0,$_GET['songid']);
$iamdo = 0;
}elseif($op == 'getmusicpinglun') {
$start = empty($_GET['start'])?0:intval($_GET['start']);
$perpage = 3;
ckstart($start,$perpage);
$count = 0;
if(empty($_SGLOBAL['member'])) {getmember();}
$mymusicpinglun = array();
$mymusicpinglun = getmusicguestnotes(1,$_GET['songid'],-1,$start,$perpage);
$count = $mymusicpinglun[0]['returncount'];
$multi = smulti($start,$perpage,$count,"do.php?ac=ajaxm&op=getmusicpinglun&songid=".$_GET['songid']."&songuser=".$_GET['songuser'],'musicpinglunlist');
}elseif($op == 'selectmydiskfile') {
$mydiskfilelist = array();
$mydiskfilelist = getmydiskfilelist($_SGLOBAL['supe_uid']);
}elseif($op == 'getcollectionuser') {
$collectionuserlist = array();
$datatemparr = array();
$datatemparr = getsinglemusic($_GET['songid'],0);
$collectionuserlist = getlistofuserspace(implode(",",array_filter(explode(",",$datatemparr['collectionuser']))));
}elseif($op == 'tomymusicbox') {
$songexist = getmusicguestnotes(2,$_SGLOBAL[supe_uid],$_GET[songid]);
}elseif($op == 'tocollection') {
$csongexist = getmusicguestnotes(3,$_GET[songid],$_SGLOBAL[supe_uid]);
}elseif($op == 'getmyboxlist') {
if(!empty($_GET['bgsongid']) &&empty($_GET['type'])){
setmusicbg($_GET['bgsongid']);
}
if(!empty($_GET['bgsongid']) &&$_GET['type']=="not"){
setmusicbg($_GET['bgsongid'],1);
}
if(!empty($_GET['bgsongid']) &&$_GET['type']=="del"){
setmusicbg($_GET['bgsongid'],2);
}
if(!empty($_GET['songorder'])){
ordermusic(explode("|",$_GET['songorder']));
}
$mymusicboxlist = array();
$mymusicboxlist = getmybox('myboxlist');
$mymusicboxlisttc = empty($mymusicboxlist) ?0 : count($mymusicboxlist);
}
include template('do_ajaxm');
?>