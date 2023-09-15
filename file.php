<?
$file_dir = "attachment/";//文件目录
$name = $_GET['name'];
$forbids_character=array("../","./","%2E","%2F");
$name=str_replace($forbids_character, "", $name);//替换非法字符，防止跨目录下载

$file_name=explode("/",$name);
$file_name_count=count($file_name);
$file_name=$file_name[$file_name_count-1];

if (!file_exists($file_dir . $name)) { //检查文件是否存在 
	echo "文件找不到"; 
	exit; 
} else { 
	$file = fopen($file_dir . $name,"r"); // 打开文件 
	// 输入文件标签 
	Header("Content-type: application/octet-stream"); 
	Header("Accept-Ranges: bytes"); 
	Header("Accept-Length: ".filesize($file_dir . $name)); 
	Header("Content-Disposition: attachment; filename=舍内网_" . $file_name); 
	
	if (preg_match('/MSIE/',$_SERVER['HTTP_USER_AGENT'])){//解决输出文件名中文乱码
		$filename = rawurlencode($filename);
	}
	// 输出文件内容 
	echo fread($file,filesize($file_dir . $name)); 
	fclose($file); 
	exit;
}
?>