<?php
if ( !defined( "IN_UCHOME" ) )
{
	exit( "Access Denied" );
}
if(isset($_POST['formhash']))
{
	if(!empty($_POST['del'])){
		$id			=	$_POST['del'];
		$num		=	count($id);
		for($i=0;$i<$num;$i++)
		{
			$ids	.=	$id[$i].',';
		}
		$ids		=	substr($ids,0,strlen($ids)-1);
		$sql		=	"select * from ".tname('nd_data')." where id in (".$ids.")";
		$query 		= 	$_SGLOBAL['db']->query($sql);
		while($value= $_SGLOBAL['db']->fetch_array($query)) {
		
			$filelist	=	$value['datasrc'];
			$filelist	=	explode(',',$filelist);
			for($i=0;$i<count($filelist)-1;$i++)
			{
				if(file_exists($filelist[$i]))
				{
					unlink($filelist[$i]);
				}
			}
		}
		$sql		=	"delete from ".tname('nd_data')." where id in (".$ids.")";
		$_SGLOBAL['db']->query($sql);
		
		$sql		=	"delete from ".tname('nd_keywords')." where ND_id in (".$ids.")";
		$_SGLOBAL['db']->query($sql);
		
		$sql		=	"delete from ".tname('comment')." where id in (".$ids.") and idtype='ND_coment_id'";
		$_SGLOBAL['db']->query($sql);
		
		$sql		=	"delete from ".tname('nd_down')." where nd_id in (".$ids.")";
		$_SGLOBAL['db']->query($sql);
		showmessage('删除成功!', 'ND_upload.php?do=my');
	}else{

        showmessage('请选择要删除的对象.......!', 'ND_upload.php?do=my');
	}
}
else
{
	showmessage('路径错误！','ND_upload.php',3);
}
include_once template("ND_upload/template/info");
?>
