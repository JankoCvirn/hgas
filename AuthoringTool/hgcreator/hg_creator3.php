<?php
ini_set("session.gc_maxlifetime", "3600");
require_once  ("../inc/hgutils.php");

$page_title='HikeGuide Authoring Tools';
$brand_text='HikeGuide Creator - Step 3 (upload map icons)';
$hero_text ='Create a new guide user interface.';

session_start();

$msg_status = '';

$username = $_SESSION['username'];

//logout
if (isset($_REQUEST["logout"])){
	session_unset();
	session_destroy();
}
//back to index.html
if (!session_is_registered($username)) {
	header("Location:../index.php");
}
if (isset($_POST['SubmitDetails'])){
	
	$name=$_POST['name'];
	$subName=$_POST['subname'];
	$wkt=$_SESSION['wkt'];
	$summary=$_POST['summary'];
	$tcolor=$_POST['tcolor'];
	$bcolor=$_POST['bcolor'];
	$distance=$_POST['distance'];
	$region=$_POST['region'];
	$difficulty=$_POST['difficulty'];
	$nature=$_POST['nature'];
	
	
	
	
	
	$oHgUtil=new hgutils($username);
	$oHgUtil->setGuideData($name, $subName, $wkt, $summary, $tcolor, $bcolor, $distance, $region, $difficulty, $nature);
	
	//$cmd='mkdir '.$name;
	//echo shell_exec($cmd);
	
	
	$destination_path = getcwd().DIRECTORY_SEPARATOR;
	//logo upload
	$target_path_logo = $destination_path.'logo/'.$name.'-'. basename( $_FILES['logoFile']['name']);
	
	$result_logo=0;
	if (@move_uploaded_file($_FILES['logoFile']['tmp_name'], $target_path_logo)){
		
		$result_logo=1;
	} 
	
	//pic1 upload
	$target_path_pic1 = $destination_path.'logo/'.$name.'-'. basename( $_FILES['pic1']['name']);
	
	$result_pic1=0;
	if (@move_uploaded_file($_FILES['pic1']['tmp_name'], $target_path_pic1)){
	
		$result_pic1=1;
	}
	
	
	//pic2 upload
	$target_path_pic2 = $destination_path.'logo/'.$name.'-'. basename( $_FILES['pic2']['name']);
	
	$result_pic2=0;
	if (@move_uploaded_file($_FILES['pic2']['tmp_name'], $target_path_pic2)){
	
		$result_pic2=1;
	}
	
	//pic3 upload
	$target_path_pic3 = $destination_path.'logo/'.$name.'-'. basename( $_FILES['pic3']['name']);
	
	$result_pic3=0;
	if (@move_uploaded_file($_FILES['pic3']['tmp_name'], $target_path_pic3)){
	
		$result_pic3=1;
	}
	
	//pic4 upload
	$target_path_pic4 = $destination_path.'logo/'.$name.'-'. basename( $_FILES['pic4']['name']);
	
	$result_pic4=0;
	if (@move_uploaded_file($_FILES['pic4']['tmp_name'], $target_path_pic4)){
	
		$result_pic4=1;
	}
	
	
}

?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="initial-scale=1.0, user-scalable=yes" />
<meta charset="utf-8">

<link href="../css/bootstrap.css" rel="stylesheet">
<link href="../css/bootstrap-responsive.css" rel="stylesheet">
<style>
      body {
        padding-top: 60px; 
      }
    </style>




<!--Map Libs -->
<script src="http://www.openlayers.org/api/OpenLayers.js"></script>
<script src="http://maps.google.com/maps/api/js?sensor=false"></script>	
<script src="http://www.openstreetmap.org/openlayers/OpenStreetMap.js"></script>
<script src="../js/jquery-1.7.1.js"></script>
	<script src="../js/bootstrap-modal.js"></script>
	<script src="../js/bootstrap-dropdown.js"></script>
	<script src="../js/bootstrap-scrollspy.js"></script>
	<script src="../js/bootstrap-tab.js"></script>
	<script src="../js/bootstrap-tooltip.js"></script>
	<script src="../js/bootstrap-popover.js"></script>
	<script src="../js/bootstrap-button.js"></script>
	<script src="../js/bootstrap-collapse.js"></script>
	<script src="../js/bootstrap-carousel.js"></script>
	<script src="../js/bootstrap-typeahead.js"></script>

<script type="text/javascript">



////////////////////////
//Validation
	


    </script>
</head>
<body onload="init_map()">

	<div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <a class="brand" href="#"><?php echo $brand_text;?></a>
          <?php include '../inc/navigation/navigation.php';?>
          
        </div>
      </div>
    </div>

	<div class="container-fluid">
	    <div class="row_fluid">
	    <div class="row_fluid">
	    	<div class="progress progress-striped">
  			<div class="bar" style="width: 66%;"></div>
			</div>
	    </div>
	    	
	    	
	    	
	    </div>
	
		<div class="row-fluid">
			
		    <div class="span3">
		    
				<table>
				<thead>
					<tr>
					<th></th>
					</tr>
				
				</thead>
				<tbody>
					<tr>
					<td></td>
					
					
					</tr>
				
				
				</tbody>
				
				
				</table>
			
			
			</div>
	    
			
			<div class="span3">
				
			</div>
			
			
		</div>



	</div>


	


</body>
</html>


