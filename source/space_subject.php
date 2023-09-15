<?php
/*
	[UCenter Home] (C) 2007-2008 Comsenz Inc.
	$Id: space_album.php 8658 2008-09-04 01:28:18Z liguode $
*/

if(!defined('IN_UCHOME')) {
	exit('Access Denied');
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$view = isset($_GET['view']) ? $_GET['view'] : "all";

$menu = array($view => ' class="active"');

$dbh = @mysql_connect("localhost:3306","shenebwx_wp","wp@SheNei01") or die ('Êý¾Ý¿âÁ¬½ÓÊ§°Ü£¡');
@mysql_select_db("shenebwx_wp", $dbh) or die('Êý¾Ý¿âÑ¡¶¨Ê§°Ü£¡');
mysql_query("set names 'gbk'");


if(!$id) {
  if($view=="me"){

  }else{
	  $sql_domain = "SELECT domain FROM wp_site";
	  $rs_domain = mysql_query($sql_domain, $dbh) or die('Êý¾Ý¿â²éÑ¯Ê§°Ü£¡');
	  $row_domain = mysql_fetch_array($rs_domain);
	  $sql_id = "SELECT blog_id,registered FROM wp_blogs ORDER BY registered DESC";
	  $rs_id = mysql_query($sql_id, $dbh) or die('Êý¾Ý¿â²éÑ¯Ê§°Ü£¡');

	  while ($row_id = mysql_fetch_array($rs_id)) {
/*
		$sql_snid = "SELECT sn_id FROM wp_users WHERE user_email='".GEToptions($row_id[0],"admin_email")."'";
		$rs_snid = mysql_query($sql_snid, $dbh) or die('Êý¾Ý¿â²éÑ¯Ê§°Ü£¡');
		$row_snid = mysql_fetch_array($rs_snid);
		if(!$row_snid){$row_snid=1;}
*/
		$sql_comment = "SELECT * FROM wp_".$row_id[0]."_comments";
		$rs_comment = mysql_query($sql_comment, $dbh) or die('Êý¾Ý¿â²éÑ¯Ê§°Ü£¡');
		$num_comment = mysql_num_rows($rs_comment);


	$subject = Array("id"=>$row_id[0],"title"=>GEToptions($row_id[0],"blogname"),"description"=>GEToptions($row_id[0],"blogdescription"),"siteurl"=>GEToptions($row_id[0],"siteurl"),"createtime"=>$row_id[1],"pic"=>"http://".$row_domain[0]."/wp-content/themes/".GEToptions($row_id[0],"stylesheet")."/screenshot.png","post_count"=>GEToptions($row_id[0],"post_count"),"comment_count"=>$num_comment);

	$subjectlist[] = $subject;
	  }
  }
	$_TPL['css'] = 'event';
	include_once template("space_subject");
} else {

	$_TPL['css'] = 'event';
	include_once template("space_");
}

Function GEToptions($blogid,$option_name){
	global $dbh;
	$sql_options = "SELECT option_value,option_name FROM wp_".$blogid."_options WHERE option_name='".$option_name."'";
	$rs_options = mysql_query($sql_options, $dbh) or die('Êý¾Ý¿â²éÑ¯Ê§°Ü£¡');
	$row_options = mysql_fetch_array($rs_options);
	Return $row_options[0];
}

@mysql_close($dbh);

?>