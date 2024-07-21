<style>
    body{
     background: #EAF4FC;
    }
</style>

<link rel=”stylesheet” href=”https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css” />
<?php
require_once('appvars.php');
require_once('connectvars.php');
session_start();
$page_title = 'Sem Exam Mark Entry';
require_once('header.php');
$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
if (isset($_GET['pgm_id'])) 
{
        $semester = $_GET['sem'];
        $stud_id = $_GET['stud_id'];
        $pgm_id = $_GET['pgm_id'];
        $roll_no = $_GET['roll_no'];
        $year_of_admn = $_GET['year_of_admn'];
        $query = "SELECT pgm_id FROM stud_master WHERE stud_id=" . $stud_id;

    $pgm_ids = mysqli_query($dbc, $query);
    foreach ($pgm_ids as $a) {
        $pgm_id = $a['pgm_id'];
    }
    $query = "SELECT pgm_name FROM programme WHERE pgm_id=" . $pgm_id;
    $pgm_names = mysqli_query($dbc, $query);
    foreach ($pgm_names as $a) {
        $pgm_name = $a['pgm_name'];
    }
    $query = "SELECT name FROM stud_master WHERE stud_id=" . $stud_id;
    $names = mysqli_query($dbc, $query);
    foreach ($names as $a) {
        $name = $a['name'];
    }
    $query = "SELECT uty_reg_no FROM stud_master WHERE stud_id=" . $stud_id;
    $utyregs = mysqli_query($dbc, $query);
    foreach ($utyregs as $a) {
        $utyreg = $a['uty_reg_no'];
    }
}

if (isset($_SESSION['username'])) 
{ 
    if($_SESSION['role_id'] == 2)
    {
        if (isset($_POST['submit']))  
        {
            $stud_id = $_POST['stud_id'];
            $semester = $_POST['semester'];
            $roll_no = $_POST['roll_no'];
            $year_of_admn = $_POST['year_of_admn'];

            foreach ($_POST['ce'] as $course_id => $ce_value) {
                $ese_value = $_POST['ese'][$course_id];

          
            $query = "UPDATE sem_exam SET ce = '$ce_value', ese = '$ese_value' WHERE stud_id = '$stud_id' AND course_id = '$course_id'";
            echo $query;
            mysqli_query($dbc, $query);
        }        
            $success_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/resulteditsuccess.php?' . 'stud_id=' . $stud_id . '&roll_no=' . $roll_no . '&sem=' . $semester . '&year_of_admn=' . $year_of_admn;
            header('Location: ' . $success_url);
        }
    }
}

?>



<br>
<form enctype="multipart/form-data" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" onsubmit="return validateForm()">

<div class="filterform">
    <?php require_once('navmenu.php'); ?>
    
        <input type="hidden" name="stud_id" value="<?php echo $stud_id; ?>">
        <input type="hidden" name="semester" value="<?php echo $semester; ?>">
        <input type="hidden" name="roll_no" value="<?php echo $roll_no; ?>">
        <input type="hidden" name="year_of_admn" value="<?php echo $year_of_admn; ?>">
        <table align=center >
            <tr>
                <td class=leftalign><b>Name</b></td><td>:</td>
                <td class=leftalign><?php echo $name ; ?></td>
            </tr>
            <tr>
                <td class=leftalign><b>Register No</b></td><td>:</td>
                <td class=leftalign><?php echo $utyreg ; ?></td>
            </tr>
            <tr>
                <td class=leftalign><b>Programme</b></td><td>:</td>
                <td class=leftalign><?php echo $pgm_name; ?></td>
            </tr>
            <tr>
                <td class=leftalign><b>Semester</b></td><td>:</td>
                <td class=leftalign><?php echo $semester; ?></td>
            </tr>
        </table>
        <br>
