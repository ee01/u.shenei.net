<?php 
if(!defined('IN_UCHOME')) {
	exit('Access Denied');
}
header   ("Expires:   Mon,   26   Jul   1997   05:00:00   GMT");         //   Date   in   the   past   
header   ("Last-Modified:   "   .   gmdate("D,   d   M   Y   H:i:s")   .   "   GMT");   
header   ("Cache-Control:   no-cache,   must-revalidate");     //   HTTP/1.1   
header   ("Pragma:   no-cache");   
$u = $_SGLOBAL['supe_uid'];
if($_GET['type']=='sharestyle')
{
header('Content-type: text/xml');
$pp = toXml(); 
echo $pp; 
}
elseif($_GET['type']=="fastdiy")
{
header('Content-type: text/xml');
$pp = toXmlfastdiy(); 
echo $pp; 
}
elseif($_GET['type']=="userstyle")
{
$styleid = $_GET['styleid'];
header('Content-type: text/xml');
$pp = toXmluserstyle($u,$styleid); 
echo $pp; 
}
elseif($_GET['type']=="mystyle")
{
$styleid = 0;
header('Content-type: text/xml');
$pp = toXmluserstyle($u,$styleid); 
echo $pp; 
}
elseif($_GET['op']=="changename")
{
$chgstyleid=$_POST['chgstyleid'];
$newstylename=$_POST['newstylename'];
global $_SGLOBAL;
$_SGLOBAL['db']->query( "update ".tname('app_viewspace_suser')." set name='$newstylename' where uid='$u' and id='$chgstyleid'" );

//header("location:viewspace.php?do=mystyle&inajax=1");
}
elseif($_GET['op']=="edit")
{
$styleid = $_GET['styleid'];
global $_SGLOBAL;
$_SGLOBAL['db']->query( "update ".tname('app_viewspace_suser')." set status=0 where uid='$u' and status=1" );
$_SGLOBAL['db']->query( "update ".tname('app_viewspace_suser')." set status=1 where uid='$u' and id=$styleid" );


$value=query_one("select * from ".tname('app_viewspace_suser')." where id ='$styleid'");
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


if(@$fp = fopen('viewspace/css/'.get_userURL1($u), 'w')) {
fwrite($fp, $css);
fclose($fp);
} else {
	exit('Can not write to cache files, please check directory ./forumdata/ and ./forumdata/cache/ .');
}


header("location:viewspace.php?op=diy");

}

elseif($_GET['op']=="use")
{
$styleid = $_GET['styleid'];
global $_SGLOBAL;
$_SGLOBAL['db']->query( "update ".tname('app_viewspace_suser')." set status=0 where uid='$u' and status=1" );
$_SGLOBAL['db']->query( "update ".tname('app_viewspace_suser')." set status=1 where uid='$u' and id=$styleid" );


$value=query_one("select * from ".tname('app_viewspace_suser')." where id ='$styleid'");
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


if(@$fp = fopen('viewspace/css/'.get_userURL1($u), 'w')) {
fwrite($fp, $css);
fclose($fp);
} else {
	exit('Can not write to cache files, please check directory ./forumdata/ and ./forumdata/cache/ .');
}

header("location:viewspace.php");
}


elseif($_GET['op']=="del")
{
$styleid = $_GET['styleid'];
global $_SGLOBAL;
$_SGLOBAL['db']->query( "delete from ".tname('app_viewspace_suser')." where id='$styleid' and uid='$u'" );
header("location:viewspace.php?do=mystyle&styleid=$styleid&inajax=1");
}
elseif($_GET['op']=="share")
{
$styleid=$_POST['styleid'];
$stylename=$_POST['stylename'];
$stylecategory=$_POST['stylecategory'];
$stylecolor=$_POST['stylecolor'];
$time=GmtToUnix(date("Y-m-d H:i:s"));
global $_SGLOBAL;
$_SGLOBAL['db']->query( "update ".tname('app_viewspace_suser')." set name='$stylename',category='$stylecategory',color='$stylecolor',share=1,date=$time where uid='$u' and id='$styleid' " );


header('Content-type: text/xml');
$pp = toXmlmydiy($u); 
echo $pp; 

}
else
{
header('Content-type: text/xml');
$pp = toXmlmydiy($u); 
echo $pp; 

}



function toXml() 
{ 
$styleid = $_GET['styleid'];
if($styleid=='-1')
{
$css='';
}
else
{
$value=get_share($styleid);
$effectall=unserialize($value[effectall]);
$block=unserialize($value[block]);
$style=array(
'effectall'=>$effectall,
'block'=>$block,
);
$css=outframeeffectall($style,1);
$css.=outframeblock($style,1);
}
$xmlstr = ''; 
$xmlstr .='<?xml version="1.0" encoding="gbk" ?>
'; 
$xmlstr .='<root><![CDATA['; 
$xmlstr .=$css; 
$xmlstr.=']]></root>
';
return $xmlstr; 
} 



