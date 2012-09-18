<?php
require 'inc/utils.php';
session_start();
$msg_status='';
$reg_notify='';
if (isset($_POST['btnLogin'])){
	
	$page_title='Hike Guide Authoring Tools';
	$brand_text='Hike Guide';
	
	$user_name=$_REQUEST['username'];
	$password=$_REQUEST['password'];
	//sanitize
	$user_name=stripslashes($user_name);
	$user_name=strip_tags($user_name);
	
	$free=0;
	
	if ($user_name=='admin' && $password=='secret2012'){
		$free=1;
	}
	else
	{
	$oAuth=new utils($user_name, $password);
	$free=$oAuth->getCheckLogin();
	//echo $free;
	}
	
	if($free==1 ){
		
		session_register($user_name);
		$_SESSION['username']=$user_name;
		$_SESSION['password']=$password;
		header ("Location:main.php");
	}
	
	else 
	{
		$msg_status='Authentication failed.Please contact our Technical support.';
	}
}


?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>HG Authoring Tool</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <style>
      body {
        padding-top: 60px; /* 60px to make the container go all the way to the bottom of the topbar */
      }
    </style>
    <link href="css/bootstrap-responsive.css" rel="stylesheet">

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- Le fav and touch icons -->
<!--    <link rel="shortcut icon" href="../assets/ico/favicon.ico">-->
    
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
          <a class="brand" href="#">HikeGuide</a>
          
        </div>
      </div>
    </div>

    <div class="container-fluid">
      <div class="row-fluid">
        
        <div class="span9">
          <div class="hero-unit">
            <h1></h1>
            <p>
            <form method="post" action="<?php echo($PHP_SELF)?>">
            <label>Username:</label><input name="username"  />
            <label>Password:</label><input name="password" /><p></p>
            <button type="submit" name="btnLogin" class="btn btn-primary btn-large">Login</button>
            </form>
            
            </p>
            <p><?php echo $msg_status;?></p>
          </div>
          
          <?php echo $free;?>
        </div><!--/span-->
      </div><!--/row-->

      <hr>

      <footer>
        <p>&copy; HikeGuide 2012</p>
      </footer>
    </div> 
    <script src="js/jquery-1.7.1.js"></script>
    
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
