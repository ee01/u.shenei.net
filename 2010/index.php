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
<SCRIPT language="JavaScript">
<!--
var sound1="mid/1.mid"
var sound2="mid/2.mid"
var sound3="mid/3.mid"
var sound4="mid/4.mid"
var sound5="mid/5.mid"
var sound6="mid/6.mid"
var sound7="mid/7.mid"
var sound8="mid/8.mid"
var sound9="mid/5.mid"
var sound10="mid/2.mid"
var x=Math.round(Math.random()*9)
if (x==0) x=sound1
else if (x==1) x=sound2
else if (x==2) x=sound3
else if (x==3) x=sound4
else if (x==4) x=sound5
else if (x==5) x=sound6
else if (x==6) x=sound7
else if (x==7) x=sound8
else if (x==8) x=sound9
else x=sound10
if (navigator.appName=="Microsoft Internet Explorer")
document.write('<bgsound src='+'"'+x+'"'+' loop="infinite">')
else 
document.write('<embed src='+'"'+x+'"'+'hidden="true" border="0" width="20" height="20" autostart="true" loop="true">')


//-->


</SCRIPT>
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
<div id="main" TEXT-ALIGN: center>
	<script type="text/javascript" src="inc/index.js"></script>
<?php 
include("inc/coon.php");
include("config.php");
$result=mysql_query("select * from edi_love order by edi_id"); 
$num=mysql_numrows($result); 
for ($i=0;$i<$num;$i++) { 
$edi_id=mysql_result($result,$i,"edi_id");
$edi_class=mysql_result($result,$i,"edi_class"); 
$edi_images=mysql_result($result,$i,"edi_images"); 
$edi_head=mysql_result($result,$i,"edi_head");
$edi_sign=mysql_result($result,$i,"edi_sign");
$edi_lr=mysql_result($result,$i,"edi_lr");
$edi_date=mysql_result($result,$i,"edi_date");
$edi_cs=mysql_result($result,$i,"edi_cs");
$top =rand(110,418);
$left=rand(21,525)
?>
	<div id="Layer<?=$edi_id?>" class="Face<?=$edi_class?>" style="top:<?=$top?>px;left:<?=$left?>px;z-index:<?=$edi_id?>" onmousedown="Move(this,event)" ondblclick="Show(<?=$edi_id?>)"><p class="Num">字条编号：<?=$edi_id?><img src="images/close.gif" alt="关闭" onclick="Close(<?=$edi_id?>)" /></p><p class="Detail"><img alt="" src="images/icon<?=$edi_images?>.gif" /><span class="Head">To:<?=$edi_head?></span><br /><?=$edi_lr?></p><p class="Sign"><?=$edi_sign?></p><p class="Date"><span class="vote"><a href="zf.php?id=<?=$edi_id?>">祝福<?=$edi_cs?>次</a></span><?=$edi_date?></p></div>
<?php
}
?>	
</div></div>
  <Div class="copyArea"><p>Copyright 2009 舍内网-2010虎年新年许愿墙- Www.shenei.net</p></Div>
</body>
</html>