function toXmlfastdiy() 
{ 
$fastkey = $_GET['fastkey'];
$Cfg=new Viewcfg();
if($fastkey=="black")
{
$cfgstyle=$Cfg->cfgblack;
}
if($fastkey=="blue")
{
$cfgstyle=$Cfg->cfgblue;
}
if($fastkey=="green")
{
$cfgstyle=$Cfg->cfggreen;
}
if($fastkey=="pink")
{
$cfgstyle=$Cfg->cfgpink;
}
if($fastkey=="white")
{
$cfgstyle=$Cfg->cfgwhite;
}
if($fastkey=="yellow")
{
$cfgstyle=$Cfg->cfgyellow;
}
$effectall=unserialize($cfgstyle[effectall]);
$block=unserialize($cfgstyle[block]);
$cursor=unserialize($value[cursor]);
$style=array(
'effectall'=>$effectall,
'block'=>$block,
'cursor'=>$cursor,
);
$css=outframeeffectall($style,1);
$css.=outframeblock($style,1);
$css.=outframecursor($style,1);
$xmlstr = ''; 
$xmlstr .='<?xml version="1.0" encoding="gbk" ?>
'; 
$xmlstr .='<root><![CDATA['; 
$xmlstr .=$css; 
$xmlstr.=']]></root>
';
return $xmlstr; 
} 



function toXmluserstyle($u,$styleid) 
{ 

global $_SGLOBAL;
if($styleid!="0")
{
$value=query_one("select * from ".tname('app_viewspace_suser')." where id ='$styleid'");
}
else
{
$value=query_one("select * from ".tname('app_viewspace_suser')." where uid ='$u' and status=1");
}
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

$xmlstr = ''; 
$xmlstr .='<?xml version="1.0" encoding="gbk" ?>
'; 
$xmlstr .='<root><![CDATA['; 
$xmlstr .=$css; 
$xmlstr.=']]></root>
';
return $xmlstr; 
} 




function toXmlmydiy($u) 
{

global $_SGLOBAL;
$sql="SELECT * FROM ".tname('app_viewspace_suser')." where uid=$u order by id desc";
$feedlist = array();
$query = $_SGLOBAL['db']->query($sql);
while ($value = $_SGLOBAL['db']->fetch_array($query)) {
	$value['name']=shtmlspecialchars($value['name']);
	$feedlist[] = $value;
}
foreach ($feedlist as $key => $value) {

$name=$value[name]==""?"自定义模版$value[id]":$value[name];

$csss=$value[status]=="0"?"state_off":"state_on";
$arrString.='<tr>
';
$arrString.='<td class="title"><a  target="mainyinyue" href="javascript:void(0);" onClick="$(\'selectmystyle\').value=\''.$value[id].'\';switchstyle('.$value[id].', \'userstyle\');" class="'.$csss.'" title="点击预览" id="name_.$value[id].">'.$name.'</a>[<a  target="mainyinyue" href="javascript:void(0);" id="chg_'.$value[id].'" onClick="showchgname(this, '.$value[id].', \''.$name.'\');">改名</a>]</td>
';
if($value[share]!="1")
{
$dell=$value[status]=="0"?" [<a target=\"mainyinyue\" href=\"javascript:void(0);\" onclick=\"if(confirm('删除后将不能恢复。') == true) {stylenum=0; getstylelist('viewspace.php?do=mystyle&op=del&styleid=$value[id]'); } else { return false;}\">删除</a>]":"";
$arrString.='<td>[<a href="viewspace.php?do=mystyle&op=edit&styleid='.$value[id].'">编辑</a>]'.$dell.' [<a id="share_id"  target="mainyinyue" href="javascript:void(0);" onclick="if(confirm(\'在风格审核完成24小时后才能编辑此风格。确定要分享吗？\') == true) share_mystyle(\''.$value[id].'\', \''.$name.'\');">分享</a>] </td>
';
}
else
{
$arrString.='<td>
';
$arrString.='[编辑]
';
$arrString.='[删除]
';
$arrString.='[分享 审核中...]
';
$arrString.='</td>
';
}
$dateline=date('Y-m-d H:i:s',$value[date]);
$arrString.='<td class="time">'.$dateline.'</td>
';
$arrString.='</tr>
';

}


$xmlstr = ''; 
$xmlstr .='<?xml version="1.0" encoding="gbk" ?>
'; 
$xmlstr .='<root><![CDATA[<form id="chgnameform" method="post" target="mainyinyue" action="viewspace.php?do=mystyle&op=changename" onkeydown="if(event.keyCode==13) ajaxpost(\'chgnameform\', \'mystylelist\', \'\');">'; 
$xmlstr .='<table border="0" cellpadding="0" cellspacing="1" class="mystyle">
'; 
$xmlstr .='<tr><th>风格模板名称 </th><th>操作 </th><th>最后编辑时间 </th></tr>
'; 
$xmlstr .=$arrString; 
$xmlstr .='</table></form>
';
$xmlstr .='<div id="chgname_inner" style="display:none">
';
$xmlstr .='<input type="text" name="newstylename" id="newstylename" class="chgtitle" value="" onkeydown="if(event.keyCode==13) ajaxpost(\'chgnameform\', \'mystylelist\', \'\');" >
';
$xmlstr .='<input type="hidden" name="chgstyleid" id="chgstyleid" value="">
';
$xmlstr .='<input type="button" name="add" id="add" value="OK" class="submit" onclick="ajaxpost(\'chgnameform\', \'mystylelist\', \'\')" />
';
$xmlstr.='</div>]]></root>
';
return $xmlstr; 
}


?> 
