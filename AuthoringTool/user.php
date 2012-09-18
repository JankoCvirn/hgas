<?php
ini_set("session.gc_maxlifetime", "3600");
require_once  ("inc/utils.php");


session_start();

$page_title='HikeGuide Authoring Tools';
$brand_text='HikeGuide';


$msg_status='';

$username=$_SESSION['username'];

//logout
if (isset($_REQUEST["logout"])){
	session_unset();
	session_destroy();
}
//back to index.html
if (!session_is_registered($username) || ($username!='admin')) {
	header("Location:index.php");}

if (isset($_POST['SubmitNewUser'])){

		$n=$_POST['user'];
		$p=$_POST['pass'];
		$f=$_POST['fname'];
		$l=$_POST['lname'];

		$helper=new utils('none', 'none');
		$helper->setNewUser($n, $p,$f,$l);
		

	}
	
if (isset($_POST['SubmitChange'])){
	
	$id_a=$_POST['edit_id'];
	$state=$_POST['edit_auth'];
	$pass=$_POST['edit_pass'];
	$usern=$_POST['edit_name'];
	$fn=$_POST['edit_fname'];
	$ln=$_POST['edit_lname'];
	$helper=new utils('none', 'none');
	$helper->setUserActiv($id_a,$state);
	$helper->setAlterUser($id_a, $fn, $ln,$usern,$pass);
	
}

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
    <style>
      body {
        padding-top: 60px; 
      }
    </style>
    
    <link href="css/bootstrap-responsive.css" rel="stylesheet">

    
<link rel="stylesheet" href="datatables/media/css/demo_page.css">
<link rel="stylesheet" href="datatables/media/css/demo_table.css">


<script src="js/jquery-1.7.1.min.js">
        </script>

<script type="text/javascript"
	src="datatables/media/js/jquery.dataTables.js"></script>
<script type="text/javascript">

        $(document).ready(function() {
        	$('#user_table').dataTable( {
        		"bProcessing": true,
        		"bServerSide": true,
        		"sAjaxSource": "inc/data_sources/data_source_user.php"
        	} );    
        	  
			
        	  
                
            } );

		$('#user_table tbody tr').live('click', function () {
            
            var nTds = $('td', this);
            var id = $(nTds[0]).text();
            var username = $(nTds[1]).text();
            var userpass= $(nTds[2]).text();
            var userfname = $(nTds[3]).text();
            var userlname = $(nTds[4]).text(); 

            oFormObject = document.forms['userEdit'];
            oFormObject.elements["edit_id"].value = id;
            oFormObject.elements["edit_pass"].value = userpass;
            oFormObject.elements["edit_name"].value = username;
            oFormObject.elements["edit_fname"].value = userfname;
            oFormObject.elements["edit_lname"].value = userlname;
            

            
            
            
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
          <?php include 'inc/navigation/navigation.php';?>
          
        </div>
      </div>
    </div>

    <div class="container-fluid">
      <div class="row-fluid">
        
        <div class="span9">
         
          
          <div class="row-fluid">
          <div class="span4">
           <div id="container">




<div style="color: blue;"><?php echo $msg_status;?></div>
<form action="<?php echo($PHP_SELF)?>" method="POST">
<legend>Create a new user</legend>
<label
	for="username" style="color: blue;"> Username: </label> <input
	id="username" value="*" type="text" name="user" />
<div id="reg_username_a"></div>




<label for="password" style="color: blue;"> Password: </label> <input
	id="password" value="*" type="password" name="pass" />
<div id="reg_password_a"></div>
<label for="fname" style="color: blue;"> First Name: </label> <input
	id="fname" value="" type="text" name="fname" />
<label for="lname" style="color: blue;"> Last Name: </label> <input
	id="lname" value="" type="text" name="lname" />
<p></p>
<button type="submit" value="New User" name="SubmitNewUser" class="btn btn-success">Create</button>
</form>
</div><!--/row-->
</div>
<div class="span4">

<form name="userEdit" action="<?php echo($PHP_SELF)?>" method="post" >
	<legend>Modify a user profile</legend>
	<label for="edit_id" style="color: blue;"> UserID: </label> <input
	id="edit_id" value="" type="text" name="edit_id" />
	<label for="edit_name" style="color: blue;"> Username: </label> <input
	id="edit_name" value="" type="text" name="edit_name" />
	<label for="password" style="color: blue;"> Password: </label> <input
	id="password" value="" type="text" name="edit_pass" />
	<label for="edit_fname" style="color: blue;"> First Name: </label> <input
	id="edit_fname" value="" type="text" name="edit_fname" />
	<label for="edit_lname" style="color: blue;"> Last Name: </label> <input
	id="edit_lname" value="" type="text" name="edit_lname" />
	<label for="edit_auth" style="color: blue;"> Active: </label> <select name="edit_auth" id="edit_auth">
	     <option value="1">Yes</option>
	     <option value="0">No</option>
	    </select>
	<p></p>
	<button type="submit" class="btn btn-success"  value="Submit"  name="SubmitChange">Update User</button>
	
	
	
	
	
    </form>
        </div>
</div>
<div class="row-fluid">
<div class="span8">
<p></p>
<p class="lead">System Users</p>
<p class="small">*click on a user to modify a profile</p>
<table id="user_table" class="display">
	<thead>
		<tr>
			<th>Id</th>
			<th>Username</th>
			<th>Password</th>
			<th>First Name</th>
			<th>Last Name</th>
			<th>Active (1-on , 0-off)</th>
			
			

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
			
			
			

		</tr>

	</tbody>
</table>



<p></p>
</div>
            
          </div><!--/row-->
<div class="row-fluid">
            <!--/span-->
      </div><!--/row-->

      <hr>

      <footer>
        <p><?php include 'inc/footer/footer.php'?></p>
      </footer>
    </div> <!-- /container -->

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    
    
    <script src="js/bootstrap-alert.js"></script>
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
