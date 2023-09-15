<?

require('mtag.DB.php');
	$sql = "SELECT `mtagid` , `logo` , `head` , `background` , `guidance` FROM `sn_mtag_diy` WHERE `mtagid` ='$tagid'";
	
	$conn=mysql_connect( $mysql_server_name, $mysql_username, $mysql_password );
	$result=mysql_db_query( $mysql_database, $sql,$conn );	
	$row=mysql_fetch_row($result);
	//print_r($row);
	mysql_free_result($result);

if($row>0)
{
}else{
  	$conn=mysql_connect( $mysql_server_name, $mysql_username, $mysql_password);
	$query = "INSERT INTO `sn_mtag_diy` (`mtagid`,`logo`,`head`,`background`,`guidance`) VALUES ('$tagid','./mtag/image/logo.gif','#FFF url(./mtag/image/head.gif)','#C9C9C9 url(./mtag/image/back.gif)','#555555 url(./mtag/image/gui.gif)')";
	mysql_select_db($mysql_database,$conn);
	$result = mysql_query($query);
	mysql_close($conn);	
	
	$sql = "SELECT `mtagid` , `logo` , `head` , `background` , `guidance` , `tail` FROM `sn_mtag_diy` WHERE `mtagid` ='$tagid'";
	$conn=mysql_connect( $mysql_server_name, $mysql_username, $mysql_password );
	$result=mysql_db_query( $mysql_database, $sql,$conn );	
	$row=mysql_fetch_row($result);
	//print_r($row);
	mysql_free_result($result);
}
?>