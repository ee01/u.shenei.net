<?php
function getuserhost() {
getdigest();
}
function integralop($integral,$uid) {
global $_SGLOBAL,$_SC;
if($_SC['music_integralset']=="1") {
$_SGLOBAL['db']->query('UPDATE '.tname('space').' SET credit=credit+'.intval($integral).' WHERE uid='.$uid);
}
}

function updatemyset($do=1,$type=1,$strvalue,$userid){
global $_SGLOBAL,$_SC;
if($do==1){
$query = $_SGLOBAL['db']->query("SELECT musictaste FROM ".tname( "space" )." WHERE uid=$userid");
return $_SGLOBAL['db']->fetch_array($query);
}else{
if($type==1){
$_SGLOBAL['db']->query("UPDATE ".tname( "space" )." SET musictaste='$strvalue' WHERE uid=$userid");
}
}
}

function checkboxis($ary,$str){
$ary = explode(",",$ary);	//split
if(in_array($str,$ary)) return ' checked="checked"';
}

function getarysvalue($chartstr,$stringv,$indexvalue){
$resultary = explode($chartstr,$stringv);	//split
return $resultary[$indexvalue];
}

function mycongener($str,$spaceuid,$arycount=5){
global $_SGLOBAL,$_SC;
$datasqlstr = array();
$resultlist = array();
$query = $_SGLOBAL['db']->query("SELECT main.userid,COUNT(main.songid) as songcount,SUM(main.playtotal) as songplay,album.albumcount,s.uid,s.username,s.name,s.namestatus,s.musictaste FROM ".tname( "musicbox" )." main left JOIN (SELECT userid,COUNT(albumid) as albumcount FROM ".tname( "musicbox_album" )." GROUP BY userid) album ON (main.userid=album.userid) left JOIN ".tname( "space" )." s ON (main.userid=s.uid) WHERE main.userid<>$spaceuid GROUP BY main.userid ORDER BY songcount DESC");
while ($value = $_SGLOBAL['db']->fetch_array($query)) {
realname_set($value['uid'],$value['username'],$value['name'],$value['namestatus']);
similar_text(str_replace("@@@",",",$str),$value['musictaste'],$ptemp);
$value['musictastep'] = $ptemp;
$resultlist[] = $value;
}

foreach ($resultlist as $key =>$value) {
$musictastep[$key] = $value['musictastep'];
}
@array_multisort($musictastep,SORT_DESC,$resultlist);
$resultlist = array_slice($resultlist,0,$arycount);
return $resultlist;
}

function getdatetoarray($table,$file,$wheresql){
global $_SGLOBAL;
$temparray = array();
$ttemp = array();
$query = $_SGLOBAL['db']->query("SELECT ".$file." FROM ".tname($table)." WHERE 1=1 ".$wheresql);
while ($value = $_SGLOBAL['db']->fetch_array($query)) {
$ttemp = explode(",",$value[$file]);	//split
$temparray = array_merge($temparray,$ttemp);
}
$temparray = array_merge($temparray,array("流行歌曲","舞曲","翻唱","怀旧","最新单曲","hip-hop","RandB","RAP","轻音乐","电影原声","动漫原声","HOUSE","迷幻","电子","爵士","古典乐","交响乐","朋克","民谣","金属","摇滚","乡村","歌特","其他"));
$temparray=array_remove_empty($temparray);
$temparray=getalinkint($temparray,2);
return array_count_values($temparray);
}

function array_remove_empty($arr){
foreach ($arr as $key =>$value){
if($value==""){
unset($arr[$key]);
}
}
return $arr;
}

function getalinkint($namesrt,$type=-1){
$arr1 = array();
$arr1[25] = array("","流行歌曲","舞曲","翻唱","怀旧","最新单曲","hip-hop","RandB","RAP","轻音乐","电影原声","动漫原声","HOUSE","迷幻","电子","爵士","古典乐","交响乐","朋克","民谣","金属","摇滚","乡村","歌特","其他");
if($type==-1){
for($i=1;$i<25;$i++){
if($arr1[25][$i]==$namesrt) return $i;
}
}else{
foreach ($namesrt as $key =>$value){
$isin = 0;
for($i=1;$i<25;$i++){
if($value==$arr1[25][$i]) $isin=1;
}
if($isin!=1) unset($namesrt[$key]);
}
return $namesrt;
}
}
function writeselectlist($type,$value)
{
$tempstr ="";
for($i=0;$i<=20;$i++){
$temp = "";
$temp = $type.$i;
if($temp==$value){
$tempstr.= '<option value="'.$temp.'" selected="selected">'.$temp.'</option>';
}else{
$tempstr.= '<option value="'.$temp.'">'.$temp.'</option>';
}
}
return $tempstr;
}

function getallpinglun($start,$perpage) {
global $_SGLOBAL,$_SC;
$allpinglunlist = array();
$query = $_SGLOBAL['db']->query("SELECT main.id, main.pltext, main.dateline, s.uid, s.username, s.name, s.namestatus, song.userid, song.songid, song.songname FROM ".tname( "musicbox_pinglun" )." main left JOIN ".tname( "space" )." s on ( main.puserid=s.uid ) left JOIN ".tname( "musicbox" )." song on ( main.songid=song.songid ) WHERE song.songname is not null ORDER BY main.dateline DESC LIMIT $start,$perpage");
while ($value = $_SGLOBAL['db']->fetch_array($query)) {
realname_set($value['uid'],$value['username'],$value['name'],$value['namestatus']);
$allpinglunlist[] = $value;
}
return $allpinglunlist;
}

function getallpinglun_count() {
global $_SGLOBAL,$_SC;
$listcount = 0;
$allpinglunlist_c = array();
$query = $_SGLOBAL['db']->query("SELECT COUNT(main.id) as c FROM ".tname( "musicbox_pinglun" )." main left JOIN ".tname( "space" )." s on ( main.puserid=s.uid ) left JOIN ".tname( "musicbox" )." song on ( main.songid=song.songid ) WHERE song.songname is not null");
$allpinglunlist_c = $_SGLOBAL['db']->fetch_array($query);
return $allpinglunlist_c['c'];
}


