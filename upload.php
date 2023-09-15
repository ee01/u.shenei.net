<?php
$updir="./music/";
$dirtype="1";
$renamed="1";
$overwrite="0";
if (isset($_FILES["resume_file"]) && is_uploaded_file($_FILES["resume_file"]["tmp_name"]) && $_FILES["resume_file"]["error"] == 0) {
	$upload_file=$_FILES["resume_file"];
	$file_info=pathinfo($upload_file["name"]);
	$file_ext=$file_info["extension"];
	switch($dirtype){
		case '1':
			$m_dir=date(Y)."/".date(m)."/".date(d)."/";
			break;
		case '2':
			$m_dir=date(Y)."/".date(m)."/";
			break;
		default:
			$m_dir=date(Y)."/";
			break;
	}
	$upload_path=$updir.$m_dir;
	create($upload_path);
	if($renamed){
		list($usec, $sec) = explode(" ",microtime());
		$upload_file['name']=substr($usec,2).'.'.$file_ext;
		unset($usec);
		unset($sec);
	}
	$upload_file['filename']=$upload_path.$upload_file['name'];
	if(file_exists($upload_file['filename'])){
		if($overwrite){
			@unlink($upload_file['filename']);
		}else{
			$j=0;
			do{
				$j++;
				$temp_file=str_replace('.'.$file_ext,'('.$j.').'.$file_ext,$upload_file['filename']);
			}while (file_exists($temp_file));
			$upload_file['filename']=$temp_file;
			unset($temp_file);
			unset($j);
		}
	}
	if(@move_uploaded_file($upload_file["tmp_name"],$upload_file["filename"])){
		echo $upload_file["filename"];
	}else{
		echo '';
	}
} else {
	echo ' '; 
}
function create($dir)
{
	if (!is_dir($dir))
	{
		$temp = explode('/',$dir);
		$cur_dir = '';
		for($i=0;$i<count($temp);$i++)
		{
			$cur_dir .= $temp[$i].'/';
			if (!is_dir($cur_dir))
			{
				@mkdir($cur_dir,0777);
				@fopen("$cur_dir/index.htm","a");
			}
		}
	}
}
?>