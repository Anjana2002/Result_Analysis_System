<style>
    body{
     background: #EAF4FC;
    }
</style>
<?php
    require_once('appvars.php');
    require_once('connectvars.php');
    session_start();
    $page_title = 'Student Entry Success';
    require_once('header.php');
    require_once('navmenu.php');
    $name = $_GET['name'];
    if (isset($_SESSION['username']))
    {
?>
    <div class="login-page" align=center>
    <div class="form" align=center">
        <b>Student Added Successfully</b>
        <br />
        <br />
        <?php
      
        ?>
        <h3><?php echo $name; ?></h3>
        <p><a href="addstudent.php">Add More Students</a></p>
    </div>
    </div>
<?php
    }    
?>
