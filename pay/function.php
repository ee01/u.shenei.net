<?php

/*
	安装程序 执行完请删除
	author:冯超养
	website:http://xekee.com
	description:本插件业余时间完成 可能还存在不足和BUG 希望大家一起完善他
*/

/*
取VIP购买信息
$sum
*/
   function get_vipday ($sum) {
         global $_SCONFIG;
             $vipcls = explode("\n", $_SCONFIG['vipcls']);
   
  while (list($key,$value) = each($vipcls))
    {
      if ($key == $sum)
	       {
             $vipinfo = explode("|", $value);
             $vcmey = ($vipinfo[1] <= 0) ? 0 : $vipinfo[1];
			 $vipinfo[1] = intval($vcmey*($vipinfo[2]/100));
          break;
                }
         }
	      return $vipinfo;
    }
/*
更新充值记录
$paynoid:订单号
$paynote:返回内容 x:x:x
$money:金额
*/
   function pay_update ($payid,$paynote,$money) {
   global $_SGLOBAL,$_SCONFIG;
			//支付成功
			  date_default_timezone_set("PRC"); 
			   $money = intval($money);
			   $payid = intval($payid);
               $paytime = date("YmdHis");
			   
               $payarr = explode(":",$paynote);            //0 充值类型 1 用户ID 2 VIP类型
               $space = getspace(intval($payarr[1]));      //取用户信息
               $ty_pay = (intval($payarr[3]) <= 0) ? $payarr[2] : $payarr[3];
               $bilv = (intval($ty_pay) == 6) ? intval($_SCONFIG[ppbilv]) : intval($_SCONFIG[bilv]);            //比率
               $credit = $money*$bilv;                     //取充值积分
			   $varr = get_vipday(intval($payarr[2]));
			   $vday = $varr[0];     //取购买VIP天数
      $sql = $_SGLOBAL['db']->query("select * from ".tname('mardpay')." where payno='$payid'");
      $row = $_SGLOBAL['db']->fetch_array($sql);
	  

   if (empty($row[payno]))
 {
//入充值记录
          if (intval($payarr[0]) == 1) 
		{
		//在线充值
                $paytext = "充值".$money."元(获得".$credit."积分).";
                           $_SGLOBAL['db']->query("update ".tname('space').
						   " set credit=credit+".$credit." where uid='".$space[uid]."'");
              } elseif (intval($payarr[0]) == 2) {
			  
	                      vip_update($payarr[1],$_SCONFIG[vipapp],$vday,$paytime);//vip_update(用户ID,会员组ID,VIP天数,VIP开始时间)
                $paytext = "购买VIP".$vday."天(消费".$money."元).";
		//购买VIP
                 } else {
				 
             echo "标识返回错误,请与管理员联系.";
             exit;
                      }
		   switch (intval($ty_pay)) {
		     case 1:
                $paytext = "<b>财付通</b> ".$paytext;
             break;
		     case 2:
                $paytext = "<b>网银在线</b> ".$paytext;
             break;
		     case 3:
                $paytext = "<b>支付宝</b> ".$paytext;
             break;
		     case 4:
                $paytext = "<b>易宝手机充值卡</b> ".$paytext;
             break;
		     case 5:
                $paytext = "<br>易宝银行卡</b> ".$paytext;
             break;
		     case 6:
                $paytext = "<b>PayPal</b> ".$paytext;
             break;

		   }
                //记录订单
                     $tbarr = array(
					 "payno" => $payid,
					 "payuid" => $space[uid],
					 "paymcard" => $money,
					 "paynote" => $payarr[0],
					 "paytext" => $paytext,
					 "paytime" => $paytime
					 );
                          inserttable("mardpay",$tbarr);
              echo "已支付成功.";  		
	} else {
	echo "支付成功!请关闭此页面...";
	}
   }
/*
更新VIP充值记录
$vuid:用户ID
$vgid:VIP会员组ID
$vday:VIP天数
$vtime:VIP开始时间
*/
   function vip_update ($vuid,$vgid,$vday,$vtime) {
   global $_SGLOBAL;
   
              $sql = $_SGLOBAL['db']->query("select * from ".tname('vipspace').
	   " where vuid='$vuid'");
              $row = $_SGLOBAL['db']->fetch_array($sql);
      if (isset($row[vuid])) 
	{
                             $_SGLOBAL['db']->query("update ".tname('vipspace').
							 " set vday=vday+".$vday." where vuid='".$vuid."'");
           } else {
                             $_SGLOBAL['db']->query("update ".tname('space').
							 " set groupid=".$vgid." where uid='".$vuid."'");
                   $varr = array(
				   "vuid" => $vuid,
				   "stime" => time(),
				   "vday" => $vday,
				   "vtime" => $vtime,
				   "vgid" => $vgid
				   );
                        inserttable("vipspace",$varr);
                  }
   }
   
		   //积分开通VIP 
