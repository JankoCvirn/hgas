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
        padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
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

$('#track_table tbody tr').live('click', function () {
	
    var nTds = $('td', this);
	
    var name = $(nTds[1]).text();
    
    oFormObject = document.forms['selectForm'];
    oFormObject.elements["guide_name"].value = name;
    o

	
    
    
    
} );
    

		

		
        	
        </script>
  </head>

  <body>

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
            <table id="track_table" class="display">
				<thead>
					<tr>
						<th>Id</th>
						<th>Name</th>
						<th>SubName</th>
						<th>Summary</th>
						<th>Navigation</th>
						
						<th>Created</th>
						<th>Created by</th>
						<th>Last update</th>
						<th>Active</th>
						<th>Downloads</th>
			
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
						
			
				</tbody>
			</table>
            
            <h3></h3>
            <table class="table-bordered">
			<form name="selectForm" action="<?php echo($PHP_SELF)?>" method="post" >
			<tr><td><span class="label label-info">Guide</span>
			</td>
			</tr>
			<tr class="success"><td>
			<label for="guide_name" style="color: blue;"> Guide Name: </label>
			<input id="guide_name" value="" type="text" name="guide_name" readonly="readonly"  />
			</td>
			</tr>
			
			<tr class="success"><td>
			<button type="submit" class="btn btn-success"  value="Submit"  name="SubmitGuideName">Draw</button>
			</td>	
			</tr>
			
			</form>
			</table>
            
            
            
          </div><!--/row-->
          
        </div><!--/span-->
      </div><!--/row-->

      <hr>

      <footer>
        <p><?php include '../inc/footer/footer.php'?></p>
      </footer>
    </div> <!-- /container -->

    
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


