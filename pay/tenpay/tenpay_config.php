<?

//此文件为财付通支付配置文件
//http://192.168.1.106/nhome/tenpay/tenpay_notify.php?attach=备注&bargainor_id=1202965901&cmdno=1&date=20081021&fee_type=1&pay_info=OK&pay_result=0&pay_time=1224624618&sign=58ACFB62A5BE3153AA1B35C40854954F&sp_billno=1224624173&total_fee=1&transaction_id=1202965901200810211224624173&ver=1&interfaceName=ICBC_PERBANK_B2C&interfaceVersion=1.0.0.0&orderid=165882636&TranSerialNo=HFG000240164984&amount=1&curType=001&merID=4000EC20000058&merAcct=4000027219200075654&verifyJoinFlag=0&JoinFlag=&UserNum=&resultType=1&orderDate=20081022052930&notifyDate=20081022053021&tranStat=1&comment=&remark1=&remark2=&signMsg=nXDkK28Mc%2BeWN2PZSw%2FY5axlOaoMWsWuyLFyT84wrwVWbq2TlfyHc%2F7yRiLzI3Y8vUvabxUxVmvbJvGZTncgfnU%2BzT%2BLQ7LSGkjbDzAo7QUzZArEvFrdFJ2PWwQKKBEHDNSU7ZzvjvfV2wz%2BZ6B360oIZT0bBmE5lIH7z7%2FIxDg%3D

//☆★☆★☆★☆★☆★☆★财付通测试开关   0 关闭测试    1 开启测试☆★☆★☆★☆★☆★☆★
class tenpay_config
{
var $beta_switch		="0";

//☆★☆★☆★☆★☆★☆★财付通支付配置项。☆★☆★☆★☆★☆★☆★

//以下每一项都必须要配置，并准确
var $spid;																	//卖家帐号
var $sp_key;																		//密钥
var $domain;															//商户网站域名
var $tenpay_dir;																			//财付通安装目录
var $site_name;																			//商户网站名称
var $attach				="tencent_magichu";																	//支付附加数据，非中文标准字符
var $imgtitle			="财付通支付"; 																		//图片说明
var $imgsrc				="/image/tenpay_buy.gif";													//图片地址
var $pay_url			="https://www.tenpay.com/cgi-bin/v1.0/pay_gate.cgi"; 						//财付通支付网关地址
function tenpay_config()
{
	$this->imgsrc.=$this->tenpay_dir;
}


}

?>
