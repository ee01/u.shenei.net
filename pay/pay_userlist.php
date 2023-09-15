<?php
/*
	安装程序 执行完请删除
	author:冯超养
	website:http://xekee.com
	description:本插件业余时间完成 可能还存在不足和BUG 希望大家一起完善他
*/

if(!defined('IN_UCHOME')) {
	exit('Access Denied');
}


    if (intval($adid) <= 0){ 
	showmessage('普通会员无此权限');
          }

    if (!empty($_POST[openvip])) 
   {
   //upvip(用户ID,天数,消费积分,消费类型 3用户兑换VIP 4管理员激活VIP 5管理员取消VIP状态);
   upvip($_POST[uid],($_POST[v_day]),0,4);
	showmessage('升级成功.','pay.php?ac=userlist');
           }
    if (!empty($_POST[disablevip])) 
   {
   upvip($_POST[uid],intval($_POST[v_day]),0,5);
	showmessage('取消成功.','pay.php?ac=userlist');
           }
     if (!empty($_POST[Submit]))
	 { 
          $v_username = $_POST[s_users];
		  $cdl = (intval($_POST[s_class]) == 1) ? " selected=\"selected\"" : "";
		  $sdata = (intval($_POST[s_class]) == 1) ? " and s.groupid='$_SCONFIG[vipapp]'" : "";
          $sdata .= ($v_username != "") ? " and s.username LIKE '%$v_username%'" : "";
       }
          $pid = intval($_GET[pid]) <= 0 ? 0 : intval($_GET[pid]);
		  $vlist = array();
          $max = $pid*10;
	$query = $_SGLOBAL['db']->query("SELECT s.*,f.sex,f.email,g.grouptitle,g.system FROM ".tname('spacefield')." f,".tname('space').
	" s,".tname('usergroup')." g WHERE g.gid=s.groupid and g.system!='-1' and f.uid=s.uid".$sdata." order by s.uid desc limit ".$max.",10");
	while ($value = $_SGLOBAL['db']->fetch_array($query)) 
	{   
	    
	      if ($value[sex] == 1) { 
		     $value[sex] = "男";
		  } elseif ($value[sex] == 2) {
		     $value[sex] = "女";
		  } else {
		     $value[sex] = "未设置";
		  }
		  $value[name] = $value[name] == '' ? "无名氐" : $value[name];
		  if ($value[groupid] == $_SCONFIG[vipapp]) {
		  $vday = ckvip($value[uid]);
		  $value[vday] =  "剩余:".ceil($vday[ztime]/3600/24)."天";
		  } else {
		  $value[vday] =  "未激活";
		  }
          $vlist[] = $value;
	      $vlist['cout']++;
	   }
	      $vlist['cout'] += 0;
          $vlist['mid'] = $pid-1;
          $vlist['mad'] = $pid+1;
		if ($vlist['cout'] < 1 and $pid > 0) showmessage("","pay.php?ac=userlist&&pid=".$vlist['mid'],0);
		
include template('pay_userlist');

?>