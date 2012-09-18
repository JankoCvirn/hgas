<?php
ini_set("session.gc_maxlifetime", "3600");
require_once  ("inc/utils.php");

$page_title='HikeGuide Authoring Tools';
$brand_text='HikeGuide Creator';
$hero_text='Create a new track interface.';

session_start();

$msg_status='';

$username=$_SESSION['username'];

//logout
if (isset($_REQUEST["logout"])){
	session_unset();
	session_destroy();
}
//back to index.html
if (!session_is_registered($username)) {
	header("Location:index.php");
}




?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
<meta charset="utf-8">

<link href="css/bootstrap.css" rel="stylesheet">
<link href="css/bootstrap-responsive.css" rel="stylesheet">
<style>
      body {
        padding-top: 60px; 
      }
    </style>


<script type="text/javascript"
	src="http://maps.googleapis.com/maps/api/js?key=AIzaSyDO6tvjnEASjgCJqGNAvU8t3bZGF0W9eNU&sensor=true">

    </script>

<script type="text/javascript"
	src="http://maps.googleapis.com/maps/api/js?sensor=false&libraries=drawing ">

    </script>

<script type="text/javascript">
      function initialize() {
        var mapOptions = {
        		panControl: true,
        	    zoomControl: true,
        	    scaleControl: true,      
          center: new google.maps.LatLng(50.005, 4.00),
          zoom: 8,
          mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        var map = new google.maps.Map(document.getElementById("map_canvas"),
            mapOptions);
		//Drawing Manager options
        var drawingManager = new google.maps.drawing.DrawingManager({
        	  drawingMode: google.maps.drawing.OverlayType.MARKER,
        	  drawingControl: true,
        	  drawingControlOptions: {
        	    position: google.maps.ControlPosition.TOP_CENTER,
        	    drawingModes: [google.maps.drawing.OverlayType.MARKER, 
        	           	       google.maps.drawing.OverlayType.CIRCLE,
        	           	       google.maps.drawing.OverlayType.POLYLINE
        	           	       ]
        	  },

         	  markerOptions: {
        	    icon: new google.maps.MarkerImage('img/icon/marker.png'),
        	    editable: true
        	  },
        	  circleOptions: {
        	    fillColor: '#ffff00',
        	    fillOpacity: 0.8,
        	    strokeWeight: 2,
        	    clickable: false,
        	    zIndex: 1,
        	    editable: true
        	  },
        	  polylineOptions:{

        		  editable: true,
        		  fillColor: '#669966',
          	      fillOpacity: 0.8,
          	      strokeWeight: 2,
          	      clickable: false

        		  
            	  }
        	});
        drawingManager.setMap(map);
      }
    </script>
</head>
<body onload="initialize()">

	<div class="navbar navbar-inverse navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <a class="brand" href="#"><?php echo $brand_text;?></a>
          <?php include 'inc/navigation/navigation.php';?>
          
        </div>
      </div>
    </div>

	<div class="container-fluid">
		<div class="row-fluid">
			
		    <div class="span9">
		    <table>
				<form name="newTrack" action="<?php echo($PHP_SELF)?>" method="post" >
					<tr>
					<legend>Track details</legend>
					</tr>
					<tr>
					<td>
					<label for="name" style="color: blue;"> Name: </label> 
					<input id="name" value="" type="text" name="name" /> 
					</td>
					<td>
					<label for="subname" style="color: blue;"> Track Sub Name: </label> 
					<input id="subname" value="" type="text" name="subname" /> 
					</td>
					<td>
					<label for="mapname" style="color: blue;"> Map Name: </label> 
					<input id="mapname" value="" type="text" name="mapname" /> 
					</td>
					<td>
					<label for="trackname" style="color: blue;"> Track Name: </label> 
					<input id="trackname" value="" type="text" name="trackname" /> 
					</td>
					</tr>
					<tr>
					<td colspan="3">
					<label for="summary" style="color: blue;"> Summary: </label> 
					
					<textarea id="summary" value="" type="text" cols="100" rows="5" name="summary" > 
					</textarea>
					</td>
					</tr>
					<tr>
					
					</tr>
					<tr>
					<td>
					<label for="geom" style="color: blue;"> Geometry: </label> 
					<input id="geom" value="" type="text" name="geom" /> 
					</td>
					<td>
					<label for="active" style="color: blue;"> Active: </label> 
					<input id="active" value="" type="text" name="active" /> 
					</td>
					</tr>
					
					
					<tr>
					<td>
					<button type="submit" class="btn btn-success" value="Submit"
						name="SubmitChange">Create</button>
					</td>
					</tr>
				</form>
			</table>
			
			</div>
	    </div>
	    <div class="row-fluid">
			
			
			
			<div class="span9">
				<p></p>
				<p></p>
				<p></p>
				<div id="map_canvas" style="width: 100%; height: 600px;"></div>
			</div>
		</div>



	</div>


	<script src="js/jquery-1.7.1.js"></script>
	<script src="js/bootstrap-modal.js"></script>
	<script src="js/bootstrap-dropdown.js"></script>
	<script src="js/bootstrap-scrollspy.js"></script>
	<script src="js/bootstrap-tab.js"></script>
	<script src="js/bootstrap-tooltip.js"></script>
	<script src="js/bootstrap-popover.js"></script>
	<script src="js/bootstrap-button.js"></script>
	<script src="js/bootstrap-collapse.js"></script>
	<script src="js/bootstrap-carousel.js"></script>
	<script src="js/bootstrap-typeahead.js"></script>


</body>
</html>


