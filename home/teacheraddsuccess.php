<style>
    body{
     background: #EAF4FC;
    }
  </style>
<?php
    require_once('appvars.php');
    require_once('connectvars.php');
    session_start();
    $page_title = 'Teacher Entry Success';
    require_once('header.php');
    require_once('navmenu.php');
    $name = $_GET['name'];
    if (isset($_SESSION['username']))
    {
?>
    <center><?php require_once('navmenu.php'); ?>
        <br><br><br>
        <img src="/result/images/tick2.jpeg" style="width: 5%; padding-top: 5px;">    
            <br /><br/><b><h2>Teacher Added Successfully<h2></b>
           
    </center>
        
        <p><a href="addteacher.php" class="upload-button1">Add More Teachers</a></p>
    </div>
    </div>
<?php
    }    
?>