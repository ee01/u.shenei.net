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
		$arr = array
		      (
			  '0' => '会员及功能',
			  '1' => 'VIP图标',
			  '2' => '搜索优先显示',
			  '3' => '查看联系方式',
			  '4' => '个人空间大小<br>(Mb)',
			  '5' => '最多好友数<br>(个)',
			  '6' => '两次搜索时间<br>(秒)',
			  '7' => '两次搜索时间间隔<br>(秒)'
			   );
				  if ($_SCONFIG[allowdomain] == 1) $arr[] = '二级域名长度<br>(字符)';
		       $carr = array();
          $sql = $_SGLOBAL['db']->query("select * from ".tname('usergroup').
		  " where system>='0' or gid='".intval($_SCONFIG[vipapp])."'");
         while ($value = $_SGLOBAL['db']->fetch_array($sql)) 
		{
				      if ($value[maxattachsize] == 0) $value[maxattachsize] = "<span class=\"STYLE1\">无限制</span>";
				      if ($value[maxfriendnum] == 0) $value[maxfriendnum] = "<span class=\"STYLE1\">无限制</span>";
				      if ($value[searchinterval] == 0) $value[searchinterval] = "<span class=\"STYLE1\">无限制</span>";
				      if ($value[postinterval] == 0) $value[postinterval] = "<span class=\"STYLE1\">无限制</span>";
				      if ($value[domainlength] == 0) $value[domainlength] = "<font color='red'>禁止使用</font>";
			      if ($value[gid] == intval($_SCONFIG[vipapp]))
				  {
				          $value[vus] = intval($_SCONFIG[vipyx]) <= 0 ? "<span class=\"STYLE1\">√</span>" : "<b>X</b>";
				          $value[vip] = "<img src=\"image/pay/vip.gif\" border=\"0\" />";
			              $varr = $value;
				    } else {
				          $value[vus] = "<b>X</b>";
				          $value[vip] = "<img src=\"image/pay/vip2.gif\" border=\"0\" />";
			              $carr[] = $value;
				    }
		     }
			  $carr[] = $varr;
   $buyurl = ($space[credit] > 0 and $_SCONFIG[ctvip] == 1) ? "pay.php?pay=ctvip" : "pay.php?pay=buyvip";
include template('pay_vip');

?>
