<style>
    
    
    body, html {
    margin: 0;
    padding: 0;
    height: 100%;
    overflow: hidden;
    background:#EAF4FC;
}

.split-background {
    position: relative;
    width: 100%;
    height: 100%;
    background: linear-gradient(140deg,#0E3657 20%, #EAF4FC 20%, #EAF4FC 80%,#0E3657 80%);
}
    .upload-button2 {
        height:30px;
        display: block; 
        margin: 0 auto;
        border:solid;
        padding: 10px 10px 5px 10px;
        background-color: #0E3657;
        color: white;
        text-decoration: none;
        border-radius: 8px;
        width: 300px;
        text-align: center;

    }
</style>
<?php
  require_once('appvars.php');
  require_once('connectvars.php');
  session_start();
  $page_title = 'UNIVERSITY EXAM';
  require_once('header.php');
  //require_once('navmenu.php');
  // Generate the navigation menu
  $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
  if (isset($_SESSION['username']))
  {
    $query = "SELECT pwd_sl_no
              FROM   staff_login
              WHERE username = '". $_SESSION['username']."'";
    $users = mysqli_query($dbc, $query);
    $user = mysqli_fetch_array($users);
    if($user['pwd_sl_no'] == 0)
    {
        $pwdchange_url = 'http://' . $_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']). '/pwdchange.php?.SID';
        header('Location: ' . $pwdchange_url);
    }
  	if($_SESSION['role_id'] == 1)
  	{ ?>
    <div class="split-background">
        <?php require_once('navmenu.php'); ?>
        <br><br></br>
        <a href="addteacher.php" class="upload-button">ADD TEACHER</a><br /><br/>
        <a href="addcourse.php" class="upload-button">ADD COURSE</a><br /><br />
        <a href="filterstudent.php"  class="upload-button">VIEW STUDENTS</a><br /><br />
        <a href="res_analysis.php"  class="upload-button">RESULT ANALYSIS</a><br /><br />

    </div>
  	<?php }
    else if($_SESSION['role_id'] == 2)
  	{
        $username = $_SESSION['username'];
        $user_id = $_SESSION['user_id'];
		    $query = "SELECT teacher_id FROM staff_login
		              WHERE username = '".mysqli_real_escape_string($dbc, $username)."'";
              $teacher_ids=mysqli_query($dbc,$query);
              foreach($teacher_ids as $a)
              {
                $teacher_id = $a['teacher_id'];
              }
              $query = "SELECT dept_id FROM teacher 
              WHERE teacher_id = ".$teacher_id;
              $dept_ids= mysqli_query($dbc, $query);
              foreach($dept_ids as $a)
              {
              $dept_id = $a['dept_id'];
            
              }
              $query = "SELECT pgm_name,pgm_id FROM programme
                    WHERE dept_id = ".$dept_id;
              $dept_names= mysqli_query($dbc, $query);
              $dept_names;
              if(mysqli_num_rows($dept_names)>0)
              {
                ?>
          
          <div class="split-background">
                  <?php require_once('navmenu.php'); ?>

                   
                      <!--<img src="/result/images/dwnld.png" width="600" height="200">-->
                      <?php foreach($dept_names as $dept_name)
                      {
                        
                            
                            $query = "SELECT no_of_sems FROM programme WHERE pgm_name = '" . $dept_name['pgm_name'] . "'";
                            $no_of_sems = mysqli_query($dbc,$query);
                            foreach($no_of_sems as $a)
                            {
                                $no_of_sem = $a['no_of_sems'];
                            }
                            if($no_of_sem==6 || $no_of_sem==10)
                            {
                        ?>     
                       
                               <center>
                              <br><br> <br><br>
                              <a href="/result/semexamsel.php?pgm_id=<?php echo $dept_name['pgm_id']; ?>"class="upload-button">
                                RESULT UPLOAD
                              </a><br/><br/>
                          
                              <a href="/result/semexamedit.php?pgm_id=<?php echo $dept_name['pgm_id']; ?>"class="upload-button">
                              RESULT VIEW/EDIT
                              </a><br/><br/>

                              <a href="/result/res_analysis.php" class="upload-button">
                              RESULT ANALYSIS
                              </a><br/><br/>

                              <a href="filterstudent.php"  class="upload-button">VIEW STUDENTS</a><br /><br />
                              <center>
                        </div>
                              
                    </div>
                  </div>
               
              
                <?php 
                            }
                      }
              }
                ?>
           
            
         <?php
      }
      else if($_SESSION['role_id'] == 5)
  	{
      
          $user_id = $_SESSION['user_id'];
          $query = "SELECT pgm_id, pgm_name
                    FROM   programme
                    WHERE grad_level = 'UG'
                    ORDER BY pgm_name";
          $pgms = mysqli_query($dbc, $query);
          $query = "SELECT DISTINCT current_sem
                    FROM   stud_master WHERE current_sem > 0 order by current_sem";
          $sems = mysqli_query($dbc, $query);
          ?>
<div class="split-background">
<?php require_once('navmenu.php'); ?>    
      

          <br><br> <br><br><br><br><br><br><a href="res_analysis.php"  class="upload-button1">RESULT ANALYSIS</a>
          <br /><a href="filterstudent.php"  class="upload-button1">VIEW STUDENTS</a><br /><br />
    </div>
  	<?php
      }
  }
  else
  {
        $startup_url = 'http://' . $_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']). '/login.php?.SID';
        header('Location: ' . $startup_url);
  }
?>