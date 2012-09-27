<?php
ini_set("session.gc_maxlifetime", "3600");
require_once  ("../inc/utils.php");
require '../inc/hgutils.php';

$page_title='HikeGuide Authoring Tools';
$brand_text='HikeGuide Creator - Step 2';
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

if (isset($_POST['SubmitChange'])){
	
	$name=$_POST['name'];
	$subName=$_POST['subname'];
	$wkt=$_POST['wkt'];
	$summary=$_POST['summary'];
	$navigation=$_POST['navigation'];
	$mapName=$_POST['mapname'];
	$trackName=$_POST['trackname'];
	
	//$oHgUtil=new hgutils($username);
	//$oHgUtil->setGuideData($name, $subName, $wkt, $summary, $navigation, $mapName, $trackName);
	
	
}



?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
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
<!--  -->

<script type="text/javascript">


var lat=47.496792;
var lon=7.571726;
var zoom=13;

var map; 
var vector;
var wkt='<?php echo $wkt;?>';
var drawControls;
var trackName='test v1';
var pointLayer;
var pointControl;
var modifyControl;

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

	//Layer to hold the info-nav objects
	pointLayer = new OpenLayers.Layer.Vector("Guide objects Layer");
	map.addLayer(pointLayer);
	//
	layerMapnik = new OpenLayers.Layer.OSM.Mapnik("Mapnik");
	map.addLayer(layerMapnik);
	//layerCycleMap = new OpenLayers.Layer.OSM.CycleMap("CycleMap");
	//map.addLayer(layerCycleMap);
	//layerMarkers = new OpenLayers.Layer.Markers("Markers");
	//map.addLayer(layerMarkers);

	var google_hybrid_layer = new OpenLayers.Layer.Google(
			"Google Hybrid",
			{type: google.maps.MapTypeId.HYBRID}
			);
	map.addLayer(google_hybrid_layer);

	//////////////////////////////////////////////////
	//Add the wkt to vector
	vector= new OpenLayers.Layer.Vector(trackName,{style: {strokeColor: "green", strokeWidth: 5, strokeOpacity: 0.5},
   		projection: new OpenLayers.Projection("EPSG:4326")});
	var track_geom = new OpenLayers.Geometry.fromWKT(<?php echo wkt;?>, { strokeColor: "green", strokeWidth: 5, strokeOpacity: 0.5 }); 
	
	var trackFeature = new OpenLayers.Feature.Vector(track_geom,null, {}); 
	vector.addFeatures([trackFeature]);
	
	map.addLayer(vector);

	var dataExtent=	vector.getDataExtent();
	map.zoomToExtent(dataExtent);

	modifyControl = new OpenLayers.Control.ModifyFeature(pointLayer);
	map.addControl(modifyControl);	

    }


	function setObjectDraw(){

		pointControl=new OpenLayers.Control.DrawFeature(pointLayer,
	            OpenLayers.Handler.Point);
		map.addControl(pointControl);
		modifyControl.deactivate();
		pointControl.activate();   
		}

	function setObjectStopDraw(){
		pointControl.deactivate();
		modifyControl.deactivate();
		}

	function setObjectModifyDrag(){
		pointControl.deactivate();
		mode = OpenLayers.Control.ModifyFeature.DRAG;
		
		modifyControl.mode=mode;
		
		modifyControl.activate();
		}

	function setObjectDelete(){
		pointControl.deactivate();
		modifyControl.deactivate();
		pointLayer.removeAllFeatures();

		}

	function validateForm()
	{
	var x=document.forms["formNewGuide2"]["name"].value;
	if (x==null || x=="")
	  {
	  alert("Object Name must be filled out");
	  //document.getElementById("submitChange").disabled = true;
	  return false;
	  }
	var x2=document.forms["formNewGuide2"]["wkt_object"].value;
	if (x2==null || x2=="")
	  {
	  alert("No spatial object created.");
	  //document.getElementById("submitChange").disabled = true;
	  return false;
	  }
	 
	}

	//ajax call to store object
	
	//event to fire on map add objects
	

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
	    	<div class="progress progress-striped">
  <div class="bar" style="width: 66%;"></div>
</div>
	    </div>
	
		<div class="row-fluid">
			
		    <div class="span3">
		    	
				<form name="formNewGuide2" action="" method="post" onsubmit="return validateForm()" >
					
					<legend>Guide details - step 2</legend>
					
					<label for="name" style="color: blue;"> Object name: </label> 
					<input id="name" value="" type="text" name="name" /> 
					
					<select title="Select Object Type:" name="type" >
						<option value="1">Navigation point</option>
						<option value="2">Information point</option>
					
					</select>
					
					<label for="proxy" style="color: blue;"> Proximity (50m) alert: </label> 
					<input id="proxy" value="50" type="text" name="proxy" /> 
					<label for="proxy" style="color: blue;"> *default 50m </label> 
					
					<label for="wkt_object" style="color: blue;"> WKT: </label>
					<input id="wkt_object" value="" type="text" name="wkt_object" readonly="readonly" />  
					<legend>Lat-Lng Projection:900913</legend>
					<label for="lat_object" style="color: blue;"> Lat: </label>
					<input id="lat_object" value="" type="text" name="lat_object" readonly="readonly" />  
	
					<label for="lng_object" style="color: blue;"> Lng: </label>
					<input id="lng_object" value="" type="text" name="lng_object" readonly="readonly" />  
					
					<legend>Info</legend>
					<label for="description" style="color: blue;"> Description: </label> 
					<input id="description" value="" type="text" name="description" /> 
					</br>
					<button type="submit" class="btn btn-primary" value="Submit"
						name="SubmitChange">Save object</button>
					
				</form>
			
			
			</div>
	    
			<p></p>
			<p></p>
			<div class="span3">
			<button type="button" class="btn btn-success" value="Draw"
						name="addObject" onclick="setObjectDraw()">Draw</button>
		    <button type="button" class="btn btn-success" value="Modify"
						name="addModify" onclick="setObjectModifyDrag()">Modify </button>
		    <button type="button" class="btn btn-success" value="Pan"
						name="addPan" onclick="setObjectStopDraw()">Pan </button>
		    <button type="button" class="btn btn-danger" value="Delete"
						name="addDelete" onclick="setObjectDelete()">Delete </button>
		    
				<p></p>
				</br>
			    
				
				<div id="map_canvas" style="width: 800px; height: 600px;"></div>
				<p></p>
			</div>
			
			
		</div>



	</div>


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


</body>
</html>


