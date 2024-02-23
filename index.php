<?php
//require("recipeconnect-db.php");
require("connect-db.php");
require("recipe-db.php");
$recipes = selectAllRecipes();
//var_dump($recipes);

// if ($_SERVER['REQUEST_METHOD']=='POST')
// {
//     if(!empty($_POST['actionBtn']) && ($_POST['actionBtn']=="Update")) {
//         $friend_info_to_update = getFriendByName($_POST['friend_to_update']);
//     }

// }


if (isset($_POST['sort'])) { 
  $sort_choice = $_POST['sort'];
  if ($sort_choice == 'recipe_asc') {
      $recipes = sortRecipeByNameA();
  } 
  elseif ($sort_choice == 'recipe_desc') {
      $recipes = sortRecipeByNameZ();
  } 
  elseif ($sort_choice == 'cook_time_asc') {
      $recipes = sortRecipeByCookTimeLow();
  } 
  elseif ($sort_choice == 'cook_time_desc') {
      $recipes = sortRecipeByCookTimeHigh();
  } 
  elseif ($sort_choice == 'skill_level_asc') {
      $recipes = sortRecipeBySkillLevelEasy();
  } 
  elseif ($sort_choice == 'skill_level_desc') {
      $recipes = sortRecipeBySkillLevelHard();
  } 
}

if (isset($_POST['cuisine']) || !empty($_POST['cuisine']) ||
(isset($_POST['rating']) && !empty($_POST['rating'])) ||
(isset($_POST['skill_level']) && !empty($_POST['skill_level']))) {
//$query = "SELECT * FROM Recipe NATURAL JOIN Cooked_it GROUP BY recipe_id HAVING 1=1";
//$query = "SELECT * FROM Recipe WHERE 1=1";
if (isset($_POST['rating']) && !empty($_POST['rating'])) {
$query = "SELECT * FROM Recipe NATURAL JOIN Cooked_it GROUP BY recipe_id HAVING rating IS NOT NULL AND 1=1";
} else {
$query = "SELECT * FROM Recipe GROUP BY recipe_id HAVING 1=1";
}
}

if (isset($_POST['cuisine']) || !empty($_POST['cuisine'])) {
  $cuisine = $_POST['cuisine'];
  if (isset($_POST['cuisine']) && $_POST['cuisine'] == 'Mexican') {
    $query .= " AND cuisine = 'Mexican'";
  } elseif (isset($_POST['cuisine']) && $_POST['cuisine'] == 'Italian') {
    $query .= " AND cuisine = 'Italian'";
  } elseif (isset($_POST['cuisine']) && $_POST['cuisine'] == 'Chinese') {
    $query .= " AND cuisine = 'Chinese'";
  } elseif (isset($_POST['cuisine']) && $_POST['cuisine'] == 'Indian') {
    $query .= " AND cuisine = 'Indian'";
  } elseif (isset($_POST['cuisine']) && $_POST['cuisine'] == 'American') {
    $query .= " AND cuisine = 'American'";
  } elseif (isset($_POST['cuisine']) && $_POST['cuisine'] == 'Thai') {
    $query .= " AND cuisine = 'Thai'";
  } elseif (isset($_POST['cuisine']) && $_POST['cuisine'] == 'Mediterranian') {
    $query .= " AND cuisine = 'Mediterranian'";
  } elseif (isset($_POST['cuisine']) && $_POST['cuisine'] == 'Japanese') {
    $query .= " AND cuisine = 'Japanese'";
  } elseif (isset($_POST['cuisine']) && $_POST['cuisine'] == 'Other') {
    $query .= " AND cuisine = 'Other'";
  }
}

if (isset($_POST['rating']) && !empty($_POST['rating'])) {
  $rating = $_POST['rating'];
  if (isset($_POST['rating']) && $_POST['rating'] == '1') {
    $query .= " AND rating = '1'";
  } elseif (isset($_POST['rating']) && $_POST['rating'] == '2') {
    $query .= " AND rating = '2'";
  } elseif (isset($_POST['rating']) && $_POST['rating'] == '3') {
    $query .= " AND rating = '3'";
  } elseif (isset($_POST['rating']) && $_POST['rating'] == '4') {
    $query .= " AND rating = '4'";
  } elseif (isset($_POST['rating']) && $_POST['rating'] == '5') {
    $query .= " AND rating = '5'";
  }
}

