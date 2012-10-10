<?php
ini_set("session.gc_maxlifetime", "3600");
require_once  ("../inc/utils.php");
require '../inc/hgutils.php';

$page_title='HikeGuide Authoring Tools';
$brand_text='HikeGuide Creator - Step 2';
$hero_text ='Create a new guide user interface.';

session_start();

$msg_status = '';
$user_name = "h7993hga_user";
$password = "secret2012";
$database = "h7993hga_hg-auth";
$server = "localhost";

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

if (isset($_POST['name'])){
	
	$_SESSION['guide_name']=$_POST['name'];
}

if (isset($_POST['SubmitChange'])){
	
	$name=$_POST['name'];
	$subName=$_POST['subname'];
	$wkt=$_POST['wkt'];
	$summary=$_POST['summary'];
	//$navigation=$_POST['navigation'];
	//$mapName=$_POST['mapname'];
	//$trackName=$_POST['trackname'];
	$tcolor=$_POST['tcolor'];
	$bcolor=$_POST['bcolor'];
	$distance=$_POST['distance'];
	$region=$_POST['region'];
	$difficulty=$_POST['difficulty'];
	$nature=$_POST['nature'];
	
	//logo upload
	
	
	//pic1 upload
	
	//pic2 upload
	
	//pic3 upload
	
	//pic4 upload
	
	
	
	$oHgUtil=new hgutils($username);
	$oHgUtil->setGuideData($name, $subName, $wkt, $summary, $tcolor, $bcolor, $distance, $region, $difficulty, $nature);
	//$oHgUtil->setGuideData($name, $subName, $wkt, $summary, $navigation, $mapName, $trackName);
	
	
	
}

if (isset($_POST['AddNewObject'])){
	
	$type=$_POST['type'];
	$oname=$_POST['oname'];
	$proxy=$_POST['proxy'];
	$wkt_object=$_POST['wkt_object'];
	$desc=$_POST['description'];
	
	$oHgUtil=new hgutils($username);
	
	//Navigation
	if ($type=='1'){
		$oHgUtil->setNavPoint($_SESSION['guide_name'], $desc, '0', $wkt_object, $oname);
	}
	//Info
	else if ($type=='2'){
		$oHgUtil->setInfoPoint($_SESSION['guide_name'], $desc, '0', $wkt_object, $oname);
	}
	//Proxy
	else if ($type=='3'){
		$oHgUtil->setProxyPoint($_SESSION['guide_name'], $desc, '0', $wkt_object, $prox, $oname);
		
	}
	
	
}

///Populate points info points array
$db_handle = mysql_connect($server, $user_name, $password);
$db_found = mysql_select_db($database, $db_handle);

if ($db_found) {

	$SQL = "SELECT * FROM infospot where parent_id='".$_SESSION['guide_name']."'     ";
	$result = mysql_query($SQL);

	while ($db_row = mysql_fetch_assoc($result)) {
		$rezultati_pretrage_infopoints[] = $db_row;

	}
	mysql_close($db_handle);

}
else {
	print "Database NOT Found ";
	mysql_close($db_handle);
}
///Populate nav-points array
$db_handle = mysql_connect($server, $user_name, $password);
$db_found = mysql_select_db($database, $db_handle);

if ($db_found) {

	$SQL = "SELECT WKT FROM navspot where parent_id='".$_SESSION['guide_name']."'      ";
	$result = mysql_query($SQL);

	while ($db_row = mysql_fetch_assoc($result)) {
		$rezultati_pretrage_nav[] = $db_row;

	}
	mysql_close($db_handle);

}
else {
	print "Database NOT Found ";
	mysql_close($db_handle);
}


///Populate poi-points array

$db_handle = mysql_connect($server, $user_name, $password);
$db_found = mysql_select_db($database, $db_handle);

