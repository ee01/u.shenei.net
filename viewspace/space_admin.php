<?php
if ( !defined( "IN_UCHOME" ) ) exit( "Access Denied" );
if($_SGLOBAL['supe_uid'] != 1) showmessage('这个地方不是你应该来的!', 'index.php', 1);
$a = isset($_GET['a']) ? $_GET['a'] : 'a';
$acs = array('a','b','c','d','e','f','g','h','ajax');
if(!in_array($a, $acs)) $a = 'a';
$perpage = 8;
$page = intval($_GET['page']) ?  intval($_GET['page']) : 1;
$offset = ($page - 1) * $perpage;

foreach($acs as $v){
	$menus[$v] = '';
}
$menus[$a] = ' class=active';

switch($a)
{
	case 'a':
                $type = intval($_GET['type']) ? intval($_GET['type']) : '0';
                $category = intval($_GET['category']) ? intval($_GET['category']) : '0';
                $color = intval($_GET['color']) ? intval($_GET['color']) : '0';
		$id = intval($_GET['id']);

		$theurl = "viewspace.php?&do=admin&a=a";
		if($type) {
			$wheresql .= " AND type='$type'";
			$theurl .= "&type=$type";
		}
		if($category) {
			$wheresql .= " AND category='$category'";
			$theurl .= "&category=$category";
		}
		if($color) {
			$wheresql .= " AND color='$color'";
			$theurl .= "&color=$color";
		}
		if($id <= 0){
                $count = $_SGLOBAL['db']->result($_SGLOBAL['db']->query("SELECT COUNT(*) FROM ".tname('app_viewspace_p')." where 1=1 $wheresql "),0); 
                if($count)
                {
		$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('app_viewspace_p')." where 1=1 $wheresql LIMIT $offset,$perpage");
		while ($value = $_SGLOBAL['db']->fetch_array($query)) {
                         $value['type']=type($value['type']);
                         $value['category']=category($value['category']);
                         $value['color']=color($value['color']);
			 $list[] = $value;
	
		      }
                }
		$multi = array('html' => multi($count, $perpage, $page, $theurl));
		}

	break;

	case 'b':
                $name = empty($_GET['name'])?'':(stripsearchkey($_GET['name'])?$_GET['name']:'');
		$theurl = "viewspace.php?&do=admin&a=b";
		if($name) {
			$wheresql .= " AND name like '%$name%'";
			$theurl .= "&name=$name";
		}

		if($id <= 0){
                $count = $_SGLOBAL['db']->result($_SGLOBAL['db']->query("SELECT COUNT(*) FROM ".tname('app_viewspace_getshare')." where 1=1 $wheresql order by id desc "),0); 
                if($count)
                {
		$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('app_viewspace_getshare')." where 1=1 $wheresql order by id desc LIMIT $offset,$perpage");
		while ($value = $_SGLOBAL['db']->fetch_array($query)) {
                         $value['type']=type($value['type']);
                         $value['name']=shtmlspecialchars($value['name']);
                         $value['category']=category($value['category']);
                         $value['color']=color($value['color']);
                         if($value['uid']!='0')
                         {
                         $value['uname']=getUserName($value['uid']);
                         }
                         else
                         {
                          $value['uname']='官方风格';
                         }
			 $list[] = $value;
	
		      }
                }
		$multi = array('html' => multi($count, $perpage, $page, $theurl));
		}

	break;

	case 'c':
                $id = intval($_GET['id']);
		if(isset($_POST['myFormAc']) && $_POST['myFormAc'] == 'update'){
			$data = $_POST['data'];
			if($id > 0){
				updatetable('app_viewspace_p', $data, " id = '{$id}' ");
                                if($data['type']!='7')
                                {
                                upload_pic($id);
                                }
                                else{
                                upload_pic7($id);
                                }
				showmessage('修改图片成功', 'viewspace.php?do=admin&a=c&id=' . $id, 1);
			}else{
				$id = inserttable('app_viewspace_p', $data, true);
                                if($data['type']!='7')
                                {
                                upload_pic($id);
                                }
                                else{
                                upload_pic7($id);
                                }
				showmessage('添加图片成功', 'viewspace.php?do=admin', 1);
			}
		}else{

			if($id > 0){
				$sql = "SELECT * FROM ".tname("app_viewspace_p")." where id = '{$id}' ";
				$query = $_SGLOBAL['db']->query( $sql );
				$data = $_SGLOBAL['db']->fetch_array( $query );
                                if($data['type']=='7')
                                {
                                  $data['photob']=$data['photob'].".swf";
                                }
                                
			}
			realname_get();
		}
	break;

	case 'd':
                $id = intval($_GET['id']);
                $FILE = $_FILES['photopic'];
                if(isset($FILE) && is_array($FILE) && $FILE['name'] && $FILE['error'] == 0){
			if($id > 0){
                                upload_photopic($id);
				showmessage('上传成功', 'viewspace.php?do=admin&a=d&id=' . $id, 1);
			}else{
				upload_photopic($id);
				showmessage('上传成功', 'viewspace.php?do=admin&a=d', 1);
			}

		}
		
                elseif(isset($_POST['myFormAc']) && $_POST['myFormAc'] == 'update'){
			$data = $_POST['data'];

			if($id > 0){
                                eval('$arrName1='.  trim(stripslashes($data['allframe'])). ";");
                                $data['allframe']=serialize($arrName1);
                                eval('$arrName2='.  trim(stripslashes($data['effectall'])). ";");
                                $data['effectall']=serialize($arrName2);
                                eval('$arrName3='.  trim(stripslashes($data['block'])). ";");
                                $data['block']=serialize($arrName3);
                                eval('$arrName4='.  trim(stripslashes($data['cursor'])). ";");
                                $data['cursor']=serialize($arrName4);
                                updatetable('app_viewspace_getshare', $data, " id = '{$id}' ");
                                upload_pic1($id);
				showmessage('修改风格成功', 'viewspace.php?do=admin&a=d&id=' . $id, 1);
			}else{
				$id = inserttable('app_viewspace_getshare', $data, true);
				upload_pic($id);
				showmessage('添加风格成功', 'viewspace.php?do=admin&a=d', 1);
			}

		}else{

			if($id > 0){
				$sql = "SELECT * FROM ".tname("app_viewspace_getshare")." where id = '{$id}' ";
				$query = $_SGLOBAL['db']->query( $sql );
				$data = $_SGLOBAL['db']->fetch_array( $query );
                                $data['allframe'] = var_export(unserialize($data['allframe']),true); 
                                $data['effectall'] = var_export(unserialize($data['effectall']),true); 
                                $data['block'] = var_export(unserialize($data['block']),true); 
                                $data['cursor'] = var_export(unserialize($data['cursor']),true); 

                           }
			realname_get();
		}
	break;

	case 'e':
                $name = empty($_GET['name'])?'':(stripsearchkey($_GET['name'])?$_GET['name']:'');
                $uid = empty($_GET['uid'])?'':(intval($_GET['uid'])?$_GET['uid']:'');
                 $theurl = "viewspace.php?&do=admin&a=e";
		if($name) {
			$wheresql .= " AND name like '%$name%'";
			$theurl .= "&name=$name";
		}
		if($uid) {
			$wheresql .= " AND uid=$uid";
			$theurl .= "&uid=$uid";
		}
		if($id <= 0){
                $count = $_SGLOBAL['db']->result($_SGLOBAL['db']->query("SELECT COUNT(*) FROM ".tname('app_viewspace_suser')." where 1=1 $wheresql "),0); 
                if($count)
                {
		$query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('app_viewspace_suser')." where 1=1 $wheresql order by share desc, date desc LIMIT $offset,$perpage");
		while ($value = $_SGLOBAL['db']->fetch_array($query)) {
                         $value['name']=shtmlspecialchars($value['name']);
                         $value['uname']=getUserName($value['uid']);
			 $list[] = $value;
		      }
                }
		$multi = array('html' => multi($count, $perpage, $page, $theurl));
		}

	break;

	case 'f':
                $id = intval($_GET['id']);
		if(isset($_POST['myFormAc']) && $_POST['myFormAc'] == 'update'){
			$data = $_POST['data'];
			if($id > 0){
                                eval('$arrName1='.  trim(stripslashes($data['allframe'])). ";");
                                $data['allframe']=serialize($arrName1);
                                eval('$arrName2='.  trim(stripslashes($data['effectall'])). ";");
                                $data['effectall']=serialize($arrName2);
                                eval('$arrName3='.  trim(stripslashes($data['block'])). ";");
                                $data['block']=serialize($arrName3);
                                eval('$arrName4='.  trim(stripslashes($data['cursor'])). ";");
                                $data['cursor']=serialize($arrName4);
				updatetable('app_viewspace_suser', $data, " id = '{$id}' ");
  				showmessage('修改用户风格成功', 'viewspace.php?do=admin&a=f&id=' . $id, 1);
			}else{
                                $time=GmtToUnix(date("Y-m-d H:i:s"));
                                $data['date']=$time;
                                $data['blockname']='a:0:{}';
				$id = inserttable('app_viewspace_suser', $data, true);
				showmessage('添加用户风格成功', 'viewspace.php?do=admin&a=f', 1);
			}
		}else{

			if($id > 0){
				$sql = "SELECT * FROM ".tname("app_viewspace_suser")." where id = '{$id}' ";
				$query = $_SGLOBAL['db']->query( $sql );
				$data = $_SGLOBAL['db']->fetch_array( $query );
                                if($_GET['go']=='y')
                                {
                                    global $_SGLOBAL;
                                    $_SGLOBAL['db']->query( "update ".tname("app_viewspace_suser")." set share=0 where id = '{$id}'" );
                                    $arrsuser = array(
                                    		"uid" => $u,
                                    		"frame_set" =>$data[frame_set],
                                    		"allframe" =>$data['allframe'],
                                    		"effectall" =>$data['effectall'],
                                    		"block" =>$data['block'],
                                    		"cursor" =>$data['cursor'],
                                    		"category" =>$data['category'],
                                    		"color" =>$data['color'],
                                    		"status" =>0,
                                    );
                                    inserttable( "app_viewspace_getshare", $arrsuser );
  				    showmessage('添加分享风格成功', 'viewspace.php?do=admin&a=f&id=' . $id, 1);
                                }
                                if($_GET['go']=='n')
                                {
                                    global $_SGLOBAL;
                                    $_SGLOBAL['db']->query( "update ".tname("app_viewspace_suser")." set share=0 where id = '{$id}'" );
                                    showmessage('取消分享风格成功', 'viewspace.php?do=admin&a=f&id=' . $id, 1);
                                }
                                $data['allframe'] = var_export(unserialize($data['allframe']),true); 
                                $data['effectall'] = var_export(unserialize($data['effectall']),true); 
                                $data['block'] = var_export(unserialize($data['block']),true); 
                                $data['cursor'] = var_export(unserialize($data['cursor']),true); 
			}
			realname_get();
		}
	break;

        case 'ajax':
		$change = $_POST['change'];
		switch($change)
		{
			

			case 'get_delete':
				$id = intval($_POST['hash']);
	                        global $_SGLOBAL;
				$_SGLOBAL['db']->query("delete from ".tname("app_viewspace_getshare")." where id = '{$id}' ");

			break;

			case 'p_delete':
				$id = intval($_POST['hash']);
	                        global $_SGLOBAL;
				$_SGLOBAL['db']->query("delete from ".tname("app_viewspace_p")." where id = '{$id}' ");


			break;

			case 's_delete':
				$id = intval($_POST['hash']);
	                        global $_SGLOBAL;
				$_SGLOBAL['db']->query("delete from ".tname("app_viewspace_suser")." where id = '{$id}' ");


			break;
		}
		exit();
	break;

}


