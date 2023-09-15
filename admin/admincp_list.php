<?php
/*
	[UCenter Home] (C) 2007-2008 Comsenz Inc.
	$Id: admincp_blog.php 9233 2008-10-28 06:21:44Z liguode $
*/

if(!defined('IN_UCHOME') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
//权限
if(!checkperm('manageusergroup')) {
	cpmessage('no_authority_management_operation');
}

$op			=	$_GET['op'];
$type		=	array();
$query 	= 	$_SGLOBAL['db']->query("SELECT * FROM ".tname('ND_type')." where parent=0 order by sort desc");
while($value= $_SGLOBAL['db']->fetch_array($query)) {
	$query2 	= 	$_SGLOBAL['db']->query("SELECT * FROM ".tname('ND_type')." where parent=".$value['id']." order by sort desc");
	while($value2 = $_SGLOBAL['db']->fetch_array($query2)) {
		$value['child'][]	=	$value2;
	}
	$type[]	=	$value;
}
if(empty($op))
{
	$perpage 	= 	10;
	$mpurl 		= 	'admincp.php?ac=list';
	$page 		= 	empty($_GET['page'])?1:intval($_GET['page']);
	if($page<1) $page = 1;
	$start = ($page-1)*$perpage;
	ckstart($start, $perpage);
	$sql 		= 	"select nd.*,nt.name as ntt from ".tname('nd_data')." as nd left join ".tname('nd_type')." as nt on nt.id =nd.stwo order by nd.id desc";
	$query 		= 	$_SGLOBAL['db']->query($sql);
	$count		=	mysql_num_rows($query);
	$sql 		.=	" limit $start,$perpage";
	$list	=	array();
	$query 		= 	$_SGLOBAL['db']->query($sql);
	while($value = $_SGLOBAL['db']->fetch_array($query)) {
		$spaces 			= 	getspace($value['uid']);
		$value['username']	=	$spaces['username'];
		$list[]	=	$value;
	}
}
if($op=="ND_search")
{
	$keyword		=	$_POST['keyword']?$_POST['keyword']:urldecode($_GET['keyword']);
	
	$sql			=	"";
	if(!empty($types)&&!empty($keyword))
	{
		$sql	=	"select nd.*,nt.name as ntt from ".tname('nd_data')." as nd left join ".tname('nd_type')." as nt on nt.id =nd.type where nd.title like '%".$keyword."%' order by nd.id desc";
		$perpage 	= 	10;
		$mpurl 		= 	'admincp.php?ac=list&op=ND_search&keyword='.urlencode($keyword).'&types='.urlencode($types).'&typelist='.urlencode($typelist);
		$page 		= 	empty($_GET['page'])?1:intval($_GET['page']);
		if($page<1) $page = 1;
		$start = ($page-1)*$perpage;
		ckstart($start, $perpage);
		$query 		= 	$_SGLOBAL['db']->query($sql);
		$count		=	mysql_num_rows($query);
		$sql 		.=	" limit $start,$perpage";
		$list	=	array();
		$query 		= 	$_SGLOBAL['db']->query($sql);
		while($value = $_SGLOBAL['db']->fetch_array($query)) {
			$spaces 			= 	getspace($value['uid']);
			$value['username']	=	$spaces['username'];
			$list[]	=	$value;
		}
	}
	else
	{
		showmessage('请填写相关参数！','admincp.php?ac=list',3);
	}
	
}
if($op=="edit")
{
	if (isset($_FILES["Filedata"]) && is_uploaded_file($_FILES["Filedata"]["tmp_name"]) && $_FILES["Filedata"]["error"] == 0) 
	{
		$file 				= 	$_FILES["Filedata"]; 
		$shijian			=	date("Y").date("m").date("d");
		$destination_folder	=	str_replace("\\","/",dirname(__FILE__)).'/ND_data/'.$shijian.'/'; 
		$destination_folder	=	eregi_replace('/admin','',$destination_folder);			
		$array_dir			=	explode("/",$destination_folder);
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
		exit();
	}

	
	if(isset($_POST['formhash'])){
		$sql 	= 	"select * from ".tname('nd_data')."  where id=".$_POST['fileid'];
		$query 	= 	$_SGLOBAL['db']->query($sql);
		$value	= 	$_SGLOBAL['db']->fetch_array($query);
		$isjifen	=	$value['state'];
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
		$data 		= 	array(
			"uid"		=> 	$_POST['fileuid'],
			"nouser"	=> 	$_POST['nouser'],
			"title"		=> 	$title,
			"type" 		=> 	$type,
			"stwo"		=>	$stwo,
			"jifen"		=>	$jifen,
			"publicall"	=>	$publicall,
			"datetime" 	=>	$times,
			"jieshao" 	=>	$jieshao,
			"datasrc" 	=>	$datasrc,
			"namesrc"	=> 	$namesrc,
			"size"		=>	$size,
			"state"		=>	$_POST['shenhe']
		);
		updatetable('nd_data', $data, array('id'=>$_POST['fileid']));
		
		if($_POST['shenhe']>0&&$isjifen<=0)
		{
			$reward			=	getreward('ND_upload', 0);
			$_SGLOBAL['db']->query("UPDATE ".tname('space')." SET updatetime=".$_SGLOBAL['timestamp'].", credit=credit+".$reward['credit'].", experience=experience+".$reward['experience']." WHERE uid=".$_POST['fileuid']);
	
			$setarr['icon'] 			=	'share';
			$setarr['id'] 				=	$_POST['fileid'];
			$setarr['idtype'] 			=	'ND_upload';
			$setarr['uid'] 				=	$_POST['fileuid'];
			$spaces						=	getspace($_POST['fileuid']);
			$setarr['username'] 		=	$spaces['username'];
			$setarr['dateline'] 		= 	time();
			$setarr['target_ids'] 		= 	'';
			$setarr['friend'] 			= 	0;
			$setarr['hot'] 				= 	0;
					
			$url 						= 	"ND_upload.php?do=info&id=".$_POST['fileid'];
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
					$query 	= $_SGLOBAL['db']->query("SELECT feedid FROM ".tname('feed')." WHERE id='".$_POST['fileid']."' AND idtype='ND_upload'");
					$feedid = $_SGLOBAL['db']->result($query, 0);
				}
				if($feedid) {
					updatetable('feed', $setarr, array('feedid'=>$feedid));
				} else {
					inserttable('feed', $setarr);
				}		
			}
		}
		$keyword		=	explode(',',$keyword);
		if(count($keyword)>0)
		{
			for($i=0;$i<count($keyword);$i++)
			{
				if(!empty($keyword[$i]))
				{
				$query 		= 	$_SGLOBAL['db']->query("select id from ".tname('nd_keywords ')." where ND_id=".$_POST['fileid']." and name='".$keyword[$i]."'");
				if($valueND= 	$_SGLOBAL['db']->fetch_array($query))
				{	
					break;
				}
				else
				{
					$data 		= 	array(
						"uid"		=> 	$_POST['fileuid'],
						"ND_id"		=> 	$_POST['fileid'],
						"name" 		=> 	$keyword[$i]
					);
					inserttable("nd_keywords",$data,1 );
				}
				}
			}
		}
		showmessage('编辑成功！','admincp.php?ac=list&op=edit&id='.$_POST['fileid'],3);
	}
	if(isset($_GET['id'])){
		if(!empty($_GET['id']))
		{
			$id		=	intval($_GET['id']);
			include_once(S_ROOT.'./ND_upload/source/config.php');
			$sql	=	"select nd.*,nt.name as ntt from ".tname('nd_data')." as nd left join ".tname('nd_type')." as nt on nt.id =nd.stwo  where nd.id=".$id;
			$query 	= 	$_SGLOBAL['db']->query($sql);
			$valueND= 	$_SGLOBAL['db']->fetch_array($query);
			$spaces	=	getspace($valueND['uid']);
			$dates	=	date('Y-m-d',$valueND['datetime']);

			$sql	=	"select * from ".tname('nd_keywords')." where ND_id=".$id;
			$query 	= 	$_SGLOBAL['db']->query($sql);
			$tags	=	"";
			while($value = $_SGLOBAL['db']->fetch_array($query)) {
				$tags.=	$value['name'].',';
			}
			$tags	=	substr($tags,0,strlen($tags)-1);
			$filesrc=	explode(',',$valueND['datasrc']);
			$filenn	=	explode('htk200908251449',$valueND['namesrc']);	
		}
		else
		{
			showmessage('路径错误！','admincp.php?ac=list',3);
		}
		$sql 		= 	"select * from  ".tname('nd_config');
		$query 		= 	$_SGLOBAL['db']->query($sql);
		$ND_config	= 	$_SGLOBAL['db']->fetch_array($query);
		$ND_filesize	=	$ND_config['filesize'];
		$ND_filetype	=	$ND_config['filetype'];
		$ND_add_module	=	1;
 		include_once template("admin/tpl/ND_edit");
		exit();
	}
}
if($op=="del"&&isset($_POST['formhash']))
{
	if(!empty($_POST['del'])){
		$id			=	$_POST['del'];
		$num		=	count($id);
		for($i=0;$i<$num;$i++)
		{
			$ids	.=	$id[$i].',';
		}
		$ids		=	substr($ids,0,strlen($ids)-1);
		$sql		=	"select * from ".tname('nd_data')." where id in (".$ids.")";
		$query 		= 	$_SGLOBAL['db']->query($sql);
		while($value= $_SGLOBAL['db']->fetch_array($query)) {
			$reward			=	getreward('ND_del', 0);
			$_SGLOBAL['db']->query("UPDATE ".tname('space')." SET updatetime=".$_SGLOBAL['timestamp'].", credit=credit-".$reward['credit'].", experience=experience-".$reward['experience']." WHERE uid=".$value['uid']);
			
			$filelist	=	$value['datasrc'];
			$filelist	=	explode(',',$filelist);
			for($i=0;$i<count($filelist)-1;$i++)
			{
				if(file_exists($filelist[$i]))
				{
					unlink($filelist[$i]);
				}
			}
			if(!empty($value['pic'])&&$value['pic']!="upload_js/forms/no_pic.JPG")
			{
				if(file_exists($value['pic']))
				{
					unlink($value['pic']);
				}
			}
		}
		$sql		=	"delete from ".tname('nd_data')." where id in (".$ids.")";
		$_SGLOBAL['db']->query($sql);
		$sql		=	"delete from ".tname('nd_keywords')." where ND_id in (".$ids.")";
		$_SGLOBAL['db']->query($sql);
		$sql		=	"delete from ".tname('nd_comment')." where nd_id in (".$ids.")";
		$_SGLOBAL['db']->query($sql);
		cpmessage('删除成功!', 'admincp.php?ac=list');
	}else{

        cpmessage('请选择要删除的对象.......!', 'admincp.php?ac=list');
	}
}
if($op=="delfile")
{
	if(!empty($_GET['id'])){
		$id			=	$_GET['id'];
		$sql		=	"select * from ".tname('nd_data')." where id =".$id;
		$query 		= 	$_SGLOBAL['db']->query($sql);
		$value		= 	$_SGLOBAL['db']->fetch_array($query);
		$filelist	=	$value['datasrc'];
		$namelist	=	$value['namesrc'];
		$filelist	=	explode(',',$filelist);
		$namelist	=	explode('htk200908251449',$namelist);
		if(file_exists($filelist[$_GET['n']]))
		{
			unlink($filelist[$_GET['n']]);
		}
		$str		=	str_replace($filelist[$_GET['n']].',',"",$value['datasrc']);
		$name		=	str_replace($namelist[$_GET['n']].'htk200908251449',"",$value['namesrc']);
		$sql		=	"update ".tname('nd_data')." set datasrc='".$str."',namesrc='".$name."' where id =".$id;
		$_SGLOBAL['db']->query($sql);
		cpmessage('删除成功!', 'admincp.php?ac=list&op=edit&id='.$id);
	}else{
        cpmessage('请选择要删除的对象.......!', 'admincp.php?ac=list&op=edit&id='.$id);
	}
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