<?php
ini_set("session.gc_maxlifetime", "3600");
require_once  ("../inc/utils.php");

$page_title='HikeGuide Authoring Tools';
$brand_text='HikeGuide Creator - Step 1';
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


var lat=47.496792
var lon=7.571726
var zoom=6

var map; 

function init_map() {
	map = new OpenLayers.Map ("map_canvas", {
		controls:[
			new OpenLayers.Control.Navigation(),
			new OpenLayers.Control.PanZoomBar(),
			new OpenLayers.Control.LayerSwitcher(),
			new OpenLayers.Control.Attribution()],
		maxExtent: new OpenLayers.Bounds(-20037508.34,-20037508.34,20037508.34,20037508.34),
		maxResolution: 156543.0399,
		numZoomLevels: 19,
		units: 'm',
		projection: new OpenLayers.Projection("EPSG:900913"),
		displayProjection: new OpenLayers.Projection("EPSG:4326")
	} );

	document.getElementById('f1_upload_process').style.visibility = 'hidden';
	
	layerMapnik = new OpenLayers.Layer.OSM.Mapnik("Mapnik");
	map.addLayer(layerMapnik);
	layerCycleMap = new OpenLayers.Layer.OSM.CycleMap("CycleMap");
	map.addLayer(layerCycleMap);
	layerMarkers = new OpenLayers.Layer.Markers("Markers");
	map.addLayer(layerMarkers);

	// Add the Layer with the GPX Track
	/*var lgpx = new OpenLayers.Layer.Vector("Lakeside cycle ride", {
		strategies: [new OpenLayers.Strategy.Fixed()],
		protocol: new OpenLayers.Protocol.HTTP({
			url: "around_lake.gpx",
			format: new OpenLayers.Format.GPX()
		}),
		style: {strokeColor: "green", strokeWidth: 5, strokeOpacity: 0.5},
		projection: new OpenLayers.Projection("EPSG:4326")
	});*/
	//map.addLayer(lgpx);
	var google_hybrid_layer = new OpenLayers.Layer.Google(
			"Google Hybrid",
			{type: google.maps.MapTypeId.HYBRID}
			);
	map.addLayer(google_hybrid_layer);
	///////////////////////////////////////////////////////
	var lonLat = new OpenLayers.LonLat(lon, lat).transform(new OpenLayers.Projection("EPSG:4326"), map.getProjectionObject());
	map.setCenter(lonLat, zoom);

	var size = new OpenLayers.Size(21, 25);
	var offset = new OpenLayers.Pixel(-(size.w/2), -size.h);
	var icon = new OpenLayers.Icon('http://www.openstreetmap.org/openlayers/img/marker.png',size,offset);
	//layerMarkers.addMarker(new OpenLayers.Marker(lonLat,icon));

	
	
	
}

	//////////////////////////////////
	///Ajax upload functions
	function startUpload(){
		document.getElementById('upload_form').style.visibility = 'hidden';
	    document.getElementById('f1_upload_process').style.visibility = 'visible';
	    return true;
	}

	function stopUpload(success){
	      var result = '';
	      if (success==0) {
		         document.getElementById('result').innerHTML = 
		           '<span class="emsg">There was an error during file upload!<\/span><br/><br/>';
		      }
	      else{
	         document.getElementById('result').innerHTML =
	           '<span class="msg">'+success+' - The .gpx file was uploaded successfully!<\/span><br/><br/>';

	        // Add the Layer with the GPX Track
	       	var lgpx = new OpenLayers.Layer.Vector("Imported track ", {
	       		strategies: [new OpenLayers.Strategy.Fixed()],
	       		protocol: new OpenLayers.Protocol.HTTP({
	       			url: '../upload/'+success,
	       			format: new OpenLayers.Format.GPX()
	       		}),
	       		style: {strokeColor: "green", strokeWidth: 5, strokeOpacity: 0.5},
	       		projection: new OpenLayers.Projection("EPSG:4326")
	       	});
	       	map.addLayer(lgpx);
	       	lgpx.events.register("loadend", lgpx, function() { 
	       		var size = new OpenLayers.Size(21,25);
	            var offset = new OpenLayers.Pixel(-(size.w/2), -size.h);
	       		var icon = new OpenLayers.Icon('../marker/map_start.png',size,offset);
		       	this.map.zoomToExtent(this.getDataExtent());
		       	var startPoint = this.features[0].geometry.components[0];
		        layerMarkers.addMarker(new OpenLayers.Marker(new OpenLayers.LonLat(startPoint.x, startPoint.y),icon));


		       	 } );

	       	var report = function(e) {
                OpenLayers.Console.log(e.type, e.feature.id);
            };

	       	var highlightCtrl = new OpenLayers.Control.SelectFeature(lgpx, {
                hover: true,
                highlightOnly: true,
                renderIntent: "temporary",
                eventListeners: {
                    beforefeaturehighlighted: report,
                    featurehighlighted: report,
                    featureunhighlighted: report
                }
            });

            var selectCtrl = new OpenLayers.Control.SelectFeature(lgpx,
                {clickout: true}
            );

            map.addControl(highlightCtrl);
            map.addControl(selectCtrl);

            highlightCtrl.activate();
            selectCtrl.activate();

			//////////////////////////////////////////////
			//Insert event
            map.layers[4].onFeatureInsert = function(feature){
    			
              //  point_to_transform=feature.geometry;
			
			///where to insert wkt
			document.forms[1].wkt.value=feature.geometry;
			
			
		  
			};

            
			////////////////////////////////////////////////////////
        	//Controls
        	var select_feature_control = new OpenLayers.Control.
        	SelectFeature(
        	lgpx,
        	{
        	multiple: false,
        	toggle: true,
        	multipleKey: 'shiftKey'
        	}
        	);

        	map.addControl(select_feature_control);

        	select_feature_control.activate();

        	
        	            

			//we dont need edit here
	       	//map.addControl(new OpenLayers.Control.EditingToolbar(lgpx));
	      }
	      
	      document.getElementById('upload_form').style.visibility = 'visible';
	      document.getElementById('f1_upload_process').style.visibility = 'hidden';
	      return true;   
	}
	/////////////////////////
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
	  alert("Please upload track geometry.");
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
  <div class="bar" style="width: 33%;"></div>
