<style>
    body{
     background: #EAF4FC;
    }
</style>
<?php
    require_once('appvars.php');
    require_once('connectvars.php');
    session_start();
    $page_title = 'Student Profile - Edit';
    require_once('header.php');
    require_once('navmenu.php');  // Connect to the database
    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    if (isset($_SESSION['username']))
    {
        if (isset($_GET['id']))
        {
            $studid = $_GET['id'];
            $query = "SELECT * FROM stud_master WHERE stud_id = ". $studid." LIMIT 1";
            $selstud = mysqli_query($dbc, $query);
            $stud = mysqli_fetch_array($selstud);
            if(isset($studid))
            {
                $name = $stud['name'];
                $admno = $stud['admn_no'];
                $rollno = $stud['roll_no'];
                $regno = $stud['uty_reg_no'];
                $yearofadmn = $stud['year_of_admn'];
                $pgmid = $stud['pgm_id'];
                $quota = $stud['quota'];
                $dob = $stud['dob'];
                $sex = $stud['sex'];
                $religionid = $stud['religion_id'];
                $doa = $stud['date_of_admission'];
                $egrantz = $stud['egrantz'];
                $fishegrantz = $stud['fish_egrantz'];
                $casteid = $stud['caste_id'];
                $contact = $stud['contact_no'];
                $email = $stud['email'];
                $parentname = $stud['parent_name'];
                $parentoccupation = $stud['parent_occupation'];
                $parentmob = $stud['parent_mob'];
                
               
                $dist = $stud['dist'];
                $state = $stud['state'];
                $pincode = $stud['pincode'];
                $markstenth = $stud['marks_sslc'];
                $markstwelth = $stud['marks_twelth'];
                $boardid = $stud['board_twelth'];
                $status = $stud['status'];
                $comments = $stud['comments'];
              
                $currentsem = $stud['current_sem'];
                $lang = $stud['language_id'];
            }
        }
        $query = "SELECT dept_id,dept_name FROM department order by dept_name";
        $depts = mysqli_query($dbc, $query);
        $query = "SELECT pgm_id,pgm_name FROM programme order by pgm_name";
        $pgms = mysqli_query($dbc, $query);
        $query = "SELECT religion_id,religion_name FROM religion order by religion_name";
        $religions = mysqli_query($dbc, $query);
        $query = "SELECT caste_id,caste_name FROM caste order by caste_name";
        $castes = mysqli_query($dbc, $query);
        $query = "SELECT * FROM examboard order by board_id";
        $boards = mysqli_query($dbc, $query);
        $query = "SELECT quota_id,quota_name FROM quota order by quota_id";
        $quotas = mysqli_query($dbc, $query);
        if (isset($_POST['submit']))
        { 

            // Grab the profile data from the POST
            $name = mysqli_real_escape_string($dbc, trim($_POST['name']));
            $admno = mysqli_real_escape_string($dbc, trim($_POST['admno']));
            $rollno = mysqli_real_escape_string($dbc, trim($_POST['rollno']));
            $regno = mysqli_real_escape_string($dbc, trim($_POST['regno']));
            $yearofadmn = mysqli_real_escape_string($dbc, trim($_POST['yearofadmn']));
            $pgmid = mysqli_real_escape_string($dbc, trim($_POST['programme']));
            $quota = mysqli_real_escape_string($dbc, trim($_POST['quota']));
            $dob = mysqli_real_escape_string($dbc, trim($_POST['dob']));
            $doa = mysqli_real_escape_string($dbc, trim($_POST['doa']));
            $egrantz = mysqli_real_escape_string($dbc, trim($_POST['egrantz']));
            $fishegrantz = mysqli_real_escape_string($dbc, trim($_POST['fishegrantz']));
            $sex = mysqli_real_escape_string($dbc, trim($_POST['sex']));
            $religionid = mysqli_real_escape_string($dbc, trim($_POST['religion']));
            $casteid = mysqli_real_escape_string($dbc, trim($_POST['caste']));
            $contact = mysqli_real_escape_string($dbc, trim($_POST['contact']));
            $email = mysqli_real_escape_string($dbc, trim($_POST['email']));
            $parentname = mysqli_real_escape_string($dbc, trim($_POST['parentname']));
            $parentoccupation = mysqli_real_escape_string($dbc, trim($_POST['parentoccupation']));
            $parentmob = mysqli_real_escape_string($dbc, trim($_POST['parentmob']));
            
           
            $dist = mysqli_real_escape_string($dbc, trim($_POST['dist']));
            $state = mysqli_real_escape_string($dbc, trim($_POST['state']));
            $pincode = mysqli_real_escape_string($dbc, trim($_POST['pincode']));
            $markstenth = mysqli_real_escape_string($dbc, trim($_POST['markstenth']));
            $markstwelth = mysqli_real_escape_string($dbc, trim($_POST['markstwelth']));
            $boardid = mysqli_real_escape_string($dbc, trim($_POST['board']));
            $status = mysqli_real_escape_string($dbc, trim($_POST['status']));
            $comments = mysqli_real_escape_string($dbc, trim($_POST['comments']));
            $currentsem = mysqli_real_escape_string($dbc, trim($_POST['currentsem']));
            $lang = mysqli_real_escape_string($dbc, trim($_POST['lang']));
            
            $studid=mysqli_real_escape_string($dbc, trim($_POST['studid']));
            $error=0;

            if (!empty($name) && !empty($yearofadmn) && !empty($pgmid) && !empty($sex) && !empty($dist) && !empty($state))
            {
                $query = "UPDATE stud_master SET admn_no = ". (empty($admno)? "NULL":$admno) . ","
                                                . "roll_no = ". (empty($rollno)? "NULL":$rollno) . ","
                                                . "uty_reg_no = ". (empty($regno)? "NULL":"'$regno'") . ","
                                                . "name = ". (empty($name)? "NULL":"'$name'") . ","
                                                . "year_of_admn = ". (empty($yearofadmn)? "NULL":$yearofadmn) . ","
                                                . "dob = ". (empty($dob)? "NULL":"'".$dob."'") . ","
                                                . "date_of_admission = ". (empty($doa)? "NULL":"'".$doa."'") . ","
                                                . "egrantz = ". $egrantz . ","
                                                . "fish_egrantz = ". $fishegrantz . ","
                                                . "sex = ". (empty($sex)? "NULL":"'$sex'") . ","
                                                . "caste_id = ". (empty($casteid)? "NULL":$casteid) . ","
                                                . "email = ". (empty($email)? "NULL":"'$email'") . ","
                                                . "religion_id = ". (empty($religionid)? "NULL":$religionid) . ","
                                                . "contact_no = ". (empty($contact)? "NULL":"'$contact'") . ","
                                                . "parent_name = ". (empty($parentname)? "NULL":"'$parentname'") . ","
                                                . "parent_occupation = ". (empty($parentoccupation)? "NULL":"'$parentoccupation'") . ","
                                                . "parent_mob = ". (empty($parentmob)? "NULL":"'$parentmob'") . ","
                                               
                                                
                                                . "dist = ". (empty($dist)? "NULL":"'$dist'") . ","
                                                . "state = ". (empty($state)? "NULL":"'$state'") . ","
                                                . "pincode = ". (empty($pincode)? "NULL":"'$pincode'") . ","
                                                . "pgm_id = ". (empty($pgmid)? "NULL":$pgmid) . ","
                                                . "marks_sslc = ". (empty($markstenth)? "NULL":$markstenth) . ","
                                                . "marks_twelth = ". (empty($markstwelth)? "NULL":$markstwelth) . ","
                                                . "board_twelth = ". (empty($boardid)? "NULL":"'$boardid'") . ","
                                                . "quota = ". (empty($quota)? "NULL":"'$quota'") . ","
                                                . "status = ". (empty($status)? "NULL":"'$status'") . ","
                                                . "comments = ". (empty($comments)? "NULL":"'$comments'") . ","
                                                . "current_sem = ". (empty($currentsem)? "NULL":$currentsem) . ","
                                                . "language_id = ". (empty($lang)? "NULL":$lang) ;

               
                $query = $query. " WHERE stud_id = ". $studid;
                echo $query;
                mysqli_query($dbc, $query) or die("Error while uploading details.");
                if($error == 0)
                {
                    $success_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/studenteditsuccess.php?name='.$name.SID;
                    header('Location: ' . $success_url);
                }
            }
            mysqli_close($dbc);
        }
    }