include template('viewspace_administrator');


function type($type)
{
$bac='未设置';
switch($type)
{
case '1':
$bac='背景';
break;

case '2':
$bac='头部';
break;

case '3':
$bac='标题';
break;

case '6':
$bac='鼠标';
break;
case '7':
$bac='播放器';
break;
}
return $bac;
}


function category($category)
{
$bac='未设置';
switch($category)
{
case '1':
$bac='简约';
break;

case '2':
$bac='可爱';
break;

case '3':
$bac='炫酷';
break;

case '4':
$bac='浪漫';
break;

case '5':
$bac='其它';
break;
}
return $bac;
}

function color($color)
{
$bac='未设置';
switch($color)
{
case '1':
$bac='黑';
break;

case '2':
$bac='白';
break;

case '3':
$bac='粉';
break;

case '4':
$bac='黃';
break;

case '5':
$bac='红';
break;

case '6':
$bac='蓝';
break;
}
return $bac;
}


function query_all($sql)
{
	global $_SGLOBAL;
	$query = $_SGLOBAL['db']->query( $sql );
	while($value = $_SGLOBAL['db']->fetch_array( $query )){
		$list[] = $value;
	}
	return $list;
}

function query_player($hash)
{
	global $_SGLOBAL;
	$query = $_SGLOBAL['db']->query("select * from ".tname("app_movie_player")." where hash = '{$hash}' ");
	return $_SGLOBAL['db']->fetch_array( $query );
}