</div>
	    </div>
	    	<p id="f1_upload_process">Uploading...<br/></p>
			<p id="result"></p>
	    	<form id="upload_form" action="../upload/upload.php" method="post" enctype="multipart/form-data" target="upload_target" onsubmit="startUpload();" >
    			<legend></legend>
    			<label for="myFile" style="color: blue;"> GPX File location: </label>
    			<input name="myfile" type="file" />
    			</br>
         			  <button type="submit" name="submitGPX" value="Upload"  class="btn btn-primary" data-loading-text="Loading...">Upload</button>
			</form>
 
			<iframe id="upload_target" name="upload_target" src="#" style="width:0;height:0;border:0px solid #fff;"></iframe>
	    	
	    	
	    </div>
	
		<div class="row-fluid">
			
		    <div class="span3">
		    
				<form name="formNewGuide" action="hg_editor.php" method="post" onsubmit="return validateForm()" >
					
					<legend>Guide details</legend>
					
					<label for="name" style="color: blue;"> Name: </label> 
					<input id="name" value="" type="text" name="name" /> 
					<span id="validateName"><?php if ($error) { echo $error['msg']; } ?></span>
					
					<label for="subname" style="color: blue;"> Track Sub Name: </label> 
					<input id="subname" value="" type="text" name="subname" /> 
					
					<label for="summary" style="color: blue;"> Summary text: </label> 
					
					<textarea id="summary" value="" type="text" cols="200" rows="5" name="summary" > 
					</textarea>
					<label for="navigation" style="color: blue;"> Navigation text: </label> 
					<textarea id="navigation" value="" type="text" cols="200" rows="5" name="navigation" > 
					</textarea>
					
					<label for="mapname" style="color: blue;"> Map Name: </label> 
					<input id="mapname" value="" type="text" name="mapname" /> 
					
					<label for="trackname" style="color: blue;"> Track Name: </label> 
					<input id="trackname" value="" type="text" name="trackname" /> 
						
					<label for="wkt" style="color: blue;"> Geometry WKT: </label> 
					<input id="wkt" value="" type="text" name="wkt" readonly="readonly"/> 
					
					
					
					<button id="submitChange" type="submit" class="btn btn-success" value="Submit"
						name="SubmitChange">Go To Step 2</button>
					
				</form>
			
			
			</div>
	    
			<p></p>
			<p></p>
			<div class="span3">
				<p></p>
				</br>
			    
				
				<div id="map_canvas" style="width: 800px; height: 600px;"></div>
				<p>*Map centers on the track geometry when upload is succesfull.</p>
			</div>
			
			
		</div>



	</div>


	


</body>
</html>


