<?php
session_start();
$username = $_SESSION['username'];
?>


<!DOCTYPE html>
<html>
<head>
  <title>Welcome to My Site</title>
</head>
<body>
  <h1>Welcome, <?php echo $username; ?>!</h1>
  <p>This is the homepage of my site. Thanks for logging in!</p>
</body>
</html>