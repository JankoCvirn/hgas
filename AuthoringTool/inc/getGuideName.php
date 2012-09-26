<?php
require '../inc/hgutils.php';

$check=true;
$msg='available';
if (isset($_REQUEST['action']) && isset($_REQUEST['name'])){
	
	
	$oMethod=new hgutils('none');
	$check=$oMethod->getGuideName(trim($_REQUEST['name']));
	if($check==true){
	$msg='not available';
	}	
}

$response=array('ok'=>$check,'msg'=>$msg);

echo json_encode($response);


?>