function get_reply_rid($rid)
{
	global $_SGLOBAL;
	$query = $_SGLOBAL['db']->query("select * from ".tname("app_movie_reply")." where rid = '{$rid}' ");
	return $_SGLOBAL['db']->fetch_array( $query );
}

function query_total($sql)
{
	global $_SGLOBAL;
	$query = $_SGLOBAL['db']->query( $sql );
	$value = $_SGLOBAL['db']->fetch_array( $query );
	return array_shift($value);
}

function check_only($name)
{
	global $_SC, $_SGLOBAL;
	$name = addslashes($name);
	$query = $_SGLOBAL['db']->query("select id from {$_SC['tablepre']}app_movie where name = '{$name}' ");
	$num = $_SGLOBAL['db']->fetch_array($query);
	return isset($num['id']) ? $num['id'] : 0;
}

function rmdirs($file) {
 if(function_exists('xcache_set')){
	xcache_clear_cache(XC_TYPE_VAR,0);
 }
 if (file_exists($file)) {
   chmod($file,0777);
   if (is_dir($file)) {
     $handle = opendir($file); 
     while($filename = readdir($handle)) {
       if ($filename != "." && $filename != "..") {
        rmdirs($file."/".$filename);
       }
     }
    closedir($handle);
     rmdir($file);
   } else {
    unlink($file);
   }
 }
}