function getlistofuserspace($user){
global $_SGLOBAL,$_SC;
$userlist = array();
$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname( "space" )." WHERE uid in($user)");
while ($value = $_SGLOBAL['db']->fetch_array($query)) {
realname_set($value['uid'],$value['username'],$value['name'],$value['namestatus']);
$userlist[] = $value;
}
return $userlist;
}
/*
function server_getdiskurl($diskurl,$songid=-1){
global $_SGLOBAL;
exit($diskurl);
$tempdatearray = getdiskurl($diskurl);
return $tempdatearray;
}
*/
function server_getdiskurl($diskurl,$songid=-1){
global $_SGLOBAL;
$configfile = S_ROOT.'./musicboxdate.txt';
$tempdatesrr = sreadfile($configfile);
$musicboxdate = explode("|",$tempdatesrr);
$dyokkdb=fetch_urlpage_contents($diskurl);
$dyqjvgdqss=fetch_match_contents( "downloads_url", "']", $dyokkdb );
$dyqjvgdqss = str_replace( " = ['", "", $dyqjvgdqss );

				if ( $dyqjvgdqss != "" )
				{
								$dygdfgdqs = str_replace( "zh-cn/download", $musicboxdate[0]."/zh-cn/preview", $dyqjvgdqss );
								$dygdklghgqs = explode( "/", $dygdfgdqs );	//split
								$dyggqdklshg = str_replace( ".", "/", $dygdklghgqs[count( $dygdklghgqs ) - 1] );
								$dygdkhlsg = explode( "/", $dyggqdklshg );	//split
								$dygdfgdqs = str_replace( $dygdklghgqs[count( $dygdklghgqs ) - 1], "preview.".$dygdkhlsg[1], $dygdfgdqs );
								}
//if(!$dygdfgdqs) updatetable('musicbox',array('songurl'=>$dygdfgdqs),array('songid'=>$songid));
return $dygdfgdqs;
}




function getdiskurl( $dyqjdisk )
{
$dyokkdb=fetch_urlpage_contents($dyqjdisk);
$dyqjvgdqss=fetch_match_contents( "downloads_url", "']", $dyokkdb );
$dyqjvgdqss = str_replace( " = ['", "", $dyqjvgdqss );
				if ( $dyqjvgdqss != "" )
				{
								$dygdfgdqs = str_replace( "zh-cn/download", "3fb4/zh-cn/preview", $dyqjvgdqss );
								$dygdklghgqs = explode( "/", $dygdfgdqs );	//split
								$dyggqdklshg = str_replace( ".", "/", $dygdklghgqs[count( $dygdklghgqs ) - 1] );
								$dygdkhlsg = explode( "/", $dyggqdklshg );	//split
								$dygdfgdqs = str_replace( $dygdklghgqs[count( $dygdklghgqs ) - 1], "preview.".$dygdkhlsg[1], $dygdfgdqs );
								
								return $dygdfgdqs;
				}
				return "";
}

function fetch_urlpage_contents( $dykgdlshig )
{
				$dykgdlshigdb = file_get_contents( $dykgdlshig );
				$dyurls = fetch_match_contents( "<div class=\"btn_indown_zh-cn\">", "</div>", $dykgdlshigdb );
                                $dyurls = fetch_match_contents( "<a href=\"", "\">", $dyurls );
				$dykgdlshigdb = file_get_contents( $dyurls );
				return $dykgdlshigdb;
}

function fetch_match_contents( $dykgdlsrrr, $dykgddelrrr, $dykgdlshigdb )
{
				$dykgdlsrrr = change_match_string( $dykgdlsrrr );
				$dykgddelrrr = change_match_string( $dykgddelrrr );
				if ( @preg_match( "/".$dykgdlsrrr."(.*?){$dykgddelrrr}/i", $dykgdlshigdb, $dyqjvgdqss ) )
				{
								return $dyqjvgdqss[1];
				}
				return "";
}

function change_match_string( $dykgdkggg )
{
				$dykggdkggw = array( "/", "\$" );
				$dykggdkxxw = array( "\\/", "\$" );
				$dykgdkggg = str_replace( $dykggdkggw, $dykggdkxxw, $dykgdkggg );
				return $dykgdkggg;
}
function pick( $dykgdlshig, $dykggdkllo, $dykvvvh )
{
				$dykgdlshigdb = fetch_urlpage_contents( $dykgdlshig );
				foreach ( $dykggdkllo as $dyopid => $dyqrs )
				{
								$dyqjvgdqss[$dyopid] = fetch_match_contents( $dyqrs['begin'], $dyqrs['end'], $dykgdlshigdb );
								if ( is_array( $dykvvvh[$dyopid] ) )
								{
												foreach ( $dykvvvh[$dyopid] as $dykggdkggw => $dykggdkxxw )
												{
																$dyqjvgdqss[$dyopid] = str_replace( $dykggdkggw, $dykggdkxxw, $dyqjvgdqss[$dyopid] );
												}
								}
				}
				return $dyqjvgdqss;
}


function getsinglemusic($songid,$update=1) {
global $_SGLOBAL,$_SC;
$query = $_SGLOBAL['db']->query("SELECT main.*,a.albumname as albumname, a.albumfengmian as albumfengmian, er.* FROM ".tname( "musicbox" )." as main left JOIN ".tname( "musicbox_album" )." as a ON ( main.albumid = a.albumid ) left join ".tname( "musicbox_singer" )." as er ON (main.singer=er.singername) WHERE main.songid=$songid");
$musicinfo = $_SGLOBAL['db']->fetch_array($query);
if($musicinfo['upload']=="2"){$musicinfo['songurl'] = server_getdiskurl($musicinfo['songurl'],$musicinfo['songid']);}
if($musicinfo['photourl']==""&&$musicinfo['singerid']==""){
//Add By 01↓
$query2 = $_SGLOBAL['db']->query("SELECT * FROM ".tname( "musicbox_singer" )." WHERE singername='$singername'");
$tempdataa = $_SGLOBAL['db']->fetch_array($query2);
if($tempdataa){
$musicinfo['photourl'] = $tempdataa['photourl'];
$musicinfo['singerid'] = $tempdataa['singerid'];
if($update==1){updatetable('musicbox',array('playtotal'=>$musicinfo['playtotal']+1,'commendtotal'=>$_SGLOBAL['timestamp']),array('songid'=>$songid));}
}else{
//Add By 01↑
$setarr1 = array(
'area'=>"3",
'singername'=>$musicinfo['singer'],
'photourl'=>"images/singernophoto.jpg",
);
$newsongerid = inserttable('musicbox_singer',$setarr1,1);
$musicinfo['photourl'] = "images/singernophoto.jpg";
$musicinfo['singerid'] = $newsongerid;
}	//Add By 01
}

if(!empty($musicinfo) &&$update==1){updatetable('musicbox',array('playtotal'=>$musicinfo['playtotal']+1,'commendtotal'=>$_SGLOBAL['timestamp']),array('songid'=>$songid));}
return $musicinfo;
}

function editsingerphotofun($singerid,$photourl) {
global $_SGLOBAL;
updatetable('musicbox_singer',array('photourl'=>$photourl),array('singerid'=>$singerid));
showmessage('do_success');
}

