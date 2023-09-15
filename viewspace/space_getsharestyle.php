<?php 
if(!defined('IN_UCHOME')) {
	exit('Access Denied');
}

if($_REQUEST['smusicpid']!='')
{
$id = $_POST['smusicpid'];
$u = $_SGLOBAL['supe_uid'];
global $_SGLOBAL;
$_SGLOBAL['db']->query( "update ".tname('space')." set smusicp='$id' where uid=$u" );
//header("location:viewspace.php?op=diy");
}

if($_REQUEST['sflashpid']!='')
{
$id = $_POST['sflashpid'];
$u = $_SGLOBAL['supe_uid'];
global $_SGLOBAL;
$_SGLOBAL['db']->query( "update ".tname('space')." set sflashp='$id' where uid=$u" );
//header("location:viewspace.php?op=diy");
}

if($_REQUEST['sharestyleid']!='')
{
$styleid = $_POST['sharestyleid'];
$value=get_share($styleid);

$u = $_SGLOBAL['supe_uid'];
$time=GmtToUnix(date("Y-m-d H:i:s"));
global $_SGLOBAL;
$_SGLOBAL['db']->query( "update ".tname('app_viewspace_suser')." set status=0 where uid=$u and status=1" );
$arrsuser = array(
		"uid" => $u,
		"frame_set" =>$value[frame_set],
		"allframe" =>$value[allframe],
		"effectall" =>$value[effectall],
                "blockname" =>'a:0:{}',
		"block" =>$value[block],
		"cursor" =>$value[cursor],
		"date" =>$time,
		"status" =>1,
);
inserttable( "app_viewspace_suser", $arrsuser );

$effectall=unserialize($value[effectall]);
$block=unserialize($value[block]);
$cursor=unserialize($value[cursor]);
$style=array(
		 'effectall'=>$effectall,
		 'block'=>$block,
		 'cursor'=>$cursor,
                );
$css=outframeeffectall($style,1);
$css.=outframeblock($style,1);
$css.=outframecursor($style,1);
if(@$fp = fopen('./viewspace/css/'.get_userURL1($u), 'w')) {
fwrite($fp, $css);
fclose($fp);
} else {
	exit('Can not write to cache files, please check directory ./forumdata/ and ./forumdata/cache/ .');
}



header("location:viewspace.php");
}


header('Content-type: text/xml');
$pp = toXml("gbk"); 
echo $pp; 

function toXml($encoding='') 
{ 
$color = empty($_GET['color'])?'0':$_GET['color'];
$page = (isset($_GET['page']) && intval($_GET['page']) > 0 ) ? intval($_GET['page']) : 1;
$perpage = '5';
$start = ($page - 1 ) * $perpage;

$xmlstr = ''; 
if($encoding=='') 
{ 
  $xmlstr  .='<?xml version="1.0" encoding="utf-8" ?>
'; 
} 
else 
{ 
  $xmlstr  .='<?xml version="1.0" encoding="'; 
  $xmlstr  .=$encoding; 
  $xmlstr  .='" ?>
'; 
} 
$xmlstr .='<root><![CDATA[<ul class="imglist imgmax imginfo">
'; 
// 调用遍历数组函数 
$xmlstr .='<li><a id="style_default"  target="mainyinyue" href="javascript:void(0);" onclick="changeselect_share(this, \'-1\')"><img src="viewspace/img/tool/default_ico.jpg" title="点击预览"/></a><p>默认风格</p><p><a  target="mainyinyue" href="javascript:void(0);" >官方风格</a></p></li>
'; 
$xmlstr .= get_all($start,$perpage,$page)
;
return $xmlstr; 
} 




