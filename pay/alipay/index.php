<?php
/*
	*���ܣ�������Ʒ�й���Ϣ��������Ϣ
	*�汾��2.0
	*���ڣ�2008-08-01
	*���ߣ�֧������˾���۲�����֧���Ŷ�
	*��ϵ��0571-26888888
	*��Ȩ��֧������˾
*/

require_once("alipay_service.php");
require_once("alipay_config.php");
			   $varr = get_vipday(intval($_POST[cvip]));//������0Ϊ����,1Ϊ���
$p3_Amt						= (intval($_POST[paym]) > 0) ? intval($_POST[paym]) : intval($varr[1]);									
$pa_MP						= (intval($_POST[paym]) > 0) ? "1:".$_SGLOBAL['supe_uid'].":3" : "2:".$_SGLOBAL['supe_uid'].":".$_POST[cvip].":3";
$p5_Pid						= (intval($_POST[paym]) > 0) ? "���ֳ�ֵ" : "����VIP";	
$parameter = array(
	"service"        => "create_partner_trade_by_buyer",  //��������
	"partner"        => $partner,         //�����̻���
	"return_url"     => $return_url,      //ͬ������
	"notify_url"     => $notify_url,      //�첽����
	"_input_charset" => $_input_charset,  //�ַ�����Ĭ��ΪGBK
	"subject"        => $p5_Pid,       //��Ʒ���ƣ�����
	"body"           => $pa_MP,       //��Ʒ����������
	"out_trade_no"   => date(Ymdhms),     //��Ʒ�ⲿ���׺ţ������֤Ψһ�ԣ�
	"price"          => $p3_Amt,           //��Ʒ���ۣ�����۸���Ϊ0��
	"payment_type"   => "1",              //Ĭ��Ϊ1,����Ҫ�޸�
	"quantity"       => "1",              //��Ʒ����������
		
	"logistics_fee"      =>'0.00',        //�������ͷ���
	"logistics_payment"  =>'BUYER_PAY',   //�������ø��ʽ��SELLER_PAY(����֧��)��BUYER_PAY(���֧��)��BUYER_PAY_AFTER_RECEIVE(��������)
	"logistics_type"     =>'EXPRESS',     //�������ͷ�ʽ��POST(ƽ��)��EMS(EMS)��EXPRESS(�������)

	"show_url"       => $show_url,        //��Ʒ�����վ
	"seller_email"   => $seller_email     //�������䣬����
);
$alipay = new alipay_service($parameter,$security_code,$sign_type);
$link=$alipay->create_url();
if (intval($_POST[paym]) > 0 or $_POST[cvip] != '') {
	showmessage('', $link,0);	
	}
?>