function _uc_fopen($url, $limit = 0, $post = '', $cookie = '', $bysocket = FALSE, $ip = '', $timeout = 15, $block = TRUE) {
	$return = '';
	$matches = parse_url($url);
	!isset($matches['host']) && $matches['host'] = '';
	!isset($matches['path']) && $matches['path'] = '';
	!isset($matches['query']) && $matches['query'] = '';
	!isset($matches['port']) && $matches['port'] = '';
	$host = $matches['host'];
	$path = $matches['path'] ? $matches['path'].($matches['query'] ? '?'.$matches['query'] : '') : '/';
	$port = !empty($matches['port']) ? $matches['port'] : 80;
	if($post) {
		$out = "POST $path HTTP/1.0\r\n";
		$out .= "Accept: */*\r\n";
		//$out .= "Referer: $boardurl\r\n";
		$out .= "Accept-Language: zh-cn\r\n";
		$out .= "Content-Type: application/x-www-form-urlencoded\r\n";
		$out .= "User-Agent: $_SERVER[HTTP_USER_AGENT]\r\n";
		$out .= "Host: $host\r\n";
		$out .= 'Content-Length: '.strlen($post)."\r\n";
		$out .= "Connection: Close\r\n";
		$out .= "Cache-Control: no-cache\r\n";
		$out .= "Cookie: $cookie\r\n\r\n";
		$out .= $post;
	} else {
		$out = "GET $path HTTP/1.0\r\n";
		$out .= "Accept: */*\r\n";
		//$out .= "Referer: $boardurl\r\n";
		$out .= "Accept-Language: zh-cn\r\n";
		$out .= "User-Agent: $_SERVER[HTTP_USER_AGENT]\r\n";
		$out .= "Host: $host\r\n";
		$out .= "Connection: Close\r\n";
		$out .= "Cookie: $cookie\r\n\r\n";
	}
	$fp = @fsockopen(($ip ? $ip : $host), $port, $errno, $errstr, $timeout);
	if(!$fp) {
		return '';//note $errstr : $errno \r\n
	} else {
		stream_set_blocking($fp, $block);
		stream_set_timeout($fp, $timeout);
		@fwrite($fp, $out);
		$status = stream_get_meta_data($fp);
		if(!$status['timed_out']) {
			while (!feof($fp)) {
				if(($header = @fgets($fp)) && ($header == "\r\n" ||  $header == "\n")) {
					break;
				}
			}

			$stop = false;
			while(!feof($fp) && !$stop) {
				$data = fread($fp, ($limit == 0 || $limit > 8192 ? 8192 : $limit));
				$return .= $data;
				if($limit) {
					$limit -= strlen($data);
					$stop = $limit <= 0;
				}
			}
		}
		@fclose($fp);
		return $return;
	}
}


