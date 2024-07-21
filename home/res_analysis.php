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
  session_start();?>
<head>
  <link rel="stylesheet" type="text/css" href="s.css" />
</head>


  <h2 align=center style="color:#0E3657;">RESULT ANALYSIS SYSTEM</h2>
  <div class="split-background"> 
  <?php require_once('navmenu.php');?>

  <center>
            <br><br>
            <a href="dept_top.php" class="upload-button2">Department Top</a><br><br>
        
            <a href="studanalysis.php" class="upload-button2">Student Analysis</a><br><br>
            
            <a href="singlesub.php" class="upload-button2">Single Subject Analysis</a><br><br>
            
            <a href="semwiseanalysis.php" class="upload-button2">Semester Wise Analysis</a><br><br>
            
            <a href="advanced_search.php" class="upload-button2">Advanced Search</a><br>
    <center>
  </div>
   
  
   