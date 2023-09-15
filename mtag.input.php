<?php
require('mtag.DB.php');

	$tagid = $_GET['tagid'];
	$logo = $_POST['logo'];
	$head = $_POST['head'];
	$background = $_POST['background'];
	$guidance = $_POST['guidance'];

	$sql = "UPDATE sn_mtag_diy SET logo='$logo',head='$head',background='$background',guidance='$guidance' WHERE mtagid='$tagid'";		
	
	$conn=mysql_connect( $mysql_server_name, $mysql_username, $mysql_password);	
	mysql_select_db($mysql_database,$conn);
	$result = mysql_query($sql);
	mysql_close($conn);


?> 
<html> 
<head> 
<meta http-equiv="Content-Language" content="zh-CN"> 
<meta HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=gb2312"> 
<title></title> 
</head> 
<body> 
<meta http-equiv="refresh" content="0.1;url=cp.php?ac=mtag&op=manage&tagid=<?=$tagid?>&subop=skin"> 
</body> 
</html>