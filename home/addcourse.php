<style>
    body{
     background: #EAF4FC;
    }
  </style>
<?php
require_once('appvars.php');
require_once('connectvars.php');
session_start();
$page_title = 'Course Entry';
require_once('header.php');
require_once('navmenu.php');
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
// Connect to the database
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

if (isset($_SESSION['username'])) {
    $query = "SELECT dept_id, dept_name FROM department ORDER BY dept_name";
    $depts = mysqli_query($dbc, $query);

    $query = "SELECT course_type_id, course_type_desc FROM course_type ORDER BY course_type_desc";
    $course_types = mysqli_query($dbc, $query);
    $query = "SELECT pgm_id,pgm_name FROM programme order by pgm_name";
    $pgms = mysqli_query($dbc, $query);
    if (isset($_POST['submit'])) {
        //$course_id = mysqli_real_escape_string($dbc, trim($_POST['course_id']));
        $query = "SELECT MAX(course_id) AS max_course_id FROM course";
        $course_ids = mysqli_query($dbc, $query);
        foreach($course_ids as $a)
        {
            $course_id2 = $a['max_course_id']; 
        }
        $course_id = $course_id2 + 1;
        $course_title = mysqli_real_escape_string($dbc, trim($_POST['course_title']));
        $course_code = mysqli_real_escape_string($dbc, trim($_POST['course_code']));
        $lab_theory = mysqli_real_escape_string($dbc, trim($_POST['lab_theory']));
        $course_type_id = mysqli_real_escape_string($dbc, trim($_POST['course_types'])); // Corrected variable name
        $dept_id = mysqli_real_escape_string($dbc, trim($_POST['department']));
        $sem = mysqli_real_escape_string($dbc, trim($_POST['sem']));
        $total_external = mysqli_real_escape_string($dbc, trim($_POST['total_external']));
        $total_internal = mysqli_real_escape_string($dbc, trim($_POST['total_internal']));
        $credits = mysqli_real_escape_string($dbc, trim($_POST['credit'])); // Corrected variable name
        $syllabus = mysqli_real_escape_string($dbc, trim($_POST['syllabus_intro_year'])); // Corrected variable name
        $grad_level = mysqli_real_escape_string($dbc, trim($_POST['level'])); // Corrected variable name
        $pgmid = mysqli_real_escape_string($dbc, trim($_POST['programme']));
        $error = 0;
        if (!empty($course_id) && !empty($course_title)) {
            $query = "INSERT INTO course(course_id, course_title, course_code, lab_theory, course_type_id,
            dept_id, semester, syllabus_intro_year, credits, total_external, total_internal, grad_level) 
             VALUES(" . (empty($course_id) ? "NULL" : "'" . $course_id . "'") . ","
                . (empty($course_title) ? "NULL" : "'" . $course_title . "'") . ","
                . (empty($course_code) ? "NULL" : "'" . $course_code . "'") . ","
                . (empty($lab_theory) ? "NULL" : "'" . $lab_theory . "'") . ","
                . (empty($course_type_id) ? "NULL" : "'" . $course_type_id . "'") . ","
                . (empty($dept_id) ? "NULL" : "'" . $dept_id . "'") . ","
                . (empty($sem) ? "NULL" : "'" . $sem . "'") . ","
                . (empty($syllabus) ? "NULL" : "'" . $syllabus. "'") . ","
                . (empty($credits) ? "NULL" : "'" . $credits . "'") . ","
                . (empty($total_external) ? "NULL" : "'" . $total_external . "'") . ","
                . (empty($total_internal) ? "NULL" : "'" . $total_internal . "'") . ","
                . (empty($grad_level) ? "NULL" : "'" . $grad_level . "'") . ")";

                echo $query;
            $query1 ="INSERT INTO pgm_course(pgm_id,course_id) 
                        VALUES(". (empty($pgmid)? "NULL":$pgmid) . ","
                        . (empty($course_id) ? "NULL" : "'" . $course_id . "'") . ")";
                
            // Execute the query
            mysqli_query($dbc, $query) or die("Error while inserting course data.");
            mysqli_query($dbc, $query1) or die("Error while inserting course data.");
            // Redirect on success
            $success_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/courseaddsuccess.php?'  . SID;
            header('Location: ' . $success_url);
        }
    }

    mysqli_close($dbc);
}
?>


    <form enctype="multipart/form-data" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <center>
        <fieldset style="width:50%;background-color:white">
            <legend><b>Course Details</b></legend>
            <table align="center">
                <tr>
                    <th>Course Title</th>
                    <th>:</th>
                    <td><input type="text" id="course_title" name="course_title" value="<?php if (!empty($course_title)) echo $course_title; ?>" /></td>
                </tr>
                <tr>
                    <th>Course Code</th>
                    <th>:</th>
                    <td><input type="text" id="course_code" name="course_code" value="<?php if (!empty($course_code)) echo $course_code; ?>" /></td>
                </tr>
                <tr>
                    <th><label for="lab_theory">Category</label></th>
                    <th>:</th>
                    <td>
                        <select name="lab_theory">
                            <option value="0" <?php if (!empty($lab_theory) && $lab_theory == "0") echo "selected"; ?>></option>
                            <option value="T" <?php if (!empty($lab_theory) && $lab_theory == "T") echo "selected"; ?>>Theory</option>
                            <option value="L" <?php if (!empty($lab_theory) && $lab_theory == "L") echo "selected"; ?>>Lab</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th>Course Type</th>
                    <th>:</th>
                    <td>
                        <select name="course_types">
                            <option value="0" <?php if (!empty($course_type_id) && $course_type_id == "0") echo "selected"; ?>></option>
                            <?php foreach ($course_types as $course_type) { ?>
                                <option value="<?php echo $course_type['course_type_id']; ?>" <?php if (!empty($course_type_id) && $course_type_id == $course_type['course_type_id']) echo "selected"; ?>><?php echo $course_type['course_type_desc']; ?></option>
                            <?php } ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th><label for="department">Department</label></th>
                    <th>:</th>
                    <td>
                        <select name="department">
                            <?php foreach ($depts as $dept) { ?>
                                <option value="<?php echo $dept['dept_id']; ?>" <?php if (!empty($dept_id) && $dept_id == $dept['dept_id']) echo "selected"; ?>><?php echo $dept['dept_name']; ?></option>
                            <?php } ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th><label for="programme">Programme</label></th>
                    <th>:</th>
                    <td><select name="programme">
                    <?php foreach($pgms as $pgm)
                    {?>
                        <option value="<?php echo $pgm['pgm_id'];?>" <?php if(!empty($pgmid)) if($pgmid == $pgm['pgm_id']) echo "selected"; ?>><?php echo $pgm['pgm_name']; ?></option>
                    <?php }?>
                     </select><td></tr>
                <tr>
                    <th>Semester</th>
                    <th>:</th>
                    <td><input type="number" id="sem" name="sem" value="<?php if (!empty($sem)) echo $sem; ?>" /></td>
                </tr>
                <tr>
                    <th>Credits</th>
                    <th>:</th>
                    <td><input type="number" id="credit" name="credit" value="<?php if (!empty($credits)) echo $credits; ?>" /></td>
                </tr>
                <tr>
                    <th>Total Internal</th>
                    <th>:</th>
                    <td><input type="number" id="total_internal" name="total_internal" value="<?php if (!empty($total_internal)) echo $total_internal; ?>" /></td>
                </tr>
                <tr>
                    <th>Total External</th>
                    <th>:</th>
                    <td><input type="number" id="total_external" name="total_external" value="<?php if (!empty($total_external)) echo $total_external; ?>" /></td>
                </tr>
                <tr>
                    <th>Syllabus Intro Year</th>
                    <th>:</th>
                    <td>
                        <select name="syllabus_intro_year">
                            <?php
                            if (empty($syllabus)) {
                                for ($i = 2004; $i <= 2050; $i++) { ?>
                                    <option value="<?php echo $i; ?>" <?php if ($i == date("Y")) echo "selected"; ?>><?php echo $i; ?></option>
                                <?php }
                            } else {
                                for ($i = 2004; $i <= 2050; $i++) { ?>
                                    <option value="<?php echo $i; ?>" <?php if ($i == $syllabus) echo "selected"; ?>><?php echo $i; ?></option>
                                <?php }
                            } ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th><label for="level">Graduation Level</label></th>
                    <th>:</th>
                    <td>
                        <select name="level">
                            <option value="0" <?php if (!empty($grad_level) && $grad_level == "0") echo "selected"; ?>></option>
                            <option value="UG" <?php if (!empty($grad_level) && $grad_level == "UG") echo "selected"; ?>>UG</option>
                            <option value="PG" <?php if (!empty($grad_level) && $grad_level == "PG") echo "selected"; ?>>PG</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td colspan="3"><button type="submit" value="Submit" name="submit" class="upload-button">SUBMIT</button></td>
                </tr>
            </table>
        </fieldset>
    <center>
    </form>