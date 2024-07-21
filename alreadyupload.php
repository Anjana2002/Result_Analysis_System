<style>
    body{
     background: #EAF4FC;
    }
  </style>
<?php
    require_once('appvars.php');
    require_once('connectvars.php');
    session_start();
    $page_title = 'Mark Entry Success';
    require_once('header.php');
    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    if (isset($_GET['stud_id'])) 
        {
            $stud_id = $_GET['stud_id'];
        } 
        if (isset($_GET['sem'])) 
        {
            $semester = $_GET['sem'];
        }
        $query = "SELECT uty_reg_no FROM stud_master WHERE stud_id = ".$stud_id;
        $reg_nos = mysqli_query($dbc,$query);
        foreach($reg_nos as $a)
        {
            $reg_no = $a['uty_reg_no'];
        }
?>
<?php require_once('navmenu.php'); ?>
        <br><br><br>
        <center>
            <?php 

                echo '<br><br><br>';
                echo '<h2>';
                echo 'The marks for '.$reg_no .' in semester '.$semester.' have already been uploaded';
               
                echo '<h2>';
                echo '<br><br><br>';
            ?>
            <br />
            <br />
        <center>
        </div>
    </div>
    </div>




    