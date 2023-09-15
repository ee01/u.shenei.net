<?php 
include_once('../common.php');
header('Content-type: text/xml');
$pp = toXml(); 
echo $pp; 


function toXml() 
{
$space = getspace($_GET['uid'], 'uid', 0);
$musiclist=unserialize($space[smusic]);
$xmlstr .='<playlist version="1" xmlns="http://xspf.org/ns/0/">
'; 
$xmlstr .='<trackList>
'; 
foreach ($musiclist as $key => $value) {
if($value)
{
$xmlstr .='<track>
'; 
$xmlstr .='<location>'.$value.'</location>
'; 
$xmlstr .='<identifier>'.$key.'</identifier>
'; 
$xmlstr .='</track>
'; 
}
}
$xmlstr .='</trackList>
'; 
$xmlstr .='</playlist>
'; 
return $xmlstr; 
}
?> 