function getallsinger($keyword,$start,$perpage){
global $_SGLOBAL,$_SC;
$allsingerlist = array();
$resultcount = 0;
if(!empty($keyword)){
$wheresql = " AND singername like '%".$keyword."%'";
}else{
$wheresql = "";
}
$limitstr = " LIMIT ".$start.",".$perpage;
$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname( "musicbox_singer" )." WHERE 1=1 $wheresql $limitstr");
while ($value = $_SGLOBAL['db']->fetch_array($query)) {$allsingerlist[] = $value;$resultcount++;}
if($resultcount!=0) $allsingerlist[0]['returncount'] = $resultcount;
return $allsingerlist;
}

function insertsinger($singername){
global $_SGLOBAL,$_SC;
$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname( "musicbox_singer" )." WHERE singername='$singername'");
$tempdataa = $_SGLOBAL['db']->fetch_array($query);
if(empty($tempdataa)){
$setarr1 = array(
'area'=>"3",
'singername'=>$singername,
'photourl'=>"images/singernophoto.jpg",
);
inserttable('musicbox_singer',$setarr1,0);
}
}

function getfriendsmusiclist($userlist) {
global $_SGLOBAL,$_SC,$_SN,$_SCONFIG;
$datasqlstr = array();
$thisuserid = "-1";
$thisalbumid = "-1";
$musiclist = array();
$datasqlstr['userlist'] = $userlist;
if(!empty($datasqlstr['userlist'])){
$query = $_SGLOBAL['db']->query("SELECT main.songid, main.albumid, main.userid, main.songname, main.dataline, s.lastlogin, s.uid,s.username,s.name,s.namestatus,a.albumid as aalbumid, a.albumname,a.albumfengmian FROM ".tname( "musicbox" )." as main left join ".tname( "space" )." s on (main.userid=s.uid) left join ".tname( "musicbox_album" )." a on (main.albumid=a.albumid) WHERE main.userid in($userlist) order by s.lastlogin desc, main.userid desc, main.dataline desc");
while ($value = $_SGLOBAL['db']->fetch_array($query)) {$musiclist[] = $value;}
//exit($client->server_getmyfriendsmusiclist(1,$musiclist)."999999");
//$musiclist = $client->server_getmyfriendsmusiclist(1,$musiclist);
}
return $musiclist;
}

function musichandle($type=-1,$setarr=-1,$id=-1,$spaceuid=-1,$arraylist=-1) {
global $_SGLOBAL,$_SC;
$datasqlstr = array();
switch ($type) {
case 0:
if($spaceuid==0){
$datatemparr = array();
$datatemparr = getsinglemusic($id,0);
$_SGLOBAL['db']->query('UPDATE '.tname('space').' SET attachsize='.intval(intval($arraylist)-intval(filesize(S_ROOT.$datatemparr['songurl']))).' WHERE uid='.$datatemparr['userid']);
unlink(str_replace("space.php","",realpath("space.php")).str_replace("./","",$datatemparr['songurl']));
}
$datasqlstr['id'] = $id;
$_SGLOBAL['db']->query("DELETE FROM ".tname( "musicbox" )." WHERE songid=$id");
$_SGLOBAL['db']->query("DELETE FROM ".tname( "musicbox_mybox" )." WHERE boxsongid=$id");
$_SGLOBAL['db']->query("DELETE FROM ".tname( "musicbox_pingfen" )." WHERE songid=$id");
$_SGLOBAL['db']->query("DELETE FROM ".tname( "musicbox_pinglun" )." WHERE songid=$id");
showmessage('deletemusic_success');
break;
case 1:
$datasqlstr['id'] = $id;
$_SGLOBAL['db']->query("DELETE FROM ".tname( "musicbox_album" )." WHERE albumid=$id");
$defaultid = getalbumdefaultid($spaceuid);
updatetable('musicbox',array('albumid'=>$defaultid),array('albumid'=>$id));
showmessage('deletemusicalbum_success');
break;
case 2:
$datasqlstr['id'] = $id;
$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname( "musicbox" )." where songid=$id");
$datatemparr = $_SGLOBAL['db']->fetch_array($query);
if(!empty($setarr)){
include_once(S_ROOT.'./source/function_cp.php');
$note = cplang('music_toshare',array($datatemparr['userid'],$id,$datatemparr['songname']));
$ckarray = $setarr;
$cksize = count($ckarray);
for($i=0;$i<$cksize;$i++){
notification_add($ckarray[$i],'wall',$note);
}
}
showmessage('do_success');
break;
case 3:
inserttable('musicbox_mybox',$setarr,0);
showmessage('do_success');
break;
case 4:
inserttable('musicbox_pingfen',$setarr,0);
include_once(S_ROOT.'./source/function_cp.php');
$note = cplang('music_topingfen',array($arraylist['songuserid'],$arraylist['songid'],$arraylist['pingfensongname'],$arraylist['pingfen']));
notification_add($arraylist['songuserid'],'wall',$note);
showmessage('musicpingfen_success');
break;
case 5:
inserttable('musicbox_pinglun',$setarr,0);
include_once(S_ROOT.'./source/function_cp.php');
$note = cplang('music_topinglun',array($arraylist['songuserid'],$arraylist['songid'],$arraylist['pinglunsongname']));
notification_add($arraylist['songuserid'],'wall',$note);
showmessage('musicpinglun_success');
break;
case 6:
updatetable('musicbox',$setarr,array('songid'=>$id));
include_once(S_ROOT.'./source/function_cp.php');
$note = cplang('music_collection',array($arraylist['userid'],$arraylist['songid'],$arraylist['songname']));
notification_add($arraylist['userid'],'wall',$note);
showmessage('do_success');
break;
case 7:
updatetable('musicbox',$setarr,array('songid'=>$id));
showmessage('musicsetbg_success');
break;
case 8:
$datasqlstr['id'] = $id;
$_SGLOBAL['db']->query("DELETE FROM ".tname( "musicbox_pinglun" )." WHERE id=$id");
showmessage('do_success');
break;
case 9:
$datasqlstr['id'] = $id;
$_SGLOBAL['db']->query("DELETE FROM ".tname( "musicbox" )." WHERE songid in ($id)");
$_SGLOBAL['db']->query("DELETE FROM ".tname( "musicbox_mybox" )." WHERE boxsongid in ($id)");
$_SGLOBAL['db']->query("DELETE FROM ".tname( "musicbox_pingfen" )." WHERE songid in ($id)");
$_SGLOBAL['db']->query("DELETE FROM ".tname( "musicbox_pinglun" )." WHERE songid in ($id)");
showmessage('do_success','space.php?do=musicbox&mview=admin&control=music');
break;
case 10:
$datasqlstr['id'] = $id;
$_SGLOBAL['db']->query("DELETE FROM ".tname( "musicbox_pinglun" )." WHERE id in ($id)");
showmessage('do_success','space.php?do=musicbox&mview=admin&control=pinglun');
break;
case 11:
$datasqlstr['id'] = $id;
$_SGLOBAL['db']->query("DELETE FROM ".tname( "musicbox_album" )." WHERE albumid in ($id)");
updatetable('musicbox',array('albumid'=>'0'),"albumid in (".$id.")");
showmessage('do_success','space.php?do=musicbox&mview=admin&control=album');
break;
default:
break;
}
return true;
}