function get_all($offset = 0, $limit = 5,$page) 
{
$category = empty($_GET['category'])?'0':$_GET['category'];
$color = empty($_GET['color'])?'0':$_GET['color'];

$term.=' status=1';
if($color!='0')
{
$term.=' and color='.$color.'';
}
if($category!='0')
{
$term.=' and category='.$category.'';
}
$arrString = '';
global $_SGLOBAL;
$sql="SELECT * FROM ".tname('app_viewspace_getshare')." where {$term} ORDER BY id DESC LIMIT $offset, $limit";
$feedlist = array();
$query = $_SGLOBAL['db']->query($sql);
while ($value = $_SGLOBAL['db']->fetch_array($query)) {
	$feedlist[] = $value;
}
$num = $_SGLOBAL['db']->result($_SGLOBAL['db']->query("SELECT COUNT(*) FROM ".tname('app_viewspace_getshare')." WHERE {$term}"),0);
foreach ($feedlist as $key => $value) {
$value['name']=shtmlspecialchars($value['name']);
$uname=getUserName($value[uid]);
$author=$value[uid]=='0'?'<a  target="mainyinyue" href="javascript:void(0);" >官方风格</a>':'<a href="viewspace.php?uid='.$value[uid].'" target="_blank">'.$uname.'</a>';
$arrString.='<li><a id="style'.$value[id].'"  target="mainyinyue" href="javascript:void(0);" onclick="changeselect_share(this, '.$value[id].')"><img src="'.$value[img].'" title="点击预览"/></a><p>'.$value[name].'</p><p>'.$author.'</p></li>
';
}
$arrString.='</ul>
';
$arrString.='<div class="floatpage"><div class="pages">';
$arrString.= pp($num,$limit,$page);
$arrString.='</div></div>]]></root>
';
return $arrString;
} 

function pp($num, $perpage, $curpage) {
$category = empty($_GET['category'])?'0':$_GET['category'];
$color = empty($_GET['color'])?'0':$_GET['color'];
	global $_SCONFIG;
	$page = 5;
	$multipage = '';
	$realpages = 1;
	if($num > $perpage) {
		$offset = 2;
		$realpages = @ceil($num / $perpage);
		$pages = $_SCONFIG['maxpage'] && $_SCONFIG['maxpage'] < $realpages ? $_SCONFIG['maxpage'] : $realpages;
		if($page > $pages) {
			$from = 1;
			$to = $pages;
		} else {
			$from = $curpage - $offset;
			$to = $from + $page - 1;
			if($from < 1) {
				$to = $curpage + 1 - $from;
				$from = 1;
				if($to - $from < $page) {
					$to = $page;
				}
			} elseif($to > $pages) {
				$from = $pages - $page + 1;
				$to = $pages;
			}
		}
		$multipage = ($curpage - $offset > 1 && $pages > $page ? '<a  target="mainyinyue" href="javascript:void(0);" onclick="getpage(1,\'viewspace.php?do=getsharestyle&category='.$category.'&category='.$category.'&color='.$color.'&inajax=1\');">1 ...</a>' : '').
			($curpage > 1 ? '<a  target="mainyinyue" href="javascript:void(0);" onclick="getpage('.($curpage - 1).',\'viewspace.php?do=getsharestyle&category='.$category.'&category='.$category.'&color='.$color.'&inajax=1\');">&lsaquo;&lsaquo;</a>' : '');
		for($i = $from; $i <= $to; $i++) {
			$multipage .= $i == $curpage ? '<strong>'.$i.'</strong>' :
				'<a  target="mainyinyue" href="javascript:void(0);" onclick="getpage('.$i.',\'viewspace.php?do=getsharestyle&category='.$category.'&category='.$category.'&color='.$color.'&inajax=1\');">'.$i.'</a>';
		}
		$multipage .= ($curpage < $pages ? '<a  target="mainyinyue" href="javascript:void(0);" onclick="getpage('.($curpage + 1).',\'viewspace.php?do=getsharestyle&category='.$category.'&category='.$category.'&color='.$color.'&inajax=1\');">&rsaquo;&rsaquo;</a>' : '').
			($to < $pages ? '<a  target="mainyinyue" href="javascript:void(0);" onclick="getpage('.$pages.',\'viewspace.php?do=getsharestyle&category='.$category.'&category='.$category.'&color='.$color.'&inajax=1\');">... '.$realpages.'</a>' : '');
		$multipage = $multipage ? ('<em>&nbsp;'.$num.'&nbsp;</em>'.$multipage):'';
	}
	$maxpage = $realpages;
	return $multipage;
}





?> 
