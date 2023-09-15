<?php

if(!defined('IN_UCHOME')) {
exit('Access Denied');
}
include_once(S_ROOT.'./source/function_music.php');
$mview = $_GET['mview'];
$viewtype = $_GET['viewtype'];

//Add By 01↓	同步背景音乐
if($mview=='backgroundmusic'){
	$myboxlist = array();
	$myboxlist = getmybox('myboxlist');
	$myboxlisttc = empty($myboxlist) ?0 : count($myboxlist);
	if($myboxlisttc == 0){
		showmessage("<b>音乐盒里还没有歌曲呢~快去添加吧！！</b><br><br>音乐盒加音乐方法：打开歌曲播放页，在播放器下面点击“添加到音乐盒”即可！");
	}
/*
	//方法一
	if($myboxlist[0][boxorder] == 0 && $myboxlist[($myboxlisttc-1)][boxorder] == 0){
		$isorder = 0;
	}else{
		$isorder = 1;
	}
	$orderfirst = -1;
	foreach ($myboxlist as $key =>$value) {
		if($value['boxorder']==0) $orderfirst++;
	}
	$orderelse = $orderfirst;
	if($orderfirst > 0) {
		for ($i=$myboxlisttc-1; $i>=0; $i--) {
			if($myboxlist[$i][boxorder]!=0) {
				$myboxlist[$i][boxorder]+=$orderelse;
			}else{
				$myboxlist[$i][boxorder]+=$orderfirst;
				$orderfirst--;
			}
		}
	}
//	echo "<pre>";
//		print_r($myboxlist);
//	echo "</pre>";
	foreach ($myboxlist as $key =>$value) {
		if($key<10){
			if($isorder){
				$bgmusics['music_'.($value['boxorder']+1)] = $value['songurl'];
			}else{
				$bgmusics['music_'.($key+1)] = $value['songurl'];
			}
		}
	}
*/
	//方法二
	foreach ($myboxlist as $key =>$value) {
		if($key<10) $bgmusics['music_'.($key+1)] = $value['songurl'];
	}
	$bgmusic = serialize($bgmusics);
	$_SGLOBAL['db']->query( "update ".tname('space')." set smusic='$bgmusic' where uid=".$_SGLOBAL['supe_uid'] );
	showmessage("成功同步音乐盒列表到博客背景音乐！","space.php?do=musicbox&mview=mybox&thismenu=mymusicbox");
/*遗弃
	if($myboxlist[0][boxorder] == 0 && $myboxlist[$myboxlisttc][boxorder] == 0){
		foreach ($myboxlist as $key =>$value) {
			if($key<10){
				$bgmusics['music_'.($key+1)] = $value['songurl'];
			}
		}
	}else{
		foreach ($myboxlist as $key =>$value) {
			if($value['boxorder']<10){
				$bgmusics['music_'.($value['boxorder']+1)] = $value['songurl'];
			}
		}
	}
*/
}
//Add By 01↑	同步背景音乐

