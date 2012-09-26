<?php
ini_set("session.gc_maxlifetime", "3600");
require_once  ("../inc/utils.php");



session_start();

$page_title='HikeGuide Authoring Tools';
$brand_text='HikeGuide Overview';
$hero_text='HikeGuide tracks.';


$msg_status='';

$username=$_SESSION['username'];

//logout
if (isset($_REQUEST["logout"])){
	session_unset();
	session_destroy();
}
//back to index.html
if (!session_is_registered($username)) {
	header("Location:../index.php");}
	

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
    <link href="../css/bootstrap.css" rel="stylesheet">
    <style>
      body {
        padding-top: 60px; 
      }
    </style>
    
    <link href="../css/bootstrap-responsive.css" rel="stylesheet">

    
<link rel="stylesheet" href="../datatables/media/css/demo_page.css">
<link rel="stylesheet" href="../datatables/media/css/demo_table.css">


<script src="../js/jquery-1.7.1.min.js">
        </script>

<script type="text/javascript"
	src="../datatables/media/js/jquery.dataTables.js"></script>
<script type="text/javascript">

$(document).ready(function() {
	$('#track_table').dataTable( {
		"bProcessing": true,
		"bServerSide": true,
		"sAjaxSource": "../inc/data_sources/data_source_track.php"
	} );    
	  
	
	  
        
    } );

		

		
        	
        </script>
    
  </head>

  <body>

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
      <div class="row-fluid">
        
        <div class="span9">
          <div class="hero-unit">
            
            <p><?php echo $hero_text;?></p>
            
          </div>
          <!-- Available guides -->
          <div class="row-fluid">
            <table id="track_table" class="display">
				<thead>
					<tr>
						<th>Id</th>
						<th>Name</th>
						<th>SubName</th>
						<th>Summary</th>
						<th>Navigation</th>
						<th>MapName</th>
						<th>TrackName</th>
						<th>Active</th>
						<th>Last Update</th>
						<th>Owner</th>
						<th>Created</th>
			
					</tr>
				</thead>
				<tbody>
					<tr>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						
						<td></td>
						<td></td>
						<td></td>
						
						<td></td>
						
			
					</tr>
			
				</tbody>
			</table>
              
            
          </div><!--/row-->
          
        </div><!--/span-->
      </div><!--/row-->

      <hr>

      <footer>
        <p><?php include '../inc/footer/footer.php'?></p>
      </footer>
    </div> <!-- /container -->

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    
<!--    <script src="js/bootstrap-transition.js"></script>-->
<!--    <script src="js/bootstrap-alert.js"></script>-->
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

