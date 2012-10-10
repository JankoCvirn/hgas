<?php
ini_set("session.gc_maxlifetime", "3600");
require_once  ("../inc/utils.php");

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
	
	$cmd='mkdir '.$name;
	echo shell_exec($cmd);
	
	
	$destination_path = getcwd().DIRECTORY_SEPARATOR;
	//logo upload
	$target_path_logo = $destination_path.'/'.$name.'/' . basename( $_FILES['logoFile']['name']);
	
	@move_uploaded_file($_FILES['logoFile']['tmp_name'], $target_path) ;
	
	
	//pic1 upload
	
	
	//pic2 upload
	
	//pic3 upload
	
	//pic4 upload
	
	
	
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
	
//Guide name validation
	$(document).ready(function () {
	  var validateName = $('#validateName');
	  $('#name').keyup(function () {
	    var t = this; 
	    if (this.value != this.lastValue) {
	      if (this.timer) clearTimeout(this.timer);
	      validateName.removeClass('error').html('<img src="../img/loader.gif" height="16" width="16" /> checking availability...');
	      
	      this.timer = setTimeout(function () {
	        $.ajax({
	          url: '../inc/getGuideName.php',
	          data: 'action=check_username&name=' + t.value,
	          dataType: 'json',
	          type: 'post',
	          success: function (j) {
	            validateName.html(j.msg);
	            if (j.ok==true){
		            alert('Name already taken.Please change Guide name.');
	            	document.getElementById("submitChange").disabled = true;
		            }
	            else{
	            	document.getElementById("submitChange").disabled = false;
		            }
	          }
	        });
	      }, 200);
	      
	      this.lastValue = this.value;
	    }
	  });
	});
	$('input').keyup(function() {
	    var $th = $(this);
	    $th.val( $th.val().replace(/[^a-zA-Z0-9]/g, function(str) { alert('You typed " ' + str + ' ".\n\nPlease use only letters and numbers.'); return ''; } ) );
	});

	function validateForm()
	{
	var x=document.forms["formNewGuide"]["name"].value;
	if (x==null || x=="")
	  {
	  alert("GuideName must be filled out");
	  //document.getElementById("submitChange").disabled = true;
	  return false;
	  }
	var x1=document.forms["formNewGuide"]["subname"].value;
	if (x1==null || x1=="")
	  {
	  alert("Guide SubName must be filled out");
	  //document.getElementById("submitChange").disabled = true;
	  return false;
	  }
	var x2=document.forms["formNewGuide"]["wkt"].value;
	if (x2==null || x2=="")
	  {
	  alert("Missing Guide Geometry.Please go to Step 1.");
	  //document.getElementById("submitChange").disabled = true;
	  return false;
	  }
	 
	}

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
		    
				
			
			
			</div>
	    
			
			<div class="span3">
				
			</div>
			
			
		</div>



	</div>


	


</body>
</html>


