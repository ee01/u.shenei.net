<?php

$pa = "4444333"; //修改成您的密码.
  include("inc/coon.php");
  include("inc/number.php");
  $id=edi_convet($_GET["id"]);
  $pass=edi_convet($_GET["pass"]);
  if( !$id) { 
die("<script>alert('参数错误！');history.back();</script>"); 
}	
if(!ereg("[0-9-]",$id)){
die("<script>alert('输入错误，请返回重填！');history.back();</script>");
}
$result=mysql_query("select * from edi_love where edi_id='$id'"); 
$num=mysql_numrows($result); 
if($num==''){
die("<script>alert('输入错误，请返回重填！');history.back();</script>");
}
if($pa!==$pass){
die("<script>alert('密码错误，请返回重填！');history.back();</script>");
}
$sql = "DELETE FROM edi_love where edi_id='$id'"; 
$result = mysql_query($sql);
echo '<SCRIPT language=JavaScript>alert("祝福已删除！")</SCRIPT><meta http-equiv="refresh" content="0;URL=index.php">'; exit;
?>