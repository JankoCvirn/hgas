<?php
//TODO 
//populate array on guide selected
//draw all in a layer
//add select control
//populate from db the fields
//should be done with mapserver or geoserver

ini_set("session.gc_maxlifetime", "3600");
require_once  ("../inc/utils.php");

$page_title='HikeGuide Authoring Tools';
$brand_text='HikeGuide Editor';

session_start();





$msg_status='';

$page_title='HikeGuide Authoring Tools';
$brand_text='HikeGuide Publisher';
$hero_text='HikeGuide guides.';

$username=$_SESSION['username'];
$wkt_json=null;
//logout
if (isset($_REQUEST["logout"])){
	session_unset();
	session_destroy();
}
//back to index.html
if (!session_is_registered($username)) {
	header("Location:../index.php");}
	
	
	$user_name = "h7993hga_user";
	$password = "secret2012";
	$database = "h7993hga_hg-auth";
	$server = "localhost";
	
	$db_handle2 = mysql_connect($server, $user_name, $password);
	$db_found2 = mysql_select_db($database, $db_handle2);
	$SQL = "SELECT * FROM track ORDER BY id ASC    ";
	$result2 = mysql_query($SQL);
	
	while ($db_row = mysql_fetch_assoc($result2)) {
		$result_names[] = $db_row;
	
	}
	mysql_close($db_handle2);
	
if (isset($_REQUEST["SubmitGuideName"])){
	$guideName=$_REQUEST['guide_name'];
	//read object from db and populate arrays
	
	$user_name = "h7993hga_user";
	$password = "secret2012";
	$database = "h7993hga_hg-auth";
	$server = "localhost";
	
	$db_handle = mysql_connect($server, $user_name, $password);
	$db_found = mysql_select_db($database, $db_handle);
	$result=null;
	$result_infopoints=null;
	$result_navpoints=null;
	$result_poipoints=null;
	$result_track=null;
	$result_wkt=null;
	
	if ($db_found) {
	
		$SQL = "SELECT * FROM infospot where parent_id='".$guideName."'     ";
		$result = mysql_query($SQL);
	
		while ($db_row = mysql_fetch_assoc($result)) {
			$result_infopoints[] = $db_row;
	
		}
		$SQL = "SELECT * FROM navspot where parent_id='".$guideName."'     ";
		$result = mysql_query($SQL);
		
		while ($db_row = mysql_fetch_assoc($result)) {
			$result_navpoints[] = $db_row;
		
		}
		$SQL = "SELECT * FROM poispot where parent_id='".$guideName."'     ";
		$result = mysql_query($SQL);
		
		while ($db_row = mysql_fetch_assoc($result)) {
			$result_poipoints[] = $db_row;
		
		}
		$SQL = "SELECT * FROM track where name='".$guideName."'    ";
		$result = mysql_query($SQL);
		
		while ($db_row = mysql_fetch_assoc($result)) {
			$result_track[] = $db_row;
		
		}
		$SQL = "SELECT WKT FROM GUIDE_WKT where name='".$guideName."'    ";
		$result = mysql_query($SQL);
		
		while ($db_row = mysql_fetch_assoc($result)) {
			$result_wkt[] = $db_row;
		
		}
		if ($result_wkt!=null){
		$wkt_json=json_encode($result_wkt);
		}
		
		
		mysql_close($db_handle);
	
	}
	else {
		print "Database NOT Found ";
		mysql_close($db_handle);
	}
	
	
}
	
	
?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta name="apple-mobile-web-app-capable" content="yes">
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
	<script src="../js/openStreetMap.js"></script>
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
<!--  -->
<script type="text/javascript">

//MAP VARIABLES
var lat=47.496792;
var lon=7.571726;
var zoom=13;

//projection part
var proj4326 = new OpenLayers.Projection("EPSG:4326");
var projGoogle = new OpenLayers.Projection("EPSG:900913");
var wktObj = new OpenLayers.Format.WKT;
var map; 
var vector;
var navlayer;
var info_layer;
var poi_layer;
<?php 
$wkt_json=json_encode($result_wkt);
echo "var wkt = ". $wkt_json . ";\n";?>