if (isset($_POST['skill_level']) && !empty($_POST['skill_level'])) {
  $skill_level = $_POST['skill_level'];
  if (isset($_POST['skill_level']) && $_POST['skill_level'] == 'Easy') {
    $query .= " AND skill_level = 'Easy'";
  } elseif (isset($_POST['skill_level']) && $_POST['skill_level'] == 'Medium') {
    $query .= " AND skill_level = 'Medium'";
  } elseif (isset($_POST['skill_level']) && $_POST['skill_level'] == 'Hard') {
    $query .= " AND skill_level = 'Hard'";
  }
} 
        

if (isset($_POST['cuisine']) || !empty($_POST['cuisine']) || 
(isset($_POST['rating']) && !empty($_POST['rating'])) ||
(isset($_POST['skill_level']) && !empty($_POST['skill_level']))){
    $recipes = getFilteredRecipe($query);
}



if (isset($_GET['action']) && $_GET['action'] == 'select-all-recipes') {
  $recipes = selectAllRecipes();
}


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['search'])) {
  $name = $_POST['search'];
  $recipes= getRecipeByName($name);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['ingredient_search'])) {
  $ingredient = $_POST['ingredient_search'];
  $recipes = getRecipeByIngredient($ingredient);
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
  <div class="jumbotron">
    <div class="container">
      <h1 class="display-3">Top Rated Recipes</h1>
      <p>Start today by adding your original recipes!</p>
      <p><a class="btn btn-primary btn-lg" href="account.php" role="button">Add Recipe &raquo;</a></p>
    </div>
  </div>
  <!-- <form action ="simpleform.php" method="post">
                        <input type ="submit" name="actionBtn" value="Update" class="btn btn-dark"/>
                        <input type="hidden" name = "friend_to_update" 
                                value="" />
                    </form> -->
</main>
<body>
<div class="container">
<h1>Recipe Database</h1>
	<style>
		.recipe-grid {
			display: grid;
			grid-template-columns: repeat(3, 1fr);
			grid-gap: 20px;
		}

		.recipe-item {
			border: 1px solid #ccc;
			padding: 10px;
			text-align: center;
		}

		.recipe-item img {
			max-width: 100%;
			height: auto;
		}
    .recipe-image {
     height: 200px;
     width: 200px;
    }

    
	</style>

<div class="active-cyan-4 mb-4">
  <form method="POST" action="index.php" style="float:left;">
      <label for="search"></label>
    <input type="text" id="search" name="search" placeholder="Search for a Recipe Name" style="width: 470px;">
    <input type="submit" value="Search" class="btn btn-primary">
  </form>
  <form method="POST" action="index.php" style="float:right;">
      <label for="ingredient_search"></label>
      <input type="text" id="ingredient_search" name="ingredient_search" placeholder="Search for an Ingredient" style="width: 470px;">
      <input type="submit" value="Search" class="btn btn-primary">
  </form><br><br>
    <form action="index.php" method="post">
        <div class="dropdown">
            <select name="sort" id="sort">
                <option value="">--SORT BY--</option>
                <option value="recipe_asc">Recipe (A-Z)</option>
                <option value="recipe_desc">Recipe (Z-A)</option>
                <option value="cook_time_asc">Cook Time (Lowest to Highest)</option>
                <option value="cook_time_desc">Cook Time (Highest to Lowest)</option>
                <option value="skill_level_asc">Skill Level (Lowest to Highest)</option>
                <option value="skill_level_desc">Skill Level (Highest to Lowest)</option>
            </select>
            <input type="submit" value="Sort" class="btn btn-primary">
        </div>
    </form>

  </div>


    <style>
  #filter-container {
    margin: 20px;
    padding: 20px;
    border: 1px solid black;
  }

  #filter-form {
    display: none;
  }
