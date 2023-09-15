<?php

//Powered by Jiekii.com
//QQ：357754800

//类别
$gEumsType = array(1 => '新闻事件', 2 => '真理格言', 3 => '名人名言', 4 => '影视台词', 5 => '名人随语', 6 => '广告口号', 7 => '经典短信', 8 => '幽默搞笑', 9 => '爱情语录', 10 => '诗词歌赋' );

//最大分值
$gMaxScore = 0;
//系统管理员ID，请修改成管理员ID，这样能删除所有人发布的语录
define("ADMIN_ID","1");	

include_once('./common.php');

//是否关闭站点
checkclose();
//获取当前用户的空间信息
$space = getspace($_SGLOBAL['supe_uid']);
//添加菜单
window_set1('分类信息', "ana.php"); 

//允许动作
$dos = array('ana', 'cp');
//允许的方法
$acs = array(
'ana' => array('index', 'post', 'view', 'cp'),
'cp' => array('post', 'update', 'del')
);


$gEumsStatus = array(1 => '未鉴定', 2 => '已鉴定' );


//更新活动session
if($_SGLOBAL['supe_uid']) {
        getmember(); //获取当前用户信息
        updatetable('session', array('lastactivity' => $_SGLOBAL['timestamp']), array('uid'=>$_SGLOBAL['supe_uid']));
}

//添加窗口
window_set1("经典语录", 'ana.php');


//获取变量
$isinvite = 0;

$do = (!empty($_GET['do']) && in_array($_GET['do'], $dos))? $_GET['do'] : 'ana';
$ac = (!empty($_GET['ac']) && in_array($_GET['ac'], $acs[$do])) ? $_GET['ac'] : 'index';
if (empty($do)) {
	showmessage('no_app_do' );
}

if( @file_exists(S_ROOT."./ana/ana_install.php") ) {
	$ac = "install";
}

//是否公开
checklogin();//需要登录

//获取空间
if(empty($_SGLOBAL['supe_uid'])) {
	ssetcookie('_refer', rawurlencode($_SERVER['REQUEST_URI']));
	showmessage('to_login', 'do.php?ac=login', 0);
}
$uid = $_SGLOBAL['supe_uid'];
 

function updatecredit($uid, $credit, $method='+')
{
	global $_SGLOBAL;
	$credit = intval($credit);
	if (empty($credit)) {
		return;
	}
	$sqlcredit = ($credit > 0) ? " {$method} {$credit} " : " {$method} {$credit} ";
	$newcredit = $num * $credit;
	$_SGLOBAL['db']->query("UPDATE ".tname('space')." SET credit=credit {$sqlcredit} WHERE uid = $uid ");
	//$_SGLOBAL['db']->query("UPDATE ".tname('session')." SET credit=credit {$sqlcredit} WHERE uid = $uid ");
}

//处理
include_once(S_ROOT."./ana/{$do}_{$ac}.php");
function window_set1($a,$b='',$c='')
{
	return ;
}
?>