var drawControls;
var trackName;
var pointLayer;
var pointControl;
var modifyControl;
var dataExtent;


        



		
function mapInit(){	
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

	vector= new OpenLayers.Layer.Vector('Track',{style: {strokeColor: "green", strokeWidth: 5, strokeOpacity: 0.5, pointRadius: 8,  fillColor: "#ffcc66"},projection: new OpenLayers.Projection("EPSG:4326")});

	if (wkt==null){
		nav_wkt="LINESTRING(490158.3411753205 6661184.599164436,490241.77980424586 6661121.419501719,490174.7135812718 6661040.655053333,490152.1024698673 6660949.246723023,490373.84897532547 6660632.632630629,490396.1742183763 6660313.416491047)";
		var nav_geom = new OpenLayers.Geometry.fromWKT(nav_wkt); 
		
		var navFeature = new OpenLayers.Feature.Vector(nav_geom,null, {}); 
		
		vector.addFeatures([navFeature]);
		
		map.addLayer(vector);
		dataExtent=vector.getDataExtent();
		map.zoomToExtent(dataExtent);
		
	}

	else {
		for (var i=0;i<wkt.length;i++){
			//nav_wkt="LINESTRING(490158.3411753205 6661184.599164436,490241.77980424586 6661121.419501719,490174.7135812718 6661040.655053333,490152.1024698673 6660949.246723023,490373.84897532547 6660632.632630629,490396.1742183763 6660313.416491047)";
				
			var poi_geom = new OpenLayers.Geometry.fromWKT(wkt[i].WKT); 
			
			var poiFeature = new OpenLayers.Feature.Vector(poi_geom,null, {}); 
			//var navFeature=wktObj.read(nav_geom);
			vector.addFeatures([poiFeature]);
		}
		map.addLayer(vector);
		dataExtent=vector.getDataExtent();
		map.zoomToExtent(dataExtent);
		
	}


	}


    

		

		
        	
        </script>
  </head>

  <body onload="mapInit()">

    <div class="navbar navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container-fluid">
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
      <div class="row-fluid">
        
        <div class="span9">
          
          <div class="row-fluid">
          <h2>HGuides:</h2>
            
            </div>
            <div class="row-fluid">
            <div class="span4 offset2">
            <table class="table-bordered">
			            <caption>Guide to display:</caption>
						<form name="selectForm" action="<?php echo($PHP_SELF)?>" method="post" >
						
						<tr class="success"><td>
						<label for="guide_name" style="color: blue;"> Guide Name: </label>
						<select  name="guide_name" title="Select a Guide ">	      
		        			<?php foreach ($result_names as $names):?>
		        			  <option value="<?php echo $names['name'] ?>"><?php echo $names['name'] ?> </option>
		        			<?php endforeach;?>
		        			</select>
						</td>
						</tr>
						
						<tr class="success"><td>
						<button type="submit" class="btn btn-success"  value="Submit"  name="SubmitGuideName">Draw</button>
						</td>	
						</tr>
						
						</form>
						
					</table>
            
            
            
            </div>
          </div><!--/row-->
          
        <!-- Map holder -->
        
        <!-- Object selectors --> 
        <div class="row-fluid">
	        <div class="container-fluid">
			  <div class="row-fluid">
			    <div class="span3">
			    
			    
			    	
					</br>
					
			    	<table class="table">
				    	<caption>Details:</caption>
				    	<tr><td>
				    	     <?php foreach ($result_track as $track):?>
				    	     Summary: <?php echo $track['summary']?>
				    	     
				    	    </td></tr>
				    	<tr><td>
				    	    
				    	     Navigation: <?php echo $track['navigation']?>
				    	     <?php endforeach;?>
				    	    </td></tr>
				    	
			    	</table>
			    	
			    	</br>
			    
			        <form>
						
							<label class="control-label" >Info points:</label>
							<div class="controls">
							<select  onchange="">	      
		        			<?php foreach ($result_infopoints as $info):?>
		        			  <option value="<?php echo $info['WKT'] ?>"><?php echo $info['name'].' - '.$info['description'] ?> </option>
		        			<?php endforeach;?>
		        			</select>
		        			</div>
		        			
		        			<label class="control-label" >Navigation points:</label>
							<div class="controls">
							<select  onchange="">	      
		        			<?php foreach ($result_navpoints as $nav):?>
		        			  <option value="<?php echo $nav['WKT'] ?>"><?php echo $nav['name'].' - '.$nav['description'] ?> </option>
		        			<?php endforeach;?>
		        			</select>
		        			</div>
		        			
		        			<label class="control-label" >Points of interest:</label>
							<div class="controls">
							<select  onchange="">	      
		        			<?php foreach ($result_poipoints as $poi):?>
		        			  <option value="<?php echo $poi['WKT'] ?>"><?php echo $poi['name'].' - '.$poi['description'] ?> </option>
		        			<?php endforeach;?>
		        			</select>
		        			</div>
		        			
        			</form>
			    </div>
			    <div class="span10">
			      <!--MAP content-->
			      <div id="map_canvas" style="width: 800px; height: 600px;"></div>
			      
			    </div>
			  </div>
			</div>
        
        </div>
         
          
        </div><!--/span-->
      </div><!--/row-->

      <hr>

      <footer>
        <p><?php include '../inc/footer/footer.php'?></p>
      </footer>
    </div> <!-- /container -->

    
    

  </body>
</html>


