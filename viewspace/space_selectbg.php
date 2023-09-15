<?php 
if(!defined('IN_UCHOME')) {
	exit('Access Denied');
}
header('Content-type: text/xml');

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
  $xmlstr  .=' <?xml version="1.0" encoding="utf-8" ?>
'; 
} 
else 
{ 
  $xmlstr  .=' <?xml version="1.0" encoding="'; 
  $xmlstr  .=$encoding; 
  $xmlstr  .='" ?>
'; 
} 
$xmlstr .=' <root><![CDATA[<ul class="imglist '.$css.'">
'; 
// 调用遍历数组函数 
$xmlstr .=' <li><a target="mainyinyue" href="javascript:void(0);"><img src="viewspace/img/tool/pic_nobgmax.gif" onclick="changebg(this, 1);" /></a></li>
'; 
$xmlstr .= get_all($start,$perpage,$page)
;

return $xmlstr; 
} 




function get_all($offset = 0, $limit = 5,$page) 
{ 
$type = empty($_GET['type'])?'0':$_GET['type'];

 
$term =" albumid='$id' ";

$arrString = '';
global $_SGLOBAL;
$sql="SELECT * FROM ".tname('pic')." where {$term} ORDER BY dateline DESC LIMIT $offset, $limit";
$feedlist = array();
$query = $_SGLOBAL['db']->query($sql);
while ($value = $_SGLOBAL['db']->fetch_array($query)) {
	$feedlist[] = $value;
}
$num = $_SGLOBAL['db']->result($_SGLOBAL['db']->query("SELECT COUNT(*) FROM ".tname('app_viewspace_p')." WHERE {$term}"),0);
foreach ($feedlist as $key => $value) {
$photo=$value[photo];
$id=$value[id];
$arrString.='<li><a target="mainyinyue" href="javascript:void(0);" ><img src="viewspace/picfile/thumb/'.$photo.'" id="a_pic_'.$id.'" onclick="changebg(this);"/></a></li>
';
}
$arrString.=' </ul>
';
$arrString.=' <div class="floatpage"><div class="pages">';
$arrString.= pp($num,$limit,$page);
$arrString.='</div></div>]]></root>
';
return $arrString; 
} 

function pp($num, $perpage, $curpage) {
$type = empty($_GET['type'])?'0':$_GET['type'];
$color = empty($_GET['color'])?'0':$_GET['color'];
$category = empty($_GET['category'])?'0':$_GET['category'];
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
		$multipage = ($curpage - $offset > 1 && $pages > $page ? '<a target="mainyinyue" href="javascript:void(0);" onclick="getpage(1,\'viewspace.php?do=getsyspic&type='.$type.'&category='.$category.'&color='.$color.'&inajax=1\');">1 ...</a>' : '').
			($curpage > 1 ? '<a target="mainyinyue" href="javascript:void(0);" onclick="getpage('.($curpage - 1).',\'viewspace.php?do=getsyspic&type='.$type.'&category='.$category.'&color='.$color.'&inajax=1\');">&lsaquo;&lsaquo;</a>' : '');
		for($i = $from; $i <= $to; $i++) {
			$multipage .= $i == $curpage ? '<strong>'.$i.'</strong>' :
				'<a target="mainyinyue" href="javascript:void(0);" onclick="getpage('.$i.',\'viewspace.php?do=getsyspic&type='.$type.'&category='.$category.'&color='.$color.'&inajax=1\');">'.$i.'</a>';
		}
		$multipage .= ($curpage < $pages ? '<a target="mainyinyue" href="javascript:void(0);" onclick="getpage('.($curpage + 1).',\'viewspace.php?do=getsyspic&type='.$type.'&category='.$category.'&color='.$color.'&inajax=1\');">&rsaquo;&rsaquo;</a>' : '').
			($to < $pages ? '<a target="mainyinyue" href="javascript:void(0);" onclick="getpage('.$pages.',\'viewspace.php?do=getsyspic&type='.$type.'&category='.$category.'&color='.$color.'&inajax=1\');">... '.$realpages.'</a>' : '');
		$multipage = $multipage ? ('<em>&nbsp;'.$num.'&nbsp;</em>'.$multipage):'';
	}
	$maxpage = $realpages;
	return $multipage;
}

?> 
