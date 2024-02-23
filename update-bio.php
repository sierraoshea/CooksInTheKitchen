<!-- PLACEHOLDER VARIABLES -->
<?php
$logged_in = true;
$username = 'username';
?>

<?php 
require('connect-db.php');
require('db_functions.php');
$isAuthor = isAuthor($username);

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
  if (!empty($_POST['action']) && $_POST['action'] == 'Submit')
    {
      updateBio($username, $_POST['bio']);
    } 
}  

?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">  
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="author" content="your name">
  <meta name="description" content="include some description about your page">      
  <title>DB interfacing</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
  <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
  <link rel="icon" type="image/png" href="http://www.cs.virginia.edu/~up3f/cs4750/images/db-icon.png" />
</head>

<body>
<?php include('header.html') ?> 

<?php if (!$isAuthor) { ?>

  <p>You must <a href="create-recipe.php">create your first recipe</a> before updating your bio.</p>

<?php } else { ?>

  <div class="container">

  <h1>
    Update Bio
  </h1>
  <form action="update-bio.php" method="post">
    <!-- <p><label for="bio">Tell the world about yourself:</label></p> -->
    <textarea id="bio" name="bio" rows="8" cols="80">Write your bio here...</textarea>
    <br>
    <input style="margin:5px" type="submit" value="Submit" name="action" class="btn btn-primary" title="Submit" >
  </form>

<?php } ?>


<br/>
<?php include('footer.html') ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>
</html>