
                    
 <div class="nav-collapse collapse">
            <ul class="nav">
              <li ><a href="main.php">Home</a></li>
              <?php if ($username=='admin') :?>
              
              <li><a href="user.php">User Manager</a></li>
              
              <?php endif;?>
              
              <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Guide Manager <b class="caret"></b></a>
                <ul class="dropdown-menu">
                  <li><a href="hg_main.php">Guide Overview</a></li>
	              <li><a href="../hgcreator/hg_creator.php">Guide Manager</a></li>
				  <li><a href="../hgcreator/hg_editor.php">Guide Editor</a></li>   
	              <li><a href="../hgcreator/hg_publisher.php">Guide Publisher</a></li>
                </ul>
              </li>
             <li><a href="">Mobile application</a></li>
             <li><a href="">Help topics</a></li>
            </ul>
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