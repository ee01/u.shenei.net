<?php
$uhm=str_replace("\\","/",dirname(__FILE__));  
$uhm=str_replace('/ND_upload/source','',$uhm); 
include_once( $uhm."/common.php" );
checkclose( );
if(empty($_SGLOBAL['supe_uid'])) {
	if($_SERVER['REQUEST_METHOD'] == 'GET') {
		ssetcookie('_refer', rawurlencode($_SERVER['REQUEST_URI']));
	} else {
		ssetcookie('_refer', rawurlencode('admincp.php?ac='.$_GET['ac']));
	}
	showmessage('to_login', 'do.php?ac='.$_SCONFIG['login_action']);
}

$uptypes=array( 
     'image/jpg', 
     'image/jpeg', 
     'image/png', 
     'image/pjpeg', 
     'image/gif', 
     'image/bmp', 
     'image/x-png' 
); 
$w	=	100;
$h	=	200;
if(isset($_GET['w']))
{
	$w	=	$_GET['w'];
}
if(isset($_GET['h']))
{
	$h	=	$_GET['h'];
}
$overwrite=true;
$max_file_size=2000000;      //上传文件大小限制, 单位BYTE 
$shijian=date("Y").date("m").date("d");
$destination_folder=str_replace("\\","/",dirname(__FILE__)).'/ND_pic/'.$shijian.'/'; 
$destination_folder=eregi_replace('/ND_upload/source','',$destination_folder); //上传文件路径 
$ims_sub=1;			//是否生成缩略图 
?>
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
-->
</style> 
<form enctype="multipart/form-data" method="post" name="upform"> 
<input name="upfile" type="file"> <input type="submit" value="上传">
</form> 

<?php 
$error		=	'';
if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{ 
     if (!is_uploaded_file($_FILES["upfile"][tmp_name])) 
     //是否存在文件 
     { 
          $error ="图片不存在!"; 
          exit; 
     } 

     $file = $_FILES["upfile"]; 
     if($max_file_size < $file["size"]) 
     //检查文件大小 
     { 
         $error ="文件太大!"; 
         exit; 
     } 

     if(!in_array($file["type"], $uptypes)) 
     //检查文件类型 
     { 
         $error ="文件类型不符!".$file["type"]; 
         exit; 
     } 

	$array_dir=explode("/",$destination_folder);//把多级目录分别放到数组中
 		for($i=0;$i<count($array_dir);$i++){
  		$path .= $array_dir[$i]."/";
 	 		if(!file_exists($path)){
   			mkdir($path);
  		}
  	}

     $filename=$file["tmp_name"]; 
     $image_size = getimagesize($filename); 
     $pinfo=pathinfo($file["name"]); 
     $ftype=$pinfo['extension']; 
	 $shijian2=time().$uid;
     $destination = $destination_folder.$shijian2.".".$ftype;
	 $destination_name='ND_pic/'.$shijian.'/'.$shijian2.".".$ftype;
	 if(isset($_GET['url'])&&!empty($_GET['url']))
	 {
	 	$destination		=	str_replace("\\","/",dirname(__FILE__)).'/'.$_GET['url']; 
		$destination		=	eregi_replace('/ND_upload/source','',$destination);
		$destination_name	=	$_GET['url'];
	 }
     if (file_exists($destination) && $overwrite != true) 
     { 
         echo "同名文件已经存在了"; 
         exit; 
     } 

     if(!move_uploaded_file ($filename, $destination)) 
     { 
         echo $destination; 
		 exit; 
     } 

     $pinfo=pathinfo($destination); 
     $fname=$pinfo[basename]; 
	 if($ims_sub==1) 
     { 
         $iinfo=getimagesize($destination,$iinfo); 
         $nimage=imagecreatetruecolor($w,$h);  
         $white=imagecolorallocate($nimage,255,255,255); 
         $black=imagecolorallocate($nimage,0,0,0); 
         $red=imagecolorallocate($nimage,255,0,0); 
         imagefill($nimage,0,0,$white); 
         switch ($iinfo[2]) 
         { 
             case 1: 
             $simage =imagecreatefromgif($destination); 
             break; 
             case 2: 
             $simage =imagecreatefromjpeg($destination); 
             break; 
             case 3: 
             $simage =imagecreatefrompng($destination); 
break; 
             case 6: 
             $simage =imagecreatefromwbmp($destination); 
             break; 
             default: 
             die("不支持的文件类型"); 
             exit; 
         } 

         imagecopyresampled($nimage,$simage,0,0,0,0,$w,$h,$image_size[0],$image_size[1]); 
         switch ($iinfo[2]) 
         { 
             case 1: 

//imagegif($nimage, $destination); 
             imagejpeg($nimage, $destination); 
             break; 
             case 2: 
             imagejpeg($nimage, $destination); 
             break; 
             case 3: 
             imagepng($nimage, $destination); 
             break; 
             case 6: 
             imagewbmp($nimage, $destination); 
             //imagejpeg($nimage, $destination); 
             break; 
         } 

         //覆盖原上传文件 
         imagedestroy($nimage); 
         imagedestroy($simage); 
     } 
	echo "<script language=javascript>"; 
	echo "window.parent.document.form1.pic.value='".$destination_name."';";
	echo "window.parent.document.getElementById('uppic').src='".$destination_name."';";
	echo "</script>";
} 
?> 