function array_to_string($arr)
{
	$r = array('"' ,"'");
	$string = array();
	foreach($arr as $val){
		$val = str_replace($r, '', $val);
		$string[] = "'{$val}'";
	}
	return implode(',', $string);
}

function upload_pic($id,$dd=0)
{
	global $_SC;
	$url = 'viewspace.php?do=admin&a=c&id='.$id;
	include_once(S_ROOT.'./source/function_cp.php');
        $allowpictype = array('jpg','gif','png','ani');
	$FILE = $_FILES['pic'];
        $FILE2 = $_FILES['pic2'];
        $rand=randompic(32);
	if(isset($FILE) && is_array($FILE) && $FILE['name'] && $FILE['error'] == 0 && isset($FILE2) && is_array($FILE2) && $FILE2['name'] && $FILE2['error'] == 0)
	{
		$FILE['size'] = intval($FILE['size']);
		if(empty($FILE['size']) || empty($FILE['tmp_name']) || !empty($FILE['error'])) showmessage(cplang('lack_of_access_to_upload_file_size'), $url, 1);
		$fileext = fileext($FILE['name']);
		if(!in_array($fileext, $allowpictype)) showmessage(cplang('only_allows_upload_file_types'), $url, 1);
                $filepath = $rand.".$fileext";
		$new_name = 'viewspace/picfile/thumb/';
		$new_name .=  $filepath;
		$tmp_name = $FILE['tmp_name'];
		if(@copy($tmp_name, $new_name)) {
			@unlink($tmp_name);
		} elseif((function_exists('move_uploaded_file') && @move_uploaded_file($tmp_name, $_SC['attachdir'].$new_name))) {
		} elseif(@rename($tmp_name, $_SC['attachdir'].$new_name)) {

		} else {
			showmessage(cplang('mobile_picture_temporary_failure'), $url, 1);
		}
		//缩略图
		$img = new picture($new_name, $new_name);
		$img->setSize(0, 160);
		$out = $img->resize($img->im);
		$img->output($out);



		$FILE2['size'] = intval($FILE2['size']);
		if(empty($FILE2['size']) || empty($FILE2['tmp_name']) || !empty($FILE2['error'])) showmessage(cplang('lack_of_access_to_upload_file_size'), $url, 1);
		$fileext2 = fileext($FILE2['name']);
		if(!in_array($fileext2, $allowpictype)) showmessage(cplang('only_allows_upload_file_types'), $url, 1);
                $filepath2 = $rand."_b.$fileext2";
		$new_name2 = 'viewspace/picfile/';
		$new_name2 .=  $filepath2;
		$tmp_name2 = $FILE2['tmp_name'];
		if(@copy($tmp_name2, $new_name2)) {
			@unlink($tmp_name2);
		} elseif((function_exists('move_uploaded_file') && @move_uploaded_file($tmp_name2, $_SC['attachdir'].$new_name2))) {
		} elseif(@rename($tmp_name2, $_SC['attachdir'].$new_name2)) {

		} else {
			showmessage(cplang('mobile_picture_temporary_failure'), $url, 1);
		}

	global $_SGLOBAL;
	$_SGLOBAL['db']->query("update ".tname("app_viewspace_p")." set photo = '{$filepath}',photob = '{$filepath2}' where id = '{$id}' ");
	}

}

