<style>
    body{
     background: #EAF4FC;
    }
</style>
<?php 
    require_once('appvars.php');
    require_once('connectvars.php');
    session_start();
    $page_title = 'University Exam Mark Upload';
    require_once('header.php');
   // require_once('navmenu.php');  // Connect to the database
    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    if (isset($_SESSION['username']))
    {
        $query = "SELECT pgm_id,pgm_name FROM programme order by pgm_name";
        $pgms = mysqli_query($dbc, $query);?>
       
        <?php
        if (isset($_POST['submit']))
        {
            $sort = mysqli_real_escape_string($dbc, trim($_POST['sort']));
            $tc = mysqli_real_escape_string($dbc, trim($_POST['tc']));
            $sortby = "uty_reg_no";
            $pid=$_POST['pid'];
            
           // $pgmid = mysqli_real_escape_string($dbc, trim($_POST['programme']));
            $query = "SELECT * FROM programme WHERE pgm_id = ".$pid;
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
            $query = "SELECT stud_id,name,uty_reg_no,roll_no,admn_no,dob,sex,roll_no,
                             quota_name,date_of_admission,status,contact_no,cc.common_course_type_dec
                      FROM stud_master,common_course_type as cc,quota as q
                      WHERE cc.common_course_type_id = stud_master.language_id" .$remove_tc. " AND q.quota_id = stud_master.quota AND
                            pgm_id = ". $pid . "  AND status='Studying' AND year_of_admn = ". $yearofadmn . " order by ".$sortby;
            }
            else
            {
            $query = "SELECT stud_id,name,uty_reg_no,roll_no,admn_no,dob,sex,roll_no,contact_no,quota_name,date_of_admission,status,NULL
                        FROM stud_master,quota as q
                        WHERE q.quota_id = stud_master.quota ".$remove_tc. " 
                              pgm_id = ". $pid . " AND status='Studying' AND year_of_admn = ". $yearofadmn . " order by ".$sortby;
            }
            $studs = mysqli_query($dbc, $query);
            mysqli_close($dbc);
        }
    }
?>
    <br />
    <?php require_once('navmenu.php'); ?>
   
    <form enctype="multipart/form-data" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
   
      
            <table align=center>
            <tr>

            <th><label for="yearofadmn">Year of Admission</label></th><th>:</th>
            <td><select name="yearofadmn" id="yearofadm" style="width:100%">
            <?php
                if(empty($yearofadmn))
                {
                    for ($i = 2010; $i <= 2050; $i++) : ?>
                        <option align="center" value="<?php echo  $i; ?>" <?php if ($i==date("Y")) echo "selected"; ?>><?php echo $i; ?></option>
            <?php   endfor;
                }
                else
                {
                    for ($i = 2010; $i <= 2050; $i++) : ?>
                    <option align="center" value="<?php echo  $i; ?>" <?php if ($i==$yearofadmn) echo "selected"; ?>><?php echo $i; ?></option>
            <?php   endfor;
                } ?>
            </select>&nbsp;&nbsp;&nbsp;&nbsp;</td>
            </tr>
            <tr>
                    <th><label for="semester">Semester</label></th>
                    <th>:</th>
                    <td>
                    <label style="display: inline-block; margin-right: 10px;">
                        <input type="radio" name="sem" value="1" <?php if (isset($_POST['sem']) && $_POST['sem'] == '1') echo 'checked'; ?>>1
                    </label>
                    <label style="display: inline-block; margin-right: 10px;">
                        <input type="radio" name="sem" value="2" <?php if (isset($_POST['sem']) && $_POST['sem'] == '2') echo 'checked'; ?>>2
                    </label>
                    <label style="display: inline-block; margin-right: 10px;">
                        <input type="radio" name="sem" value="3" <?php if (isset($_POST['sem']) && $_POST['sem'] == '3') echo 'checked'; ?>>3
                    </label>
                    <label style="display: inline-block; margin-right: 10px;">
                        <input type="radio" name="sem" value="4" <?php if (isset($_POST['sem']) && $_POST['sem'] == '4') echo 'checked'; ?>>4
                    </label>
                    <label style="display: inline-block; margin-right: 10px;">
                        <input type="radio" name="sem" value="5" <?php if (isset($_POST['sem']) && $_POST['sem'] == '5') echo 'checked'; ?>>5
                    </label>
                    <label style="display: inline-block; margin-right: 10px;">
                        <input type="radio" name="sem" value="6" <?php if (isset($_POST['sem']) && $_POST['sem'] == '6') echo 'checked'; ?>>6
                    </label>
                        </td> 
            </table>
            <br>
            <?php $semester = $_POST['sem'];
            ?>
            <button type="submit" value="Log In" name="submit" class="upload-button1">SEARCH</button><br/>
            <input type="hidden" name="pid" id="pid" value="<?php echo $_GET['pgm_id']; ?>">
           <!-- <input type="submit" value="Search" name="submit" class/>&nbsp;&nbsp;&nbsp;&nbsp;-->
            
    </form>
            
    <?php
        if (isset($_POST['submit']))
        {
            
            echo '<h3 align=center>Result Upload</h3>';
            echo '<table align=center style="width:50%;" class="custom-table" id="studentstable" width=80% >';
            echo '<tr><th class=sl>Sl No.</th>
            <th style="width:100px">Uty Reg. No.</th><th style="width:250px">Name</th>
            <th>Action</th></tr>';
            $i = 1;
            while ($row = mysqli_fetch_array($studs))
            {
                echo '<tr><td class=sl>'.$i.'</td>';
                echo '<td>' . $row['uty_reg_no'] . '</td>';
                echo '<td style="text-align: left;">' . ucwords(strtolower($row['name'])) . '</td>';

                echo '<td class=center><a href="resultupload.php?stud_id='.$row['stud_id'].'&pgm_id='.$pid.'&sem='.$semester.'&roll_no='.$row['roll_no'].'&year_of_admn='.$yearofadmn.'" class="upload">Upload</a>';
                echo '</td></tr>';
                $i++;
            }
        }
    ?>
</div>