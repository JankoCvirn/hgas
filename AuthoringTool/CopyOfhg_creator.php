<?php
ini_set("session.gc_maxlifetime", "3600");
require_once  ("inc/utils.php");

$page_title='HikeGuide Authoring Tools';
$brand_text='HikeGuide Creator';

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
	header("Location:index.php");}
	

	
	
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title><?php echo $page_title;?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="css/bootstrap.css" rel="stylesheet">
    
    <link href="css/bootstrap-responsive.css" rel="stylesheet">

    
<link rel="stylesheet" href="datatables/media/css/demo_page.css">
<link rel="stylesheet" href="datatables/media/css/demo_table.css">
<style>
      body {
        padding-top: 60px; 
        }
      #map_canvas { 
        height: 100% }
      
    </style>

<script src="js/jquery-1.7.1.min.js">
        </script>
<script type="text/javascript"
      src="http://maps.googleapis.com/maps/api/js?key=AIzaSyChBs-ETza2YfxW6eIHqBw2nqMi4uPsEWM&sensor=true">
    </script>
<script type="text/javascript"
	src="datatables/media/js/jquery.dataTables.js"></script>
	<script type="text/javascript">

	function initialize() {
        var mapOptions = {
          center: new google.maps.LatLng(-34.397, 150.644),
          zoom: 8,
          mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        var map = new google.maps.Map(document.getElementById("map_canvas"),
            mapOptions);
      }





      </script>
    
    
    
    
    
  </head>

  <body onload="initialize()">
	<div id="map_canvas" style="width:80%%; height:60%%"></div>
    <div class="navbar navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container-fluid">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <a class="brand" href="#"><?php echo $brand_text;?></a>
          <div class="btn-group pull-right">
            <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
              <i class="icon-user"></i> Loged as: <?php echo $username?>
              <span class="caret"></span>
            </a>
            <ul class="dropdown-menu">
              <!--<li><a href="#">Profile</a></li>
              <li class="divider"></li>
              --><li><a href="main.php?logout=true">Sign Out</a></li>
            </ul>
          </div>
          
        </div>
      </div>
    </div>

    <div class="container-fluid">
      <div class="row-fluid">
        <div class="span3">
          <div class="well sidebar-nav">
            <?php include'inc/navigation/navigation.php'?>
          </div><!--/.well -->
        </div><!--/span-->
        <div class="span9">
          <div class="hero-unit">
            
            <p></p>
            
          </div>
          <div class="row-fluid">
            
              
            
          </div><!--/row-->
          
        </div><!--/span-->
        <div class="span9">
          
          <div class="row-fluid">
            
              <!-- Form for Hike properties -->
              
              
              	
            
            
          </div><!--/row-->
          
        </div><!--/span-->
      </div><!--/row-->

      <hr>

      <footer>
        <p><?php include 'inc/footer/footer.php'?></p>
      </footer>
    </div> <!-- /container -->

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    
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


