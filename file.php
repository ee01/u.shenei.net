<?
$file_dir = "attachment/";//�ļ�Ŀ¼
$name = $_GET['name'];
$forbids_character=array("../","./","%2E","%2F");
$name=str_replace($forbids_character, "", $name);//�滻�Ƿ��ַ�����ֹ��Ŀ¼����

$file_name=explode("/",$name);
$file_name_count=count($file_name);
$file_name=$file_name[$file_name_count-1];

if (!file_exists($file_dir . $name)) { //����ļ��Ƿ���� 
	echo "�ļ��Ҳ���"; 
	exit; 
} else { 
	$file = fopen($file_dir . $name,"r"); // ���ļ� 
	// �����ļ���ǩ 
	Header("Content-type: application/octet-stream"); 
	Header("Accept-Ranges: bytes"); 
	Header("Accept-Length: ".filesize($file_dir . $name)); 
	Header("Content-Disposition: attachment; filename=������_" . $file_name); 
	
	if (preg_match('/MSIE/',$_SERVER['HTTP_USER_AGENT'])){//�������ļ�����������
		$filename = rawurlencode($filename);
	}
	// ����ļ����� 
	echo fread($file,filesize($file_dir . $name)); 
	fclose($file); 
	exit;
}
?>