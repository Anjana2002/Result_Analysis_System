<style>
    body{
     background: #EAF4FC;
    }
</style>
<?php
require_once('appvars.php');
require_once('connectvars.php');
session_start();
$page_title = 'Semester Wise Analysis';
require_once('header.php');
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
if (isset($_SESSION['username']))
    {
        $query = "SELECT pgm_id,pgm_name FROM programme order by pgm_name";
        $pgms = mysqli_query($dbc, $query);
       
        if (isset($_POST['submit']))
        {
            $year = 2019;
            $year2 = 2020;
            $credit = 0;
            $pgm_id = mysqli_real_escape_string($dbc, trim($_POST['programme']));
            $semester = $_POST['sem'];
            $yearofadmn = mysqli_real_escape_string($dbc, trim($_POST['yearofadmn']));


            $query1 = "SELECT stud_id,name,uty_reg_no
                       FROM stud_master WHERE pgm_id = ". $pgm_id . "  AND status='Studying' AND year_of_admn = ". $yearofadmn . " ORDER BY uty_reg_no";
   
            $studs = mysqli_query($dbc, $query1);
            
            $query2 = "SELECT course_id FROM pgm_course WHERE pgm_id = " . $pgm_id;
            $course_ids = mysqli_query($dbc, $query2);
           
           
            
        }
    }