if ($db_found) {

	$SQL = "SELECT * FROM poispot where parent_id='".$_SESSION['guide_name']."'   ";
	$result = mysql_query($SQL);

	while ($db_row = mysql_fetch_assoc($result)) {
		$rezultati_pretrage_poi[] = $db_row;

	}
	mysql_close($db_handle);

}
else {
	print "Database NOT Found ";
	mysql_close($db_handle);
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
<!--  -->
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


var lat=47.496792;
var lon=7.571726;
var zoom=13;

//projection part
var proj4326 = new OpenLayers.Projection("EPSG:4326");
var projGoogle = new OpenLayers.Projection("EPSG:900913");
var wktObj = new OpenLayers.Format.WKT;



//
///Add geometry to layers
	<?php 
		$nav_array=json_encode($rezultati_pretrage_nav);
		$info_array=json_encode($rezultati_pretrage_infopoints);
		$poi_array=json_encode($rezultati_pretrage_poi);
		
		echo "var nav_array = ". $nav_array . ";\n";
		echo "var info_array = ". $info_array . ";\n";
		echo "var poi_array = ". $poi_array . ";\n";
		
	?>
var map; 
var vector;
var navlayer;
var info_layer;
var poi_layer;
var wkt='<?php echo $_SESSION['wkt'];?>';
var drawControls;
var trackName='<?php $_SESSION['guide_name'];?>';
var pointLayer;
var pointControl;
var modifyControl;

var myStyles = new OpenLayers.StyleMap({
    "default": new OpenLayers.Style({
        pointRadius: 8, // sized according to type attribute
        fillColor: "#ffcc66",
        strokeColor: "#ff9933",
        strokeWidth: 2,
        graphicZIndex: 1
    }),
    "select": new OpenLayers.Style({
        fillColor: "#66ccff",
        strokeColor: "#3399ff",
        graphicZIndex: 2
    })
});

var vector_style = new OpenLayers.Style({
	'fillColor': '#669933',
	'fillOpacity': .8,
	'strokeColor': '#aaee77',
	'strokeWidth': 3,
	'pointRadius': 8
	});

var vector_style_map = new OpenLayers.StyleMap({
	'default': vector_style
	});
	

var nav_style = new OpenLayers.Style({
	'fillColor': '#990033',
	'fillOpacity': .8,
	'strokeColor': '#aaee77',
	'strokeWidth': 3,
	'pointRadius': 8
	});

var nav_style_map = new OpenLayers.StyleMap({
	'default': nav_style
	});
	
	
var info_style = new OpenLayers.Style({
	'fillColor': '#6600CC',
	'fillOpacity': .8,
	'strokeColor': '#aaee77',
	'strokeWidth': 3,
	'pointRadius': 8
	});

var info_style_map = new OpenLayers.StyleMap({
	'default': info_style
	});
	
	
var poi_style = new OpenLayers.Style({
	'fillColor': '#FF6600',
	'fillOpacity': .8,
	'strokeColor': '#aaee77',
	'strokeWidth': 3,
	'pointRadius': 8
	});

var poi_style_map = new OpenLayers.StyleMap({
	'default': poi_style
	});
	
	
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
	//Add the wkt to vector its the [0] layer
	vector= new OpenLayers.Layer.Vector('Track',{style: myStyles,projection: new OpenLayers.Projection("EPSG:4326")});
	var track_geom = new OpenLayers.Geometry.fromWKT('<?php echo $_SESSION['wkt'];?>', { strokeColor: "green", strokeWidth: 5, strokeOpacity: 0.5 }); 
	
	var trackFeature = new OpenLayers.Feature.Vector(track_geom,null, {}); 
	vector.addFeatures([trackFeature]);
	
	map.addLayer(vector);

	

	modifyControl = new OpenLayers.Control.ModifyFeature(pointLayer);
	map.addControl(modifyControl);	
	
		


	
	
	
	map.layers[0].onFeatureInsert = function(feature){ 
		
		alert("New object created.");
		document.forms[0].wkt_object.value=feature.geometry;
	};

	map.layers[0].events.register('afterfeaturemodified',this,point_modified);


	///Add allready db object to various layers
	navlayer= new OpenLayers.Layer.Vector('Navigation Points',	{style: nav_style,projection: new OpenLayers.Projection("EPSG:4326")});
	info_layer= new OpenLayers.Layer.Vector('Info Points',{	projection: new OpenLayers.Projection("EPSG:4326")});
	poi_layer= new OpenLayers.Layer.Vector('Point of inter.',{projection: new OpenLayers.Projection("EPSG:4326")});

	
	//alert (nav_array[0].WKT);
	//////////////////////////////////////////////////////
	//check if array are empty if not populate the layers nav,info and poi
	if (nav_array==null){
	
	}

	else{
		for (var i=0;i<nav_array.length;i++){
			var nav_geom = new OpenLayers.Geometry.fromWKT(nav_array[i].WKT); 
	
			var navFeature = new OpenLayers.Feature.Vector(nav_geom,null, {}); 
			//var navFeature=wktObj.read(nav_geom);
			navlayer.addFeatures([navFeature]);
			}
			map.addLayer(navlayer);
	}
		
	if (info_array==null){

		}
	else{
		for (var i=0;i<info_array.length;i++){
			
			var info_geom = new OpenLayers.Geometry.fromWKT(info_array[i].WKT); 
	
			var infoFeature = new OpenLayers.Feature.Vector(info_geom,null, {}); 
			//var navFeature=wktObj.read(nav_geom);
			info_layer.addFeatures([infoFeature]);
			}
			map.addLayer(info_layer);
		}

	if (poi_array==null){

		}
	else{
		for (var i=0;i<poi_array.length;i++){
			var poi_geom = new OpenLayers.Geometry.fromWKT(poi_array[i].WKT); 
	
			var poiFeature = new OpenLayers.Feature.Vector(poi_geom,null, {}); 
			//var navFeature=wktObj.read(nav_geom);
			poi_layer.addFeatures([poiFeature]);
			map.addLayer(poi_layer);
		}
		map.addLayer(poi_layer);
		}
	///Zoom out to track bounds
	var dataExtent=vector.getDataExtent();
	map.zoomToExtent(dataExtent);

	//Styles applied

	
	
	
	
	//vector.styleMap = vector_style_map;
	//navlayer.styleMap = nav_style_map;
	
	
	
    }


	function point_modified(obj){

		alert('Object moved.');
		document.forms[0].wkt_object.value=obj.feature.geometry;

		};
    
	function setObjectDraw(){

		polyOptions = {sides: 20};
		pointControl=new OpenLayers.Control.DrawFeature(pointLayer,
	            OpenLayers.Handler.RegularPolygon,
                {handlerOptions: polyOptions});
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
		document.forms[0].wkt_object.value='';

		}

	function validateForm()
	{
	var x=document.forms["formNewGuide2"]["oname"].value;
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

	var x2=document.forms["formNewGuide2"]["description"].value;
	if (x2==null || x2=="")
	  {
	  alert("Please fill out the object description.");
	  //document.getElementById("submitChange").disabled = true;
	  return false;
	  }
	 
	}

	//ajax call to store object
	
	
	
	

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
		    	
				<form name="formNewGuide2" action="hg_editor.php" method="post" onsubmit="return validateForm()" >
					
					<legend>Guide details - step 2</legend>
					
					<label for="oname" style="color: blue;"> Object name: </label> 
					<input id="oname" value="" type="text" name="oname" /> 
					
					<select title="Select Object Type:" name="type" >
						<option value="1">Navigation point</option>
						<option value="2">Information point</option>
						<option value="3">Point of interest + proxyalert</option>
					
					</select>
					
					<label for="proxy" style="color: blue;"> Proximity (50m) alert: </label> 
					<input id="proxy" value="50" type="text" name="proxy" /> 
					<label for="proxy" style="color: blue;"> *default 50m (will be applied only to POI)</label> 
					
					<label for="wkt_object" style="color: blue;"> WKT: </label>
					<input id="wkt_object" value="" type="text" name="wkt_object" readonly="readonly" />  
					 
					
					<legend>Info</legend>
					<label for="description" style="color: blue;"> Description: </label> 
					<input id="description" col="3" value="" type="text" name="description" /> 
					</br>
					<button type="submit" class="btn btn-primary" value="Submit"
						name="AddNewObject">Save object</button>
					
				</form>
			
			
			</div>
	    
			<p></p>
			<p></p>
			<div class="span3">
			<button type="button" class="btn btn-success" value="Draw"
						name="addObject" onclick="setObjectDraw()">Draw</button>
			<button type="button" class="btn btn-success" value="Draw"
						name="addObject" onclick="setObjectStopDraw()">Stop draw</button>
			<button type="button" class="btn btn-success" value="Move"
						name="addModify" onclick="setObjectModifyDrag()">Drag Object</button>
		    <button type="button" class="btn btn-success" value="Pan"
						name="addPan" onclick="setObjectStopDraw()">Pan</button>
		    <button type="button" class="btn btn-danger" value="Delete"
						name="addDelete" onclick="setObjectDelete()">Delete Objects </button>
		    
				<p></p>
				</br>
			    
				
				<div id="map_canvas" style="width: 800px; height: 600px;"></div>
				<p></p>
			</div>
			
			
		</div>



	</div>


	


</body>
</html>


