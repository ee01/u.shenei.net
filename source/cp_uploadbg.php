<?php
/*
	[UCenter Home] (C) 2007-2008 Comsenz Inc.
	$Id: cp_upload.php 9233 2008-10-28 06:21:44Z liguode $
*/

if(!defined('IN_UCHOME')) {
	exit('Access Denied');
}
$u = $_SGLOBAL['supe_uid'];
$albumid = empty($_GET['albumid'])?0:intval($_GET['albumid']);

$ac = empty($_GET['ac'])?'':$_GET['ac'];

if($ac=='uploadbg') {
	
	//开始上传
	$albumid = $picid = 0;

	if(!checkperm('allowupload')) {
		echo "<script>";
		echo "alert(\"".cplang('not_allow_upload')."\")";
		echo "</script>";
		exit();
	}

	//上传
	$uploadfiles = pic_savebg($_FILES['attach'], $_POST['albumid'], $_POST['pic_title']);
	
	if($uploadfiles && is_array($uploadfiles)) {
                $picid = $uploadfiles['picid'];
	        $query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('pic')." WHERE picid='$picid' AND uid='$u' LIMIT 1");
	        $pic = $_SGLOBAL['db']->fetch_array($query);
                $pic['pic'] = mkpicurl($pic, 0);
		$uploadStat = 1;
	} else {
		$uploadStat = $uploadfiles;
	}
@header('Content-type: text/xml');

echo '<?xml version="1.0" encoding="gbk"?>';
echo '<root><![CDATA[<dt>';
echo '<img src="'.$pic['pic'].'" id="newbg" width="150px" />';
echo '</dt>';
echo '<dd>';
echo '<p class="upload_succeed">上传成功</p>';
echo '<p>您正在用该图片进行预览，它保存在您的相册中。</p>';
echo '</dd>]]></root>';


	exit();
	
}


