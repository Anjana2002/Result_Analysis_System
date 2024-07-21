<style>
    body{
     background: #EAF4FC;
    }
</style>
<?php
    require_once('appvars.php');
    require_once('connectvars.php');
    session_start();
    $page_title = 'Student Search';
    require_once('header.php');
    //require_once('navmenu.php');  // Connect to the database
    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    if (isset($_SESSION['username']))
    {
        $query = "SELECT pgm_id,pgm_name FROM programme order by pgm_name";
        $pgms = mysqli_query($dbc, $query);
        if (isset($_POST['submit']))
        {
            $sort = mysqli_real_escape_string($dbc, trim($_POST['sort']));
            $tc = mysqli_real_escape_string($dbc, trim($_POST['tc']));
            $sortby = getSortByColumn($sort);
            $pgmid = mysqli_real_escape_string($dbc, trim($_POST['programme']));
            $query = "SELECT * FROM programme WHERE pgm_id = ".$pgmid;

            $selected_pgms = mysqli_query($dbc, $query);
            $selected_pgm = mysqli_fetch_array($selected_pgms);
            $level = $selected_pgm['grad_level'];

            $yearofadmn = mysqli_real_escape_string($dbc, trim($_POST['yearofadmn']));

            if($tc == "on")
            {
              $remove_tc = " AND status='Studying'";
            }

            if($level=='UG' or $level=='IN')
            {
            $query = "SELECT stud_id,name,uty_reg_no,admn_no,dob,sex,roll_no,
                             quota_name,date_of_admission,status,contact_no,cc.common_course_type_dec
                      FROM stud_master,common_course_type as cc,quota as q
                      WHERE cc.common_course_type_id = stud_master.language_id" .$remove_tc. " AND q.quota_id = stud_master.quota AND
                            pgm_id = ". $pgmid . " AND year_of_admn = ". $yearofadmn . " order by ".$sortby;
            }
            else
            {
              $query = "SELECT stud_id,name,uty_reg_no,admn_no,dob,sex,roll_no,contact_no,quota_name,date_of_admission,status,NULL
                        FROM stud_master,quota as q
                        WHERE q.quota_id = stud_master.quota ".$remove_tc. " AND
                              pgm_id = ". $pgmid . " AND year_of_admn = ". $yearofadmn . " order by ".$sortby;
            }

            $studs = mysqli_query($dbc, $query);
            mysqli_close($dbc);
        }
    }