function usergetallmusiclist($listorder,$listlanguage,$listkeywork,$start,$perpage){
global $_SGLOBAL,$_SC;
$resultcount=0;
$musiclistarr = array();
$limitstr = " LIMIT ".$start.",".$perpage;
$query = $_SGLOBAL['db']->query("SELECT main.*,a.albumname as albumname, a.albumfengmian as albumfengmian, s.uid as suid, s.username as username, s.name as name, s.namestatus as namestatus, pf.mark as mark, pl.pinglunc as pinglunc, lastpl.puserid as lastpluid, lastpl.dateline as lastdateline, ls.uid as lsuid, ls.username as lsusername, ls.name as lsname, ls.namestatus as lsnamestatus FROM ".tname( "musicbox" )." as main left JOIN ".tname( "musicbox_album" )." as a ON ( main.albumid = a.albumid ) left JOIN ".tname( "space" )." s ON ( main.userid = s.uid ) left JOIN (SELECT songid, sum( mark ) AS mark FROM ".tname( "musicbox_pingfen" )." GROUP BY songid) pf ON ( main.songid = pf.songid ) left JOIN (SELECT songid, COUNT( id ) as pinglunc FROM ".tname( "musicbox_pinglun" )." GROUP BY songid) pl ON ( main.songid = pl.songid ) left JOIN (SELECT songid,puserid,MAX(dateline) as dateline FROM (SELECT * FROM ".tname( "musicbox_pingfen" )." WHERE mark=5 ORDER BY id DESC) lastpl1 GROUP BY songid) lastpl ON ( main.songid = lastpl.songid ) left JOIN ".tname( "space" )." ls ON ( lastpl.puserid = ls.uid ) WHERE 1=1 $listlanguage $listkeywork ORDER BY $listorder mark DESC $limitstr");
while ($value = $_SGLOBAL['db']->fetch_array($query)) {
realname_set( $value['suid'], $value['username'], $value['name'], $value['namestatus'] );
realname_set( $value['lsuid'], $value['lsusername'], $value['lsname'], $value['lsnamestatus'] );
$musiclistarr[] = $value;
++$resultcount;
}
if ( $resultcount != 0 )
{
$musiclistarr[0]['returncount'] = $resultcount;
}
return $musiclistarr;
}

function getmusiclist($userid=-1,$albumid=-1,$songid=-1,$label=-1,$singer=-1,$lang=-1,$collectionuser=-1,$start=-1,$perpage=-1,$songname="",$alllist=-1,$orderbyid=-1,$lastplay=-1,$geturl=-1,$songidlist="") {
global $_SGLOBAL,$_SC,$_SN,$_SCONFIG;
$musiclistarr = array();
$datasqlstr = array();
$resultcount = 0;
$playtotalcount = 0;
if($songname!=""){
$songnamestr = " AND main.songname like '%".$songname."%'";}
if($userid!=-1){
$useridstr = " AND main.userid=".$userid;
$orderbylasttime = "main.songid DESC,";
}
if($albumid!=-1){
$albumidstr = " AND main.albumid=".$albumid;}
if($songid!=-1){
$songidstr = " AND main.songid=".$songid;}
if($songidlist!=""){
$songidliststr = " AND main.songid in(".$songidlist.")";
$songidliststrorder = "substring_index('".$songidlist."',main.songid,1),";
}

if($label!=-1){
$labelstr = " AND main.label like '%".$label."%'";}
if($singer!=-1){
$singerstr = " AND main.singer like '%".$singer."%'";}
if($lang!=-1){
$langstr = " AND main.lang=".$lang;}
if($collectionuser!=-1){
$collectionuserstr = " AND main.collectionuser like '%,".$collectionuser.",%'";}
if($orderbyid!=-1){
$orderbyidstr = " main.songid DESC,";}
if($start!=-1 ||$perpage!=-1){
$limitstr = " LIMIT ".$start.",".$perpage;}
if($lastplay!=-1){
$lastplaystr = " AND (".$_SGLOBAL['timestamp']."-`commendtotal`)/3600<0.5";
$lastplaystrorder = "commendtotal DESC,";
}
$query = $_SGLOBAL['db']->query("SELECT main.*,a.albumname as albumname, a.albumfengmian as albumfengmian, s.uid as suid, s.username as username, s.name as name, s.namestatus as namestatus, pf.mark as mark, pl.pinglunc as pinglunc, lastpl.puserid as lastpluid, lastpl.dateline as lastdateline, ls.uid as lsuid, ls.username as lsusername, ls.name as lsname, ls.namestatus as lsnamestatus FROM ".tname( "musicbox" )." as main left JOIN ".tname( "musicbox_album" )." as a ON ( main.albumid = a.albumid ) left JOIN ".tname( "space" )." s ON ( main.userid = s.uid ) left JOIN (SELECT songid, sum( mark ) AS mark FROM ".tname( "musicbox_pingfen" )." GROUP BY songid) pf ON ( main.songid = pf.songid ) left JOIN (SELECT songid, COUNT( id ) as pinglunc FROM ".tname( "musicbox_pinglun" )." GROUP BY songid) pl ON ( main.songid = pl.songid ) left JOIN (SELECT songid,puserid,MAX(dateline) as dateline FROM (SELECT * FROM ".tname( "musicbox_pingfen" )." WHERE mark=5 ORDER BY id DESC) lastpl1 GROUP BY songid) lastpl ON ( main.songid = lastpl.songid ) left JOIN ".tname( "space" )." ls ON ( lastpl.puserid = ls.uid ) WHERE 1=1  $songnamestr $useridstr $albumidstr $songidstr $songidliststr $labelstr $singerstr $langstr $collectionuserstr $lastplaystr ORDER BY  $orderbylasttime $orderbyidstr $lastplaystrorder $songidliststrorder lastdateline DESC,mark DESC,main.songid DESC  $limitstr");
if($songname==""&$alllist==-1){
while ($value = $_SGLOBAL['db']->fetch_array($query)) {
realname_set( $value['suid'], $value['username'], $value['name'], $value['namestatus'] );
realname_set( $value['lsuid'], $value['lsusername'], $value['lsname'], $value['lsnamestatus'] );
$musiclistarr[] = $value;
++$resultcount;
}
if ( $resultcount != 0 )
{
$musiclistarr[0]['returncount'] = $resultcount;
}
return $musiclistarr;

}elseif($songname!=""&$alllist==2){

while ($value = $_SGLOBAL['db']->fetch_array($query)) {
realname_set( $value['suid'], $value['username'], $value['name'], $value['namestatus'] );
realname_set( $value['lsuid'], $value['lsusername'], $value['lsname'], $value['lsnamestatus'] );
$musiclistarr[] = $value;
++$resultcount;
}
if ( $resultcount != 0 )
{
$musiclistarr[0]['returncount'] = $resultcount;
}
return $musiclistarr;
}else{
while ($value = $_SGLOBAL['db']->fetch_array($query)) 
{
$songnames .= "'".$value['songname']."',";
}
return $songnames;
}
}


