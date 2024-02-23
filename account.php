<!-- PLACEHOLDER VARIABLES -->
<?php
require('connect-db.php');
require('db_functions.php');
require('login-user.php');
session_start();
$username = $_SESSION['username'];


$user = getUser($username);


$first_name = $user['first_name'];
$last_name = $user['last_name'];
$email = $user['email'];


$isAuthor = isAuthor($username);
if ($isAuthor) {
  $author = getAuthor($username);
  $bio = $author['bio'];
}




if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (!empty($_POST['actionBtn']) && ($_POST['actionBtn'] == "Update Profile") && ($isAuthor == True)) {
    updateUser($username, $_POST['first_name'], $_POST['last_name'], $_POST['email']);
    updateAuthor($username, $_POST['bio']);
    $user = getUser($username);
    $first_name = $user['first_name'];
    $last_name = $user['last_name'];
    $email = $user['email'];
    $author = getAuthor($username);
    $bio = $author['bio'];
  }
  if (!empty($_POST['actionBtn']) && ($_POST['actionBtn'] == "Update Profile") && ($isAuthor == False)) {
    updateUser($username, $_POST['first_name'], $_POST['last_name'], $_POST['email']);
    $user = getUser($username);
    $first_name = $user['first_name'];
    $last_name = $user['last_name'];
    $email = $user['email'];
  }


  if (!empty($_POST['actionBtn']) && ($_POST['actionBtn'] == "Delete Recipe")) {
    $recipe_id = $_POST['recipe_id'];
    deleteRecipe($recipe_id);
    $recipes = getRecipes($username);
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


  <body class="d-flex flex-column h-100">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">


    <header>
      <!-- Fixed navbar -->
      <nav class="navbar navbar-expand-lg navbar navbar-dark bg-dark">
        <div class="d-flex justify-content-between w-100">
          <a class="navbar-brand d-flex align-items-center" href="index.php">
            <img src="stove.png" width="50" height="50" class="d-inline-block align-top" alt="">
            <span class="ml-2" style="font-size: 3.5rem; font-family: Copperplate, fantasy; ">Cooks in the Kitchen</span>
          </a>
          <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="font-size: 2.5rem; font-family: Copperplate, fantasy;">
                Profile
              </a>
              <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                <a class="dropdown-item" style="font-size: 2rem;" href="account.php">My Account</a>
                <div class="dropdown-divider"></div>
                <form action="logout.php" method="POST">
                  <button type="submit" name="actionBtn" style="font-size: 1.7rem;" value="Logout" class="dropdown-item">Logout</button>
                </form>
              </div>
            </li>
          </ul>
        </div>
      </nav>
    </header>




    <!-- Main jumbotron for a primary marketing message or call to action -->
    <div class="jumbotron jumbotron-sm">
      <div class="container">
        <h1 class="display-3"> Welcome, <?php echo $username; ?> </h1>
        <?php if ($isAuthor == True) { ?>
          <h2 class="display-6 text-secondary"> <?php echo $author['bio']; ?> </h2>
        <?php } ?>
        <h2 class="display-6 text-secondary"> Number of Comments: <?php echo $user['num_comments']; ?> </h2>
      </div>
    </div>




    <style>
      .jumbotron.jumbotron-sm {
        padding-top: 16px;
        padding-bottom: 16px;
      }


      .card {
        background-color: #f8f9fa;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        padding: 20px;
      }
    </style>


    <div class="container mt-4">
      <div class="card p-3">
        <h1 class="mb-3 profile-edit">Edit Profile</h1>
        <form action="account.php" method="post">
          <div class="mb-3">
            <label for="first_name" class="form-label">First Name:</label>
            <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo $first_name; ?>">
          </div>
          <div class="mb-3">
            <label for="last_name" class="form-label">Last Name:</label>
            <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo $last_name; ?>">
          </div>
          <div class="mb-3">
            <label for="email" class="form-label">Email:</label>
            <input type="email" class="form-control" id="email" name="email" value="<?php echo $email; ?>">
          </div>


          <?php if (isAuthor($username)) : ?>
            <div class="mb-3">
              <label for="bio" class="form-label">Bio:</label>
              <textarea class="form-control" id="bio" name="bio" value="<?php echo $bio; ?>"><?php echo $bio; ?></textarea>
            </div>
          <?php endif; ?>




          <button type="submit" name="actionBtn" value="Update Profile" class="btn btn-primary">Update Profile</button>
        </form>
      </div>
    </div>


    <div class="container mt-4">
      <div class="card p-3">
        <?php if (!$isAuthor) { ?>
          <div class="d-flex justify-content-center my-5">
            <p><button class="btn btn-primary btn-lg"><a href="upload-recipe.php" style="color:white;">Create your first recipe</a></button></p>
          </div>
        <?php } else { ?>
          <h2> Your Recipes </h2>
          <table class="table table-bordered">
            <thead>
              <tr class="table-active">
                <th scope="col">Name</th>
                <th scope="col">Cuisine</th>
                <th scope="col">Date</th>
                <th scope="col"></th>
                <th scope="col"></th>
              </tr>
            </thead>
            <tbody>
              <?php
              $recipes = getRecipes($username);
              foreach ($recipes as $recipe) { ?>
                <tr>
                  <td><?php echo $recipe['recipe_name']; ?></td>
                  <td><?php echo $recipe['cuisine']; ?></td>
                  <td><?php echo $recipe['date_upload']; ?></td>
                  <?php $recipe_id = $recipe['recipe_id']; ?>
                  <td>
                      <div class="d-flex justify-content-center">
                        <button class="btn btn-primary btn-lg" onclick=<?php echo "window.location.href='upload-recipe.php?r=" . $recipe_id . "'"?>>Edit</button>
                        
                      </div>
                  </td>
                  <td>
                    <form action="account.php" method="POST">
                      <div class="d-flex justify-content-center">
                        <input type="hidden" name="recipe_id" value="<?php echo $recipe_id; ?>">
                        <button class="btn btn-danger btn-lg" type="submit" name="actionBtn" value="Delete Recipe">Delete</button>
                      </div>
                    </form>
                  </td>
                </tr>
              <?php } ?>
            </tbody>
          </table>
          <div class="d-flex justify-content-center my-5">
            <p><button class="btn btn-success btn-lg"><a href="upload-recipe.php" style="color:white;">Add a Recipe</a></button></p>
          </div>
        <?php } ?>
      </div>


    </div>






    <br />
    <?php include('footer.html') ?>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
  </body>


</html>
