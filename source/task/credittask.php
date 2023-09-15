<?php
/*
	[UCenter Home] (C) 2007-2008 Comsenz Inc.
	$Id: renzheng.php 8401 2008-08-12 15:12:55Z PopLong $
*/



if(!defined('IN_UCHOME')) {
	exit('Access Denied');
}

//判断是否通过实名认证

$space['showcredit'] = getcount('show', array('uid'=>$space['uid']), 'credit');
$space['showcredit'] = intval($space['showcredit']);

if($space[credit] <= 500 && $task[dateline] <= time()-3600 && $space['showcredit']<=1000) {
	$money = rand(1,rand(1,13));
	if		($money==11){$money=rand(20,50);}
	elseif	($money==12){$money=rand(50,100);}
	elseif	($money==13){$money=rand(100,200);}
	updatecredit($_SGLOBAL['supe_uid'], $money, $method='+');
	$moneynow = $space[credit]+$money;

	$task['done'] = 1;//活动完成
	$task['result'] = '
	<font color=red size=3><strong>哇塞！点一下就有 <font size=5>'.$money.'</font> 积分了～<font size=2>您现在已经有 <font size=3>'.$moneynow.'</font> 个积分啦！</font></strong></font><br><br>
	<font color=red size=2><b>别忘了在<font size=3>一小时</font>后再来噢～</b></font><br><br>
	<font size=2><b>随机小秘籍：<br>1-10分概率较大，一定概率获得20-50分，也有可能随机到50-100，如果RP及其好的话可以获得100-200积分！</b></font>
			';
	
	

} else {

	//活动完成向导
	$reason = $space['showcredit']<=1000?'您的积分超出了本活动的积分限制，请在积分低于500时参与本活动获取积分补偿！':'您的财产(竞价排行积分)超出了本活动的财产限制(1000分)<br>　您可以让好友访问你的博客以花费竞价积分（增加博客的访问次数噢~<br>　　再来参与本活动获取积分补偿！~~';
	$task['guide'] = '
		<span style="color:red;">'.$reason.'</span><br><br>
		<strong>积分有什么用呢？</strong>
		<ul class="task">
		<li>1. 购买功能丰富的道具；</li>
		<li>2. 增加竞价积分让大家都看到你；</li>
		<li>3. 使你的日志装扮的更漂亮；</li>
		<li>4. 下载网盘原创资源；</li>
		<li>5. 发布悬赏投票让大家告诉你答案；</li>
		<li>6. 在个人主页埋下红包吸引别人来访问你的博客；</li>
		<li>7. 增加空间好友容量和相册容量；</li>
		<li>8. 购买VIP特权（后续功能开发ing...）；</li>
		<li>9. 兑换一些虚拟或实物奖品（后续功能开发ing...）；</li>
		<li>10. 更多消费途径自己发现哈！；</li>
		<br><br><span style="color:orangered;"><b>请不要恶意灌水刷积分，如发现有刷积分现象者一律封账号，谢谢合作！</b></span>
		</ul>';
/*
		<strong>获取积分的方式</strong>
		<ul class="task">
		<li>1. 发布日志；</li>
		<li>2. 发布评论/留言；</li>
		<li>3. 发起话题；</li>
		<li>4. 发布回帖；</li>
		<li>5. 邀请好友注册成功；</li>
		<li>6. 参与有奖活动；</li>
		<br><br><span style="color:red;"><b>请不要恶意灌水刷积分，如发现有刷积分现象者一律封账号，谢谢合作！</b></span>
		</ul>';
*/

}

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
?>