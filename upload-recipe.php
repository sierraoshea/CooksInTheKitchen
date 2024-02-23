<?php
require('connect-db.php');
require('upload_functions.php');
session_start();

$m_id = -1;
$modify = false;
$m_recipe_name = '';
$m_image_url = '';
$m_cuisine = '';
$m_cook_time = '';
$m_skill_level = '';
$m_instructions = '';

//TODO 
// kill dropdown
//uncomment header

//PLACEHOLDER SESSION USERNAME
//$username = 'stacyschips';
$username = $_SESSION['username'];

$unauthorized = (!empty($_GET['r']) && getRecipe($_GET['r'])['username'] != $username);
if (!empty($_GET['r']) && !$unauthorized) {
  //echo $username;
  $modify = true;
  $m_id = $_GET['r'];
}

//submit, add - want ingredient arr to persist. otherwise, unset session
$refresh = false;
$ingredient_error = '';
$recipe_error = '';


if (empty($_SESSION['ingredients'])) {
  $_SESSION['ingredients'] = array();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (!empty($_POST['action']) && $_POST['action'] == 'Add Ingredient') {
    if (!empty($_POST['ingredient_name']) && !empty($_POST['ingredient_amount'])) {

      $ingredient_name = $_POST['ingredient_name'];
      $ingredient_amount = $_POST['ingredient_amount'];

      $ingredient_existing_id = checkIngredient($ingredient_name);


      //if ingredient does not exist
      if ($ingredient_existing_id == 0) {
        addIngredient($ingredient_name);
        // //echo "ingredient does not exist!";
        array_push($_SESSION['ingredients'], array(getLastIngredient(), $ingredient_name, $ingredient_amount));
      } else {
        // //ingredient already exists
        // $ingredient_id = $ingredient_existing_id;
        // // //echo "ingredient already exists!";
        // array_push($_SESSION['ingredients'], array($ingredient_id, $ingredient_name, $ingredient_amount));
        //addIngredient($ingredient_name);
        // //echo "ingredient does not exist!";
        array_push($_SESSION['ingredients'], array($ingredient_existing_id[0], $ingredient_name, $ingredient_amount));
      }
    } else {
      $ingredient_error = "Please fill out all fields";
    }
  } else if (!empty($_POST['action']) && $_POST['action'] == 'x') {
    //echo "deleting ingredient:(" . $_POST['ingredient_to_delete'] . ")";
    \array_splice($_SESSION['ingredients'], $_POST['ingredient_to_delete'], 1);
  } else if (!empty($_POST['action']) && $_POST['action'] == 'Submit') {
    if (empty($_POST['image_url']) || empty($_POST['skill_level']) || empty($_POST['recipe_name']) || empty($_POST['cuisine']) || empty($_POST['cook_time']) || empty($_POST['instructions']) || empty($_SESSION['ingredients'])) {
      $recipe_error = "Please Fill Out All Fields";
    } else {

      $recipe_result = createRecipe($_POST['image_url'], $_POST['skill_level'], $_POST['recipe_name'], $_POST['cuisine'], $_POST['cook_time']);

      if ($recipe_result == -1) {
        $recipe_error = "Please Choose Easy, Medium or Hard for Difficulty";
      } else {

        //echo 'attempting to submit recipe: ';
        // echo $_POST['image_url'];

        $cur_recipe = $recipe_result;

        addInstructions($_POST['instructions'], $cur_recipe);
        foreach ($_SESSION['ingredients'] as $ingredient) :
          linkIngredient($cur_recipe, $ingredient[0], $ingredient[2]);
        endforeach;

        //echo "cur_recipe=(" . $cur_recipe . ")<br>";
        //echo "username=(" . $username . ")<br>";
        upload($cur_recipe, $username);
        if (!isAuthor($username)) {
          createAuthor($username);
        }
        unset($_SESSION['ingredients']);


        $recipe_url = "Location:recipepage.php?rname=" . getRecipe($cur_recipe)['recipe_name'] . "&rid=" . $cur_recipe . "#";
        header($recipe_url);
      }
    }
  } else if (!empty($_POST['action']) && $_POST['action'] == 'Update') {
    if (empty($_POST['image_url']) || empty($_POST['skill_level']) || empty($_POST['recipe_name']) || empty($_POST['cuisine']) || empty($_POST['cook_time']) || empty($_POST['instructions']) || empty($_SESSION['ingredients'])) {
      $recipe_error = "Please Fill Out All Fields";
    } else {

      $cur_recipe = $m_id;

      $recipe_result = updateRecipe($_POST['image_url'], $_POST['skill_level'], $_POST['recipe_name'], $_POST['cuisine'], $_POST['cook_time'], $cur_recipe);

      if ($recipe_result == -1) {
        $recipe_error = "Please Choose Easy, Medium or Hard for difficulty";
      } else {



        deleteIngredients($cur_recipe);
        //echo "cur_recipe = (" . $cur_recipe . ")<br>";
        deleteInstructions($cur_recipe);

        addInstructions($_POST['instructions'], $cur_recipe);
        foreach ($_SESSION['ingredients'] as $ingredient) :
          linkIngredient($cur_recipe, $ingredient[0], $ingredient[2]);
        endforeach;

        $recipe_url = "Location:recipepage.php?rname=" . getRecipe($cur_recipe)['recipe_name'] . "&rid=" . $cur_recipe . "#";
        header($recipe_url);
      }
    }
  }

  //dont unset ingredient arr session if there is any post action
  $refresh = true;
}