/*
$uid 用户ID
$vday 天数
$money 消费积分
$cid 消费类型 3用户兑换VIP 4管理员激活VIP 5管理员取消VIP状态
*/
		   function upvip($uid,$vday,$money,$cid) {
		   	  global $_SGLOBAL,$_SCONFIG;
			  date_default_timezone_set("PRC"); 
                 $space = getspace($uid);
				 $vday = (intval($vday) <= 0) ? 0 : intval($vday);
			  if ($cid == 3) {
      if ($space[credit] < $money) showmessage('积分不足.','pay.php?pay=buyvip');
          $_SGLOBAL['db']->query("update ".tname('space').
		  " set credit=credit-".$money." where uid='".$uid."'");
                 $paytext = "积分兑换VIP消费".$money."积分(增加".$vday."天VIP服务).";
				 } elseif ($cid == 4) {
                 $paytext = "激活VIP状态(增加".$vday."天VIP服务).";
				 } elseif ($cid == 5) {
                 $paytext = "取消VIP状态(减少".$vday."天VIP服务).";
				 }
               $paytime = date("YmdHis");
          $vsql = $_SGLOBAL['db']->query("select * from ".tname('vipspace').
		  " where vuid='$uid'");
          $vrow = $_SGLOBAL['db']->fetch_array($vsql);
	   
					   if ($cid < 5) {
      if (isset($vrow[vuid])) 
	  {
	  //存在VIP记录
          $_SGLOBAL['db']->query("update ".tname('vipspace')." set vday=vday+".$vday.
		" where vuid='".$uid."'");
		
           } else {
		   
	  //不存在VIP记录
        $_SGLOBAL['db']->query("update ".tname('space').
		" set groupid=".$_SCONFIG[vipapp]." where uid='".$uid."'");
		
                $varr = array(
				       "vuid" => $uid,
					   "stime" => time(),
					   "vday" => $vday,
					   "vtime" => $paytime,
					   "vgid" => $space[groupid]
					   );
                        inserttable("vipspace",$varr);
             }
						} elseif ($cid == 5) {
						if($vday >= $vrow[vday]) {
						
           $_SGLOBAL['db']->query("update ".tname('space').
		   " set groupid='$vrow[vgid]' where uid='".$uid."'");
           $_SGLOBAL['db']->query("DELETE FROM ".tname('vipspace')." where vuid='".$uid."'");
		                        } else {
           $_SGLOBAL['db']->query("update ".tname('vipspace').
		   " set vday=vday-$vday where vuid='".$uid."'");
                 $paytext = "减少".$vday."天VIP服务.";
								
								}
						}
        $paynoid = time();
        $tbarr = array(
			"payno" => $paynoid,
			"payuid" => $uid,
			"paymcard" => $money,
			"paynote" => $cid,
			"paytext" => $paytext,
			"paytime" => $paytime
			);
			inserttable("mardpay",$tbarr);

		   }
//检查VIP会员
/*
$uid 用ID 返回array()
*/
function ckvip($uid) {
	global $_SGLOBAL,$_SCONFIG;
        if (empty($_SCONFIG[vipapp])) return -1; 
			
            $space = getspace($uid);
     if ($_GET['ac'] != "install" and $space[groupid] == $_SCONFIG[vipapp]) {
	$query = $_SGLOBAL['db']->query("SELECT (vday*3600*24-(". time() ." - stime)) as ztime,vtime,vgid,vday FROM ".tname('vipspace')." WHERE vuid='$uid'");
	        $value = $_SGLOBAL['db']->fetch_array($query);
	
//VIP检查
              $vday = ceil($value[ztime]/3600/24);
       if ($vday <= 0) {
                 $value[day] = 0;
           if ($value[vgid] != '') {
           $_SGLOBAL['db']->query("update ".tname('space')." set groupid='$value[vgid]' where uid='".$uid."'");
                                      }
           $_SGLOBAL['db']->query("DELETE FROM ".tname('vipspace')." where vuid='".$uid."'");
                            } else {
                   $value[day] = $vday;
                              }
                         }
	return $value;
                       }
?>