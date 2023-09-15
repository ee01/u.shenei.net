<?php
if(!defined('IN_UCHOME')) {
	exit('Access Denied');
}
$sql 		= 	"select * from  ".tname('nd_config');
$query 		= 	$_SGLOBAL['db']->query($sql);
$config		= 	$_SGLOBAL['db']->fetch_array($query);
if($config['realuserup']&&empty($space['name']))
{
	showmessage('只有实名会员才能上传','cp.php?ac=profile',3);
}
if($config['upjifen']>$space['credit'])
{
	showmessage('你的积分低于'.$config['upjifen'].',不允许上传资源!','ND_upload.php',3);
}
if (!isset($_POST["formhash"]) && !isset($_FILES["Filedata"])) {
	$type		=	array();
	$query 	= 	$_SGLOBAL['db']->query("SELECT * FROM ".tname('ND_type')." where parent=0 order by sort desc");
	while($value= $_SGLOBAL['db']->fetch_array($query)) {
		$query2 	= 	$_SGLOBAL['db']->query("SELECT * FROM ".tname('ND_type')." where parent=".$value['id']." order by sort desc");
		while($value2 = $_SGLOBAL['db']->fetch_array($query2)) {
			$value['child'][]	=	$value2;
		}
		$type[]	=	$value;
	}
	$times		=	date('Y-m-d',time()+3600*8);
	$tlist		=	"";
	$typelist	=	explode(';',$config['filetype']);
	for($i=0;$i<count($typelist);$i++)
	{
		$tlist	.=	substr($typelist[$i],2,strlen($typelist[$i])).'/';
	}
	$tlist		=	substr($tlist,0,strlen($tlist)-1);
	$count1		=	0;
	$sql 		= 	"select * from ".tname('nd_data')." where state>0  order by id desc";
	$query 		= 	$_SGLOBAL['db']->query($sql);
	$count1		=	mysql_num_rows($query);
	
	$count2		=	0;
	$sql 		= 	"select friend from ".tname('spacefield')." where uid=".$_SGLOBAL['supe_uid'];
	$query 		= 	$_SGLOBAL['db']->query($sql);
	$value		= 	$_SGLOBAL['db']->fetch_array($query);
	$friends	=	$value['friend'];
	if(!empty($friends))
	{
		$sql 		= 	"select * from ".tname('nd_data')." where state>0 and uid in (".$friends.") and publicall<=2 order by id desc";
		$query 		= 	$_SGLOBAL['db']->query($sql);
		$count2		=	mysql_num_rows($query);
	}
	
	$count3		=	0;
	$countdown	=	0;
	$huodefen	=	0;
	$mynd_id	=	"";
	$sql 		= 	"select * from ".tname('nd_data')."  where uid=".$_SGLOBAL['supe_uid']." order by id desc";
	$query 		= 	$_SGLOBAL['db']->query($sql);
	while($value= $_SGLOBAL['db']->fetch_array($query)) {
		$count3++;
		$countdown	=	$countdown+$value['downnum'];
		$mynd_id=	$mynd_id.$value['id'].',';	//Modify By 01
	}
	$mynd_id	=	substr($mynd_id,0,strlen($mynd_id)-1);
	if(!empty($mynd_id))
	{
		$sql 		= 	"select sum(jifen) as j from ".tname('nd_down')."  where nd_id in (".$mynd_id.") order by id desc";
		$query 		= 	$_SGLOBAL['db']->query($sql);
		$value		= 	$_SGLOBAL['db']->fetch_array($query);
		$huodefen	=	$value['j'];
	}
	
	$sql 		= 	"select sum(jifen) as d,count(id) as dn from ".tname('nd_down')."  where uid=".$_SGLOBAL['supe_uid']." group by uid order by id desc";
	$query 		= 	$_SGLOBAL['db']->query($sql);
	$value		= 	$_SGLOBAL['db']->fetch_array($query);
	$xiazai		=	$value['dn'];
	$diufen		=	$value['d'];
	$parater	=	"";
	if (isset($_GET["op"]) && isset($_GET["id"])&&$_GET["op"]=="edit") {
		
		$query 	= 	$_SGLOBAL['db']->query("SELECT nd.*,nt.name FROM ".tname('nd_data')." as nd left join ".tname('nd_type')." as nt on nt.id=nd.stwo where nd.id=".$_GET["id"]);
		$infond	=	$_SGLOBAL['db']->fetch_array($query);
		$times	=	date('Y-m-d',$infond['datetime']);
		$ND_keyword=	"";
		$sql 	= 	"select * from ".tname('nd_keywords')."  where ND_id=".$_GET["id"]." order by id desc";
		$query 	= 	$_SGLOBAL['db']->query($sql);
		while($value= $_SGLOBAL['db']->fetch_array($query)) {
			$ND_keyword.=	$value['name'].',';
		}
		$ND_keyword	=	substr($ND_keyword,0,strlen($ND_keyword)-1);
		$filelist	=	explode('htk200908251449',$infond['namesrc']);
		$filesrc	=	explode(',',$infond['datasrc']);
		$parater		=	"&op=edit";
	}
	$ND_filesize	=	$config['filesize'];
	$ND_filetype	=	$config['filetype'];
	$ND_add_module	=	1;
	include_once template("ND_upload/template/add");
}
if (isset($_FILES["Filedata"]) && is_uploaded_file($_FILES["Filedata"]["tmp_name"]) && $_FILES["Filedata"]["error"] == 0) 
{
	$file 				= 	$_FILES["Filedata"]; 
	$shijian			=	date("Y").date("m").date("d");
	$destination_folder	=	str_replace("\\","/",dirname(__FILE__)).'/ND_data/'.$shijian.'/'; 
	$destination_folder	=	eregi_replace('/ND_upload/source','',$destination_folder);			
	$array_dir=explode("/",$destination_folder);
 	for($i=0;$i<count($array_dir);$i++){
  		$path .= $array_dir[$i]."/";
 	 	if(!file_exists($path)){
   			mkdir($path);
  		}
  	}
	$pinfo		=	pathinfo($file["name"]); 
    $ftype		=	$pinfo['extension'];
	$shijian2	=	dd2char($_SGLOBAL['supe_uid']."_".date("YmdHis",time()).mt_rand(1000,9999));
	
	$uploadFile	= 	'ND_data/'.$shijian.'/'.$shijian2.".".$ftype;
	
	move_uploaded_file($file['tmp_name'], $uploadFile); 
	chmod($uploadFile, 0777); 
	echo $uploadFile;
}
if (isset($_POST["formhash"])&&!empty($_GET["op"])) {
	$title		=	uc_stripslashes($_POST['title']);
	$type		=	$_POST['type'];
	$stwo		=	$_POST['stwo'];
	$jifen		=	intval($_POST['jifen']);
	$publicall	=	$_POST['publicall'];
	$datetime	=	$_POST['datetime'];
	$datetime	=	explode('-',$datetime);
	$times		=	mktime(0,0,0,$datetime[1],$datetime[2],$datetime[0]);
	$keyword	=	uc_stripslashes($_POST['keyword']);
	$jieshao	=	uc_stripslashes($_POST['message']);
	$datasrc	=	uc_stripslashes($_POST['filesrc']);
	$namesrc	=	uc_stripslashes($_POST['filename']);
	$size		=	uc_stripslashes($_POST['filesize']);
	$state		=	1;
	$turnstr	=	"资源编辑成功";
	$types		=	explode(",",$datasrc);
	$path		=	pathinfo($types[0]);
	$geshi 		= 	$path["extension"];
	if($config['isshenhe'])
	{
		$state	=	0;
		$turnstr=	"编辑成功！等待管理员审核";
	}
	$data 		= 	array(
		"uid"		=> 	$_SGLOBAL['supe_uid'],
		"nouser"	=> 	$_POST['nouser'],
		"title"		=> 	$title,
		"type" 		=> 	$type,
		"stwo"		=>	$stwo,
		"jifen"		=>	$jifen,
		"publicall"	=>	$publicall,
		"datetime" 	=>	$times,
		"jieshao" 	=>	$jieshao,
		"datasrc" 	=>	$datasrc,
		"geshi"		=>	$geshi,
		"namesrc"	=> 	$namesrc,
		"size"		=>	$size,
		"state"		=>	$state
	);
	updatetable('nd_data', $data, array('id'=>$_POST['ndid']));
	$nid			=	$_POST['ndid'];
	$keyword		=	explode(',',$keyword);
	if(count($keyword)>0)
	{
		for($i=0;$i<count($keyword);$i++)
		{
			if(!empty($keyword[$i]))
			{
				$query 		= 	$_SGLOBAL['db']->query("select id from ".tname('nd_keywords ')." where ND_id=".$_POST['ndid']." and name='".$keyword[$i]."'");
				if($valueND= 	$_SGLOBAL['db']->fetch_array($query))
				{	
					continue;
				}
				else
				{
					$data 		= 	array(
						"uid"		=> 	$_SGLOBAL['supe_uid'],
						"ND_id"		=> 	$nid,
						"name" 		=> 	$keyword[$i]
					);
					inserttable("nd_keywords",$data,1 );
				}
			}
		}
	}				
	showmessage($turnstr,'ND_upload.php',3);
}
if (isset($_POST["formhash"])&&empty($_GET["op"])) {
	$title		=	uc_stripslashes($_POST['title']);
	$type		=	$_POST['type'];
	$stwo		=	$_POST['stwo'];
	$jifen		=	intval($_POST['jifen']);
	$publicall	=	$_POST['publicall'];
	$datetime	=	$_POST['datetime'];
	$datetime	=	explode('-',$datetime);
	$times		=	mktime(0,0,0,$datetime[1],$datetime[2],$datetime[0]);
	$keyword	=	uc_stripslashes($_POST['keyword']);
	$jieshao	=	uc_stripslashes($_POST['message']);
	$datasrc	=	uc_stripslashes($_POST['filesrc']);
	$namesrc	=	uc_stripslashes($_POST['filename']);
	$size		=	uc_stripslashes($_POST['filesize']);
	$state		=	1;
	$turnstr	=	"恭喜您!资源上传成功";
	$types		=	explode(",",$datasrc);
	$path		=	pathinfo($types[0]);
	$geshi 		= 	$path["extension"];
	if($config['isshenhe'])
	{
		$state	=	0;
		$turnstr=	"上传成功！等待管理员审核";
	}
	$data 		= 	array(
		"uid"		=> 	$_SGLOBAL['supe_uid'],
		"nouser"	=> 	$_POST['nouser'],
		"title"		=> 	$title,
		"type" 		=> 	$type,
		"stwo"		=>	$stwo,
		"jifen"		=>	$jifen,
		"publicall"	=>	$publicall,
		"datetime" 	=>	$times,
		"jieshao" 	=>	$jieshao,
		"datasrc" 	=>	$datasrc,
		"geshi"		=>	$geshi,
		"namesrc"	=> 	$namesrc,
		"size"		=>	$size,
		"state"		=>	$state
	);
	$nid			=	inserttable("nd_data",$data,1 );
	$keyword		=	explode(',',$keyword);
	if(count($keyword)>0)
	{
		for($i=0;$i<count($keyword);$i++)
		{
			if(!empty($keyword[$i]))
			{
				$data 		= 	array(
					"uid"		=> 	$_SGLOBAL['supe_uid'],
					"ND_id"		=> 	$nid,
					"name" 		=> 	$keyword[$i]
				);
				inserttable("nd_keywords",$data,1 );
			}
		}
	}
	if($config['isshenhe']==0)
	{
	$setarr['icon'] 			=	'download';
	$setarr['id'] 				=	$nid;
	$setarr['idtype'] 			=	'ND_upload';
	$setarr['uid'] 				=	$_SGLOBAL['supe_uid'];
	$setarr['username'] 		=	$space['username'];
	$setarr['dateline'] 		= 	time();
	$setarr['target_ids'] 		= 	'';
	$setarr['friend'] 			= 	0;
	$setarr['hot'] 				= 	0;		
	$url 						= 	"ND_upload.php?do=info&id=".$nid;
	$setarr['title_template'] 	= 	"{actor} 上传了新的资源";
	$setarr['body_template'] 	= 	'<b>{subject}</b><br>{summary}';
	$setarr['body_data'] 		= 	array(
		'subject' => "<a href=\"$url\">".$title."</a>",
		'summary' => getstr($jieshao, 150, 1, 1, 0, 0, -1)
	);
	if($setarr['icon']) {
		$setarr['appid'] 		= 	UC_APPID;
		$setarr['title_data'] 	= 	serialize($setarr['title_data']);//数组转化
		if($idtype != 'sid') {
				$setarr['body_data'] 	= 	serialize($setarr['body_data']);//数组转化
		}
		$setarr['hash_template'] 	= 	md5($setarr['title_template']."\t".$setarr['body_template']);//喜好hash
		$setarr['hash_data'] 		= 	md5($setarr['title_template']."\t".$setarr['title_data']."\t".$setarr['body_template']."\t".$setarr['body_data']);
		$setarr = saddslashes($setarr);
		$feedid = 0;
		if($setarr['id']) {
			$query 	= $_SGLOBAL['db']->query("SELECT feedid FROM ".tname('feed')." WHERE id='".$nid."' AND idtype='ND_upload'");
			$feedid = $_SGLOBAL['db']->result($query, 0);
		}
		if($feedid) {
			updatetable('feed', $setarr, array('feedid'=>$feedid));
		} else {
			inserttable('feed', $setarr);
		}		
	}
	}		
			
	showmessage($turnstr,'ND_upload.php',3);
}
function uc_stripslashes($string) {
	!defined('MAGIC_QUOTES_GPC') && define('MAGIC_QUOTES_GPC', get_magic_quotes_gpc());
	if(MAGIC_QUOTES_GPC) {
		return stripslashes($string);
	} else {
		return $string;
	}
}
function dd2char($ddnum)
{
	$ddnum = strval($ddnum);
	$slen = strlen($ddnum);
	$okdd = '';
	$nn = '';
	for($i=0;$i<$slen;$i++)
	{
		if(isset($ddnum[$i+1]))
		{
			$n = $ddnum[$i].$ddnum[$i+1];
			if( ($n>96 && $n<123) || ($n>64 && $n<91) )
			{
				$okdd .= chr($n);
				$i++;
			}
			else
			{
				$okdd .= $ddnum[$i];
			}
		}
		else
		{
			$okdd .= $ddnum[$i];
		}
	}
	return $okdd;
}
?>