function upload_pic7($id,$dd=0)
{
	global $_SC;
	$url = 'viewspace.php?do=admin&a=c&id='.$id;
	include_once(S_ROOT.'./source/function_cp.php');
        $allowpictype = array('jpg','gif','png','ani','swf');
	$FILE = $_FILES['pic'];
        $FILE2 = $_FILES['pic2'];
        $rand=randompic(32);
	if(isset($FILE) && is_array($FILE) && $FILE['name'] && $FILE['error'] == 0 && isset($FILE2) && is_array($FILE2) && $FILE2['name'] && $FILE2['error'] == 0)
	{
		$FILE['size'] = intval($FILE['size']);
		if(empty($FILE['size']) || empty($FILE['tmp_name']) || !empty($FILE['error'])) showmessage(cplang('lack_of_access_to_upload_file_size'), $url, 1);
		$fileext = fileext($FILE['name']);
		if(!in_array($fileext, $allowpictype)) showmessage(cplang('only_allows_upload_file_types'), $url, 1);
                $filepath = $FILE['name'];
		$new_name = 'viewspace/flash/';
		$new_name .=  $filepath;
		$tmp_name = $FILE['tmp_name'];
		if(@copy($tmp_name, $new_name)) {
			@unlink($tmp_name);
		} elseif((function_exists('move_uploaded_file') && @move_uploaded_file($tmp_name, $_SC['attachdir'].$new_name))) {
		} elseif(@rename($tmp_name, $_SC['attachdir'].$new_name)) {

		} else {
			showmessage(cplang('mobile_picture_temporary_failure'), $url, 1);
		}

		$FILE2['size'] = intval($FILE2['size']);
		if(empty($FILE2['size']) || empty($FILE2['tmp_name']) || !empty($FILE2['error'])) showmessage(cplang('lack_of_access_to_upload_file_size'), $url, 1);
		$fileext2 = fileext($FILE2['name']);
		if(!in_array($fileext2, $allowpictype)) showmessage(cplang('only_allows_upload_file_types'), $url, 1);
                $filepath2 = $FILE2['name'];
		$new_name2 = 'viewspace/flash/';
		$new_name2 .=  $filepath2;
		$tmp_name2 = $FILE2['tmp_name'];
                $filelen=strpos($FILE2['name'],'.');
                $nname=substr($FILE2['name'], 0, $filelen); 
		if(@copy($tmp_name2, $new_name2)) {
			@unlink($tmp_name2);
		} elseif((function_exists('move_uploaded_file') && @move_uploaded_file($tmp_name2, $_SC['attachdir'].$new_name2))) {
		} elseif(@rename($tmp_name2, $_SC['attachdir'].$new_name2)) {

		} else {
			showmessage(cplang('mobile_picture_temporary_failure'), $url, 1);
		}

	global $_SGLOBAL;
	$_SGLOBAL['db']->query("update ".tname("app_viewspace_p")." set photo = '{$filepath}',photob = '{$nname}' where id = '{$id}' ");
	}

}


