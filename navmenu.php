<style>
  li {
    float: right;
  }

  li a {
    display: block;
    color: white;
    text-align: center;
    padding: 8px 18px;
    text-decoration: none;
    font-weight: bold;
  }
  ul {
    list-style-type: none;
    margin: 0;
    padding: 0;
    overflow: hidden;
    background-color: #0E3657;
  }

  li a:hover {
    background-color: #111;
  }
</style>
<?php
if (isset($_SESSION['username'])) {
    echo '<ul class="no-print">';
    echo '<li><a href="logout.php">Log Out (' . $_SESSION['username'] . ')</a></li>';
    echo '<li><a href="index.php">Home</a></li>';
    echo '<li><a href="javascript:history.back()">Back</a></li>';
    echo '</ul>';
    echo '<br>';
} else {
    echo '<a href="login.php">Log In</a> ';
}
?>
