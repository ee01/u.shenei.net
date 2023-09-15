<?php
if ( !defined( "IN_UCHOME" ) )
{
	exit( "Access Denied" );
}
$type		=	array();
$id			=	intval($_GET['id']);
$n			=	intval($_GET['n']);
if(!empty($id)&&!empty($n))
{
	$sql 		= 	"select * from  ".tname('nd_config');
	$query 		= 	$_SGLOBAL['db']->query($sql);
	$config		= 	$_SGLOBAL['db']->fetch_array($query);
	if($config['realuserdown']&&empty($space['name']))
	{
		showmessage('只有实名会员才能下载','cp.php?ac=profile',3);
	}
	if($config['downjifen']>$space['credit'])
	{
		if($logindown)
		{
		showmessage('to_login', 'do.php?ac='.$_SCONFIG['login_action']);
		}else{
		showmessage('你的积分低于'.$config['downjifen'].',不允许下载资源!','ND_upload.php?do=info&id='.$id,3);
		}
	}
	$n			=	$n-1;
	$sql		=	"select * from ".tname('nd_data')."  where  state>0 and id=".$id;
	$query 		= 	$_SGLOBAL['db']->query($sql);
	$valueND 	= 	$_SGLOBAL['db']->fetch_array($query);
	if($valueND)
	{
		$reward		=	$valueND['jifen'];
		if($space['credit']<$reward)
		{
			showmessage('积分不足!','ND_upload.php?do=info&id='.$id,3);
		}
		if($valueND['publicall']==3&&$_SGLOBAL['supe_uid']!=$valueND['uid'])
		{
			showmessage('这个资源被作者隐藏！','ND_upload.php?do=info&id='.$id,3);
		}
		if($valueND['publicall']==2&&!in_array($_SGLOBAL['supe_uid'],$space['friend'])&&$_SGLOBAL['supe_uid']!=$valueND['uid'])
		{
			showmessage('这个资源被作者隐藏！','ND_upload.php?do=info&id='.$id,3);
		}
		$list		=	explode(',',$valueND['datasrc']);
		$listtitle	=	explode('htk200908251449',$valueND['namesrc']);
		$httpfile = strstr($list[$n],"http://");	//Add By 01
		if (file_exists($list[$n]) or $httpfile)	//Modify By 01
		{
			$realname 	= 	$listtitle[$n];
			if (!$httpfile) {	//Modify By 01
				$realname 	= 	str_replace(" ","",$realname);   //去掉英文空格 
				$realname	=	str_replace(chr(32),"",$realname);  //去掉中文空格	//Modify By 01 $str		=	str_replace(chr(32),"",$realname);
				$realname	=	str_replace(chr(161),"",$realname);  //去掉中文空格
				$realname 	= 	str_replace(chr(227),"",$realname);   //去掉utf-8空格
				$realname 	= 	preg_replace("/[[:space:]]/","",$realname);
				$realname 	= 	ereg_replace("[[:space:]]","",$realname);
			}
			$query 		= 	$_SGLOBAL['db']->query("SELECT * FROM ".tname('nd_down')." WHERE nd_id=".$id." AND uid=".$_SGLOBAL['supe_uid']);
			if(!$report = $_SGLOBAL['db']->fetch_array($query)) {
			
			$_SGLOBAL['db']->query("UPDATE ".tname('space')." SET updatetime=".$_SGLOBAL['timestamp'].", credit=credit-".$reward." WHERE uid=".$_SGLOBAL['supe_uid']);
			$_SGLOBAL['db']->query("UPDATE ".tname('space')." SET updatetime=".$_SGLOBAL['timestamp'].", credit=credit+".$reward." WHERE uid=".$valueND['uid']);
			$_SGLOBAL['db']->query("UPDATE ".tname('nd_data')." SET downnum=downnum+1 WHERE id=".$id);
			$data		=	array(
				"uid"		=>	$_SGLOBAL['supe_uid'],
				"username"	=>	$space['username'],
				"nd_id"		=>	$id,
				"title"		=>	$valueND['title'],
				"jifen"		=>	$reward,
				"date"		=>	time()
			);
			inserttable('nd_down', $data);
			
			$setarr['icon'] 			=	'download';
			$setarr['id'] 				=	$id;
			$setarr['idtype'] 			=	'ND_download';
			$setarr['uid'] 				=	$_SGLOBAL['supe_uid'];
			$setarr['username'] 		=	$space['username'];
			$setarr['dateline'] 		= 	time();
			$setarr['target_ids'] 		= 	'';
			$setarr['friend'] 			= 	0;
			$setarr['hot'] 				= 	0;		
			$url 						= 	"ND_upload.php?do=info&id=".$id;
			$setarr['title_template'] 	= 	"{actor} 下载了新的资源";
			$setarr['body_template'] 	= 	'<b>{subject}</b><br>{summary}';
			$setarr['body_data'] 		= 	array(
				'subject' => "<a href=\"$url\">".$valueND['title']."</a>",
				'summary' => getstr($valueND['jieshao'], 150, 1, 1, 0, 0, -1)
			);
			if($setarr['icon']) {
				$setarr['appid'] 		= 	UC_APPID;
				$setarr['title_data'] 	= 	serialize($setarr['title_data']);//数组转化
				if($idtype != 'sid') {
					$setarr['body_data'] = 	serialize($setarr['body_data']);//数组转化
				}
				$setarr['hash_template'] 	= 	md5($setarr['title_template']."\t".$setarr['body_template']);//喜好hash
				$setarr['hash_data'] 		= 	md5($setarr['title_template']."\t".$setarr['title_data']."\t".$setarr['body_template']."\t".$setarr['body_data']);
				$setarr = saddslashes($setarr);
				$feedid = 0;
				if($setarr['id']) {
				$query 	= $_SGLOBAL['db']->query("SELECT feedid FROM ".tname('feed')." WHERE id='".$id."' AND idtype='ND_download'");
				$feedid = $_SGLOBAL['db']->result($query, 0);
				}
				if($feedid) {
					updatetable('feed', $setarr, array('feedid'=>$feedid));
				} else {
					inserttable('feed', $setarr);
				}		
			}
			}
			if ($httpfile) {	//Add By 01
				$fp=fopen_url($list[$n]); 
				header("content-type: application/octet-stream");  
				header("accept-ranges: bytes");
				header("content-length: ".filesize($list[$n]));
				header("content-disposition: attachment;"."filename= ".$realname);   //替换加号
				echo $fp;    //读取文件
			}else{	//Modify By 01
				$fp=fopen($list[$n],"r"); 
				header("content-type: application/octet-stream");  
				header("accept-ranges: bytes");
				header("content-length: ".filesize($list[$n]));
				header("content-disposition: attachment;"."filename= ".urlencode($realname));    //url编码文件名，正确显示中文名称
				header("content-disposition: attachment;"."filename= ".str_replace("+","%20",urlencode($realname)));   //替换加号
				echo fread($fp,filesize($list[$n]));    //读取文件
				fclose($fp);
			}
			exit;
		}
		else
		{
			showmessage('文件已经被删除！','ND_upload.php',3);
		}
	}
	else
	{
		showmessage('这个资源还没有通过审核！','ND_upload.php',3);
	}
}
else
{
	showmessage('路径错误！','ND_upload.php',3);
}

//Add By 01↓
function fopen_url($url) 
{ 
    if (function_exists('file_get_contents')) { 
        $file_content = @file_get_contents($url); 
    } elseif (ini_get('allow_url_fopen') && ($file = @fopen($url, 'rb'))){ 
        $i = 0; 
        while (!feof($file) && $i++ < 1000) { 
            $file_content .= strtolower(fread($file, 4096)); 
        } 
        fclose($file); 
    } elseif (function_exists('curl_init')) { 
        $curl_handle = curl_init(); 
        curl_setopt($curl_handle, CURLOPT_URL, $url); 
        curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT,2); 
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER,1); 
        curl_setopt($curl_handle, CURLOPT_FAILONERROR,1); 
        curl_setopt($curl_handle, CURLOPT_USERAGENT, 'Trackback Spam Check'); 
        $file_content = curl_exec($curl_handle); 
        curl_close($curl_handle); 
    } else { 
        $file_content = ''; 
    } 
    return $file_content; 
}
//Add By 01↑
?>