?>
<div class="filterform">
    <?php require_once('navmenu.php'); ?>
    <form enctype="multipart/form-data" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" class="login-form">
    <table align=center>  
    <tr>
    <th><label for="dob">Programme:</label></th><th>:</th>
    <td> <select name="programme" id="programme">
            <?php foreach($pgms as $pgm)
            {?>
                <option value="<?php echo $pgm['pgm_id'];?>" <?php if(!empty($pgm_id)) if($pgm_id == $pgm['pgm_id']) echo "selected"; ?>><?php echo $pgm['pgm_name']; ?></option>
            <?php }?>
        </select></td></tr>
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
            </tr> 
        </table>  <br>
        <button class="upload-button1" type="submit" value="Log In" name="submit">SEARCH</button><br/>
    </form>
    <?php
        if (isset($_POST['submit']))
        {
            
            echo '<h3 align=center>Analysis</h3>';
            $si_no = 1;?>
            <table align="center"  style="width:50%;" class="custom-table" id="printTable1">
            <?php $tables[]="printTable1";?>
            <tr>
            <th>Sl No.</th>
            <th>Register No.</th>
            <th>Name</th>
            <?php
            $Aplus = 0;
            $A = 0;
            $B = 0;
            $C = 0;
            $D = 0;
            $Fail = 0;
            $unique_course_titles = [];
            foreach ($course_ids as $a) {
                $course_id = $a['course_id'];
                $query = "SELECT course_title FROM course
                          WHERE course_id = " . $course_id . " AND semester = " . $semester."  AND credits <> " . $credit . " AND  syllabus_intro_year
                              IN (" . $year . ", " . $year2 . ")";
                $courses = mysqli_query($dbc, $query);
                while ($row = mysqli_fetch_assoc($courses)) {
                    $course_title = $row['course_title'];
                    $unique_course_titles[] = $course_title; 
                }
            }
            foreach ($unique_course_titles as $course_title) {
                echo '<th style="width: 50px;">' . $course_title . '</th>';
            }
            ?> <th>Status</th><?php
            $unique_course_titles = [];
            foreach ($studs as $a) {
                $name = $a['name'];
                $uty = $a['uty_reg_no'];
                $stud_id =$a['stud_id'];?>
                <tr>
                <td> <?php echo $si_no; ?> </td>
                <td><?php echo $uty ?></td>
                <td style="text-align: left;"><?php echo $name ?></td>
           
           <?php
                $i=0;
                foreach ($course_ids as $a) 
                {
                    $course_id = $a['course_id'];
                    $query = "SELECT course_title, total_internal, total_external FROM course
                              WHERE course_id = " . $course_id . " AND semester = " . $semester."  AND credits <> " . $credit . " AND  syllabus_intro_year
                                  IN (" . $year . ", " . $year2 . ")";
                    $courses = mysqli_query($dbc, $query);
                    while ($row = mysqli_fetch_assoc($courses)) 
                    {
                        $course_title = $row['course_title'];
                        $total_internal = $row['total_internal'];
                        $total_external = $row['total_external'];
                        $unique_course_titles[] = $course_title;     
                        $query3 = "SELECT ce,ese FROM sem_exam WHERE stud_id = " . $stud_id . " AND course_id = " . $course_id;
                        $marks = mysqli_query($dbc, $query3);
                        $ce_values = [];
                        while ($row = mysqli_fetch_assoc($marks)) 
                        {
                            $ce = $row['ce'];
                            $ce_values[] = $ce;
                            $ese = $row['ese'];
                            $ese_values[] = $ese;
                            $p="P";
                            $f="F";  
                        }
                        if (empty($ce_values)) 
                        {
                            echo "<td>-</td>";
                        } 
                        else 
                        {
                            foreach ($ce_values as $ce) 
                            {
                                $mark = $ese + $ce;
                                echo "<td>" .$mark. "</td>";
                            
                                if($total_external == 40)
                                {
                                    if(($ese >= 16) && ($ce + $ese >=20))
                                    {
                                        continue;
                                    }
                                    else
                                    {
                                        $i=$i+1;
                                    }
                                    
                                }
                                else
                                {
                                    if(($ese >= 8) && ($ce + $ese >=10))
                                    {
                                        continue;
                                    }
                                    else
                                    {
                                        $i=$i+1;
                                    }
                                }
                            }  
                        }
                    }   
                }
                if($i>0)
                {
                    echo "<td style=color:red>" .$f. "</td>";
                   
                }
                else
                {
                    echo "<td>" .$p. "</td>";
                }
                $si_no= $si_no+1;
            }  
           
           ?> 
        </tr>      
    </table>
    <br><br><br>
    <?php $si_no = 1;?>
    <table align="center"  style="width:50%;" class="custom-table" id=printTable2>
    <?php $tables[]="printTable2";?>
            <tr><th>Sl No.</th>
            <th>Papers</th>
            <th>A+</th>
            <th>A</th>
            <th>B</th>
            <th>C</th>
            <th>D</th>
            <th>E</th>
            <th>Fail</th></tr>
             
            <?php
            $unique_course_titles=[];  
            $total_externals=[];
            $total_internals=[];  
            $i=null;
            $course_ids2=[];
            foreach ($course_ids as $a) 
            {
                
                $Aplus=$A=$B=$C=$D=$E=$Fail=0;
                $course_id = $a['course_id'];
                $query = "SELECT course_title,total_external,total_internal 
                          FROM course WHERE course_id = " . $course_id . " AND semester = " . $semester."  AND credits <> " . $credit . " AND  syllabus_intro_year
                          IN (" . $year . ", " . $year2 . ")";
                $courses = mysqli_query($dbc, $query);
                foreach($courses as $a)
                {
                    $course_title = $a['course_title'];
                    $total_internal = $a['total_internal'];
                    $total_external = $a['total_external'];
                    if($course_title != $i)
                    {
                        $total_externals[]=$total_external;
                        $total_internals[]=$total_internal;
                        $course_ids2[]=$course_id;
                        $unique_course_titles[]=$course_title;
                        $i=$course_title;
                    }
                }
            }
            $i=0;
            foreach ($course_ids2 as $course_id)
            {
                $total_external=$total_externals[$i];
                $total_internal=$total_internals[$i];
                $course_title=$unique_course_titles[$i];
                $i++;   
                foreach ($studs as $a) 
                {
                    $stud_id = $a['stud_id'];
                    $query = "SELECT ce,ese FROM sem_exam WHERE course_id = ".$course_id." AND stud_id = ".$stud_id."";
                    $semmarks = mysqli_query($dbc,$query);
                    foreach($semmarks as $a)
                    {
                        $ese = $a['ese'];
                        $ce = $a['ce'];
                        $mark=$ese + $ce;
                        $GP = ($mark/($total_external+$total_internal)) * 10 ;
                        if($GP >= 9.00)
                        {
                            $Aplus=$Aplus+1;
                        }
                        else if($GP >= 8.00 && $GP <= 8.99)
                        {
                            $A=$A+1;
                        }
                        else if($GP >= 7.00 && $GP <= 7.99)
                        {
                            $B=$B+1;
                        }
                        else if($GP >= 6.00 && $GP <= 6.99)
                        {
                            $C=$C+1;
                        }
                        else if($GP >= 5.00 && $GP <= 5.99)
                        {
                            $D=$D+1;
                        }
                        else
                        {
                            if($total_external == 40)
                            {
                                if(($ese >= 16) && ($ce + $ese >=20))
                                {   
                                    $E=$E+1;
                                }
                                else
                                {
                                    $Fail=$Fail+1;
                                }
                            }
                            if($total_external == 20)
                            {
                                if(($ese >= 8) && ($ce + $ese >=10))
                                {   
                                    $E=$E+1;
                                }
                                else
                                {
                                    $Fail=$Fail+1;
                                }
                            }
                        }
                    }
                }
                if($previous_course != $course_title)
                {
                    $previous_course=$course_title;
                    echo '<tr>
                            <td>' . $si_no . '</td>
                            <td>' . $course_title . '</td> 
                            <td>' . $Aplus . '</td>
                            <td>' . $A . '</td>
                            <td>' . $B . '</td>
                            <td>' . $C . '</td>
                            <td>' . $D . '</td>
                            <td>' . $E . '</td>
                            <td>' . $Fail . '</td>
                          </tr>';
                    $Aplus=$A=$B=$C=$D=$E=$Fail=0;
                      
                }
                else
                {
                    $Aplus=$A=$B=$C=$D=$E=$Fail=0;
                    continue;
                }
                $si_no= $si_no+1;
            }?>
          </table>
         
         <div align=center>
         <br>
         <button class="upload-button1" onclick=print()>PRINT</button>
     </div>
     <?php }   ?>
      
        
    
   
</div>   
        
<script>
// scritp for printing
var tables = <?php echo json_encode($tables); ?>;

function print() {
    var newWin = window.open("");
    tables.forEach(function (tableId) {
        var tableToPrint = document.getElementById(tableId);
        newWin.document.write(tableToPrint.outerHTML);
        newWin.document.write("<br><br>"); 
    });
    
    newWin.print();
    newWin.close();
}
</script>