function getmusicguestnotes($type=-1,$id=-1,$id0s=-1,$start=-1,$perpage=-1) {
global $_SGLOBAL,$_SC;
$datasqlstr = array();
$resultlist = array();
$resultcount = 0;
switch ($type) {
case 0:
$datasqlstr['id'] = $id;

$query = $_SGLOBAL['db']->query("SELECT main.*,s.username,s.name,s.namestatus FROM ".tname( "musicbox_pingfen" )." main left JOIN ".tname( "space" )." s on ( main.puserid=s.uid ) WHERE songid='$id' ORDER BY id DESC");
while ($value = $_SGLOBAL['db']->fetch_array($query)) {
realname_set($value['puserid'],$value['username'],$value['name'],$value['namestatus']);
$resultlist[] = $value;
}
break;
case 1:
$datasqlstr['id'] = $id;
$datasqlstr['start'] = $start;
$datasqlstr['perpage'] = $perpage;
$query = $_SGLOBAL['db']->query("SELECT main.*,s.username,s.name,s.namestatus FROM ".tname( "musicbox_pinglun" )." main left JOIN ".tname( "space" )." s on ( main.puserid=s.uid ) WHERE songid='$id' ORDER BY id DESC LIMIT $start,$perpage");
while ($value = $_SGLOBAL['db']->fetch_array($query)) {
realname_set($value['puserid'],$value['username'],$value['name'],$value['namestatus']);
$resultlist[] = $value;
$resultcount++;
}
if($resultcount!=0) $resultlist[0]['returncount'] = $resultcount;
break;
case 2:
$datasqlstr['id'] = $id;
$datasqlstr['id0s'] = $id0s;
$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname( "musicbox_mybox" )." WHERE boxuserid=$id AND boxsongid=$id0s LIMIT 1");
$resultlist = $_SGLOBAL['db']->fetch_array($query);
break;
case 3:
$datasqlstr['id'] = $id;
$datasqlstr['id0s'] = $id0s;
$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname( "musicbox" )." where songid=$id AND collectionuser like '%,$id0s,%' LIMIT 1");
$resultlist = $_SGLOBAL['db']->fetch_array($query);
break;
default:
break;
}
return $resultlist;
}

function getalbumdefaultid($userid) {
global $_SGLOBAL,$_SC;
$datasqlstr = array();
$datasqlstr['userid'] = $userid;
$defaultid = array();
$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname( "musicbox_album" )." WHERE userid=$userid and defaultalbumid=1");
$defaultid = $_SGLOBAL['db']->fetch_array($query);
return $defaultid['albumid'];
}

function getalbuminfo($albumid) {
global $_SGLOBAL,$_SC;
$datasqlstr = array();
$datasqlstr['albumid'] = $albumid;
$thealbum = array();
$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname( "musicbox_album" )." WHERE albumid=$albumid");
$thealbum = $_SGLOBAL['db']->fetch_array($query);
return $thealbum;
}

function getmusicalbumcount($userid,$greatalbum=-1) {
global $_SGLOBAL,$_SC,$client;
$datasqlstr = array();
$datasqlstr['userid'] = $userid;
if($greatalbum==-1){
$albumcount = array();
$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname( "musicbox_album" )." WHERE userid=$userid");
$albumcount = $_SGLOBAL['db']->fetch_array($query);
if(empty($albumcount)){
return 0;
}else{
return 1;
}
}else{
$setarr1 = array(
'userid'=>$userid,
'albumname'=>$_SGLOBAL['supe_username']."的专辑",
'albumfengmian'=>"images/album.gif",
'defaultalbumid'=>1,
'dataline'=>$_SGLOBAL['timestamp'],
);
inserttable('musicbox_album',$setarr1,0);
}
}


function getmusicalbum($userid=-1,$albumid=-1,$type=0,$albumnoid=-1,$cisnotnull=-1) {
global $_SGLOBAL,$_SC;
$datasqlstr = array();
$musicalbumlistarr = array();
if($userid!=-1){
$useridstr = " AND main.userid=".$userid;}
if($albumid!=-1){
$albumidstr = " AND main.albumid=".$albumid;}
if($albumnoid!=-1){
$albumnoidstr = " AND main.albumid<>".$albumnoid;}
if($cisnotnull!=-1){
$cisnotnullstr = " AND m.mcount<>0";}
$datasqlstr['useridstr'] = $useridstr;
$datasqlstr['albumidstr'] = $albumidstr;
$datasqlstr['albumnoidstr'] = $albumnoidstr;
$datasqlstr['cisnotnullstr'] = $cisnotnullstr;
$query = $_SGLOBAL['db']->query("SELECT main.*,m.mcount,s.uid,s.username,s.name,s.namestatus FROM ".tname( "musicbox_album" )." main left JOIN (SELECT albumid,COUNT(songid) as mcount FROM ".tname( "musicbox" )." GROUP BY albumid) m ON ( main.albumid=m.albumid ) left JOIN ".tname( "space" )." s ON (main.userid=s.uid) WHERE 1=1 $useridstr $albumidstr $albumnoidstr $cisnotnullstr ORDER BY albumid DESC");
if($type==0){
$musicalbumlistarr = $_SGLOBAL['db']->fetch_array($query);
}else{
while ($value = $_SGLOBAL['db']->fetch_array($query)) {
realname_set($value['uid'],$value['username'],$value['name'],$value['namestatus']);
$value['albumname'] = getstr($value['albumname'],12,-1,-1,-1);
$musicalbumlistarr[] = $value;
}
}
return $musicalbumlistarr;
}


