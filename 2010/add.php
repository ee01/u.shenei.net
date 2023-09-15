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
<script type="text/javascript" src="inc/add.js"></script>
<div>
<form method="post" action="save.php" name="frmAdd" onSubmit="return chkAspk(this);">
<input type="hidden" name="cmd" value="add" />
<input type="hidden" name="face" value="0" />
<input type="hidden" name="icon" value="0" />
  <h1>请在此发表您的祝福：）</h1>
  <div  id="AD" class="mesInfomation">
	<p class="p1">用户须知：</p>
    <p>1.遵守中华人民共和国有关法律、法规，尊重网上道德，承担一切因您的行为而直接或间接引起的法律责任；<br />2.您在本网站所发表的内容，我们有权删除或在本网站内转载、引用；<br />3.请不要发布任何广告或无意义水贴。<br />4.请积极支持中高网-高尔夫职业社区的宣传和推广。  </p>
    <p>谢谢您的支持！</p>
  </div>
  <div id="Peview" class="Faceadd">
    <p class="Num">字条预览：<img src="images/close.gif" alt="关闭" /></p>
	<p class="Detail"><img src="images/icon0.gif" alt="aspk" width="50" height="50" id="IconImg" /><span class="Head" id="Head"></span><br /><span id="AreaText"></span></p>
	<p class="Sign" id="Sign">匿名</p>
	<p class="Date"><script type="text/javascript">getTime();</script></p>
  </div>
  <div class="InputArea">
    <p class="p2">接 受 人：<input class="input" name="pick" type="text" size="20" maxlength="10" value=""   onkeyup="InputName(this,'Head');" /></p>
	<p class="p3">发 送 人：<input class="input" name="send" type="text" size="20" maxlength="10" onKeyUp="InputName(this,'Sign');" value="匿名" onClick="if(this.value =='匿名') this.value=''; "/></p>
	<p class="p4">祝福内容：<textarea name="info" cols="25" rows="3" onKeyUp="strCounter(this);" onChange="strCounter(this);"></textarea></p>
	<p><span class="red">*</span>还剩<span class="red" id="Char"> 30 </span>字</p>
  </div>
  <div class="Faceall">
	<p>祝福图案：</p>
	<p>
	  <img class="iconBox" src="images/icon0.gif" onClick="IconChange('0');" />
	  <img class="iconBox" src="images/icon1.gif" onClick="IconChange('1');" />
      <img class="iconBox" src="images/icon2.gif" onClick="IconChange('2');" />
      <img class="iconBox" src="images/icon3.gif" onClick="IconChange('3');" />
      <img class="iconBox" src="images/icon4.gif" onClick="IconChange('4');" />
      <img class="iconBox" src="images/icon5.gif" onClick="IconChange('5');" />
      <img class="iconBox" src="images/icon6.gif" onClick="IconChange('6');" />
      <img class="iconBox" src="images/icon7.gif" onClick="IconChange('7');" />
      <img class="iconBox" src="images/icon8.gif" onClick="IconChange('8');" />
      <img class="iconBox" src="images/icon9.gif" onClick="IconChange('9');" />
      <img class="iconBox" src="images/icon10.gif" onClick="IconChange('10');" />
      <img class="iconBox" src="images/icon11.gif" onClick="IconChange('11');" />
      <img class="iconBox" src="images/icon12.gif" onClick="IconChange('12');" />
      <img class="iconBox" src="images/icon13.gif" onClick="IconChange('13');" />
      <img class="iconBox" src="images/icon14.gif" onClick="IconChange('14');" />
	</p>
	<p class="p5"><input class="input" name="key" type="text" value="" size="6" maxlength="4" onfocus="this.value='';" /> <span class="red">*</span> { 请输入 <img src="inc/getcode.php" /> }<input class="inputb" name="submit" type="submit" value=" 提 交 字 条 " /></p>
	</form>
  </div>
</div>
</div>
	
	
  </Div>
  <Div class="copyArea"><p>Copyright 2009 <?php include("config.php"); echo $headname1; ?> slany inc.</p></Div>
</DIV>
</body>
</html>
</body>
</html>
