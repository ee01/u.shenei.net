<?php 
if(!defined('IN_UCHOME')) {
	exit('Access Denied');
}
header('Content-type: text/xml');
$u = $_SGLOBAL['supe_uid'];
$pp = toXml("gbk"); 
echo $pp; 

function toXml($encoding='') 
{ 
$page = (isset($_GET['page']) && intval($_GET['page']) > 0 ) ? intval($_GET['page']) : 1;
$perpage = ($type!='2')?'11':'5';
$css= ($type!='2')?'':'imgmax';
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
$xmlstr .=' <root><![CDATA[<ul class="imglist '.$css.'">
'; 
// 调用遍历数组函数 
$xmlstr .='<li><a href="javascript:void(0);"><img src="viewspace/img/tool/pic_nobgmax.gif" onclick="changebg(this, 1);" /></a></li>
'; 
$xmlstr .= get_all($start,$perpage,$page)
;

return $xmlstr; 
} 




function get_all($offset = 0, $limit = 5,$page) 
{ 
$id = empty($_GET['id'])?'0':$_GET['id'];

 
$term =" albumid='$id' ";

$arrString = '';
global $_SGLOBAL;
$sql="SELECT * FROM ".tname('pic')." where {$term} ORDER BY dateline DESC LIMIT $offset, $limit";
$feedlist = array();
$query = $_SGLOBAL['db']->query($sql);
while ($value = $_SGLOBAL['db']->fetch_array($query)) {
	$feedlist[] = $value;
}
$num = $_SGLOBAL['db']->result($_SGLOBAL['db']->query("SELECT COUNT(*) FROM ".tname('pic')." WHERE {$term}"),0);
foreach ($feedlist as $key => $value) {
$value[pic] = mkpicurl($value);
$photo=$value[pic];
$id=$value[picid];
$arrString.='<li><a href="javascript:void(0);" ><img src="'.$photo.'" id="a_pic_'.$id.'" onclick="changebg(this);"/></a></li>
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
$id = empty($_GET['id'])?'0':$_GET['id'];
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
		$multipage = ($curpage - $offset > 1 && $pages > $page ? '<a href="javascript:void(0);" onclick="getpage(1,\'space.php?uid='.$u.'&do=selectbg&id='.$id.'&inajax=1\');">1 ...</a>' : '').
			($curpage > 1 ? '<a href="javascript:void(0);" onclick="getpage('.($curpage - 1).',\'space.php?uid='.$u.'&do=selectbg&id='.$id.'&inajax=1\');">&lsaquo;&lsaquo;</a>' : '');
		for($i = $from; $i <= $to; $i++) {
			$multipage .= $i == $curpage ? '<strong>'.$i.'</strong>' :
				'<a href="javascript:void(0);" onclick="getpage('.$i.',\'space.php?uid='.$u.'&do=selectbg&id='.$id.'&inajax=1\');">'.$i.'</a>';
		}
		$multipage .= ($curpage < $pages ? '<a href="javascript:void(0);" onclick="getpage('.($curpage + 1).',\'space.php?uid='.$u.'&do=selectbg&id='.$id.'&inajax=1\');">&rsaquo;&rsaquo;</a>' : '').
			($to < $pages ? '<a href="javascript:void(0);" onclick="getpage('.$pages.',\'space.php?uid='.$u.'&do=selectbg&id='.$id.'&inajax=1\');">... '.$realpages.'</a>' : '');
		$multipage = $multipage ? ('<em>&nbsp;'.$num.'&nbsp;</em>'.$multipage):'';
	}
	$maxpage = $realpages;
	return $multipage;
}

//处理上传图片连接
function mkpicurl($pic, $thumb=1) {
	global $_SCONFIG, $_SC, $space;

	$url = '';
	if(isset($pic['picnum']) && $pic['picnum'] < 1) {
		$url = 'image/nopic.gif';
	} elseif(isset($pic['picflag'])) {
		if($pic['pic']) {
			if($pic['picflag'] == 1) {
				$url = $_SC['attachurl'].$pic['pic'];
			} elseif ($pic['picflag'] == 2) {
				$url = $_SCONFIG['ftpurl'].$pic['pic'];
			} else {
				$url = $pic['pic'];
			}
		}
	} elseif(isset($pic['filepath'])) {
		$pic['pic'] = $pic['filepath'];
		if($pic['pic']) {
			if($thumb && $pic['thumb']) $pic['pic'] .= '.thumb.jpg';
			if($pic['remote']) {
				$url = $_SCONFIG['ftpurl'].$pic['pic'];
			} else {
				$url = $_SC['attachurl'].$pic['pic'];
			}
		}
	} else {
		$url = $pic['pic'];
	}
	if($url && $pic['friend']==4) {
		$url = 'image/nopublish.jpg';
	}
	return $url;
}

?> 