function pagecommender($commender){
global $_SGLOBAL,$_SC;
$datasqlstr = array();
$datasqlstr['commender'] = $commender;
$query = $_SGLOBAL['db']->query("SELECT main.userid,COUNT(main.songid) as songcount,SUM(main.playtotal) as songplay,album.albumcount,s.uid,s.username,s.name,s.namestatus FROM ".tname( "musicbox" )." main left JOIN (SELECT userid,COUNT(albumid) as albumcount FROM ".tname( "musicbox_album" )." WHERE userid=$commender GROUP BY userid ) album ON (main.userid=album.userid) left JOIN ".tname( "space" )." s ON (main.userid=s.uid) WHERE main.userid=$commender GROUP BY main.userid");
return $_SGLOBAL['db']->fetch_array($query);
}

function musicpeople($type=-1,$id=-1,$limit=-1) {
global $_SGLOBAL,$_SC;
$datasqlstr = array();
$musicpeoplelist = array();
if($type==1) {
$datalimit = $limit==-1?3:$limit;
$datasqlstr['datalimit'] = $datalimit;
$query = $_SGLOBAL['db']->query("SELECT main.userid,COUNT(main.songid) as songcount,SUM(main.playtotal) as songplay,album.albumcount,s.uid,s.username,s.name,s.namestatus FROM ".tname( "musicbox" )." main left JOIN (SELECT userid,COUNT(albumid) as albumcount FROM ".tname( "musicbox_album" )." GROUP BY userid) album ON (main.userid=album.userid) left JOIN ".tname( "space" )." s ON (main.userid=s.uid) GROUP BY main.userid ORDER BY songcount DESC LIMIT 0,$datalimit");
while ($value = $_SGLOBAL['db']->fetch_array($query)) {
realname_set($value['uid'],$value['username'],$value['name'],$value['namestatus']);
$musicpeoplelist[] = $value;
}
}

if($type==2) {
$datalimit = $limit==-1?0:$limit;
$collectionusertemp = array();
$collectionuserstr = "";
$datasqlstr['id'] = $id;
$query = $_SGLOBAL['db']->query("SELECT collectionuser FROM ".tname( "musicbox" )." WHERE userid=$id");
while ($value = $_SGLOBAL['db']->fetch_array($query)) {
$collectionuserstr.=$value['collectionuser'];
}

if(!empty($collectionuserstr) &&$collectionuserstr!=","){
$collectionusertemp=explode(",",$collectionuserstr);
$collectionusertemp=array_unique($collectionusertemp);
$collectionusertemp=array_filter($collectionusertemp);
$collectionusertemp = array_slice($collectionusertemp,0,$datalimit);
$collectionuserstr=implode(",",$collectionusertemp);
$datasqlstr['collectionuserstr'] = $collectionuserstr;
$query = $_SGLOBAL['db']->query("SELECT uid,username,name,namestatus FROM ".tname( "space" )." WHERE uid in ($collectionuserstr) ORDER BY uid DESC");
while ($value = $_SGLOBAL['db']->fetch_array($query)) {
realname_set($value['uid'],$value['username'],$value['name'],$value['namestatus']);
$musicpeoplelist[] = $value;
}
}
}

if($type==3) {
$datalimit = $limit==-1?5:$limit;
$datasqlstr['datalimit'] = $datalimit;
$query = $_SGLOBAL['db']->query("SELECT am.albumid,SUM(am.songmark) as amark,a.albumname,a.albumfengmian,a.userid,s.uid,s.username,s.name,s.namestatus,mi.songc FROM (SELECT main.songid,SUM(main.mark) as songmark,m.albumid FROM ".tname( "musicbox_pingfen" )." main left JOIN ".tname( "musicbox" )." m ON (main.songid=m.songid) GROUP BY main.songid) am left JOIN ".tname( "musicbox_album" )." a ON (am.albumid=a.albumid) left JOIN ".tname( "space" )." s ON (a.userid=s.uid) left JOIN (SELECT albumid,COUNT(songid) as songc FROM ".tname( "musicbox" )." GROUP BY albumid) mi ON (am.albumid=mi.albumid) WHERE am.albumid is not NULL AND am.albumid<>0 GROUP BY am.albumid ORDER BY amark DESC LIMIT 0,$datalimit");
while ($value = $_SGLOBAL['db']->fetch_array($query)) {
realname_set($value['uid'],$value['username'],$value['name'],$value['namestatus']);
$value['albumname'] = getstr($value['albumname'],12,-1,-1,-1);
$musicpeoplelist[] = $value;
}
}
return $musicpeoplelist;
}


function getmybox($songlist) {
global $_SGLOBAL,$_SC;
$datasqlstr = array();
$playmusiclist = array();
$datasqlstr['songlist'] = $songlist;
$supe_uid = $_SGLOBAL['supe_uid'];
if($songlist != "myboxlist"){
$query = $_SGLOBAL['db']->query("SELECT main.*,a.albumname as albumname, a.albumfengmian as albumfengmian, s.uid, s.username, s.name, s.namestatus FROM ".tname( "musicbox" )." as main left JOIN ".tname( "musicbox_album" )." as a ON ( main.albumid = a.albumid ) left JOIN ".tname( "space" )." as s ON (main.userid = s.uid) WHERE main.songid in($songlist) ORDER BY main.songid DESC");
}else{

$query = $_SGLOBAL['db']->query("SELECT main.*,a.albumname as albumname, a.albumfengmian as albumfengmian, box.boxorder, box.id as boxid, box.page, s.uid, s.username, s.name, s.namestatus FROM ".tname( "musicbox" )." as main left JOIN ".tname( "musicbox_album" )." as a ON ( main.albumid = a.albumid ) left JOIN ".tname( "space" )." as s ON (main.userid = s.uid) left JOIN ".tname( "musicbox_mybox" )." as box ON (main.songid=box.boxsongid and box.boxuserid='$supe_uid') WHERE main.songid in(SELECT boxsongid FROM ".tname( "musicbox_mybox" )." WHERE boxuserid='$supe_uid') ORDER BY box.boxorder ASC");
}
while ($value = $_SGLOBAL['db']->fetch_array($query)) {
realname_set($value['uid'],$value['username'],$value['name'],$value['namestatus']);
if($value['upload']=="2"){
$value['songurl']= server_getdiskurl($value['songurl'],$value['songid']);
}
$playmusiclist[] = $value;
}

return $playmusiclist;

}