//保存图片
function pic_savebg($FILE, $albumid, $title) {
	global $_SGLOBAL, $_SCONFIG, $space, $_SC;

	//允许上传类型
	$allowpictype = array('jpg','gif','png');

	//检查
	$FILE['size'] = intval($FILE['size']);
	if(empty($FILE['size']) || empty($FILE['tmp_name']) || !empty($FILE['error'])) {
		return cplang('lack_of_access_to_upload_file_size');
	}

	//判断后缀
	$fileext = fileext($FILE['name']);
	if(!in_array($fileext, $allowpictype)) {
		return cplang('only_allows_upload_file_types');
	}

	//获取目录
	if(!$filepath = getfilepath($fileext, true)) {
		return cplang('unable_to_create_upload_directory_server');
	}

	//检查空间大小
	if(empty($space)) {
		$query = $_SGLOBAL['db']->query("SELECT username, credit, groupid, attachsize, addsize FROM ".tname('space')." WHERE uid='$_SGLOBAL[supe_uid]'");
		$space = $_SGLOBAL['db']->fetch_array($query);
		$_SGLOBAL['supe_username'] = addslashes($space['username']);
	}
	$_SGLOBAL['member'] = $space;

	$maxattachsize = intval(checkperm('maxattachsize'));//单位MB
	if($maxattachsize) {//0为不限制
		if($space['attachsize'] + $FILE['size'] > $maxattachsize + $space['addsize']) {
			return cplang('inadequate_capacity_space');
		}
	}

	//相册选择
	$albumfriend = 0;
	if($albumid) {
		preg_match("/^new\:(.+)$/i", $albumid, $matchs);
		if(!empty($matchs[1])) {
			$albumname = shtmlspecialchars(trim($matchs[1]));
			if(empty($albumname)) $albumname = sgmdate('Ymd');
			$albumid = album_creat(array('albumname' => $albumname));
		} else {
			$albumid = intval($albumid);
			if($albumid) {
				$query = $_SGLOBAL['db']->query("SELECT albumname,friend FROM ".tname('album')." WHERE albumid='$albumid' AND uid='$_SGLOBAL[supe_uid]'");
				if($value = $_SGLOBAL['db']->fetch_array($query)) {
					$albumname = addslashes($value['albumname']);
					$albumfriend = $value['friend'];
				} else {
					$albumname = sgmdate('Ymd');
					$albumid = album_creat(array('albumname' => $albumname));
				}
			}
		}
	} else {
		$albumname = sgmdate('Ymd');
		$albumid = album_creat(array('albumname' => $albumname));
	}

	//本地上传
	$new_name = $_SC['attachdir'].'./'.$filepath;
	$tmp_name = $FILE['tmp_name'];
	if(@copy($tmp_name, $new_name)) {
		@unlink($tmp_name);
	} elseif((function_exists('move_uploaded_file') && @move_uploaded_file($tmp_name, $new_name))) {
	} elseif(@rename($tmp_name, $new_name)) {
	} else {
		return cplang('mobile_picture_temporary_failure');
	}
	
	//检查是否图片
	if(function_exists('getimagesize') && !@getimagesize($new_name)) {
		@unlink($new_name);
		return cplang('only_allows_upload_file_types');
	}

	//缩略图
	include_once(S_ROOT.'./source/function_image.php');
	$thumbpath = makethumb($new_name);
	$thumb = empty($thumbpath)?0:1;

	//是否压缩
	//获取上传后图片大小
	if(@$newfilesize = filesize($new_name)) {
		$FILE['size'] = $newfilesize;
	}

	//进行ftp上传
	if($_SCONFIG['allowftp']) {
		include_once(S_ROOT.'./source/function_ftp.php');
		if(ftpupload($new_name, $filepath)) {
			$pic_remote = 1;
			$album_picflag = 2;
		} else {
			@unlink($new_name);
			@unlink($new_name.'.thumb.jpg');
			runlog('ftp', 'Ftp Upload '.$new_name.' failed.');
			return cplang('ftp_upload_file_size');
		}
	} else {
		$pic_remote = 0;
		$album_picflag = 1;
	}
	
	//入库
	$title = getstr($title, 150, 1, 1, 1);

	//入库
	$setarr = array(
		'albumid' => $albumid,
		'uid' => $_SGLOBAL['supe_uid'],
		'dateline' => $_SGLOBAL['timestamp'],
		'filename' => addslashes($FILE['name']),
		'postip' => getonlineip(),
		'title' => $title,
		'type' => addslashes($FILE['type']),
		'size' => $FILE['size'],
		'filepath' => $filepath,
		'thumb' => $thumb,
		'remote' => $pic_remote
	);
	$setarr['picid'] = inserttable('pic', $setarr, 1);

	//更新附件大小
	//积分
	$setsql = '';
	if($pic_credit = creditrule('get', 'pic')) {
		$setsql = ",credit=credit+$pic_credit";
	}
	$_SGLOBAL['db']->query("UPDATE ".tname('space')." SET attachsize=attachsize+'$FILE[size]', updatetime='$_SGLOBAL[timestamp]' $setsql WHERE uid='$_SGLOBAL[supe_uid]'");

	//相册更新
	if($albumid) {
		$file = $filepath.($thumb?'.thumb.jpg':'');
		$_SGLOBAL['db']->query("UPDATE ".tname('album')."
			SET picnum=picnum+1, updatetime='$_SGLOBAL[timestamp]', pic='$file', picflag='$album_picflag'
			WHERE albumid='$albumid'");
	}

	return $setarr;
}

if(!checkperm('allowupload')) {
	showmessage('no_privilege');
}
//实名认证
ckrealname('album');

//新用户见习
cknewuser();

$siteurl = getsiteurl();

//获取相册
$albums = getalbums($_SGLOBAL['supe_uid']);

//激活
$actives = ($_GET['op'] == 'flash' || $_GET['op'] == 'cam')?array($_GET['op']=>' class="active"'):array('js'=>' class="active"');

//空间大小
$maxattachsize = checkperm('maxattachsize');
if(!empty($maxattachsize)) {
	$maxattachsize = $maxattachsize + $space['addsize'];//额外空间
	$maxattachsize = formatsize($maxattachsize);
}
$space['attachsize'] = formatsize($space['attachsize']);

//好友组
$groups = getfriendgroup();
	
//模版
include_once template("cp_upload");

?>