</style>
<div>
  <button id="filter-toggle" class="btn btn-primary">Click to see Filters </button><br>
  <form id="filter-form" action="index.php" method="post" style="display: none;">
    <h3>Filter by:</h3>

    <h4>Cuisine:</h4>
    <label><input type="radio" name="cuisine" value="Italian">Italian</label>
    <label><input type="radio" name="cuisine" value="Mexican">Mexican</label>
    <label><input type="radio" name="cuisine" value="Chinese">Chinese</label>
    <label><input type="radio" name="cuisine" value="Indian">Indian</label>
    <label><input type="radio" name="cuisine" value="American">American</label>
    <label><input type="radio" name="cuisine" value="Thai">Thai</label>
    <label><input type="radio" name="cuisine" value="Mediterranian">Mediterranian</label>
    <label><input type="radio" name="cuisine" value="Japanese">Japanese</label>
    <label><input type="radio" name="cuisine" value="Other">Other</label>

    <h4>Rating:</h4>
    <label><input type="radio" name="rating" value="1">1</label>
    <label><input type="radio" name="rating" value="2">2</label>
    <label><input type="radio" name="rating" value="3">3</label>
    <label><input type="radio" name="rating" value="4">4</label>
    <label><input type="radio" name="rating" value="5">5</label>

    <h4>Skill Level:</h4>
    <label><input type="radio" name="skill_level" value="Easy">Easy</label>
    <label><input type="radio" name="skill_level" value="Medium">Medium</label>
    <label><input type="radio" name="skill_level" value="Hard">Hard</label>

    <br><input type="submit" value="Filter" class="btn btn-primary" >
    <button type="button" class="btn btn-primary" onclick="clearSelections()">Clear Selections</button>
  </form> <br>
  <button class="btn btn-primary" onclick="location.href='index.php'">See All Recipes</button><br><br>


<script>
  function clearSelections() {
    document.querySelectorAll('input[type="radio"]').forEach(radio => {
      radio.checked = false;
    });
  }

  const filterToggle = document.getElementById('filter-toggle');
  const filterForm = document.getElementById('filter-form');
  filterToggle.addEventListener('click', function() {
    filterForm.style.display = filterForm.style.display === 'none' ? 'block' : 'none';
  });
</script>
</div>

<?php if (empty($recipes)): ?>
  <br><br><h2>No recipes fit these selections</h2><br><br>
<?php else: ?>
  <div class="recipe-grid">
    <?php foreach ($recipes as $item): ?>
      <?php $recipename = $item['recipe_name']; ?>
      <?php $recipeid = $item['recipe_id']; ?>
      <?php $recipe_img = getImageURL($recipeid); ?>
      <?php $rating = getRecipeRating($recipeid); ?>
      <div class="recipe-item">
        <a href="recipepage.php?rname=<?php echo $recipename;?>&rid=<?php echo $recipeid; ?>">
          <?php if ($recipe_img): ?>
            <img src="<?php echo $recipe_img;?>" class="recipe-image" alt="<?php echo $recipename;?>" height="200" width="200">
          <?php else: ?>
            <p>No image available</p>
          <?php endif; ?>
        </a>
        <h3><?php echo $recipename; ?></h3>
        <p>Cook Time: <?php echo $item['cook_time']; ?></p>
        <p>Skill Level: <?php echo $item['skill_level']; ?></p>
        <p>Cuisine: <?php echo $item['cuisine']; ?></p>
        <?php if ($rating): ?>
          <p>Rating: <?php echo $rating; ?></p>
        <?php else: ?>
          <p>No ratings yet</p>
        <?php endif; ?>
      </div>
    <?php endforeach; ?>
  </div>
<?php endif; ?>




  
  
  </div>




    <script src="../assets/dist/js/bootstrap.bundle.min.js"></script>
    <footer class="footer mt-auto py-3 bg-light">
    <div class="container">
      <span class="text-muted"></span>
    </div>
</footer>


      
  </body>
</html>