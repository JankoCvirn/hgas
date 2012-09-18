<?php
ini_set("session.gc_maxlifetime", "3600");
require_once  ("inc/utils.php");


session_start();

$msg_status='';

$username=$_SESSION['username'];

//logout
if (isset($_POST["SubmitLogout"])){
	session_unset();
	session_destroy();
}
//back to index.html
if (!session_is_registered($username)) {
	header("Location:index.php");}


	if (isset($_POST["SubmitDelete"])){

		$helper=new utils($username, 'none');
		$vid=$_POST['edit_videoid'];
		$path=$_POST['path'];
		$helper->getDeleteVideo($path, $vid);

	}
	if (isset($_POST['SubmitNewUser'])){

		$n=$_POST['user'];
		$p=$_POST['pass'];

		$helper=new utils('none', 'none');
		$helper->setNewUser($n, $p);
		$msg_status='Neuer Benutzer ['.$n.']['.$p.'] wurde erschaffen.';

	}
	if (isset($_POST['SubmitChange'])){

		$id_a=$_POST['edit_id'];
		$state=$_POST['edit_auth'];
		$fn=$_POST['edit_fname'];
		$ln=$_POST['edit_lname'];
		$helper=new utils('none', 'none');
		$helper->setUserActiv($id_a,$state);
		$helper->setAlterUser($id_a, $fn, $ln);

	}


	?>

<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="viewport"
	content="width=device-width, initial-scale=0.5, maximum-scale=1.0, user-scalable=1">
<meta name="apple-mobile-web-app-capable" content="yes">

<title></title>
<link rel="stylesheet" href="datatables/media/css/demo_page.css">
<link rel="stylesheet" href="datatables/media/css/demo_table.css">
<link rel="stylesheet" href="css/redmond/jquery-ui-1.8.17.custom.css">

<script src="js/jquery-1.7.1.min.js">
        </script>

<script type="text/javascript"
	src="datatables/media/js/jquery.dataTables.js"></script>

<style type="text/css">
style type ="text/css"> /* General settings */ body {
	font-family: Verdana, Geneva, Arial, Helvetica, sans-serif;
	font-size: small;
}

/* Toolbar styles */
#toolbar {
	position: relative;
	padding-bottom: 0.5em;
	display: none;
}

#toolbar ul {
	list-style: none;
	padding: 0;
	margin: 0;
}

#toolbar ul li {
	float: left;
	padding-right: 1em;
	padding-bottom: 0.5em;
}

#toolbar ul li a {
	font-weight: bold;
	font-size: smaller;
	vertical-align: middle;
	color: black;
	text-decoration: none;
}

#toolbar ul li a:hover {
	text-decoration: underline;
}

#toolbar ul li * {
	vertical-align: middle;
}
</style>
<script type="text/javascript">

        $(document).ready(function() {
        	$('#upload_table').dataTable( {
        		"bProcessing": true,
        		"bServerSide": true,
        		"sAjaxSource": "inc/data_source_flm_master.php"
        	} );    
        	  
			
        	  
                
            } );

		
        	
        </script>
</head>
<body id="dt_example" bgcolor="white">
<div id="myTabs">
<ul>
	<li><a href="#a">Users </a></li>
	<li><a href="#b">LM Report</a></li>
	<!--  <li><a href="#c">CityGuide-c</a></li>
			<li><a href="#d">CityGuide-d</a></li> -->
</ul>
<div id="a">
<div id="container">
<p></p>
<form action="<?php echo($PHP_SELF)?>" method="POST"><input
	type="submit" value="LogOut" name="SubmitLogout" /></form>
<p>Create new user</p>
<br>
<div style="color: blue;"><?php echo $msg_status;?></div>
<form action="<?php echo($PHP_SELF)?>" method="POST"><label
	for="username" style="color: blue;"> Username: </label> <input
	id="username" value="" type="text" name="user" />
<div id="reg_username_a"></div>




<label for="password" style="color: blue;"> Password: </label> <input
	id="password" value="" type="password" name="pass" />
<div id="reg_password_a"></div>


<input type="submit" value="New User" name="SubmitNewUser" /></form>

<p></p>
<p>Users</p>
<table id="upload_table" class="display">
	<thead>
		<tr>
			<th>Id</th>
			<th>User</th>
			<th>Password</th>
			<th>First Name</th>
			<th>Last Name</th>
			<th>Active</th>
			<th>Last update</th>
			

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
			

		</tr>

	</tbody>
</table>



<p></p>
</div>

<div id="b">
<p><strong>Report Overwiev : Labor and material field reports</strong></p>

<table id="upload_table" class="display">
	<thead>
		<tr>
			<th>Id</th>
			<th>Customer</th>
			<th>Job Date</th>
			<th>Job Number</th>
			<th>Job Location</th>
			<th>Fec Manager</th>
			<th>Customer Order No.</th>
			<th>Work Performed</th>
			<th>Complete</th>

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

		</tr>

	</tbody>
</table>
</div>
</div>
<script src="development-bundle/jquery-1.7.1.js"></script> <script
	src="development-bundle/ui/jquery.ui.core.js"></script> <script
	src="development-bundle/ui/jquery.ui.widget.js"></script> <script
	src="development-bundle/ui/jquery.ui.tabs.js"></script> <script
	src="development-bundle/ui/jquery.ui.button.js"></script> <script
	src="development-bundle/ui/jquery.ui.accordion.js"></script> <script>
		(function($){
			
			$("#myTabs").tabs();
			
		})(jQuery);
		</script></div>

</body>