<body>
<script src=”https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js”></script>
        <?php
        if (isset($_SESSION['username']) && $_SESSION['role_id'] == 2) {
        ?>
            
            <table style="width:50%;" class="custom-table" align="center" >
                
                <tr>
                    <th>Sl.no</th>
                    <th>Programmes</th>
                    <th>CE</th>
                    <th>ESE</th>
                </tr>
                <?php
                $query = "SELECT course_id FROM pgm_course WHERE pgm_id = " . $pgm_id;
                $course_ids = mysqli_query($dbc, $query);
                $i = 1;
                $credit = 0;
                $year = 2019;
                $year2 = 2020;
                $query = "SELECT language_id,name FROM stud_master WHERE stud_id=" . $stud_id;
                $l_ids = mysqli_query($dbc, $query);
                foreach ($l_ids as $a) {
                    $l_id = $a['language_id'];
                }
                $names = mysqli_query($dbc, $query);
                foreach ($names as $a) {
                    $name = $a['name'];
                }

                $query = "SELECT dept_id FROM common_course_type WHERE common_course_type_id=" . $l_id;
                $d_ids = mysqli_query($dbc, $query);
                foreach ($d_ids as $a) {
                    $d_id = $a['dept_id'];
                }

                foreach ($course_ids as $a) {
                    $course_id = $a['course_id'];
                    $query = "SELECT course_title, course_type_id, dept_id, total_internal,total_external FROM course
                              WHERE course_id = " . $course_id . "  AND semester = " . $semester . " 
                              AND credits <> " . $credit . " AND  syllabus_intro_year
                              IN (" . $year . ", " . $year2 . ")";
                    
                    $courses = mysqli_query($dbc, $query);
                    
                    foreach ($courses as $a) {
                        $course_type_id = $a['course_type_id'];
                        $dept_id = $a['dept_id'];
                        $total_internal =$a['total_internal'];
                        $total_external =$a['total_external'];
                        if ($course_type_id == 4 && $dept_id != 2) {
                            if ($dept_id == $d_id) {
                                $course_title = $a['course_title'];
                            } else {
                                continue;
                            }
                        }
                         else {
                            $course_title = $a['course_title'];
                        }
                        ?>
                        <tr align="center">
                            <td> <?php echo $i; ?> </td>
                            <td> <?php echo $course_title; '<br>'; ?> </td>
                           
                            <td> 
                                <?php 
                                    $query = "SELECT ce FROM sem_exam WHERE stud_id = ".$stud_id." AND course_id = ".$course_id."";
                                    $ces = mysqli_query($dbc,$query);
                                    foreach($ces as $a)
                                    {
                                        $existingvalue = $a['ce'];
                                    }
                                ?> 
                                <input type="text" class="nav-input" style="width: 40px; text-align: center;"
                                   name="ce[<?php echo $course_id; ?>]" value="<?php echo $existingvalue;?>">
                                <span class="error-message"></span>
                            </td>
                            <td>
                                <?php
                                    $query = "SELECT ese FROM sem_exam WHERE stud_id = ".$stud_id." AND course_id = ".$course_id."";
                                    $eses = mysqli_query($dbc,$query);
                                    foreach($eses as $a)
                                    {
                                        $existingvalue = $a['ese'];
                                    }
                                ?>
                                <input type="text" class="nav-input" style="width: 40px; text-align: center;" 
                                       name="ese[<?php echo $course_id; ?>]" value=" <?php echo $existingvalue;?>">
                                <span class="error-message" ></span>
                            </td>
                        </tr>
                       
                <?php
                        $i++;
                    }
                    }
                }
                ?>
                
            </table>
            <br>
            <button class="upload-button1" type="submit" value="Submit" name="submit" >SUBMIT</button>
            
        </div>
</body> 
<script>

var inputs = document.querySelectorAll('.nav-input');
var columns = 3;
document.addEventListener("keydown", function (event) 
{
    var focusedInput = document.activeElement;
    if (focusedInput && focusedInput.classList.contains("nav-input")) 
    {
        var currentIndex = Array.from(inputs).indexOf(focusedInput);
        var numRows = Math.ceil(inputs.length / columns);

        switch (event.key) 
        {
            case "ArrowRight":
                event.preventDefault();
                if (currentIndex < inputs.length - 1) {
                    inputs[currentIndex + 1].focus();
                }
                break;
            case "ArrowLeft":
                event.preventDefault();
                if (currentIndex > 0) {
                    inputs[currentIndex - 1].focus();
                }
                break;
            case "ArrowUp":
                event.preventDefault();
                var newIndexUp = currentIndex - 2;
                if (newIndexUp >= 0) {
                    inputs[newIndexUp].focus();
                }
                break;
            case "ArrowDown":
                event.preventDefault();
                var newIndexDown = currentIndex + 2;
                if (newIndexDown < inputs.length) {
                    inputs[newIndexDown].focus();
                }
                break;
        }
    }
});

function checkCeInput(input, max) {
  var inputValue = parseInt(input.value);
  var isValid = true;
  var errorMessage = input.parentNode.querySelector('.error-message');
  if (inputValue > max) {
    input.style.borderColor = "red";
    isValid = false;  
  } 
  return isValid; 
}

function validateForm() {
  var ceInputs = document.querySelectorAll('.nav-input[name^="ce["]');
  var eseInputs = document.querySelectorAll('.nav-input[name^="ese["]');
  var isValid = true;

  ceInputs.forEach(function(input) {
    var max = parseInt(input.getAttribute('max'));
    if (!checkInput(input, max)) {
      isValid = false;
    }
  });

  eseInputs.forEach(function(input) {
    var max = parseInt(input.getAttribute('max'));
    if (!checkInput(input, max)) {
      isValid = false;
    }
  });

  return isValid;
}

function checkCeInput(input, max) {
    var inputValue = parseInt(input.value);
    var isValid = true;
    var errorMessage = input.parentNode.querySelector('.error-message');
    
    if (inputValue > max) {
        input.style.borderColor = "red";
        isValid = false;
    } else {
        input.style.borderColor = ""; 
    }

    return isValid;
}
</script>