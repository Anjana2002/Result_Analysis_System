<style>
    body{
     background: #EAF4FC;
    }
</style>
<?php
function Grade($GP,$total_external,$ese,$ce) {
    $grade = ''; 

    if ($GP >= 9.00) {
        $grade = "A+";
    } else if ($GP >= 8.00 && $GP <= 8.99) {
        $grade = "A";
    } else if ($GP >= 7.00 && $GP <= 7.99) {
        $grade = "B";
    } else if ($GP >= 6.00 && $GP <= 6.99) {
        $grade = "C";
    } else if ($GP >= 5.00 && $GP <= 5.99) {
        if ($total_external == 40) {
            if (($ese >= 16) && ($ce + $ese >= 20)) {
                $grade = "D";
            } else {
                $grade = '<span style="color: red;"><b>F<b></span>';
            }
        } elseif ($total_external == 20) {
            if (($ese >= 8) && ($ce + $ese >= 10)) {
                $grade = "D";
            } else {
                $grade = '<span style="color: red;"><b>F<b></span>';
            }
        }
    } else {
        if ($total_external == 40) {
            if (($ese >= 16) && ($ce + $ese >= 20)) {
                $grade = "E";
            } else {
                $grade = '<span style="color: red;"><b>F<b></span>';
            }
        } elseif ($total_external == 20) {
            if (($ese >= 8) && ($ce + $ese >= 10)) {
                $grade = "E";
            } else {
                $grade = '<span style="color: red;"><b>F<b></span>';
            }
        }
    }
    return $grade;
}
?>
<?php
    require_once('appvars.php');
    require_once('connectvars.php');
    session_start();
    $page_title = 'Advanced Search';
    require_once('header.php');
    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    $selected_semesters = array();

    if (isset($_SESSION['username']))
    {
        $query = "SELECT pgm_id,pgm_name FROM programme order by pgm_name";
        $pgms = mysqli_query($dbc, $query);
        $query = "SELECT * FROM department order by dept_name";
        $depts = mysqli_query($dbc, $query);
        $query = "SELECT * FROM state order by state_name";
        $states = mysqli_query($dbc, $query);
        $query = "SELECT * FROM district order by dist_id";
        $dists = mysqli_query($dbc, $query);
        $query = "SELECT * FROM religion order by religion_name";
        $religions = mysqli_query($dbc, $query);
        $query = "SELECT * FROM caste order by caste_name";
        $castes = mysqli_query($dbc, $query);
        $query = "SELECT * FROM quota order by quota_id";
        $quotas = mysqli_query($dbc, $query);
        $query = "SELECT * FROM category order by cat_id";
        $cats = mysqli_query($dbc, $query);

        $semester=0;
        $pgmid=0;
        $yearofadmn = 0;
        $deptid = 0;
        $quotaid=0;
        $sex="0";
        $categoryid=0;
        $status="0";

        $unique_course_ids = array();
        $stud_ids = array();

        if (isset($_POST['submit']))
        {
            $name = mysqli_real_escape_string($dbc, trim($_POST['name']));
            $admno = mysqli_real_escape_string($dbc, trim($_POST['admno']));
            $regno = mysqli_real_escape_string($dbc, trim($_POST['regno']));
            $pgmid = mysqli_real_escape_string($dbc, trim($_POST['programme']));
            $yearofadmn = mysqli_real_escape_string($dbc, trim($_POST['yearofadmn']));
            $quotaid = mysqli_real_escape_string($dbc, trim($_POST['quota']));
            $sex = mysqli_real_escape_string($dbc, trim($_POST['sex']));
            $categoryid = mysqli_real_escape_string($dbc, trim($_POST['category']));
            $status = mysqli_real_escape_string($dbc, trim($_POST['status']));

            $year = 2019;
            $year2 = 2020;
            $credit = 0;
            if (isset($_POST['sem'])) 
            {
                $selected_semesters = array_map('intval', $_POST['sem']);
            }
            elseif (isset($_POST['sem']) && is_numeric($_POST['sem'])) 
            {
                $selected_semesters[] = intval($_POST['sem']);
            }
            foreach ($selected_semesters as $semester) 
            {
                    $query1 = "SELECT course_id FROM pgm_course";
                    if($pgmid>0)
                    {
                        $query1 = $query1." WHERE pgm_id = ".$pgmid;
                    }
                    else if(($name)>0 || ($regno)>0 || ($admno)>0)
                    {
                        if((($name)>0) && (($regno)<0) && (($admno)<0) )
                        {
                            $query3 = "SELECT pgm_id FROM stud_master WHERE name like '%".$name."%'";                           
                            $pgm_ids = mysqli_query($dbc, $query3);
                            foreach($pgm_ids as $a)
                            {
                                $pgm_id = $a['pgm_id'];
                                $pgm_ids2[] = $pgm_id;
                            }
                            $query1 = $query1." WHERE  pgm_id IN (" . implode(',', $pgm_ids2) . ")";
                        }
                        if(($regno)>0)
                        {
                            $query3 = "SELECT pgm_id FROM stud_master WHERE uty_reg_no = '$regno'";
                            $pgm_ids = mysqli_query($dbc, $query3);
                            foreach($pgm_ids as $a)
                            {
                                $pgm_id = $a['pgm_id'];
                            }
                            $query1 = $query1." WHERE pgm_id = ".$pgm_id;
                        }
                        if(($admno)>0)
                        {
                            
                            $query3 = "SELECT pgm_id FROM stud_master WHERE admn_no = ".$admno;
                            $pgm_ids = mysqli_query($dbc, $query3);
                            foreach($pgm_ids as $a)
                            {
                                $pgm_id = $a['pgm_id'];
                            }
                            $query1 = $query1." WHERE pgm_id = ".$pgm_id;
                        }
                    }
                    else
                    {
                        $query1 = "SELECT * FROM pgm_course WHERE course_id > 0";
                    }
                    $course_ids = mysqli_query($dbc,$query1);
                    foreach($course_ids as $a)
                    {
                        $course_id = $a['course_id'];
                        $query2 = "SELECT course_title,course_id FROM course WHERE course_id = ".$course_id." AND semester=".$semester." AND credits <> " . $credit . " AND  syllabus_intro_year
                        IN (" . $year . ", " . $year2 . ")";
                        $courses = mysqli_query($dbc, $query2);
                        while ($row = mysqli_fetch_assoc($courses)) 
                        {
                            $course_title = $row['course_title'];
                            $unique_course_titles[] = $course_title; 
                            $course_id = $row['course_id'];
                            $unique_course_ids[] = $course_id;
                        }
                    }
            }
            $studying="'Studying'";
            $query = "SELECT stud_id,name,uty_reg_no FROM stud_master WHERE status = ".$studying;
            if(strlen($name)>0)
                $query = $query." AND name like '%".$name."%'";
            if(strlen($admno)>0)
                $query = $query." AND admn_no like '%".$admno."%'";
            if(strlen($regno)>0)
                $query = $query." AND uty_reg_no like '%".$regno."%'";
            if($pgmid>0)
                $query = $query." AND pgm_id =".$pgmid;
            if($yearofadmn>0)
                $query = $query." AND year_of_admn =".$yearofadmn;
            if($quotaid>0)
                $query = $query." AND quota =".$quotaid;
            if($sex!="0")
                $query = $query." AND sex ='".$sex."'";
            if($categoryid>0)
            {
                $subquery = "SELECT caste_id  FROM caste WHERE cat_id =". $categoryid;

                $selectedcastes = mysqli_query($dbc, $subquery);
                while($row=mysqli_fetch_array($selectedcastes,MYSQLI_NUM))
                {
                    $castearray[]=$row[0];
                }
                $casteString = implode(",",$castearray);
                $query = $query." AND stud_master.caste_id IN (".$casteString.")";
            }
                $query = $query." ORDER BY uty_reg_no";
                $studs = mysqli_query($dbc, $query);  
        }   
    }