function upload_pic1($id,$dd=0)
{
	global $_SC;
	$url = 'viewspace.php?do=admin&a=d&id='.$id;
	include_once(S_ROOT.'./source/function_cp.php');
        $allowpictype = array('jpg','gif','png','ani');
	$FILE = $_FILES['pic'];
        $rand=randompic(10);
	if(isset($FILE) && is_array($FILE) && $FILE['name'] && $FILE['error'] == 0)
	{
		$FILE['size'] = intval($FILE['size']);
		if(empty($FILE['size']) || empty($FILE['tmp_name']) || !empty($FILE['error'])) showmessage(cplang('lack_of_access_to_upload_file_size'), $url, 1);
		$fileext = fileext($FILE['name']);
		if(!in_array($fileext, $allowpictype)) showmessage(cplang('only_allows_upload_file_types'), $url, 1);
                $filepath = $rand.".$fileext";
		$new_name = 'viewspace/img/getshare/';
		$new_name .=  $filepath;
		$tmp_name = $FILE['tmp_name'];
		if(@copy($tmp_name, $new_name)) {
			@unlink($tmp_name);
		} elseif((function_exists('move_uploaded_file') && @move_uploaded_file($tmp_name, $_SC['attachdir'].$new_name))) {
		} elseif(@rename($tmp_name, $_SC['attachdir'].$new_name)) {

		} else {
			showmessage(cplang('mobile_picture_temporary_failure'), $url, 1);
		}
		//缩略图
		$img = new picture($new_name, $new_name);
		$img->setSize(0, 160);
		$out = $img->resize($img->im);
		$img->output($out);

	global $_SGLOBAL;
	$_SGLOBAL['db']->query("update ".tname("app_viewspace_getshare")." set img = '{$new_name}' where id = '{$id}' ");
	}

}



function upload_photopic($id,$dd=0)
{
	global $_SC;
	$url = 'viewspace.php?do=admin&a=d&id='.$id;
	include_once(S_ROOT.'./source/function_cp.php');
        $allowpictype = array('jpg','gif','png','ani');
	$FILE = $_FILES['photopic'];
        $rand=randompic(10);
	if(isset($FILE) && is_array($FILE) && $FILE['name'] && $FILE['error'] == 0)
	{
		$FILE['size'] = intval($FILE['size']);
		if(empty($FILE['size']) || empty($FILE['tmp_name']) || !empty($FILE['error'])) showmessage(cplang('lack_of_access_to_upload_file_size'), $url, 1);
		$fileext = fileext($FILE['name']);
		if(!in_array($fileext, $allowpictype)) showmessage(cplang('only_allows_upload_file_types'), $url, 1);
                $filepath = $FILE['name'];
		$new_name = 'viewspace/img/getshare/';
		$new_name .=  $filepath;
		$tmp_name = $FILE['tmp_name'];
		if(@copy($tmp_name, $new_name)) {
			@unlink($tmp_name);
		} elseif((function_exists('move_uploaded_file') && @move_uploaded_file($tmp_name, $_SC['attachdir'].$new_name))) {
		} elseif(@rename($tmp_name, $_SC['attachdir'].$new_name)) {

		} else {
			showmessage(cplang('mobile_picture_temporary_failure'), $url, 1);
		}
	}
}