?>
    <br /><br>
    

    <div class="cont">
    <div class="filterform-section" align="left">
    
    <form enctype="multipart/form-data" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <fieldset>
            <legend><b>Admission/Examination Related Information</b></legend>
            <table align="center">
            <tr >
            <th>Name</th>
            <th>:</th>
            <td><input placeholder="Name" type="text" id="name" name="name" value="<?php if (!empty($name)) echo $name; ?>" /><br /></td></tr>
            
            <tr><th><label for="admno" style="width:auto">Admission No.</label></th>
            <th>:</th>
            <td><input type="text" id="admno" name="admno" value="<?php if (!empty($admno)) echo $admno; ?>" /> </td></tr>
            <tr><th><label for="rollno" style="width:auto">Roll No.</label></th>
            <th>:</th>
            <td><input type="text" id="rollno" name="rollno" value="<?php if (!empty($rollno)) echo $rollno; ?>" /></td></tr>
            <tr><th><label for="regno" style="width:auto">University Reg No.</label></th>
            <th>:</th>
            <td><input type="text" id="regno" name="regno" value="<?php if (!empty($regno)) echo $regno; ?>" /></td></tr>
            <tr><th><label for="yearofadmn">Year of Admission</label></th>
            <th>:</th>
            <td><select name="yearofadmn">
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
            </select></td></tr>
            
            
            <tr><th><label for="lang">Second Language</label></th>
            <th>:</th>
            <td><select name="lang">
                <option value="2"  <?php if(!empty($lang)) if($lang == "2") echo "selected"; ?>>Hindi</option>
                <option value="3" <?php if(!empty($lang)) if($lang == "3") echo "selected"; ?>>Malayalam</option>
            </select></td></tr>
            <tr><th><label for="programme">Programme</label></th>
            <th>:</th>
            <td><select name="programme">
            <?php foreach($pgms as $pgm)
            {?>
                <option value="<?php echo $pgm['pgm_id'];?>" <?php if(!empty($pgmid)) if($pgmid == $pgm['pgm_id']) echo "selected"; ?>><?php echo $pgm['pgm_name']; ?></option>
            <?php }?>
            </select><td></tr>
            <tr><th><label for="quota">Quota</label></th>
            <th>:</th>
            <td><select name="quota">
              <?php foreach($quotas as $q)
              {?>
                  <option value="<?php echo $q['quota_id'];?>" <?php if(!empty($quota)) if($quota == $q['quota_id']) echo "selected"; ?>><?php echo $q['quota_name']; ?></option>
              <?php }?>
            </select></td></tr>
            
            <tr><th><label for="doa">Date of Admission</label></th>
            <th>:</th>
            <td><input type="date" id="doa" name="doa" value="<?php if (!empty($doa)) echo date('Y-m-d',strtotime($doa)); ?>" required /></td></tr>
            

            <tr><th><label for="egrantz">Eligible for e-grantz?</label></th>
            <th>:</th>
           <td><select name="egrantz" required>
              <option value="1" <?php if($egrantz == "1") echo "selected"; ?>>Yes</option>
              <option value="0" <?php if($egrantz == "0") echo "selected"; ?>>No</option>
            </select></td></tr>

          
            <tr><th><label for="fishegrantz">Eligible for fisheries e-grantz?</label></th>
            <th>:</th>
            <td><select name="fishegrantz" required>
              <option value="0" <?php if($fishegrantz == "0") echo "selected"; ?>>No</option>
              <option value="1" <?php if($fishegrantz == "1") echo "selected"; ?>>Yes</option>
            </select></td>
            <tr><th><label for="markstwelth" style="width:auto">Marks in XII<sup>th</sup> (Normalized):</label></th><th>:</th>
            <td><input type="number" id="markstwelth" name="markstwelth" max=1200 min=0 value="<?php if (!empty($markstwelth)) echo $markstwelth; ?>" /></td></tr>
            <tr><th> <label for="board" style="width:auto">Board of XII<sup>th</sup>:</label></th><th>:</th>
			<td><select name="board">
            <option value=0 selected ></option>

            <?php foreach($boards as $board)

            {?>
                <option value="<?php echo $board['board_id'];?>" <?php if(!empty($boardid)) if($boardid ==$board['board_id']) echo "selected"; ?>><?php echo $board['board_name']; ?></option>
            <?php }?>
            </select></td></tr>
        
              </table>
        </fieldset>
        </div>
        
        <div class="filterform-section" align="right">
        <fieldset>
            <legend><b>Personal Information</b></legend>
            <table align="center">
            <tr><th><label for="dob">Date of Birth</label></th>
            <th>:</th>
            <td><input type="date" id="dob" name="dob" value="<?php if (!empty($dob)) echo date('Y-m-d',strtotime($dob)); ?>" /></td></tr>
            <tr><th><label for="sex">Sex</label></th>
            <th>:</th>
            <td><select name="sex">
                <option value="M" <?php if(!empty($sex)) if($sex == "M") echo "selected"; ?>>Male</option>
                <option value="F" <?php if(!empty($sex)) if($sex == "F") echo "selected"; ?>>Female</option>
            </select></td></tr>
            <tr><th><label for="religion">Religion</label></th>
            <th>:</th>
            <td><select name="religion">
            <option value=0 selected ></option>
            <?php foreach($religions as $religion)
            {?>
                <option value="<?php echo $religion['religion_id'];?>" <?php if(!empty($religionid)) if($religionid ==$religion['religion_id']) echo "selected"; ?>><?php echo $religion['religion_name']; ?></option>
            <?php }?>
            </select></td>
            
            <tr><th><label for="caste" style="width:auto">Caste</label></th>
            <th>:</th>
            <td><select name="caste">
            <option value=0 selected ></option>
            <?php foreach($castes as $caste)
            {?>
                <option value="<?php echo $caste['caste_id'];?>" <?php if(!empty($casteid)) if($casteid ==$caste['caste_id']) echo "selected"; ?>><?php echo $caste['caste_name']; ?></option>
            <?php }?>
            </select></td></tr>
            <tr><th><label for="contact">Contact</label></th><th>:</th>
            <td><input type="text" id="contact" name="contact" value="<?php if (!empty($contact)) echo $contact; ?>"/></td></tr>
            <tr><th><label for="email" style="width:auto">Email</label></th><th>:</th>
            <td><input type="email" id="email" name="email" value="<?php if (!empty($email)) echo $email; ?>" /></td></tr>
            <tr><th><label for="parentname">Parent's Name</label></th><th>:</th>
            <td><input type="text" id="parentname" name="parentname" value="<?php if (!empty($parentname)) echo $parentname; ?>" /></td></tr>
            <tr><th><label for="parentoccupation" style="width:auto">Parent's Occupation</label></th><th>:</th>
            <td><input type="text" id="parentoccupation" name="parentoccupation" value="<?php if (!empty($parentoccupation)) echo $parentoccupation; ?>" /></td></tr>
            <tr><th><label for="parentmob">Parent's Mob No</label></th><th>:</th>
            <td><input type="text" id="parentmob" name="parentmob" value="<?php if (!empty($parentmob)) echo $parentmob; ?>" /></td></tr>
            
            
            <input type="hidden" id="studid" name="studid" value="<?php if (!empty($studid)) echo $studid; ?>" />
            <tr><th><label for="pincode" style="width:auto">Pincode</label></th><th>:</th>
            <td><input type="text" id="pincode" name="pincode" value="<?php if (!empty($pincode)) echo $pincode; ?>" /></td></tr>
            <tr><th><label for="dist">District</label></th><th>:</th>
            <td><select name="dist">
                <option value="1"<?php if(!empty($dist)) if($dist == "1") echo "selected"; ?>>Kasaragod</option>
                <option value="2"<?php if(!empty($dist)) if($dist == "2") echo "selected"; ?>>Kannur</option>
                <option value="3"<?php if(!empty($dist)) if($dist == "3") echo "selected"; ?>>Wayanad</option>
                <option value="4"<?php if(!empty($dist)) if($dist == "4") echo "selected"; ?>>Kozhikode</option>
                <option value="5"<?php if(!empty($dist)) if($dist == "5") echo "selected"; ?>>Malappuram</option>
                <option value="6"<?php if(!empty($dist)) if($dist == "6") echo "selected"; ?>>Palakkad</option>
                <option value="7"<?php if(!empty($dist)) if($dist == "7") echo "selected"; ?>>Thrissur</option>
                <option value="8"<?php if(!empty($dist)) if($dist == "8") echo "selected"; ?>>Ernakulam</option>
                <option value="9"<?php if(!empty($dist)) if($dist == "9") echo "selected"; ?>>Idukki</option>
                <option value="10"<?php if(!empty($dist)) if($dist == "10") echo "selected"; ?>>Kottayam</option>
                <option value="11"<?php if(!empty($dist)) if($dist == "11") echo "selected"; ?>>Alappuzha</option>
                <option value="12"<?php if(!empty($dist)) if($dist == "12") echo "selected"; ?>>Pathanamthitta</option>
                <option value="13"<?php if(!empty($dist)) if($dist == "13") echo "selected"; ?>>Kollam</option>
                <option value="14"<?php if(!empty($dist)) if($dist == "14") echo "selected"; ?>>Thiruvananthapuram</option>
            </select></td></tr>
            <tr><th><label for="state" style="width:auto">State</label></th>
            <th>:</th><td><select name="state">
                <option value="1">Kerala</option>
            </select></td></tr>
       
            
           
        
        <tr><th><label for="status">Status</label><th>:</th>
        <td><select name="status">
            <option value="Studying"<?php if(!empty($status)) if($status == "Studying") echo "selected"; ?>>Studying</option>
            <option value="Passed Out"<?php if(!empty($status)) if($status == "Passed Out") echo "selected"; ?>>Passed Out</option>
            <option value="Removed"<?php if(!empty($status)) if($status == "Removed") echo "selected"; ?>>Removed</option>
            <option value="TC"<?php if(!empty($status)) if($status == "TC") echo "selected"; ?>>TC</option>
        </select></td></tr>
       
        <tr><td colspan="3"><td></tr>
        
    </form>
    </div>
