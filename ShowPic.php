<?php
$url=$_GET['url']?$_GET['url']:$_GET['URL'];
$ext=pathinfo($url,PATHINFO_EXTENSION);
/*
if($ext!='jpg' && $ext!='gif'){ // ֻ֧��jpg��gif 
    readfile('image/nopic.jpg');
    exit;
}
*/
$file=md5($url).'.'.$ext;
$ShowPicTMP = "data/tpl_cache/ShowPic/";
if (!is_dir($ShowPicTMP)) //�ж�Ŀ¼�Ƿ����
{
	mkdir($ShowPicTMP);
//	@system("mkdir ".$ShowPicTMP."");
}
if(file_exists($ShowPicTMP.$file)){
    readfile($ShowPicTMP.$file);
    exit;
}else{
    $data=file_get_contents($url);
    if(!$data){ // ��ȡʧ�� 
		readfile('image/nopic.jpg');
        exit;
    }
    $handle=fopen($ShowPicTMP.$file,'wb');
    fwrite($handle,$data);
    fclose($handle);
    echo $data;
}
?>