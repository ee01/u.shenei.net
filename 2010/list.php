<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" style="overflow-x:hidden;">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<meta http-equiv="Page-Enter" content="progid:DXImageTransform.Microsoft.GradientWipe(Duration=2)"/>
<meta http-equiv="Page-Exit" content="progid:DXImageTransform.Microsoft.GradientWipe(Duration=2)"/>
<title><?php include("config.php"); echo $headname1; ?></title>
<meta content="<?php include("config.php"); echo $headname2; ?>" name="Description" />
<style><!--@import url(images/css.css);--></style>
</head>
<body>
<DIV class="HeadStyle" id="header">
  <Div class="Nav">
    <ul>
      <li><a href="./">祝福首页</a></li>
      <li><a href="add.php">我要送祝福</a></li>
      <li><a href="list.php">祝福列表</a></li>
      <li><a href="http://2010.shenei.net" target="_blank">祝福送人</a></li>
         <li><a href="http://u.shenei.net" target="_blank">舍内家园</a></li>
		      <li><font color=yellow>发送祝福流程：点击“我要送祝福”，输入对方名字，点击发送</font></li>
    </ul>
  </Div>
</DIV>
<div style="display:none;" id="aspk" onclick="Hide();"></div>
<DIV class="LeftContent">
  <p class="p1"></p>
  <p class="p2"></p>
  <p class="p3"></p>
  <p class="p4"></p>
  <p class="p5"></p>
  <p class="p6"></p>
  <p class="p7" onclick="document.location='add.php';" ></p>
  <p class="p8"></p>
  <p class="p9"></p>
  <p class="p10"></p>
  <p class="p11"></p>
</DIV>
<DIV class="RightContent">
  <Div class="mesArea">
    <div class="contrArea" id="menu">
	  <div class="Search">
	    <input id="find" name="id" class="input" type="text" maxlength="10" size="15" value=" 请输入字条编号 " onclick="this.value='';" />
		<input type=submit  class="findbt" value="" lass="findbt" type="button" onclick="find();" > 
      </div>
<script type="text/javascript">
<!--
function find(){
	var noStr = document.getElementById("find").value;
	var no = parseInt(noStr);
	if(isNaN(no)){
		alert("[字条编号]肯定是数字啊");
		return;
	}else if(no < 1){
		alert("[字条编号]肯定是整数啊");
		return;
	}else{
		window.location.href = "zhufu.php?id="+no;		
	}
}
//-->
</script>

	</div>
	<div class="messageArea" id="footer">
<table width="90%" align="center" border="0" cellpadding="0" cellspacing="1" class="List">
	<tr>
		<th>编号</th>
		<th>字条内容</th>
		<th>发送人</th>
		<th>接收人</th>
		<th>日期</th>
	</tr>
	<tr class="tr">
<?php 
include("inc/coon.php");
$limit=20;
$start=$_GET["start"];
if(empty($start)) $start=0; 
$result=mysql_query("select * from edi_love"); 
$num_max=mysql_numrows($result); 

$result=mysql_query("select * from edi_love order by edi_cs  DESC limit $start,$limit"); 
$num=mysql_numrows($result); 
for ($i=0;$i<$num;$i++) { 
$edi_id=mysql_result($result,$i,"edi_id");
$edi_class=mysql_result($result,$i,"edi_class"); 
$edi_images=mysql_result($result,$i,"edi_images"); 
$edi_head=mysql_result($result,$i,"edi_head");
$edi_sign=mysql_result($result,$i,"edi_sign");
$edi_lr=mysql_result($result,$i,"edi_lr");
$edi_date=mysql_result($result,$i,"edi_date");
$top =rand(110,418);
$left=rand(81,525)
?>
	<td class="ListA"><?=$edi_id?></td>
	<td class="ListB"><a href="zhufu.php?id=<?=$edi_id?>"><?=$edi_lr?></a></td>
	<td class="ListC"><?=$edi_sign?></td><td class="ListC"><?=$edi_head?></td><td class="ListD"><?=$edi_date?></td></tr>   
	<tr>
<?
}
?>
<td colspan="5" class="ListP">
<div align="right"><a href="list.php">第一页</a>
<? 
$prve=$start-$limit; 
if ($prve>=0) { 
echo "<a href=list.php?start=$prve>上一页</a>"; 
echo "   ";
} 
//设置向后翻页的跳转 
$next=$start+$limit; 
if ($next<$num_max) { 
echo "<a href=list.php?start=$next>下一页</a>"; 
} 
?>共<font color=red><?php echo "$num_max" ?></font>条祝福</div></td>
	</tr>
</table>

</div>
	
	
  </Div>
  <Div class="copyArea"><p>Copyright 2009 <?php include("config.php"); echo $headname1; ?> slany inc.</p></Div>
</DIV>
</body>
</html>