function setmusicbg($id,$type=-1) {
global $_SGLOBAL,$_SC,$client;
$datasqlstr = array();
$datasqlstr['id'] = $id;
$setarr = array(
'page'=>'0',
);

$setarr1 = array(
'page'=>'1',
);

if($type==2){
$_SGLOBAL['db']->query("DELETE FROM ".tname( "musicbox_mybox" )." WHERE id=$id");
}else{
updatetable('musicbox_mybox',$setarr,array('boxuserid'=>$_SGLOBAL['supe_uid']));
if($type==-1){
updatetable('musicbox_mybox',$setarr1,array('id'=>$id));
}
}
}

function ordermusic($llist) {
global $_SGLOBAL;
for($i = 0;$i <count($llist)-1;$i++){
updatetable('musicbox_mybox',array('boxorder'=>$i),array('id'=>$llist[$i]));
}
}

function getmusicbg($uid) {
global $_SGLOBAL,$_SC;
$datasqlstr = array();
$firstsong = array();
$bgmusiclist = array();
$datasqlstr['uid'] = $uid;
$query = $_SGLOBAL['db']->query("SELECT main.*, mu.*, a.albumname as albumname, a.albumfengmian as albumfengmian FROM ".tname( "musicbox_mybox" )." as main left JOIN ".tname( "musicbox" )." as mu ON( main.boxsongid=mu.songid ) left JOIN ".tname( "musicbox_album" )." as a ON ( mu.albumid = a.albumid ) WHERE main.page=1 AND main.boxuserid=$uid");
while ($value = $_SGLOBAL['db']->fetch_array($query)) {
if($value['upload']=="2"){
$value['songurl']=server_getdiskurl($value['songurl'],$value['songid']);
}
$firstsong[] = $value;
}

if(!empty($firstsong)){
$query1 = $_SGLOBAL['db']->query("SELECT main.*, mu.*, a.albumname as albumname, a.albumfengmian as albumfengmian FROM ".tname( "musicbox_mybox" )." as main left JOIN ".tname( "musicbox" )." as mu ON( main.boxsongid=mu.songid ) left JOIN ".tname( "musicbox_album" )." as a ON ( mu.albumid = a.albumid ) WHERE main.page<>1 AND main.boxuserid=$uid");
while ($value = $_SGLOBAL['db']->fetch_array($query1)) {
if($value['upload']=="2"){
$value['songurl']=server_getdiskurl($value['songurl'],$value['songid']);
}
$bgmusiclist[] = $value;
}
$resultarray = array_merge($firstsong,$bgmusiclist);
return $resultarray;
}
}

function insertnewmusic($setarr,$filesize,$spacesize) {
global $_SGLOBAL,$_SC;
$datasqlstr = array();
$newsongid = inserttable('musicbox',$setarr,1);
if(intval($filesize)!=0){
$datasqlstr['filesize'] = $filesize;
$datasqlstr['spacesize'] = $spacesize;
$filesizes=$filesize+$spacesize;
$supe_uid = $_SGLOBAL['supe_uid'];
$_SGLOBAL['db']->query("UPDATE ".tname( "space" )." SET attachsize=$filesizes WHERE uid=$supe_uid");
}

include_once('./source/function_cp.php');
$icon = 'musicbox';
$title_template = '{actor} 上传了音乐 <a href="space.php?uid='.$_SGLOBAL['supe_uid'].'&do=musicbox&mview=mpage&mid='.$newsongid.'" target="_blank">'.getstr($setarr['songname'],100,1,1,1).'</a>';
feed_add($icon,$title_template);
showmessage('m_commend_success',"space.php?uid=".$_SGLOBAL['supe_uid']."&do=musicbox&mview=mpage&mid=".$newsongid);
}

function inserttodisk($setarr) {
global $_SGLOBAL;
inserttable('musicbox_disk',$setarr,0);
}

function getmydiskfilelist($userid) {
global $_SGLOBAL,$_SC,$client;
$datasqlstr = array();
$mydiskfilelist = array();
$datasqlstr['userid'] = $userid;
$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname( "musicbox_disk" )." WHERE userid=$userid ORDER BY diskid DESC");
while ($value = $_SGLOBAL['db']->fetch_array($query)) {
$mydiskfilelist[] = $value;
}
return $mydiskfilelist;
}
function updatemusic($setarr,$id) {
updatetable('musicbox',$setarr,array('songid'=>$id));
showmessage('m_commendedit_success',"space.php?uid=".$_SGLOBAL['supe_uid']."&do=musicbox&mview=mpage&mid=".$id);
}

function insertnewalbum($setarr1,$spaceuid) {
$newalbumid = inserttable('musicbox_album',$setarr1,1);
include_once('./source/function_cp.php');
$icon = 'musicbox';
$title_template = '{actor} 创建了音乐专辑 <a href="space.php?uid='.$spaceuid.'&do=musicbox&mview=malbum&albumid='.$newalbumid.'&userspace='.$spaceuid.'" target="_blank">《'.getstr($setarr1['albumname'],100,1,1,1).'》</a>';
feed_add($icon,$title_template);
showmessage('m_newmymusicalbum_success');
}

function updatealbum($setarr1,$albumid) {
updatetable('musicbox_album',$setarr1,array('albumid'=>$albumid));
showmessage('do_success');
}

function getmusiclabellist($llist) {
$labellist = "";
$llist = array_filter(explode(",",$llist));
for($i = 0;$i <= count($llist)-1;$i++){
if(!empty($llist[$i])) $labellist=$labellist.'<a href="space.php?uid=0&do=musicbox&mview=alllist&order=p&language=a&search='.urlencode($llist[$i]).'" target="_blank">'.$llist[$i].'</a>,&nbsp;';
}
return $labellist;
}

function getalink($arr=-1,$mtype=-1,$name=-1){
$arr1 = array();
$arr1[25][1] = "流行歌曲";
$arr1[25][2] = "舞曲";
$arr1[25][3] = "翻唱";
$arr1[25][4] = "怀旧";
$arr1[25][5] = "最新单曲";
$arr1[25][6] = "hip-hop";
$arr1[25][7] = "RandB";
$arr1[25][8] = "RAP";
$arr1[25][9] = "轻音乐";
$arr1[25][10] = "电影原声";
$arr1[25][11] = "动漫原声";
$arr1[25][12] = "HOUSE";
$arr1[25][13] = "迷幻";
$arr1[25][14] = "电子";
$arr1[25][15] = "爵士";
$arr1[25][16] = "古典乐";
$arr1[25][17] = "交响乐";
$arr1[25][18] = "朋克";
$arr1[25][19] = "民谣";
$arr1[25][20] = "金属";
$arr1[25][21] = "摇滚";
$arr1[25][22] = "乡村";
$arr1[25][23] = "歌特";
$arr1[25][24] = "其他";
if($name==-1){
if(empty($mtype)){
$arr1['0']=" style='font-weight:bold;'";
}else{
$arr1[$mtype]=" style='font-weight:bold;'";
}
return $arr1;
}else{
for($i=1;$i<25;$i++){
if($name==$arr1[25][$i]) $smtype=$i;
}
return $smtype;
}
}

