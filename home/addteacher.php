<style>
    body{
     background: #EAF4FC;
    }
  </style>
<?php
    require_once('appvars.php');
    require_once('connectvars.php');
    session_start();
    $page_title = 'Teacher Entry';
    require_once('header.php');
    require_once('navmenu.php');  // Connect to the database
    $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    if (isset($_SESSION['username']))
    {
        $query = "SELECT dept_id,dept_name FROM department order by dept_name";
        $depts = mysqli_query($dbc, $query);
        $query = "SELECT designation_id,designation_name FROM designation order by designation_name";
        $designs = mysqli_query($dbc, $query);
        if (isset($_POST['submit']))
        {
            
            $name = mysqli_real_escape_string($dbc, trim($_POST['name']));
            $query = "SELECT MAX(teacher_id) AS max_teacher_id FROM teacher";
            $teacher_ids = mysqli_query($dbc, $query);
            foreach($teacher_ids as $a)
            {
                $teacher_id = $a['max_teacher_id']; 
            }
            $query = "SELECT MAX(user_id) AS max_user_id FROM staff_login";
            $user_ids = mysqli_query($dbc, $query);
            foreach($user_ids as $a)
            {
                $user_id = $a['max_user_id']; 
            }
            $user_id = $user_id+1;
            $teacher_id = $teacher_id+1;
            $deptid = mysqli_real_escape_string($dbc, trim($_POST['department']));
            $email = mysqli_real_escape_string($dbc, trim($_POST['email']));
            $sex = mysqli_real_escape_string($dbc, trim($_POST['sex']));
            $designid= mysqli_real_escape_string($dbc, trim($_POST['designation']));
            $pwd = mysqli_real_escape_string($dbc, trim($_POST['pwd']));
            $error=0;
            if (!empty($name) )
            {
                $role_id = 2;
                $pwd_sl_no =0;
               $query = "INSERT INTO teacher(name, designation_id, dept_id, email,sex, teacher_id) 
                         VALUES("   . (empty($name) ? "NULL" : "'" . $name . "'") . ","
                                    . (empty($designid) ? "NULL" : $designid) . ","
                                    . (empty($deptid) ? "NULL" : $deptid) . ","
                                    . (empty($email) ? "NULL" : "'" . $email . "'") . ","
                                    . (empty($sex) ? "NULL" : "'" . $sex . "'") . ","
                                    . (empty($teacher_id) ? "NULL" : "'" . $teacher_id . "'") . ")";
               
                $query1 = "INSERT INTO staff_login (username,user_id, pwd, role_id, teacher_id, pwd_sl_no)
                           VALUES (". (empty($name) ? "NULL" : "'" . $name . "'") . ","
                                    . (empty($user_id) ? "NULL" : "'" .$user_id. "'").","
                                    . (empty($pwd) ? "NULL" : "'" . sha1($pwd) . "'") . ","
                                    . $role_id . ","
                                    . (empty($teacher_id) ? "NULL" : "'" . $teacher_id . "'") . ","
                                    . $pwd_sl_no . ")";    
                                        
         echo $query1;
        mysqli_query($dbc, $query) or die("Error while uploading details.");
        mysqli_query($dbc, $query1) or die("Error while inserting password."); // Insert password
        if ($error == 0) {
            $success_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/teacheraddsuccess.php?' . '&name=' . $name . SID;
            header('Location: ' . $success_url);
        }
    }
    mysqli_close($dbc);
        }
    }
?>

<form enctype="multipart/form-data" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <center>
        <fieldset style="width:50%;background-color:white" >
            <legend><b>Details of Teacher</b></legend>
            <table align="center">
            <tr >
            <th>Name</th>
            <th>:</th>
            <td><input type="text" id="name" name="name" value="<?php if (!empty($name)) echo $name; ?>" /></td></tr>
           
            <tr><th><label for="department">Department</label></th>
            <th>:</th>
            <td><select name="department">
            <?php foreach($depts as $dept)
            {?>
                <option value="<?php echo $dept['dept_id'];?>" <?php if(!empty($deptmid)) if($deptmid == $dept['dept_id']) echo "selected"; ?>><?php echo $dept['dept_name']; ?></option>
            <?php }?>
            </select><td></tr>
            <tr><th><label for="designation">Designation</label></th>
            <th>:</th>
            <td><select name="designation">
            <?php foreach($designs as $design)
            {?>
                <option value="<?php echo $design['designation_id'];?>" <?php if(!empty($designid)) if($designid == $design['designation_id']) echo "selected"; ?>><?php echo $design['designation_name']; ?></option>
            <?php }?>
            </select><td></tr>
            <tr><th><label for="password">Password</label></th>
            <th>:</th>
          
           <td> <input type="text" id="pwd" name="pwd" value="<?php if (!empty($pwd)) echo $pwd; ?>" /></td></tr>
           <tr><th><label for="sex">Sex</label></th>
            <th>:</th>
            <td><select name="sex">
            <option value=0 selected ></option>
                <option value="M" <?php if(!empty($sex)) if($sex == "M") echo "selected"; ?>>Male</option>
                <option value="F" <?php if(!empty($sex)) if($sex == "F") echo "selected"; ?>>Female</option>
            </select></td></tr>
            <tr><th><label for="email" style="width:auto">Email</label></th><th>:</th>
            <td><input type="email" id="email" name="email" value="<?php if (!empty($email)) echo $email; ?>" /></td></tr>
            <tr><td colspan="3"><button type="submit" value="Submit" name="submit" class="upload-button1">SUBMIT</button><td></tr>
     
    <center>