if (!$refresh) {
  unset($_SESSION['ingredients']);
  if ($modify) {
    $_SESSION['ingredients'] = array();
    $m_ingredients = getIngredients($m_id);
    // print_r($m_ingredients);
    foreach ($m_ingredients as $m_ingredient) :
      array_push($_SESSION['ingredients'], array($m_ingredient['ingredient_id'], $m_ingredient['name'], $m_ingredient['amount']));
    endforeach;
  }
}
unset($_POST['action']);

if (!empty($_GET['r']) && !$unauthorized) {
  $m_recipe = getRecipe($m_id);
  $m_recipe_name = $m_recipe['recipe_name'];
  $m_image_url = $m_recipe['image_data'];
  $m_cuisine = $m_recipe['cuisine'];
  $m_cook_time = $m_recipe['cook_time'];
  $m_skill_level = $m_recipe['skill_level'];
  $m_instructions = getInstructions($m_id);
}


?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="author" content="your name">
  <meta name="description" content="include some description about your page">
  <title>Upload Recipe</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
  <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
  <link rel="icon" type="image/png" href="http://www.cs.virginia.edu/~up3f/cs4750/images/db-icon.png" />
</head>

<body>

  <?php include('header.html') ?>

  <div class="container">
    <?php if ($unauthorized) { ?>
      <h2>You must be logged in to edit this recipe.<?php echo getRecipe($_GET['r'])['username']; ?></h2>

    <?php } else { ?>
      <div class="container mt-4">
        <?php if ($modify) { ?>
          <h1 class="mb-3 recipe-info">Modifying "<?php echo $m_recipe_name ?>"</h1>
        <?php } else { ?>
          <h1 class="mb-3 recipe-info">Upload Recipe</h1>
        <?php } ?>
        <div class="card p-3">
          <h3 class="mb-3 recipe-info">Add Ingredient</h3>
          <form <?php
                $modify_url = "action = 'upload-recipe.php?r=" . $m_id . "#add_ingredient'";
                echo ($modify) ? $modify_url : "action = 'upload-recipe.php#add_ingredient'" ?> method="post" id=add_ingredient>
            <div class="row">
              <div class="col-sm">
                <label for="ingredient_name" class="form-label">Name:</label>
                <input type="text" class="form-control" id="ingredient_name" name="ingredient_name">
              </div>
              <div class="col-sm">
                <label for="ingredient_amount" class="form-label">Amount:</label>
                <input type="text" class="form-control" id="ingredient_amount" name="ingredient_amount">
              </div>
              <div class="col-sm d-flex align-items-end">
                <button style="margin: 5px" type="submit" name="action" value="Add Ingredient" class="btn btn-outline-primary">Add Ingredient</button>
              </div>
            </div>
            <p><?php echo $ingredient_error ?></p>
          </form>
          <table class="w3-table w3-bordered w3-card-4 center" style="width:70%">
            <!-- <thead>
    <tr style="background-color:#B0B0B0">
      <th width="25%">Name</th>        
      <th width="25%">Cuisine</th>
      <th width="25%">Date</th>
    </tr>
    </thead> -->

            <!-- <?php if (!empty($_SESSION['ingredients'])) {
                    foreach ($_SESSION['ingredients'] as $ingredient) : ?>
    <tr>
      <td><?php echo $ingredient[1]; ?></td>
      <td><?php echo $ingredient[2]; ?></td>
    </tr>
  <?php endforeach;
                  } ?> -->

            <?php if (!empty($_SESSION['ingredients'])) {
              for ($i = 0; $i < sizeof($_SESSION['ingredients']); $i++) { ?>
                <tr>
                  <td><?php $ingredient = $_SESSION['ingredients'][$i];
                      echo $ingredient[1];
                      ?></td>
                  <td><?php echo $ingredient[2]; ?></td>
                  <td>
                    <form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
                      <!-- <button type="button" value="Delete Ingredient" name="action" class="btn btn-danger">x</button>   
        <input type="hidden" name="ingredient_to_delete" value=<?php echo $i ?> /> -->
                      <input type="submit" value="x" name="action" class="btn btn-danger">
                      <input type="hidden" name="ingredient_to_delete" value=<?php echo $i ?> />
                    </form>
                  </td>
                </tr>
            <?php }
            } ?>
          </table>
        </div>
      </div>


      <div class="container mt-4">
        <div class="card p-3">
          <h3 class="mb-3 recipe-info">Recipe Info</h3>
          <form <?php $modify_url = "action='upload-recipe.php?r=" . $m_id . "#recipe'";
                echo ($modify) ? $modify_url : "action='upload-recipe.php#recipe'" ?> method="post" id="recipe">
            <div class="mb-3">
              <label for="recipe_name" class="form-label">Recipe Name:</label>
              <input type="text" class="form-control" id="recipe_name" name="recipe_name" value="<?php echo $m_recipe_name ?>">
            </div>
            <div class="mb-3">
              <label for="image_url" class="form-label">Image URL:</label>
              <input type="text" class="form-control" id="image_url" name="image_url" value="<?php echo $m_image_url ?>">
            </div>
            <div class="row mb-3">
              <div class="col-sm-4">
                <label for="cuisine" class="form-label">Select Cuisine Type:</label>
                <select class="form-select" id="cuisine" name="cuisine">
                  <?php
                  $cuisines = array("Other", "Mexican", "Italian", "Indian", "American", "Chinese", "Thai", "Mediterranean", "Japanese");
                  foreach ($cuisines as $cuisine) {
                    $selected = ($m_cuisine == $cuisine) ? 'selected' : '';
                    echo "<option value='$cuisine' $selected>$cuisine</option>";
                  }
                  ?>
                </select>
              </div>
              <div class="col-sm-4">
                <label for="cook_time" class="form-label">Cook Time (minutes):</label>
                <input type="number" class="form-control" id="cook_time" name="cook_time" value="<?php echo $m_cook_time ?>">
              </div>
              <div class="col-sm-4">
                <label class="form_label">Difficulty (Easy, Medium, or Hard):</label>
                <input type="text" class="form-control" id="skill_level" name="skill_level" value="<?php echo $m_skill_level ?>">
              </div>
            </div>
            <div class="mb-3">
              <label for="instructions" class="form-label">Instructions:</label>
              <textarea id="instructions" name="instructions" rows="8" class="form-control"><?php echo ($modify) ? $m_instructions['intr_data'] : "Write your recipe instructions here..."; ?></textarea>
            </div>

            <div style="text-align:center;">
              <?php if ($modify) { ?>
                <button style="margin: 5px; padding: 10px 20px; font-size: 1.2em;" type="submit" name="action" value="Update" class="btn btn-success">Update Recipe</button>
              <?php } else { ?>
                <button style="margin: 5px; padding: 10px 20px; font-size: 1.2em;" type="submit" name="action" value="Submit" class="btn btn-primary">Submit Recipe</button>
              <?php } ?>
            </div>


            <div style="text-align: center;">
              <p style="color: red; font-size: 2.5em;"><?php echo $recipe_error ?></p>
            </div>



          </form>
        </div>
      </div>



  </div>
<?php } ?>
<?php include('footer.html') ?>
<!-- <?php
      $_SESSION['m_id'] = (empty($_GET['r'])) ? null : ($_GET['r']);
      ?> -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>

</html>