?>
   
    <form enctype="multipart/form-data" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" class="login-form">
      <input name="programme"  type="hidden" value=<?php echo $pgmid; ?> />
      <input name="yearofadmn" type="hidden" value=<?php echo $yearofadmn; ?> />

    </form>
    <div class="filterform">
    <?php require_once('navmenu.php'); ?>
    <form enctype="multipart/form-data" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" class="login-form">
    <table align=center>    
    <tr>
    <th><label for="dob">Programme:</label></th>
    <td> <select name="programme" id="programme">
            <?php foreach($pgms as $pgm)
            {?>
                <option value="<?php echo $pgm['pgm_id'];?>" <?php if(!empty($pgmid)) if($pgmid == $pgm['pgm_id']) echo "selected"; ?>><?php echo $pgm['pgm_name']; ?></option>
            <?php }?>
        </select></td></tr>
        <tr><th><label for="yearofadmn">Year of Admission:</label></th>
        <td> <select name="yearofadmn" id="yearofadm">
            <?php
                if(empty($yearofadmn))
                {
                    for ($i = 2010; $i <= 2050; $i++) : ?>
                        <option value="<?php echo  $i; ?>" <?php if ($i==date("Y")) echo "selected"; ?>><?php echo $i; ?></option>
            <?php   endfor;
                }
                else
                {
                    for ($i = 2010; $i <= 2050; $i++) : ?>
                    <option value="<?php echo  $i; ?>" <?php if ($i==$yearofadmn) echo "selected"; ?>><?php echo $i; ?></option>
            <?php   endfor;
                } ?>
            </select></td>
              </tr>
       <tr>     
        <th>Sort By:</th>
        <td><select name="sort" onchange="javascript:this.form.submit()">
              <option value=1 <?php if(!empty($sort)) if($sort==1) echo "selected"; ?>>Admission No.</option>
              <option value=2 <?php if(!empty($sort)) if($sort==2) echo "selected"; ?>>Roll No.</option>
              <option value=3 <?php if(!empty($sort)) if($sort==3) echo "selected"; ?>>University Reg. No.</option>
              <option value=4 <?php if(!empty($sort)) if($sort==4) echo "selected"; ?>>Name</option>
              <option value=5 <?php if(!empty($sort)) if($sort==5) echo "selected"; ?>>Gender</option>
              <option value=6 <?php if(!empty($sort)) if($sort==6) echo "selected"; ?>>Language</option>
              <option value=7 <?php if(!empty($sort)) if($sort==7) echo "selected"; ?>>Quota</option>
            </select></td>
        </tr>
        
        <!--<tr><th><input type="submit" value="Search" name="submit" class/></th>-->
        
       
        </table>  <br>
        <button class="upload-button1" type="submit" value="Log In" name="submit">SEARCH</button><br/>
          </form>
    </div>
    <?php
        if (isset($_POST['submit']))
        {
            echo '<div class="filterform">';
            echo '<h3 align=center>Search Results</h3>';
            echo '<table align=center id="studentstable" style="width:100%;" class="custom-table">';
            echo '<tr><th class=sl>Sl No.</th><th style="width:50px">Adm. No.</th><th style="width:50px">Roll No.</th>
            <th style="width:100px">Uty Reg. No.</th><th style="width:250px">Name</th><th>Contact</th><th>Gender</th><th>DOB</th><th>Quota</th>
            <th style="width:100px">Date of Adm.</th><th>Language</th><th>Status</th><th>Action</th></tr>';
            $i = 1;
            while ($row = mysqli_fetch_array($studs))
            {
                echo '<tr><td class=sl>'.$i.'</td>';
                echo '<td>' . $row['admn_no'] . '</td>';
                echo '<td>' . $row['roll_no'] . '</td>';
                echo '<td>' . $row['uty_reg_no'] . '</td>';
                echo '<td class=leftalign>'. ucwords(strtolower($row['name'])).'</td>';
                echo '<td>'. $row['contact_no'].'</td>';
                echo '<td>'. $row['sex'].'</td>';
                echo '<td>'. date('d.m.Y',strtotime($row['dob'])) .'</td>';
                echo '<td>'. $row['quota_name'].'</td>';
                echo '<td>'. date('d.m.Y',strtotime($row['date_of_admission'])).'</td>';
                echo '<td>'. $row['common_course_type_dec'].'</td>';
                echo '<td>'. $row['status'].'</td>';
                echo '<td><a href="editstudent.php?id=' . $row['stud_id'] . '">View</a>';
                echo '</td></tr>';
                $i++;
            }
            echo '</table>';
           
            echo '</div>';

        }
    ?>


<script>
function sortTable(n) {
  var table, rows, switching, i, x, y, shouldSwitch, dir, switchcount = 0;
  table = document.getElementById("studentstable");
  switching = true;
  // Set the sorting direction to ascending:
  dir = "asc";
  /* Make a loop that will continue until
  no switching has been done: */
  while (switching) {
    // Start by saying: no switching is done:
    switching = false;
    rows = table.rows;
    /* Loop through all table rows (except the
    first, which contains table headers): */
    for (i = 1; i < (rows.length - 1); i++) {
      // Start by saying there should be no switching:
      shouldSwitch = false;
      /* Get the two elements you want to compare,
      one from current row and one from the next: */
      x = rows[i].getElementsByTagName("TD")[n];
      y = rows[i + 1].getElementsByTagName("TD")[n];
      /* Check if the two rows should switch place,
      based on the direction, asc or desc: */
      if (dir == "asc") {
        if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
          // If so, mark as a switch and break the loop:
          shouldSwitch = true;
          break;
        }
      } else if (dir == "desc") {
        if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
          // If so, mark as a switch and break the loop:
          shouldSwitch = true;
          break;
        }
      }
    }
    if (shouldSwitch) {
      /* If a switch has been marked, make the switch
      and mark that a switch has been done: */
      rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
      switching = true;
      // Each time a switch is done, increase this count by 1:
      switchcount ++;
    } else {
      /* If no switching has been done AND the direction is "asc",
      set the direction to "desc" and run the while loop again. */
      if (switchcount == 0 && dir == "asc") {
        dir = "desc";
        switching = true;
      }
    }
  }
}
</script>
<?php
function getSortByColumn($sort)
{
  switch($sort)
  {
    case 1:
      $sortby = "admn_no";
      break;
    case 2:
      $sortby = "roll_no";
      break;
    case 3:
      $sortby = "uty_reg_no";
      break;
    case 4:
      $sortby = "name";
      break;
    case 5:
      $sortby = "sex";
      break;
    case 6:
      $sortby = "common_course_type_dec";
      break;
    case 7:
      $sortby = "quota";
      break;
  }
  return $sortby;
}
?>

 