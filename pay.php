<?php
/*
VIP积分充值系统
var 1.5
*/
include_once('./common.php');
//是否关闭站点
checkclose();
checklogin();
//允许的方法
      $vip_info = $_SCONFIG[payname];
      $vip_var = $_SCONFIG[payvar];
         $vparr = array(
		      "1" => "积分充值",
			  "2" => "购买VIP",
			  "3" => "积分兑换VIP",
			  "4" => "<span style='color: #0000CC;font-weight: bold;'>管理员操作</span>",
			  "5" => "<span style='color: #FF0000;font-weight: bold;'>管理员操作</span>"
			  );
		  $dayarr = array(
		          '3' => '3',
		          '15' => '15',
		          '30' => '30',
		          '60' => '60',
		          '120' => '120',
		          '240' => '240',
		          '360' => '360'
		          );
           $bankinfo = array(
               '1' => '财付通',
               '2' => '网银在线',
               '3' => '支付宝',
               '4' => '易宝手机充值卡',
               '5' => '易宝手机银行卡',
               '6' => 'PayPal',
			    );
           $acs = array('index','card','list','appay','tenpay','chinabank','clpay','clbpay','pppay','vip','buyvip','info','vipconfig','config','ctvip','ctinfo','cfhelp','install','userlist','v','c','update');
        $ac = (empty($_GET['ac']) || !in_array($_GET['ac'], $acs))?'index':$_GET['ac'];
        $pay = (empty($_GET['pay']) || !in_array($_GET['pay'], $acs))?'':$_GET['pay'];
     //检查权限
       $founders = empty($_SC['founder'])?array():explode(',', $_SC['founder']);
       $adid = in_array($_SGLOBAL['supe_uid'], $founders);
        $v_uid = $_GET[u] != "" ? $_GET[u] : $_SGLOBAL['supe_uid'];
        $space = getspace($v_uid);
  if (intval($adid) > 0) 
   {
  if ($pay == "buyvip" or $pay == "ctvip") 
   {
	showmessage('当前用户为管理员.','pay.php?ac=config');
	 }
	  }
        if (!file_exists("./pay/pay_install.php") and $ac == 'install') {
		
	showmessage('出错:"./pay/pay_install.php"安装文件不存在.','pay.php',3);
		} elseif ($_SCONFIG[vipapp] == '') {
		        
        if (!file_exists("./pay/pay_install.php")) {
		
	showmessage('出错:"./pay/pay_install.php"安装文件不存在.','index.php',3);
		} else {
                include_once(S_ROOT."./pay/pay_install.php");
	            exit;
				}
				
		}
		if ($ac == "ctvip" and $_SCONFIG[ctvip] <= 0) showmessage('积分兑换VIP已关闭.转向充值页面','index.php?ac=buyvip','5');
//兑换服务类型

        $ctcls = explode("\n", $_SCONFIG['vipjf']);
      while (list($key,$value) = each($ctcls)) 
       {
        $ctinfo = explode("|", $value);
        $ctarr[$key] = $ctinfo;
         }
//服务类型
        $vipcls = explode("\n", $_SCONFIG['vipcls']);
     while (list($key,$value) = each($vipcls)) 
	 {
        $vipinfo = explode("|", $value);
        $vcmey = ($vipinfo[1] <= 0) ? 0 : $vipinfo[1];
        $vipinfo[3] = intval($vcmey * (intval($vipinfo[2])/100));
        $vipinfo[4] = intval($vcmey - $vipinfo[3]);
        $varr[$key] = $vipinfo;
         }
	    $cvip = intval($_POST[cvip]);
		$cpay = intval($_POST[cpay]);
        $vipinfo['pmey'] = $varr[$cvip][3];
        $vipinfo['day'] = $varr[$cvip][0];
        $ctinfo['vjf'] = $ctarr[$cvip][0];
        $ctinfo['vday'] = $ctarr[$cvip][1];
//获取用户信息
if ($ac != "install") {
	$query = $_SGLOBAL['db']->query("SELECT (vday*3600*24-(". time() ." - stime)) as vday FROM ".tname('vipspace').
	" WHERE vuid='".$v_uid."'");
	$v_day = $_SGLOBAL['db']->fetch_array($query);
		$v_day = ckvip($v_uid);
	}
		$v_day[vday] = intval($v_day[vday]);
		
         if ($ac == 'list') {
       $_TPL['titles'][] = "交易明细";
          } elseif ($ac == 'config') {

       $_TPL['titles'][] = "充值配置";
          } elseif ($ac == 'vipconfig') {

       $_TPL['titles'][] = "VIP功能配置";
          } elseif ($ac == 'vip') {

       $_TPL['titles'][] = "VIP特权";
          } elseif ($ac == 'userlist') {

       $_TPL['titles'][] = "会员管理";
          } elseif ($ac == 'cfhelp') {

       $_TPL['titles'][] = "程序安装";

          } else {
       $_TPL['titles'][] = "在线充值";
          }
       $_TPL['titles'][] = "支付中心";
//菜单
       if ($cpay == 1) {
       $paypost = $_SCONFIG[tfile]."/index.php";
           } elseif ($cpay == 2) {
       $paypost = $_SCONFIG[cbfile]."/Send.php";
           } elseif ($cpay == 3) {
       $paypost = $_SCONFIG[apfile]."/index.php";
           } elseif ($cpay == 4) {
       $paypost = $_SCONFIG[clfile]."/req.php";
           } elseif ($cpay == 5) {
       $paypost = $_SCONFIG[clbfile]."/req.php";
           } elseif ($cpay == 6) {
       $paypost = $_SCONFIG[ppfile]."/paypal.php";
           } elseif ($pay == 'v' or $pay == 'c') {
       $paypost = "pay.php?ac=userlist";
           } 
		   //积分兑换VIP
    if (!empty($_POST[ctsubmit])) 
   {
      
		if ($_SCONFIG[ctvip] <= 0) showmessage('积分兑换VIP已关闭.转向充值页面','index.php?ac=buyvip','5');
   upvip($space[uid],$ctinfo['vday'],$ctinfo['vjf'],3);

	showmessage('兑换成功.','pay.php?pay=ctvip');
                   }
				  
				   
//取会员组

        $sql = $_SGLOBAL['db']->query("select * from ".tname('usergroup')." where system='1'");
      while ($row = $_SGLOBAL['db']->fetch_row($sql)) 
	  {
             $userapp[$row[0]] = $row[1];
         }
	   $actives = array($ac => ' class="active"');
     include_once(S_ROOT."./pay/pay_{$ac}.php");
?>