function upload_pic2($id)
{
	global $_SC;
	$url = 'viewspace.php?do=admin&a=d&id='.$id;
	include_once(S_ROOT.'./source/function_cp.php');
        $allowpictype = array('jpg','gif','png','ani');
	$FILE = $_FILES['pic'];
        $rand=randompic(10);
	if(isset($FILE) && is_array($FILE) && $FILE['name'] && $FILE['error'] == 0)
	{
		$FILE['size'] = intval($FILE['size']);
		if(empty($FILE['size']) || empty($FILE['tmp_name']) || !empty($FILE['error'])) showmessage(cplang('lack_of_access_to_upload_file_size'), $url, 1);
		$fileext = fileext($FILE['name']);
		if(!in_array($fileext, $allowpictype)) showmessage(cplang('only_allows_upload_file_types'), $url, 1);
                $filepath = $rand.".$fileext";
		$new_name = 'viewspace/img/getshare/';
		$new_name .=  $filepath;
		$tmp_name = $FILE['tmp_name'];
		if(@copy($tmp_name, $new_name)) {
			@unlink($tmp_name);
		} elseif((function_exists('move_uploaded_file') && @move_uploaded_file($tmp_name, $_SC['attachdir'].$new_name))) {
		} elseif(@rename($tmp_name, $_SC['attachdir'].$new_name)) {

		} else {
			showmessage(cplang('mobile_picture_temporary_failure'), $url, 1);
		}

	}

}


//产生随机字符
function randompic($length) {
	$seed = '0123456789abcdef';
	$hash = '';
	$max = strlen($seed) - 1;
	for($i = 0; $i < $length; $i++) {
		$hash .= $seed[mt_rand(0, $max)];
	}
	return $hash;
}

class picture
{ 
	var $type = 'jpg'; 
	var $width; 
	var $height; 
	var $srcimg; 
	var $x = 0;
	var $y = 0;
	var $w = 100;
	var $h = 100;
	var $dstimg; 
	// 临时创建的图象
	var $im; 
	var $wi = 224;
	var $wid = 0;
	var $hid = 0;

	function picture($img,  $dstimg='')
	{
		$this->srcimg = $img;
		$this->type = strtolower(substr(strrchr($this->srcimg, "."), 1)); 
		$this->initi_img(); 
		$this->dstimg = $dstimg;
		$this->width = imagesx($this->im);
		$this->height = imagesy($this->im); 
	}

	function resize(&$in)
	{ 
		$wi = imagesx($in);
		$hi = imagesy($in);
		if($this->wid > 0){//定高
			if($this->wid >= $hi) return $in;
			// 实际图象的比例
			$ratio = $wi / $hi;
			$out = imagecreatetruecolor($ratio * $this->wid, $this->wid);
			imagecopyresampled($out, $in, 0, 0, 0, 0, $ratio * $this->wid, $this->wid, $wi, $hi);
		}else{//定宽
			if($this->hid >= $wi) return $in;
			// 实际图象的比例
			$ratio = $wi / $hi;
			$out = imagecreatetruecolor($this->hid, $this->hid/$ratio);
			imagecopyresampled($out, $in, 0, 0, 0, 0, $this->hid,$this->hid/$ratio,  $wi, $hi);
		}
		return $out;
	} 

	// 初始化图象
	function initi_img()
	{
		if ($this->type == "jpg")
		{
			$this->im = imagecreatefromjpeg($this->srcimg);
		}
		if ($this->type == "gif")
		{
			$this->im = imagecreatefromgif($this->srcimg);
		}
		if ($this->type == "png")
		{
			$this->im = imagecreatefrompng($this->srcimg);
		}
	} 

	function setSize($wid, $hid = 0)
	{
		$this->wid = $wid;
		$this->hid = $hid;
	}

	function output(&$out, $weight = 70){
		ImageJpeg ($out, $this->dstimg, $weight); 
		ImageDestroy ($this->im);
		ImageDestroy ($out);
	}
}
?>