if(!empty($mview)){
$actives[$mview] = " class=active";
}else {
$actives['mybox'] = " class=active";
}
if(!empty($viewtype)){
$allactives[$viewtype] = 'style="background:#eeeeee; color:#000"';
$allactivesimg[$viewtype] = ' ';
$allactiveslink[$viewtype] = ' style="color:#666666;"';
}else {
$allactives['all'] = 'style="background:#eeeeee; color:#000"';
$allactivesimg['all'] = ' ';
$allactiveslink['all'] = ' style="color:#666666;"';
}
if(!empty($_GET['mop']) &&$_GET['mop'] == "deletemusic"&&!empty($_GET['mid'])){
if($_POST['deletemusicsubmit']){
if(intval($_GET['isupload'])==0){
if(!empty($_GET['songuserid'])){
integralop($_SC['music_i_upload_del'],$_GET['songuserid']);
}else{
integralop($_SC['music_i_upload_del'],$space['uid']);
}
}
if(intval($_GET['isupload'])==1){
if(!empty($_GET['songuserid'])){
integralop($_SC['music_i_addlink_del'],$_GET['songuserid']);
}else{
integralop($_SC['music_i_addlink_del'],$space['uid']);
}
}
musichandle(0,-1,$_GET['mid'],$_GET['isupload'],$space['attachsize']);
}
}
if(!empty($_GET['mop']) &&$_GET['mop'] == "editsingerphotoc"&&!empty($_GET['singerid'])){
if($_POST['editsingerp_commendsubmit']){
$img = new UPImages("images/singernophoto.jpg",125,125);
$newphoto = $img->upLoad("upsingerphoto");
editsingerphotofun($_POST['editphotovalue'],$newphoto);
}
}
if(!empty($_GET['mop']) &&$_GET['mop'] == "admintuijian"&&!empty($_GET['mid'])){
if($_SGLOBAL['member']['groupid']==1){
setcommendmusic($_GET['mid']);
}
}
if(!empty($_GET['mop']) &&$_GET['mop'] == "deletemusicpinglun"&&!empty($_GET['plid'])){
if($_POST['deletemusicpinglunsubmit']){
integralop($_SC['music_i_pinglun_del'],$_GET['puserid']);
musichandle(8,-1,$_GET['plid']);
}
}
if(!empty($_GET['mop']) &&$_GET['mop'] == "tonotc"&&!empty($_GET['mid'])){
if($_POST['tonotcsubmit']){
$datatemparr = array();
$datatemparr = getsinglemusic($_GET['mid'],0);
$setarrcol = array(
'collectionuser'=>str_replace($_SGLOBAL['supe_uid'].",","",$datatemparr['collectionuser']),
);
musichandle(7,$setarrcol,$_GET['mid']);
}
}
if(!empty($_GET['mop']) &&$_GET['mop'] == "deletemymusicalbum"&&!empty($_GET['albumid'])){
if($_POST['deletemusicalbumsubmit']){
integralop($_SC['music_i_addzj_del'],$space['uid']);
musichandle(1,-1,$_GET['albumid'],$_GET['spaceuid']);
}
}
if(!empty($_GET['mop']) &&$_GET['mop'] == "tosharetomyfriend"&&!empty($_GET['songid'])){
if($_POST['sharetomyfriendsubmit']){
integralop($_SC['music_i_share'],$_SGLOBAL['supe_uid']);
musichandle(2,$_POST['myfriends'],$_GET['songid']);
}
}
if(!empty($_GET['mop']) &&$_GET['mop'] == "tomymusicbox"&&!empty($_GET['songid'])){
if($_POST['tomymusicboxsubmit']){
$setarrib = array(
'boxuserid'=>$_SGLOBAL['supe_uid'],
'boxsongid'=>$_GET['songid'],
);
musichandle(3,$setarrib);
}
}
if(!empty($_GET['mop']) &&$_GET['mop'] == "tocollection"&&!empty($_GET['songid'])){
if($_POST['tocollectionsubmit']){
$datatemparr = array();
$datatemparr = getsinglemusic($_GET['songid'],0);
$setarrcol = array(
'collectionuser'=>substr($datatemparr['collectionuser'],0,-1).",".$_SGLOBAL['supe_uid'].",",
);
$setarrcol1 = array(
'userid'=>$datatemparr['userid'],
'songid'=>$datatemparr['songid'],
'songname'=>$datatemparr['songname'],
);
musichandle(6,$setarrcol,$_GET['songid'],-1,$setarrcol1);
}
}
if(!empty($_GET['mop']) &&$_GET['mop'] == "musicpingfen"&&!empty($_GET['songid'])){
if($_POST['pingfensubmit']){
$setarrpf = array(
'songid'=>$_GET['songid'],
'puserid'=>$_SGLOBAL['supe_uid'],
'mark'=>$_POST['pingfen'],
'dateline'=>$_SGLOBAL['timestamp'],
);
$setarrlist = array(
'songuserid'=>$_GET['songuserid'],
'songid'=>$_GET['songid'],
'pingfensongname'=>$_POST['pingfensongname'],
'pingfen'=>$_POST['pingfen'],
);
integralop($_SC['music_i_pingfen'],$_SGLOBAL['supe_uid']);
musichandle(4,$setarrpf,-1,-1,$setarrlist);
}
}
if(!empty($_GET['mop']) &&$_GET['mop'] == "musicpinglun"&&!empty($_GET['songid'])){
if($_POST['pinglunsubmit']){
$setarrpl = array(
'songid'=>$_GET['songid'],
'puserid'=>$_SGLOBAL['supe_uid'],
'pltext'=>getstr($_POST['pingluntext'],200,1,1,1),
'dateline'=>$_SGLOBAL['timestamp'],
);
$setarrpllist = array(
'songuserid'=>$_GET['songuserid'],
'songid'=>$_GET['songid'],
'pinglunsongname'=>$_POST['pinglunsongname'],
);
integralop($_SC['music_i_pinglun'],$_SGLOBAL['supe_uid']);
musichandle(5,$setarrpl,-1,-1,$setarrpllist);
}
}
if(!empty($_GET['mybox'])){
if(!empty($_GET['songidlist'])){
$playmusiclist = array();
$playmusiclist = getmybox($_GET['songidlist']);
}
include_once template("space_musicbox_mybox");
}elseif($mview=='homeplay'){
$allmusiclist = array();
$songlist = setcommendmusic(0);
if(!empty($songlist)){
$allmusiclist = getmusiclist(-1,-1,-1,-1,-1,-1,-1,0,10,"",-1,-1,-1,2,$songlist);
}
include_once template("space_musicbox_homeplay");
}elseif($mview=='mpage'&&!empty($_GET['mid'])){
if(empty($_GET['lastplay'])){
$musicinfo = getsinglemusic($_GET['mid']);
}else{
$musicinfo = getsinglemusic($_GET['mid'],2);
}
if(!empty($musicinfo)){
$labellist = getmusiclabellist(str_replace("@@",",",$musicinfo['label']));
$commender = pagecommender($space['uid']);
}else{
showmessage('nomusic');
}
$needdivborder = false;
preg_match_all("/([\x80-\xff].)/",$musicinfo['usersay'],$regs);
if(strlen(implode("",$regs[1]))/2>300) $needdivborder = true;
echo getuserhost();
include_once template("space_musicbox_page");
}elseif($mview=='admin'&&$_SGLOBAL['member']['groupid']==1){
if($space['uid']==0){
checklogin();
}
if($_GET['control']==""){
if($_POST['commendconfigsubmit']){
$configfile = S_ROOT.'./config.php';
$configcontent = sreadfile($configfile);
$keys = array("music_i_edit","music_v_guest","music_i_disk","music_palyer_style","music_down_auto","music_play_auto","music_upload","music_link","music_integralset","music_i_upload","music_i_upload_del","music_i_addlink","music_i_addlink_del","music_i_addzj","music_i_addzj_del","music_i_pingfen","music_i_pinglun","music_i_pinglun_del","music_i_share");
foreach ($keys as $value) {
$configcontent = preg_replace("/[$]\_SC\[\'".$value."\'\](\s*)\=\s*[\"'].*?[\"']/is","\$_SC['".$value."']\\1= '".$_POST[$value]."'",$configcontent);
}
$fp111 = fopen($configfile,'w');
fwrite($fp111,trim($configcontent));
fclose($fp111);
showmessage('do_success','space.php?do=musicbox&mview=admin');
}
}elseif($_GET['control']=="singer"){
$allsinger = array();
$start = empty($_GET['start'])?0:intval($_GET['start']);
$perpage = 24;
ckstart($start,$perpage);
$count = 0;
if(!empty($_POST['searchsingerkeyword'])){
$ukeyword = urlencode($_POST['searchsingerkeyword']);
}else{
$ukeyword = $_GET['keyword'];
}
$allsinger = getallsinger(urldecode($ukeyword),$start,$perpage);
$count = $allsinger[0]['returncount'];
$multi = smulti($start,$perpage,$count,"space.php?do=musicbox&mview=admin&control=singer&keyword=".$ukeyword);
}elseif($_GET['control']=="music"){
if($_POST['commenddelmusicsubmit']){
musichandle(9,-1,implode(",",$_POST['musicsingle']));
}
$allmusiclist = array();
$start = empty($_GET['start'])?0:intval($_GET['start']);
$perpage = 20;
ckstart($start,$perpage);
$count = 0;
$allmusiclist = getmusiclist(-1,-1,-1,-1,-1,-1,-1,$start,$perpage,"",-1,1);
$count = $allmusiclist[0]['returncount'];
$multi = smulti($start,$perpage,$count,"space.php?do=musicbox&mview=admin&control=music");
}elseif($_GET['control']=="pinglun"){
if($_POST['commenddelmusicsubmit']){
musichandle(10,-1,implode(",",$_POST['musicsingle']));
}
$allpinglunlist = array();
$page = empty($_GET['page'])?1:intval($_GET['page']);
if($page<1) $page=1;
$perpage = 20;
$start = ($page-1)*$perpage;
ckstart($start,$perpage);
$allpinglunlist = getallpinglun($start,$perpage);
$multi = multi(getallpinglun_count(),$perpage,$page,"space.php?do=musicbox&mview=admin&control=pinglun");
}elseif($_GET['control']=="album"){
if($_POST['commenddelmusicsubmit']){
musichandle(11,-1,implode(",",$_POST['musicsingle']));
}
$mymusicalbumlist = array();
$mymusicalbumlist = getmusicalbum(-1,-1,1);
}
include_once template("space_musicbox_admin");
}elseif($mview=='malbum'&&!empty($_GET['albumid'])){
$musicalbuminfo = array();
$musicalbuminfo_id = "";
$musicalbuminfo_name = "";
$musicalbuminfo_fengmian = "";
$musicalbuminfo_userid = "";
$musicalbuminfo_dateline = "";
if($_GET['albumid']!="true"&&$_GET['albumid']!="0") {
$musicalbuminfo = getmusicalbum(-1,$_GET['albumid']);
$musicalbuminfo_userid = $musicalbuminfo['userid'];
$musicalbuminfo_id = $musicalbuminfo['albumid'];
$musicalbuminfo_name = $musicalbuminfo['albumname'];
$musicalbuminfo_fengmian = getlastimg($musicalbuminfo['albumfengmian']);
$musicalbuminfo_dateline = $musicalbuminfo['dataline'];
}else{
$musicalbuminfo_userid = $space['uid'];
$musicalbuminfo_id = "0";
$musicalbuminfo_name = "默认专辑";
$musicalbuminfo_fengmian = "<img src='images/album.gif' />";
$musicalbuminfo_dateline = "";
}
$albummusiclist = array();
$albummusiclist = getmusiclist($musicalbuminfo_userid,$musicalbuminfo_id);
include_once template("space_musicbox_album");
}elseif($mview=='alllist'){
$orderstyle = $_GET['order'];
$languagestyle = $_GET['language'];
$searchkeyword;
$urlsearchkeyword = $_GET['search'];
if(!empty($_POST['example1'])){
$searchkeyword = " AND (main.songname like '%".$_POST['example1']."%' or main.label like '%".$_POST['example1']."%') ";
$urlsearchkeyword = urlencode($_POST['example1']);
}else{
if(!empty($_GET['search']) &&$_GET['search']!="true"){
$searchkeyword = " AND main.songname like '%".urldecode($_GET['search'])."%' or main.label like '%".urldecode($_GET['search'])."%'";
$urlsearchkeyword = urlencode($_GET['search']);
}else{
$searchkeyword = "";
}
}
$actives['p'] = $actives['t'] = '>';
$actives1['a'] = $actives1['c'] = $actives1['e'] = $actives1['y'] = $actives1['j'] = $actives1['o'] = '>';
if(!empty($orderstyle)){
$actives[$orderstyle] = ' class="current"> ';
}else{
$actives['p'] = ' class="current"> ';
}
if(!empty($languagestyle)){
$actives1[$languagestyle] = ' class="current"> ';
}else{
$actives1['a'] = ' class="current"> ';
}
$allmusiclist = array();
if(empty($orderstyle) ||$orderstyle=="p"){
$dataorder = "lastdateline DESC, ";
}elseif($orderstyle=="t"){
$dataorder = "main.songid DESC, ";
}
if(empty($languagestyle) ||$languagestyle=="a"){
$datalanguage = "";
}elseif($languagestyle=="c"){
$datalanguage = "AND lang=1";
}elseif($languagestyle=="e"){
$datalanguage = "AND lang=3";
}elseif($languagestyle=="y"){
$datalanguage = "AND lang=2";
}elseif($languagestyle=="j"){
$datalanguage = "AND lang=4";
}elseif($languagestyle=="o"){
$datalanguage = "AND lang=5";
}
$start = empty($_GET['start'])?0:intval($_GET['start']);
$perpage = 30;
ckstart($start,$perpage);
$count = 0;
$allmusiclist = usergetallmusiclist($dataorder,$datalanguage,$searchkeyword,$start,$perpage);
$count = $allmusiclist[0]['returncount'];
$playtotal = $allmusiclist[0]['returnplaytotal'];
$multi = smulti($start,$perpage,$count,"space.php?uid=".$space['uid']."&do=musicbox&mview=alllist&order=".$_GET['order']."&language=".$_GET['language']."&search=".$urlsearchkeyword.$orderbystr);
include_once template("space_musicbox_alllist");
}elseif($mview=='all'){
if(empty($_GET['viewtype'])){
$allmusiclist = array();
$alink = array();
$alink = getalink($alink,$_GET['mtype']);
$orderbystr = !empty($_GET['orderby']) ?"&orderby=".$_GET['orderby'] : "";
if(empty($_GET['search'])){
$start = empty($_GET['start'])?0:intval($_GET['start']);
$perpage = 15;
ckstart($start,$perpage);
$count = 0;
if(empty($_GET['mtype'])){
if(empty($_GET['orderby']) ||$_GET['orderby']=="p"){
$allmusiclist = getmusiclist(-1,-1,-1,-1,-1,-1,-1,$start,$perpage);
}elseif($_GET['orderby']=="t"){
$allmusiclist = getmusiclist(-1,-1,-1,-1,-1,-1,-1,$start,$perpage,"",-1,2);
}
}else{
$allmusiclist = getmusiclist(-1,-1,-1,$alink[25][$_GET['mtype']],-1,-1,-1,$start,$perpage);
}
$count = $allmusiclist[0]['returncount'];
$playtotal = $allmusiclist[0]['returnplaytotal'];
$multi = smulti($start,$perpage,$count,"space.php?uid=".$space['uid']."&do=musicbox&mview=all".$orderbystr);
}else{
$allmusiclist=getmusiclist(-1,-1,-1,-1,-1,-1,-1,-1,-1,$_POST['example1'],2);
}
$allmusicalbumlist = array();
$musicpeople = array();
$bestalbum = array();
$allmusicalbumlist = getmusicalbum(-1,-1,1);
$musicpeople = musicpeople(1);
$bestalbum = musicpeople(3,-1,4);
}elseif($_GET['viewtype']=="albumlist"){
$bestalbum = array();
$bestalbum = musicpeople(3,-1,6);
}elseif($_GET['viewtype']=="allsay"){
$allpinglunlist = array();
$page = empty($_GET['page'])?1:intval($_GET['page']);
if($page<1) $page=1;
$perpage = 10;
$start = ($page-1)*$perpage;
ckstart($start,$perpage);
$allpinglunlist = getallpinglun($start,$perpage);
$multi = multi(getallpinglun_count(),$perpage,$page,"space.php?do=musicbox&mview=all&viewtype=allsay");
}elseif($_GET['viewtype']=="allplay"){
$myset_like_p = mycongener($space['musictaste'],$space['uid']);
}elseif($_GET['viewtype']=="allclass"){
$thelabelc=0;
$albelorder = array();
$albelorder = getdatetoarray("musicbox","label","");
arsort($albelorder);
$thelabelc=max($albelorder);
}
include_once template("space_musicbox_all");
}elseif($mview=='disk'){
if($space['uid']==0){
checklogin();
}
if($_POST['commenddisksubmit']){
$strName = "fs2you_f_name";
$strLink = "fs2you_f_link";
$strResult = "fs2you_f_result";
$strDescription = "fs2you_f_description";
if(isset($_POST[$strResult])){
$RAW_POST = file_get_contents("php://input");
$RAW_POST = str_replace("=","[]=",$RAW_POST);
parse_str($RAW_POST,$my_post);
for($i=0;$i<count($my_post[$strResult]);$i++){
if(substr($my_post[$strName][$i],-3)=="mp3"||substr($my_post[$strName][$i],-3)=="wma"){
$setarr = array(
'userid'=>$_SGLOBAL['supe_uid'],
'fileurl'=>$my_post[$strLink][$i],
'filename'=>$my_post[$strName][$i],
'filedescription'=>$my_post[$strDescription][$i],
'dateline'=>$_SGLOBAL['timestamp'],
);
inserttodisk($setarr);
}
}
}
showmessage('do_success',"space.php?do=musicbox&mview=disk");
}
$mydiskfilelist = array();
$mydiskfilelist = getmydiskfilelist($_SGLOBAL['supe_uid']);
include_once template("space_musicbox");
}elseif($mview=='friends'){
if($space['uid']==0){
checklogin();
}
$musiclist = array();
$musiclist = getfriendsmusiclist($space['feedfriend']);
$thefirstuserid = $musiclist[0]['userid'];
$thekeyc = 0;
include_once template("space_musicbox");
}elseif($mview=='setting'){
if($space['uid']==0){
checklogin();
}
if($_POST['mylikecommendsubmit']){
$musictaste = implode(",",$_POST['myfancy']);
if(!empty($_POST['likesinger'])) $musictaste=$musictaste."@@@".$_POST['likesinger'];
updatemyset(2,1,$musictaste,$_SGLOBAL['supe_uid']);
showmessage('do_success','space.php?do=musicbox&mview=setting');
}else{
$myset_like = explode("@@@",$space['musictaste']);	//split
$myset_like_1 = $myset_like[0];
$myset_like_2 = $myset_like[1];
}
include_once template("space_musicbox");
}else{
if($space['uid']==0){
checklogin();
}
if($mview=='commend'){
checklogin();
$maxattachsize = checkperm('maxattachsize');
if(empty($maxattachsize)) {
$maxattachsize = '209715200';
$residuesize = intval(str_replace(" MB","",formatsize(intval(intval($maxattachsize-$space['attachsize'])))));
$percent = intval($space['attachsize']/$maxattachsize*100);
$maxattachsize = formatsize($maxattachsize);
}else {
$maxattachsize = $maxattachsize +$space['addsize'];
$residuesize = intval(str_replace(" MB","",formatsize(intval(intval($maxattachsize-$space['attachsize'])))));
$percent = intval($space['attachsize']/$maxattachsize*100);
$maxattachsize = formatsize($maxattachsize);
}
$spaceattachsize = formatsize($space['attachsize']);
function minfilesize($residuesize1){
if(intval($residuesize1)<10){
return intval($residuesize1);
}else{
return "10";
}
}
if(getmusicalbumcount($_SGLOBAL['supe_uid'])==0)
{
getmusicalbumcount($_SGLOBAL['supe_uid'],1);
}
if($mview=='commend'&&!empty($_GET['mid'])){
if($_POST['editcommendsubmit']){
$used_degraded = false;
$resume_id = "";
if($_POST['utype']==0 &&$_POST["hidFileID"]==""){
$resume_id = "true";
}else{
if($_POST['utype']==0){
if (isset($_FILES["resume_degraded"]) &&is_uploaded_file($_FILES["resume_degraded"]["tmp_name"]) &&$_FILES["resume_degraded"]["error"] == 0) {
$resume_id = $_FILES["resume_degraded"]["name"];
$used_degraded = true;
}
if (isset($_POST["hidFileID"]) &&$_POST["hidFileID"] != "") {
$resume_id = $_POST["hidFileID"];
}
}else{
$resume_id = getstr($_POST['songurl'],250,1,1,1);
}
}
$label = getstr($_POST['label'],100,1,1,1)."@@".implode(",",$_POST['musicca']);
if(!empty($_POST['singer'])){
insertsinger(getstr($_POST['singer'],100,1,1,1));
}
$setarr = array(
'albumid'=>$_POST['albumid'],
'usersay'=>getstr($_POST['usersay'],2000,1,1,1),
'songname'=>getstr($_POST['songname'],100,1,1,1),
'label'=>$label,
'singer'=>empty($_POST['singer']) ?"未知歌手": getstr($_POST['singer'],100,1,1,1),
'lang'=>$_POST['lang'],
'lyric'=>getstr($_POST['lyric'],2000,1,1,1),
'down'=>$_POST['down'],
);
if($resume_id=="true"){unset($setarr['songurl']);}
updatemusic($setarr,$_GET['mid']);
}
$editmusic = array();
$editmusic = getsinglemusic($_GET['mid'],0);
$musiclab = getarysvalue("@@",$editmusic['label'],0);
$musiccat = getarysvalue("@@",$editmusic['label'],1);
}
if($_POST['commendsubmit']){
$used_degraded = false;
$resume_id = "";
$uploadfilesize = 0;
if($_POST['utype']=="0"){
if (isset($_FILES["resume_degraded"]) &&is_uploaded_file($_FILES["resume_degraded"]["tmp_name"]) &&$_FILES["resume_degraded"]["error"] == 0) {
$resume_id = $_FILES["resume_degraded"]["name"];
$used_degraded = true;
}
if (isset($_POST["hidFileID"]) &&$_POST["hidFileID"] != "") {
$resume_id = $_POST["hidFileID"];
$uploadfilesize = intval($_POST["hidFileusize"]);
integralop($_SC['music_i_upload'],$space['uid']);
}
}elseif($_POST['utype']=="1"){
$resume_id = getstr($_POST['songurl'],250,1,1,1);
integralop($_SC['music_i_addlink'],$space['uid']);
}else{
$resume_id = getstr($_POST['songurldisk'],200,1,1,1);
}
$label = getstr($_POST['label'],100,1,1,1)."@@".implode(",",$_POST['musicca']);
if(!empty($_POST['singer'])){
insertsinger(getstr($_POST['singer'],100,1,1,1));
}
$setarr = array(
'userid'=>$_SGLOBAL['supe_uid'],
'albumid'=>$_POST['albumid'],
'usersay'=>getstr($_POST['usersay'],2000,1,1,1),
'songname'=>getstr($_POST['songname'],100,1,1,1),
'label'=>$label,
'singer'=>empty($_POST['singer']) ?"未知歌手": getstr($_POST['singer'],100,1,1,1),
'lang'=>$_POST['lang'],
'songurl'=>$resume_id,
'lyric'=>getstr($_POST['lyric'],2000,1,1,1),
'upload'=>$_POST['utype'],
'down'=>$_POST['down'],
'dataline'=>$_SGLOBAL['timestamp'],
);
insertnewmusic($setarr,$uploadfilesize,intval($space['attachsize']));
}
}
if((empty($mview) ||$mview == 'mybox') &&empty($_GET['thismenu'])) {
$collectionuserlist = array();
$collectionuserlist = musicpeople(2,$space['uid'],8);
$maxattachsize = checkperm('maxattachsize');
if(empty($maxattachsize)) {
$maxattachsize = '209715200';
$residuesize = intval(str_replace(" MB","",formatsize(intval(intval($maxattachsize-$space['attachsize'])))));
$percent = intval($space['attachsize']/$maxattachsize*100);
$maxattachsize = formatsize($maxattachsize);
}else {
$maxattachsize = $maxattachsize +$space['addsize'];
$percent = intval($space['attachsize']/$maxattachsize*100);
$maxattachsize = formatsize($maxattachsize);
}
$space['attachsize'] = formatsize($space['attachsize']);
$myset_like_p = mycongener($space['musictaste'],$space['uid']);
}elseif($mview == 'mybox'&&$_GET['thismenu']=="mymusicfans") {
$collectionuserlist = array();
$collectionuserlist = musicpeople(2,$space['uid'],500);
}elseif($mview == 'mybox'&&$_GET['thismenu']=="musicfeed") {
$feed_list = array();
$count = 0;
$query = $_SGLOBAL['db']->query("SELECT * FROM ".$_SC['tablepre']."feed WHERE uid='".$space['uid']."' AND appid='1' AND icon='musicbox' ORDER BY dateline DESC LIMIT 0,50");
while ($value = $_SGLOBAL['db']->fetch_array($query)) {
realname_set($value['uid'],$value['username']);
$feed_list[] = $value;
$count++;
}
$list = array();
foreach ($feed_list as $value) {
$value = mkfeed($value);
if($value['dateline']>=$_SGLOBAL['today']) {
$list['today'][] = $value;
}elseif ($value['dateline']>=$_SGLOBAL['today']-3600*24) {
$list['yesterday'][] = $value;
}else {
$theday = sgmdate('Y-m-d',$value['dateline']);
$list[$theday][] = $value;
}
}
}elseif($mview == 'mybox'&&$_GET['thismenu']=="mycollect") {
}elseif($mview == 'mybox'&&$_GET['thismenu']=="myalbum") {
if(getmusicalbumcount($_SGLOBAL['supe_uid'])==0) getmusicalbumcount($_SGLOBAL['supe_uid'],1);
}elseif($mview == 'mybox'&&$_GET['thismenu']=="mymusicbox") {
$mymusicboxlist = array();
$mymusicboxlist = getmybox('myboxlist');
$mymusicboxlisttc = empty($mymusicboxlist) ?0 : count($mymusicboxlist);
}
if($_POST['addalbum_commendsubmit']){
$postalbumname = getstr($_POST['mynewmusicalbum_albumname'],100,1,1,1);
$mynewmusicalbum_fengmian = "";
if($_POST['fengmianchoose']=="up"){
$img = new UPImages("images/album.gif");
$mynewmusicalbum_fengmian = $img->upLoad("upalbumfengmian");
}else{
$mynewmusicalbum_fengmian = $_POST['mynewmusicalbum_fengmian'];
}
$setarr1 = array(
'userid'=>$_SGLOBAL['supe_uid'],
'albumname'=>$postalbumname,
'albumfengmian'=>$mynewmusicalbum_fengmian,
'dataline'=>$_SGLOBAL['timestamp'],
);
integralop($_SC['music_i_addzj'],$space['uid']);
insertnewalbum($setarr1,$space['uid']);
}
if($_POST['editmyalbum_commendsubmit']){
$mynewmusicalbum_fengmian = "";
if($_POST['fengmianchoose']=="up"){
$img = new UPImages("images/album.gif");
$mynewmusicalbum_fengmian = $img->upLoad("upalbumfengmian");
}else{
$mynewmusicalbum_fengmian = $_POST['mynewmusicalbum_fengmian'];
}
$setarr1 = array(
'albumname'=>getstr($_POST['mynewmusicalbum_albumname'],100,1,1,1),
'albumfengmian'=>$mynewmusicalbum_fengmian,
);
updatealbum($setarr1,$_POST['editalbumidvalue']);
}
include_once template("space_musicbox");
}
?>