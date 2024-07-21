<style>
    body{
     background: #EAF4FC;
    }
    #filterform2 {
        position: relative;
        width:500px;
        z-index: 1;
        align:center;
        text-align:center;
        background-color: #FFFFFF;
        margin:0 auto;
        padding: 1% 1% 1% 15%;
        box-shadow: 0 0 20px 0 rgba(0, 0, 0, 0.2), 0 5px 5px 0 rgba(0, 0, 0, 0.24);     
        display: flex;
        flex-direction: column;
        height:50%;
    }
</style>
<?php
require_once('appvars.php');
require_once('connectvars.php');
session_start();
$page_title = 'Single Subject Analysis';
require_once('header.php');
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

if (isset($_SESSION['username']))
{
        $query = "SELECT pgm_id,pgm_name FROM programme order by pgm_name";
        $pgms = mysqli_query($dbc, $query);?>
        <div class="filterform">
        <?php require_once('navmenu.php'); ?>
        <form enctype="multipart/form-data" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" class="login-form">
        <table align=center >  
        <tr>
            <th><label for="dob">Programme</label></th>
            <th>:</th>
            <td>
                <select name="programme" id="programme">
                    <?php foreach ($pgms as $pgm) { ?>
                        <option value="<?php echo $pgm['pgm_id']; ?>" <?php if (isset($_POST['programme']) && $_POST['programme'] == $pgm['pgm_id']) echo "selected"; ?>><?php echo $pgm['pgm_name']; ?></option>
                    <?php } ?>
                </select>
            </td>
        </tr>   
        <tr>
            <th><label for="yearofadmn">Year of Admission</label></th>
            <th>:</th>
            <td>
                <select name="yearofadmn">
                    <?php
                    for ($i = 2010; $i <= 2050; $i++) {
                        $selected = (isset($_POST['yearofadmn']) && $_POST['yearofadmn'] == $i) ? 'selected' : '';
                        echo '<option value="' . $i . '" ' . $selected . '>' . $i . '</option>';
                    }
                    ?>
                </select>
            </td>
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
        </td></tr>
                </table>
            <br> <button  class="upload-button1" type="submit" value="Log In" name="submit">SEARCH</button><br/>
    <?php
        $yearofadmn = $_POST['yearofadmn'];
}  
        if (isset($_POST['submit']))
        {?>
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <?php
              $pgm_id = mysqli_real_escape_string($dbc, trim($_POST['programme']));
              $semester = $_POST['sem'];
              $yearofadmn = mysqli_real_escape_string($dbc, trim($_POST['yearofadmn']));
              $year = 2019;
              $year2 = 2020;
              $credit = 0;
                $query2 = "SELECT course_id FROM pgm_course WHERE pgm_id = " . $pgm_id;
                $course_ids = mysqli_query($dbc, $query2);
                echo '<table align="center">';
                foreach ($course_ids as $a) 
                {
                        $course_id = $a['course_id'];
                        $query = "SELECT course_title FROM course
                                  WHERE course_id = " . $course_id . " AND semester = " . $semester."  AND credits <> " . $credit . " AND  syllabus_intro_year
                                  IN (" . $year . ", " . $year2 . ")";
                        $courses = mysqli_query($dbc, $query);
                        while ($row = mysqli_fetch_assoc($courses)) 
                        {
                            $course_title = $row['course_title'];
                            $unique_course_titles[] = $course_title; 
                        }
                }
                foreach ($unique_course_titles as $course_title) : ?>
                 <tr>
                    <th><input type="radio" name="course_title" value="<?php echo htmlspecialchars($course_title); ?>"></th>
                    <td><label><?php echo $course_title; ?></label></td>
                 </tr>
                <?php 
                endforeach;  ?>
                <br>
            </table>
                <br><button class="upload-button1" type="submit" value="Log In" name="submit">VIEW</button><br />    
                <?php
                $Aplus = 0;
                $A = 0;
                $B = 0;
                $C = 0;
                $D = 0;
                $E = 0;
                $fail = 0;
                if (isset($_POST['submit']))
                {
                    if (isset($_POST['course_title'])) 
                    {   
                        $selectedCourseTitle = $_POST['course_title'];
                        $year = 2019;
                        $year2 = 2020;
                        $credit = 0;
                        $si_no = 1;
                        echo '<h3 align="center">' . $selectedCourseTitle . ' -Analysis</h3>';
                        echo '<table align="center" border="solid">';
                        echo '<tr>
                                <th>Sl No.</th>
                                <th>Register No.</th>
                                <th>Name</th>
                                <th>CE</th>
                                <th>ESE</th><th>Total</th>
                                <th>Grade</th>';
                        
                            $query = "SELECT course_id, total_internal, total_external FROM course
                            WHERE course_title = '$selectedCourseTitle'  AND semester = " . $semester . " AND credits <> " . $credit . " AND syllabus_intro_year
                            IN (" . $year . ", " . $year2 . ")";
                            $course_ids2 = mysqli_query($dbc, $query);
                            while ($row = mysqli_fetch_assoc($course_ids2)) 
                            {
                                $course_id2 = $row['course_id'];  
                                $total_internal = $row['total_internal'];
                                $total_external = $row['total_external'];
                                break;
                            }
                        
                        $query1 = "SELECT stud_id,name,uty_reg_no FROM stud_master 
                                   WHERE year_of_admn = ". $yearofadmn ." AND pgm_id= ". $pgm_id ." 
                                   ORDER BY roll_no" ;
                        $studs = mysqli_query($dbc, $query1);
                        $p="P";
                        $f="F"; 
                        $i=0; 
                        $markArray = [];
                        foreach ($studs as $a) 
                        {
                            $name = $a['name'];
                            $uty = $a['uty_reg_no'];
                            $stud_id =$a['stud_id'];?>
                            <?php
                            $query3 = "SELECT ce,ese FROM sem_exam WHERE stud_id = " . $stud_id . " AND course_id = " . $course_id2;
                            $marks = mysqli_query($dbc, $query3);
                            $ce_values = [];
                            while ($row = mysqli_fetch_assoc($marks)) 
                            {
                                echo '<tr><td>' . $si_no . '</td>';
                                echo '<td>' .$uty.'</td>';
                                echo '<td style="text-align: left;">' .$name.'</td>';
                                $ce = $row['ce'];
                                $ce_values[] = $ce;
                                $ese = $row['ese'];
                                $ese_values[] = $ese;
                                echo "<td>" .$ce. "</td>";
                                echo "<td>" .$ese. "</td>";
                                $mark = $ese + $ce;
                                echo "<td>" .$mark. "</td>";
                                $GP = ($mark/($total_external+$total_internal)) * 10 ;
                                if($GP >= 9.00)
                                {
                                    echo "<td> A+ </td>";
                                    $Aplus++;
                                }
                                else if($GP >= 8.00 && $GP <= 8.99)
                                {
                                    echo "<td> A </td>";
                                    $A++;
                                }
                                else if($GP >= 7.00 && $GP <= 7.99)
                                {
                                    echo "<td> B </td>";
                                    $B++;
                                }
                                else if($GP >= 6.00 && $GP <= 6.99)
                                {
                                    echo "<td> C </td>";
                                    $C++;
                                }
                                else if($GP >= 5.00 && $GP <= 5.99)
                                {
                                    echo "<td> D</td>";
                                    $D++;
                                }
                                else
                                {
                                    if($total_external == 40)
                                    {
                                        if(($ese >= 16) && ($ce + $ese >=20))
                                        {   
                                            echo "<td> E </td>";
                                            $E++;
                                        }
                                        else
                                        {
                                            echo "<td> - </td>";
                                            $fail++;
                                        }
                                    }
                                    if($total_external == 20)
                                    {
                                        if(($ese >= 8) && ($ce + $ese >=10))
                                        {   
                                            echo "<td> E </td>";
                                            $E++;
                                        }
                                        else
                                        {
                                            echo "<td> - </td>";
                                            $fail++;
                                        }
                                    }
                                }
                                
                            }
                        echo '</tr>';
                        $si_no= $si_no+1;
                        }
                       echo "</table>";
                       echo '<br><br>';
                        $data = [
                            'A+' => $Aplus,
                            'A' => $A,
                            'B' => $B,
                            'C' => $C,
                            'D' => $D,
                            'E' => $E,
                            'Fail' => $fail
                        ];
                    
                        // Prepare the data for the pie chart
                        $labels = array_keys($data);
                        $values = array_values($data);?>
                        <div id="filterform2" align=center>
                            <canvas id="gradePieChart" ></canvas>
                        </div>
                    <?php } 
                     }       
        }
   ?>
    </form>
    </div>
<script>
           
    var ctx = document.getElementById('gradePieChart').getContext('2d');
    var data = <?php echo json_encode($values); ?>;
    var labels = <?php echo json_encode($labels); ?>;
    var colors = ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40', '#8E44AD'];
    var config = {
        type: 'pie',
        data: {
            labels: labels,
            datasets: [{
                data: data,
                backgroundColor: colors,
            }],
        },
    };

    var gradePieChart = new Chart(ctx, config);
</script>
  
       