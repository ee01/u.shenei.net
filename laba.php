<?php
//公用的动作
$gacts = array(1=>'大喊',2=>'宣布',3=>'私语',4=>'狂笑',5=>'嘿咻',6=>'感概',7=>'惊叹',8=>'YY',9=>'寻找');

//时间设定
$gtimes = array(3,4,5,6,7,8,9,10);

//多显示一秒花费的金币
$gcost = 5;
//最大分值
$gMaxScore = 200;
//系统管理员ID，请修改成管理员ID，这样能删除所有人发布的问答
define("ADMIN_ID","1");

include_once('./common.php');
include_once(S_ROOT.'./source/function_cp.php');

//是否关闭站点
checkclose();
checklogin();
//获取空间信息
$space = getspace($_SGLOBAL['supe_uid']);
if(empty($space)) {
	showmessage('space_does_not_exist');
}
//实名认证
ckrealname('ask');
//需要上传头像
if($_SCONFIG['need_avatar'] && empty($space['avatar'])) {
	if(empty($return)) showmessage('no_privilege_avatar');
	$result = false;
}
//获取当前用户的空间信息
$space = getspace($_SGLOBAL['supe_uid']);
//允许动作
$dos = array('cp');

//更新活动session
if($_SGLOBAL['supe_uid']) {
        getmember(); //获取当前用户信息
        updatetable('session', array('lastactivity' => $_SGLOBAL['timestamp']), array('uid'=>$_SGLOBAL['supe_uid']));
}


//获取变量
$isinvite = 0;

$do = (!empty($_GET['do']) && in_array($_GET['do'], $dos))? $_GET['do'] : 'cp';
if (empty($do)) {
	showmessage('no_app_do' );
}
//是否公开
checklogin();//需要登录

//获取空间
if(empty($_SGLOBAL['supe_uid'])) {
	ssetcookie('_refer', rawurlencode($_SERVER['REQUEST_URI']));
	showmessage('to_login', 'do.php?ac=login', 0);
}
$uid = $_SGLOBAL['supe_uid'];
 

function updatecredit($uid, $credit, $method='+'){
	global $_SGLOBAL;
	$credit = intval($credit);
	if (empty($credit)) {
		return;
	}
	$sqlcredit = ($credit > 0) ? " {$method} {$credit} " : " {$method} {$credit} ";
	$newcredit = $num * $credit;
	$_SGLOBAL['db']->query("UPDATE ".tname('space')." SET credit=credit {$sqlcredit} WHERE uid = $uid ");
}

//处理
include_once(S_ROOT."./laba/{$do}.php");
?>