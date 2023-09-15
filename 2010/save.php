<?php
  include("inc/coon.php");
  include("inc/number.php");
 	$edi_class=edi_convet($_POST["face"]);
 	$edi_images=edi_convet($_POST["icon"]);
 	$edi_head=edi_convet($_POST["pick"]);
 	$edi_sign=edi_convet($_POST["send"]);
 	$edi_lr=edi_convet($_POST["info"]);
 	$today = date("Y-m-d G:i:s");  
if( !$edi_head || !$edi_sign || !$edi_lr) { 
die("<script>alert('所有表单必须填写！');history.back();</script>"); 
}	
if(!ereg("[0-9-]",$edi_class)){
die("<script>alert('输入错误，请返回重填！');history.back();</script>");
}
if(!ereg("[0-9-]",$edi_images)){
die("<script>alert('输入错误，请返回重填！');history.back();</script>");
}
if(strlen($edi_head)>16 || strlen($edi_sign)>16){
die("<script>alert('发送人或祝福人姓名过长，请返回重填！');history.back();</script>");
}
if(strlen($edi_lr)>140){
die("<script>alert('发送人内容过长，请返回重填！');history.back();</script>");
}
$sql = "INSERT INTO edi_love (edi_class,edi_images,edi_head,edi_sign,edi_lr,edi_date) VALUES ($edi_class,$edi_images,'$edi_head','$edi_sign','$edi_lr','$today')";   //构造sql语句
$result = mysql_query($sql);
echo '<SCRIPT language=JavaScript>alert("您的祝福已发出！")</SCRIPT><meta http-equiv="refresh" content="0;URL=index.php">'; exit;
?>