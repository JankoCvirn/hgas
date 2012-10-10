<?php
ini_set("session.gc_maxlifetime", "3600");
require_once  ("../inc/utils.php");

$page_title='HikeGuide Authoring Tools';
$brand_text='HikeGuide Creator - Step 2 (describe the hike)';
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
if (isset($_POST['wkt'])){

	$_SESSION['wkt']=$_POST['wkt'];
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
		    
				<form name="formNewGuide" action="hg_creator3.php" method="post" enctype="multipart/form-data" onsubmit="return validateForm()"  >
					
					
					
					<label for="name" style="color: blue;"> Name: </label> 
					<input id="name" value="" type="text" name="name" /> 
					<span id="validateName"><?php if ($error) { echo $error['msg']; } ?></span>
					
					<label for="tcolor" style="color: blue;"> Text color: </label> 
						<select id="tcolor" name="tcolor">
							<option value="black">Black</option>
							<option value="white">White</option>
					</select>
					
					<label for="subname" style="color: blue;"> Track Sub Name: </label> 
					<input id="subname" value="" type="text" name="subname" /> 
					<label for="bcolor" style="color: blue;"> Background color: </label> 
							<select id="bcolor" name="bcolor">
								<option value="black">Black</option>
								<option value="white">White</option>
							</select>
							
					<label for="logoFile" style="color: blue;">Logo file location: </label>
					<input name="logoFile" type="file" />
					
					
					
					
					
					
					<!-- picture file section -->
					
					<table class="table table-bordered">
					
					<tr>
						<td>
							<label for="pic1" style="color: blue;">Picture 1 file location: </label>
							
							<input name="pic1" type="file" />
							
							
						</td>
					</tr>
					<tr>
					<td>
							<label for="pic2" style="color: blue;">Picture 2 file location: </label>
							
							<input name="pic2" type="file" />
							
							
						</td>
					</tr>
			
						<tr>
							<td>
								<label for="pic3" style="color: blue;">Picture 3 file location: </label>
							
								<input name="pic3" type="file" />
							
								
							</td>
						 </tr>
						 <tr>
							<td>
								<label for="pic4" style="color: blue;">Picture 4 file location: </label>
								
								<input name="pic4" type="file" />
								
								
							</td>
						</tr>
		    </table>
					
					<!-- end picture file section -->
					<!-- details  -->
					<table class="table table-bordered">
		    <tr>
		        <td>
		        <label for="distance" style="color: blue;"> Distance: </label> 
							<input id="distance" value="" type="text" name="distance" /> 
		        </td>
		        <td>
		        <label for="region" style="color: blue;"> Region: </label> 
							<input id="region" value="" type="text" name="region" /> 
		        </td>
		    </tr>
		    <tr>
		       <td>
		       
		        <label for="difficulty" style="color: blue;"> Difficulty: </label> 
							<input id="difficulty" value="" type="text" name="difficulty" /> 
		        
		       </td>
		       
		       <td>
		        <label for="nature" style="color: blue;"> Nature: </label> 
							<input id="nature" value="" type="text" name="nature" /> 
		        </td>
		       
		    </tr>
			<tr>
			    <td>
					<label for="summary" style="color: blue;"> Summary text: </label> 
					<textarea id="summary" value="" type="text" cols="200" rows="5" name="summary" ></textarea>
				 </td>
			</tr>
				
				
			</table>
					
					<!-- end sub details -->
			<button id="submitChange" type="submit" class="btn btn-success" value="Submit"
						name="SubmitDetails">Go To Step 3</button>
					
				</form>
			
			
			</div>
	    
			
			<div class="span3">
				
			</div>
			
			
		</div>



	</div>


	


</body>
</html>


