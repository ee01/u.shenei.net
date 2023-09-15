<html>
<title>财付通支付系统</title>
<meta http-equiv="Cache-Control" content="no-cache"/> 
<body>
<?
define('IN_ADMINCP', TRUE);
include_once('../../common.php');  
include_once("tenpay_config.php");
$tenpay_conf = new tenpay_config();
echo $tenpay_conf->domain;

	function ShowExitMsg($msg,$iodata = NULL)
	  {
		global $tenpay_conf,$_SCONFIG;
	  	echo $tenpay_conf->beta_switch;
		if ($tenpay_conf->beta_switch =="0")
			{
				$strMsg="<body><html><meta name=\"TENCENT_ONELINE_PAYMENT\" content=\"China TENCENT\">\n";
			    $strMsg.= "<script language=javascript>\n";
			    $strMsg.= "window.location.href='".$_SCONFIG[payurl] . $_SCONFIG[tfile] ."/tenpay_show.php?msg=";
			    $strMsg.= $msg;
			    $strMsg.= "';\n";
			    $strMsg.= "</script></body></html>";
			    Exit($strMsg);
			 
			}
		else
			{
				
//				$tenpay_err = $iodata;
				echo  $msg,"<br/>";
				$htmlstring = "<html><body>".$iodata."</body></html>";
				echo $htmlstring,"<br/>";
			//	echo __file__,__line__;
			}
	  }

  import_request_variables("gpc", "frm_");
  /*取返回参数*/
  $strCmdno			= $frm_cmdno;
  $strPayResult		= $frm_pay_result;
  $strPayInfo		= $frm_pay_info;
  $strBillDate		= $frm_date;
  $strBargainorId	= $frm_bargainor_id;
  $strTransactionId	= $frm_transaction_id;
  $strSpBillno		= $frm_sp_billno;
  $strTotalFee		= $frm_total_fee;
  $strFeeType		= $frm_fee_type;
  $strAttach			= $frm_attach;
  $strMd5Sign		= $frm_sign;

$retcode = "0";
$retmsg ="支付成功";


//错误码信息
//retcode = "0"					 支付成功	
//retmsg = "支付成功"				

//retcode = "1"					 商户号错误
//retmsg = " 商户号错误"				

//retcode = "2"					签名错误
//retmsg = "签名错误"				

//retcode = "3"					 财付通返回支付失败	
//retmsg = "财付通返回支付失败"	  


  /*验签*/  $strResponseText  = "cmdno=" . $strCmdno . "&pay_result=" . $strPayResult . 
		                  "&date=" . $strBillDate . "&transaction_id=" . $strTransactionId .
			                "&sp_billno=" . $strSpBillno . "&total_fee=" . $strTotalFee .
			                "&fee_type=" . $strFeeType . "&attach=" . $strAttach .
			                "&key=" . $_SCONFIG[tenpaym];
  $strLocalSign = strtoupper(md5($strResponseText));  

  if( $strLocalSign  != $strMd5Sign)
  {
  	//验证MD5签名失败
	//植入业务逻辑处理，请注意金额单位是分，财付通有可能多次通知商户支付成功，需要对财付通的重复通知做去重处理
	$retcode = "2";
	$retmsg = "验证MD5签名失败";
    ShowExitMsg( "验证MD5签名失败.",$strResponseText); 
  }  

  if($_SCONFIG[tenpay] != $strBargainorId )
  {
  	//错误的商户号
    //植入业务逻辑处理，请注意金额单位是分，财付通有可能多次通知商户支付成功，需要对财付通的重复通知做去重处理

	echo $strBargainorId,"<br/>";
	echo $tenpay_conf->spid;
	$retcode = "1";
	$retmsg = "错误的商户号";
    ShowExitMsg( "错误的商户号.",$strResponseText); 
  }

  if( $strPayResult != "0" )
  {
  	//支付失败，系统错误
    //植入业务逻辑处理，请注意金额单位是分，财付通有可能多次通知商户支付成功，需要对财付通的重复通知做去重处理

	$retcode = "3";
	$retmsg = "支付失败，系统错误";
    ShowExitMsg( "支付失败，系统错误.",$strResponseText); 
  }
  
  if ($retcode == "0")
  {
  	//支付成功
  //植入业务逻辑处理，请注意金额单位是分，财付通有可能多次通知商户支付成功，需要对财付通的重复通知做去重处理
$money = $strTotalFee/100;//分转回元*人民币与积分比率.

			pay_update($strSpBillno,$strAttach,$money); //pay_update(订单号,返回数据,充值金额)
  }
  
  
?>