?>

<br />
<form enctype="multipart/form-data" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" class="login-form" id="search-form">
<!-- Your existing form content -->
<!--    
    <form enctype="multipart/form-data" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" class="login-form" > -->
     
    <?php require_once('navmenu.php'); ?>
      <br><br>
      <table align=center >
        <tr class=centeralign>
            <th class=center >Semester </th><th>:</th><td >
            
                <label style="display: inline-block; margin-right: 10px;">
                    <input type="checkbox" name="sem[]" value="1" <?php if (in_array('1', $selected_semesters)) echo 'checked'; ?> style="width: 20px; height: 20px;"> 1
                </label>
                <label style="display: inline-block; margin-right: 10px;">
                    <input type="checkbox" name="sem[]" value="2" <?php if (in_array('2', $selected_semesters)) echo 'checked'; ?> style="width: 20px; height: 20px;"> 2
                </label>
                <label style="display: inline-block; margin-right: 10px;">
                    <input type="checkbox" name="sem[]" value="3" <?php if (in_array('3', $selected_semesters)) echo 'checked'; ?> style="width: 20px; height: 20px;"> 3
                </label>
                <label style="display: inline-block; margin-right: 10px;">
                    <input type="checkbox" name="sem[]" value="4" <?php if (in_array('4', $selected_semesters)) echo 'checked'; ?> style="width: 20px; height: 20px;"> 4
                </label>
                <label style="display: inline-block; margin-right: 10px;">
                    <input type="checkbox" name="sem[]" value="5" <?php if (in_array('5', $selected_semesters)) echo 'checked'; ?> style="width: 20px; height: 20px;"> 5
                </label>
                <label style="display: inline-block; margin-right: 10px;">
                    <input type="checkbox" name="sem[]" value="6" <?php if (in_array('6', $selected_semesters)) echo 'checked'; ?> style="width: 20px; height: 20px;"> 6
                </label>
            </td>
        </tr>
        <tr>
            <th class=center>
                Name</th><th>:</th><td><input type="text" id="name" name="name" value="<?php if (!empty($name)) echo $name; ?>" />
            </td></tr><tr>
            <th class=center>
                Admission No</th><th>:</th><td><input type="text" id="admno" name="admno" value="<?php if (!empty($admno)) echo $admno; ?>" />
                </td></tr><tr>
            <th class=center>
                University Reg No </th><th>:</th><td> <input type="text" id="regno" name="regno" value="<?php if (!empty($regno)) echo $regno; ?>" /><br />
                </td></tr>
        
        <tr align="center">
            <th class=center>
                Status(Pass/Fail)</th><th>:</th><td>
                <select name="status">
                    <option value="0"  <?php if($status == "0") echo "selected"; ?>>All</option>
                    <option value="P"  <?php if($status == "P") echo "selected"; ?>>Pass</option>
                    <option value="F"  <?php if($status == "F") echo "selected"; ?>>Fail</option>
                </select>
                </td></tr><tr>
            <th class=center>
            Programme</th><th>:</th><td>
            <select name="programme">
                <option value=0 <?php if(!empty($pgmid)) if($pgmid == 0) echo "selected"; ?>>All</option>
                <?php foreach($pgms as $pgm)
                {?>
                    <option value="<?php echo $pgm['pgm_id'];?>" <?php if(!empty($pgmid)) if($pgmid == $pgm['pgm_id']) echo "selected"; ?>><?php echo $pgm['pgm_name']; ?></option>
                <?php }?>
            </select>
            </td></tr><tr>
            <th class=center>
                Year of Admission</th><th>:</th><td>
                <select name="yearofadmn">
                <option value="<?php echo  0; ?>" <?php if ($yearofadmn==0) echo "selected"; ?>><?php echo 'All'; ?></option>
                <?php
                        for ($i = 2010; $i <= 2050; $i++) : ?>
                        <option value="<?php echo  $i; ?>" <?php if ($i==$yearofadmn) echo "selected"; ?>><?php echo $i; ?></option>
                <?php   endfor; ?>
                </select>
                </td></tr><tr>
            <th class=center>
                Sex</th><th>:</th><td>
                <select name="sex">
                    <option value="0"  <?php if($sex == "0") echo "selected"; ?>>All</option>
                    <option value="M"  <?php if($sex == "M") echo "selected"; ?>>Male</option>
                    <option value="F"  <?php if($sex == "F") echo "selected"; ?>>Female</option>
                </select>
                </td></tr><tr>
        <tr align="center">
            <th class=center>
                Quota</th><th>:</th><td>
                <select name="quota">
                <option value=0 <?php if($quotaid == 0) echo "selected"; ?>>All</option>
                    <?php foreach($quotas as $quota)
                    {?>
                        <option value="<?php echo $quota['quota_id'];?>" <?php  if($quotaid == $quota['quota_id']) echo "selected"; ?>><?php echo $quota['quota_name']; ?></option>
                    <?php }?>
                </select>
                </td></tr><tr>
            <th class=center>
                Category</th><th>:</th><td>
                <select name="category">
                    <option value=0 <?php if($categoryid == 0) echo "selected"; ?>>All</option>
                    <?php foreach($cats as $cat)
                    {?>
                        <option value="<?php echo $cat['cat_id'];?>" <?php  if($categoryid == $cat['cat_id']) echo "selected"; ?>><?php echo $cat['cat_name']; ?></option>
                    <?php }?>
                </select>
             </td>
            </tr>
            <tr>
                <td colspan="2">
                    <button class="upload-button1" type="submit" value="Submit" name="submit" >SEARCH</button>
                </td>
                <td>
                    <button class="upload-button1" type="reset" value="reset" name="reset">RESET</button>
                </td>
            </tr> 
        </table>  
        </form>
        </div>
    <?php
    if (isset($_POST['submit']) ) {
       if(isset($_POST['sem']) && !empty($_POST['sem'])){
            $si_no = 1;
            foreach($studs as $a)
            {
                $name = $a['name'];
                $reg_no = $a['uty_reg_no'];
            }
            echo '<div class="filterform">';
            ?>
            <center>
                <table>
                    <tr>
                    <th style="font-size:20;width:500px;"><b>Search Results<b></th>
                    <td>
                        <button class="hide" style="width:70px;align:right;" value="reset" name="reset" onclick="history.back()" >Hide</button>
                    </td>    
                    </tr>
                </table>
            <center>
            <?php
            echo '<table align="center" style="width:50%;" class="custom-table" id="result-table" >';
                $i=0;
                $unique_course_titles = [];
                foreach ($studs as $a) 
                {
                    $stud_id = $a['stud_id'];
                    $name = $a['name'];
                    $reg_no = $a['uty_reg_no'];
                    if($status=="0")
                    {
                        $query5 = "SELECT stud_id FROM sem_exam";
                        $stud_ids2 = mysqli_query($dbc,$query5);
                        foreach($stud_ids2 as $a)
                        {
                            $stud_id2 = $a['stud_id'];
                            if($stud_id==$stud_id2)
                            {
                                
                                echo '
                                <tr class=centeralign>
                                    <th  colspan="4">'. $name  . '  -  ' .  $reg_no .'</th>
                                </tr>
                                <tr>
                                    <th>SI.No</th>
                                    <th>Course_title</th>
                                    <th>Mark</th>
                                    <th>Grade</th>
                                </tr>';
                              
                                break;
                               
                            }
                        }
                    }
                    $valid=1;
                    $pass=1;
                    $fail=1;
                    foreach ($unique_course_ids as $course_id) 
                    {
                        $previous=null;
                        $query = "SELECT course_title,total_external,total_internal FROM course WHERE course_id = ".$course_id."";
                        $course_titles = mysqli_query($dbc,$query);
                        foreach($course_titles as $a)
                        {
                            $course_title = $a['course_title'];
                            $total_internal =$a['total_internal'];
                            $total_external =$a['total_external'];
                        }
                        $query = "SELECT ese,ce FROM sem_exam WHERE stud_id = $stud_id AND course_id = ".$course_id;
                        $marks = mysqli_query($dbc, $query);
                        foreach ($marks as $row) 
                        {
                            $ese = $row['ese'];
                            $ce = $row['ce'];
                            $mark = $ese + $ce;
                            $GP = ($mark/($total_external+$total_internal))*10;
                            if($status=="0")
                            {
                                if(($previous!=$course_title) && (!in_array($course_title, $unique_course_titles)))
                                {
                                    echo '<tr>';
                                        echo '<td>' . $si_no . '</td>';
                                        echo '<td style="text-align: left;">'. $course_title . '</td>';
                                        echo '<td>' . $mark . '</td>';
                                        echo '<td>' .Grade($GP,$total_external,$ese,$ce).'</td>';
                                    echo '</tr>';
                                    $previous = $course_title;
                                    $unique_course_titles[] = $course_title;
                                    $si_no= $si_no+1;
                                }
                            }
                            else if($status=="P")
                            {
                                if($total_external == 40)
                                {
                                    if(($ese >= 16) && ($ce + $ese >=20))
                                    {   
                                        
                                    }
                                    else
                                    {
                                        $pass=0;
                                        break;
                                    }
                                }
                                if($total_external == 20)
                                {
                                    if(($ese >= 8) && ($ce + $ese >=10))
                                    {   

                                    }
                                    else
                                    {
                                        $pass=0;
                                        break;
                                    }
                                }
                            }
                            else if($status=="F")
                            {
                                if($total_external == 40)
                                {
                                    if(($ese >= 16) && ($ce + $ese >=20))
                                    {   
                                        
                                    }
                                    else
                                    {
                                        $fail=0;
                                        break;
                                    }
                                }
                                if($total_external == 20)
                                {
                                    if(($ese >= 8) && ($ce + $ese >=10))
                                    {   
                                        
                                    }
                                    else
                                    {
                                        $fail=0;
                                        break;
                                    }
                                }
                            }
                        }
                        
                    }
                    $unique_course_titles = [];
                    if($status=="F")
                    {
                        $previous=null;
                        if($fail==0)
                        {
                            $query5 = "SELECT stud_id FROM sem_exam";
                            $stud_ids2 = mysqli_query($dbc,$query5);
                            foreach($stud_ids2 as $a)
                            {
                                $stud_id2 = $a['stud_id'];
                                if($stud_id==$stud_id2)
                                {
                                    echo '
                                    <tr rowspan="2"></tr>
                                    <tr class=centeralign colspan="4">
                                        <th colspan="4">'. $name  . '  -  ' .  $reg_no .'</th>
                                    </tr>
                                    <tr>
                                        <th>SI.No</th>
                                        <th>Course_title</th>
                                        <th>Mark</th>
                                        <th>Grade</th>
                                    </tr>';
                                    break;
                                }
                            }
                            foreach ($unique_course_ids as $course_id) 
                            {
                                $query = "SELECT course_title,total_external,total_internal FROM course WHERE course_id = ".$course_id;
                                $course_titles = mysqli_query($dbc,$query);
                                foreach($course_titles as $a)
                                {
                                    $course_title = $a['course_title'];
                                    $total_internal =$a['total_internal'];
                                    $total_external =$a['total_external'];
                                }
                                $query = "SELECT ese,ce FROM sem_exam WHERE stud_id = $stud_id AND course_id = ".$course_id;
                                $marks = mysqli_query($dbc, $query);
                                foreach ($marks as $row) 
                                {
                                    $ese = $row['ese'];
                                    $ce = $row['ce'];
                                    $mark = $ese + $ce;
                                    $GP = ($mark/($total_external+$total_internal))*10;
                                    if(($previous!=$course_title) && (!in_array($course_title, $unique_course_titles)))
                                    {
                                        echo '<tr>';
                                        
                                            echo '<td>' . $si_no . '</td>';
                                            echo '<td style="text-align: left;">'. $course_title . '</td>';
                                            echo '<td>' . $mark . '</td>';
                                            echo '<td>' .Grade($GP,$total_external,$ese,$ce).'</td>';
                                            echo '</tr>';
                                        $previous = $course_title;
                                        $unique_course_titles[] = $course_title;
                                        $si_no= $si_no+1;
                                    }
                                }
                            }
                            $unique_course_titles = [];
                        }
                        else
                        {
                            continue;
                        }
                    }
                    if($status=="P")
                    {
                        if($pass==1)
                        {

                            $query5 = "SELECT stud_id FROM sem_exam";
                            $stud_ids2 = mysqli_query($dbc,$query5);
                            foreach($stud_ids2 as $a)
                            {
                                $stud_id2 = $a['stud_id'];
                                if($stud_id==$stud_id2)
                                {
                                    echo '
                                    <tr class=centeralign colspan="4">
                                        <th colspan="4">'. $name  . '  -  ' .  $reg_no .'</th>
                                    </tr>
                                    <tr>
                                        <th>SI.No</th>
                                        <th>Course_title</th>
                                        <th>Mark</th>
                                        <th>Grade</th>
                                    </tr>';
                                   break;
                                }
                            }
                            foreach ($unique_course_ids as $course_id) 
                            {
                                $previous=null;
                                $query = "SELECT course_title,total_external,total_internal FROM course WHERE course_id = ".$course_id."";
                                $course_titles = mysqli_query($dbc,$query);
                                foreach($course_titles as $a)
                                {
                                    $course_title = $a['course_title'];
                                    $total_internal =$a['total_internal'];
                                    $total_external =$a['total_external'];
                                }
                                $query = "SELECT ese,ce FROM sem_exam WHERE stud_id = $stud_id AND course_id = ".$course_id;
                                $marks = mysqli_query($dbc, $query);
                                foreach ($marks as $row) 
                                {
                                    $ese = $row['ese'];
                                    $ce = $row['ce'];
                                    $mark = $ese + $ce;
                                    $GP = ($mark/($total_external+$total_internal))*10;
                                    if(($previous!=$course_title) && (!in_array($course_title, $unique_course_titles)))
                                    {
                                        echo '<tr>';
                                            echo '<td>' . $si_no . '</td>';
                                            echo '<td style="text-align: left;">'. $course_title . '</td>';
                                            echo '<td>' . $mark . '</td>';
                                            echo '<td>' .Grade($GP,$total_external,$ese,$ce).'</td>';
                                        echo '</tr>';
                                        $previous = $course_title;
                                        $unique_course_titles[] = $course_title;
                                        $si_no= $si_no+1;
                                    }
                                }
                            }
                            $unique_course_titles = [];
                        }
                        echo '<tr><td></td></tr>';
                    }
                    else
                    {
                        continue;
                    }
                }
                echo '</table>';
        } else {
            
            echo '<div align="center" style="color:red;">Please select at least one semester before searching.</div>';
        }
    }
    ?>
<script>
function resetFormAndShowTable() {
    history.back();
}

function displayResultsTable() {
    // Show the results container
    var resultsContainer = document.getElementById('results-container');
    resultsContainer.style.display = 'block';
}
</script>