function imgageshow($link,$imgcort) {
$size = GetImageSize($link);
$width = $size[0];
$height = $size[1];
if($imgcort!=""){
if($width>$imgcort) {
$temp=$imgcort/$width;
$width=$imgcort;
$height=$height*$temp;
}else{
$width=$width;
$height=$height;
}
}else{
$width = $width;
$height = $height;
}
return "<img src='".$link."' width='".$width."' height='".$height."'>";
}

function getlastimg($imgsrc){
if(strpos($imgsrc,".thumb")){
$imgresult = imgageshow(substr($imgsrc,0,-10),150);
}else{
$imgresult = "<img src='".$imgsrc."' />";
}
return $imgresult;
}

function getdigestlinkurl($oldurl){
global $_SGLOBAL;
if(empty($oldurl)){
$posturl = "http://www.rayfile.com/zh-cn/files/678fcc51-9ea4-11de-8490-0014221b798a/";
}else{
$posturl = $oldurl;
}
$ft1["title"]["begin"]=$posturl;
$ft1["title"]["end"]='/"></a>';
$th1["title"]["php100"]="";
$rstemp=pick($posturl,$ft1,$th1);
return $posturl.$rstemp["title"];
}

function getdigest(){
global $_SGLOBAL;
$musicboxdate = array();
$configfile = S_ROOT.'./musicboxdate.txt';
$tempdatesrr = sreadfile($configfile);
$musicboxdate = explode("|",$tempdatesrr);
if((intval($_SGLOBAL['timestamp'])-intval($musicboxdate[1]))/3600<0.5 &&!empty($musicboxdate[0])){
return $musicboxdate[0];
}else{
$newurl = getdigestlinkurl("http://www.rayfile.com/zh-cn/files/678fcc51-9ea4-11de-8490-0014221b798a/");
$ft1["title"]["begin"]="ROXCDNKEY', '";
$ft1["title"]["end"]="', 86400";
$th1["title"]["php100"]="";
$rs=pick($newurl,$ft1,$th1);
$fp111 = fopen($configfile,'w');
fwrite($fp111,trim($rs["title"]."|".$_SGLOBAL['timestamp']));
fclose($fp111);
return $rs["title"];
}
}
function setcommendmusic($songid){
$configfile = S_ROOT.'./musicboxdate_commend.txt';
$tempdatesrr = sreadfile($configfile);
$goset =0;
if($songid==0){
return $tempdatesrr;
}else{
if(!empty($tempdatesrr)){
if(!strstr($tempdatesrr,$songid)){
$newc = $tempdatesrr.",".$songid;
$goset =1;
}
}else{
$newc = $songid;
$goset =1;
}
if($goset==1){
$fp111 = fopen($configfile,'w');
fwrite($fp111,$newc);
fclose($fp111);
}
showmessage('do_success');
}
}
class UPImages {
var $annexFolder = "upimg";
var $smallFolder = "smallimg";
var $upFileType = "jpg gif png";
var $upFileMax = 1024;
var $defaultimg;
var $thetwidth;
var $thetheight;
function UPImages($imgurl,$twidth=100,$theight=100) {
$this->annexFolder = "upimg";
$this->smallFolder = "smallimg";
$this->defaultimg = $imgurl;
$this->thetwidth = $twidth;
$this->thetheight = $theight;
$this->toFile = true;
}

function upLoad($inputName) {
$imageName = time();
if(@empty($_FILES[$inputName]["name"])){
return $this->defaultimg;
}else{
$name = explode(".",$_FILES[$inputName]["name"]);
$imgCount = count($name);
$imgType = $name[$imgCount-1];
if(strpos($this->upFileType,$imgType) === false){
return $this->defaultimg;
}else{
$photo = $imageName.".".$imgType;
$uploadFile = $this->annexFolder."/".$photo;
$upFileok = move_uploaded_file($_FILES[$inputName]["tmp_name"],$uploadFile);
return $this->smallImg($photo,$this->thetwidth,$this->thetheight);
}
}
}

function getInfo($photo) {
$photo = $this->annexFolder."/".$photo;
$imageInfo = getimagesize($photo);
$imgInfo["width"] = $imageInfo[0];
$imgInfo["height"] = $imageInfo[1];
$imgInfo["type"] = $imageInfo[2];
$imgInfo["name"] = basename($photo);
return $imgInfo;
}

function smallImg($photo,$width=100,$height=100) {
$imgInfo = $this->getInfo($photo);
$photo = $this->annexFolder."/".$photo;
$newName = substr($imgInfo["name"],0,strrpos($imgInfo["name"],"."))."_thumb.jpg";
if($imgInfo["type"] == 1) {
$img = imagecreatefromgif($photo);
}elseif($imgInfo["type"] == 2) {
$img = imagecreatefromjpeg($photo);
}elseif($imgInfo["type"] == 3) {
$img = imagecreatefrompng($photo);
}else {
$img = "";
}
if(empty($img)) return False;
$width = ($width >$imgInfo["width"]) ?$imgInfo["width"] : $width;
$height = ($height >$imgInfo["height"]) ?$imgInfo["height"] : $height;
$srcW = $imgInfo["width"];
$srcH = $imgInfo["height"];
if ($srcW * $width >$srcH * $height) {
$height = round($srcH * $width / $srcW);
}else {
$width = round($srcW * $height / $srcH);
}
if (function_exists("imagecreatetruecolor")) {
$newImg = imagecreatetruecolor($width,$height);
ImageCopyResampled($newImg,$img,0,0,0,0,$width,$height,$imgInfo["width"],$imgInfo["height"]);
}else {
$newImg = imagecreate($width,$height);
ImageCopyResized($newImg,$img,0,0,0,0,$width,$height,$imgInfo["width"],$imgInfo["height"]);
}
if ($this->toFile) {
if (file_exists($this->annexFolder."/".$this->smallFolder."/".$newName)) @unlink($this->annexFolder."/".$this->smallFolder."/".$newName);
ImageJPEG($newImg,$this->annexFolder."/".$this->smallFolder."/".$newName);
return $this->annexFolder."/".$this->smallFolder."/".$newName;
}else {
ImageJPEG($newImg);
}
ImageDestroy($newImg);
ImageDestroy($img);
return $newName;
}
}
?>