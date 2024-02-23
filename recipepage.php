<?php
require("connect-db.php");
require("recipe-db.php");

session_start();
$username = $_SESSION['username'];


if(isset($_GET["rname"]) && isset($_GET["rid"])) {
  $rname = $_GET["rname"];
  $recipe_id = $_GET["rid"];
}
if(isset($_POST["recipe_id"]) & isset($_POST["rname"])) {
  $rname = $_POST["rname"];
  $recipe_id = $_POST["recipe_id"];
}

$recipe_skill_level =  getSkillLevel($recipe_id);
$recipe_cook_time = getCookTime($recipe_id);
$recipe_people_made = getPeopleMade($recipe_id);
$recipe_ingredients = getIngredients($recipe_id);
$recipe_directions = getDirections($recipe_id);
$recipe_img = getImageURL($recipe_id);
$recipe_comments = getComments($recipe_id); //4/27

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (!empty($_POST['actionBtn']) && ($_POST['actionBtn'] == "Add Comment")) {
      addComment($_POST['username'], $_POST['recipe_id'], $_POST['comment-txt']);
      $recipe_name = urlencode($_POST['rname']);
      $recipe_id = urlencode($_POST['recipe_id']);
      header("Location: recipepage.php?rname=$recipe_name&rid=$recipe_id");
      exit();
  }
    if(!empty($_POST['rating'])) {
      //echo $_POST['rating'];
      addRating($_POST['recipe_id'], $_POST['username'], $_POST['rating']);
      $recipe_name = urlencode($_POST['rname']);
      $recipe_id = urlencode($_POST['recipe_id']);
      header("Location: recipepage.php?rname=$recipe_name&rid=$recipe_id");
      exit();
    }
}

?>
<!doctype html>
<html lang="en" class="h-100">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.88.1">
    <title>Recipe Website</title>

    <link rel="canonical" href="https://getbootstrap.com/docs/5.1/examples/sticky-footer-navbar/">

    

    <!-- Bootstrap core CSS -->
<link href="bootstrap.min.css" rel="stylesheet">

    <style>
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        user-select: none;
      }

      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }
      .jumbotron {
        background-image:url(<?php echo $recipe_img;?>);
        background-repeat: no-repeat;
        background-size: contain;
        color: #fff;
        padding: 70px 25px;
      }
    </style>

    
    <!-- Custom styles for this template -->
    <link href="sticky-footer-navbar.css" rel="stylesheet">
  </head>
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

<!-- Begin page content -->
<main role="main">

  <!-- Main jumbotron for a primary marketing message or call to action -->
  <div class="jumbotron text-center">
    
      <h1 class="display-3"><?php echo $rname;?></h1>
    
  </div>
  <!-- <div class="mask" style="background-color: rgba(0, 0, 0, 0.6);">
    <div class="d-flex justify-content-center align-items-center h-100">
      <div class="text-white">
        <h1 class="mb-3">Heading</h1>
        <h4 class="mb-3">Subheading</h4>
        <a class="btn btn-outline-light btn-lg" href="#!" role="button">Call to action</a>
      </div>
    </div>
  </div>
</div> -->

</main>
<body>
  <div class="container">
    <h1 class = "display-3"><?php echo $rname;?></h1>
    <p>Skill Level: <?php echo $recipe_skill_level;?></p>
    <p><?php echo $recipe_cook_time;?> Minutes</p>
    <p><?php echo $recipe_people_made;?> people made this</p>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <form action="recipepage.php" method="post">
    <p>Rate this recipe:</p>
    <input type="radio" name="rating" value="1"><label>1 star</label>
    <input type="radio" name="rating" value="2"><label>2 stars</label>
    <input type="radio" name="rating" value="3"><label>3 stars</label>
    <input type="radio" name="rating" value="4"><label>4 stars</label>
    <input type="radio" name="rating" value="5"><label>5 stars</label>
    <br>
    <input type="submit" value="Rate This Recipe!">
    <input type="hidden" name = "recipe_id" value="<?php echo $recipe_id; ?>" />
    <input type="hidden" name = "rname" value="<?php echo $rname; ?>" />
    <input type="hidden" name = "username" value="<?php echo $username; ?>" />
    
</form>
    
    <h2 class = "display-3"> Ingredients:</h2>
    <ul class="list-group" style="width:70%">
      <?php foreach ($recipe_ingredients as $item): ?>
        <li class="list-group-item">
          <input class="form-check-input me-1" type="checkbox" value="" aria-label="..." >
          &emsp;&emsp;<?php echo $item[0]; echo " -- "; echo $item[1];?></p>
        </li>
      <?php endforeach; ?>
    </ul>

    <h2 class = "display-3"> Directions:</h2>
    <p class="lead">
      <?php echo $recipe_directions;?>
    </p>

    <!-- display comments -->
    <h2 class = "display-3"> Comments:</h2>
    <div class="table-wrap">  
    <script src="https://www.kryogenix.org/code/browser/sorttable/sorttable.js"></script>
  
      <table class="w3-table w3-bordered w3-card-4 center" style="width:70%">
              <thead>
              <tr style="background-color:#B0B0B0">
                  <th>Username</th>       
                  <th>Comment</th>         
              </tr>
              </thead>
              <?php foreach ($recipe_comments as $item): ?>
                <tr>
                  <td><?php echo $item['username']; ?></td>        
                  <td><?php echo $item['comment_text']; ?></td>                  
              </tr>
              <?php endforeach; ?>
              </table>
      </div>
      
      

    <!-- add comments -->

    <form name="UserCommentform" action = "recipepage.php" method = "post">
      <div class="row mb-3 mx-3">
        <!-- Username:
        <input type="text" class="form-control" name="username" required /> -->
        Comment:
        <input type="text" class="form-control" name="comment-txt" required />
        <input type ="submit" name="actionBtn" value="Add Comment" class="btn btn-dark" />
        <input type="hidden" name = "recipe_id" value="<?php echo $recipe_id; ?>" />
        <input type="hidden" name = "rname" value="<?php echo $rname; ?>" />
        <input type="hidden" name = "username" value="<?php echo $username; ?>" />
      </div>
    </form>

  </div>




    <script src="../assets/dist/js/bootstrap.bundle.min.js"></script>
    <footer class="footer mt-auto py-3 bg-light">
    <div class="container">
      <span class="text-muted"></span>
    </div>
</footer>


      
  </body>
</html>