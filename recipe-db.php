<?php

// getting all the recipes
function selectAllRecipes()
{
    global $db;
    $query = "select * from Recipe";
    $statement = $db->prepare($query);
    $statement->execute();
    $results = $statement->fetchAll();
    $statement->closeCursor();
    return $results;
}

// filtering
function getRecipeByName($name)
{
    global $db;
    $query = "SELECT * FROM Recipe WHERE LOWER(recipe_name) LIKE LOWER(:name)";
    $statement = $db->prepare($query);
    $statement->bindValue(':name', '%' . $name . '%');
    $statement->execute();
    $results = $statement->fetchAll(); // do I need a fetch all
    $statement->closeCursor();
    return $results;
}

function getRecipeByIngredient($ingredient)
{
    global $db;
    $query = "SELECT * FROM Recipe NATURAL JOIN Contains NATURAL JOIN Ingredient WHERE LOWER(name) LIKE LOWER(:ingredient)";
    $statement = $db->prepare($query);
    $statement->bindValue(':ingredient', '%' . $ingredient . '%');
    $statement->execute();
    $results = $statement->fetchAll(); // do I need a fetch all
    $statement->closeCursor();
    return $results;
}

function getRecipeByCuisine($cuisine)
{
    global $db;
    $query = "select * from Recipe where cuisine =:cuisine";
    $statement = $db->prepare($query);
    $statement->bindValue(':cuisine', $cuisine);
    $statement->execute();
    $results = $statement->fetchAll(); // do I need a fetch all
    $statement->closeCursor();
    return $results;
}

function getRecipeBySkillLevel($skill_level)
{
    global $db;
    $query = "select * from Recipe where skill_level =:skill_level";
    $statement = $db->prepare($query);
    $statement->bindValue(':skill_level', $skill_level);
    $statement->execute();
    $results = $statement->fetchAll(); // do I need a fetch all
    $statement->closeCursor();
    return $results;
}

function getRecipeByCookTime($cook_time)
{
    global $db;
    $query = "select * from Recipe where cook_time =:cook_time";
    $statement = $db->prepare($query);
    $statement->bindValue(':cook_time', $cook_time);
    $statement->execute();
    $results = $statement->fetchAll(); // do I need a fetch all
    $statement->closeCursor();
    return $results;
}

function getRecipeByRating($rating)
{
    global $db;
    $query = "select * FROM Recipe WHERE rating =:rating";
    $statement = $db->prepare($query);
    $statement->bindValue(':rating', $rating);
    $statement->execute();
    $results = $statement->fetchAll(); // do I need a fetch all
    $statement->closeCursor();
    return $results;
}


function getFilteredRecipe($query)
{
    global $db;
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    $statement = $db->prepare($query);
    $statement->execute();
    $results = $statement->fetchAll();
    $statement->closeCursor();
    return $results;
}

// sorting 
function sortRecipeByRatingHigh()
{
    global $db;
    $query = "select * from Recipe NATURAL JOIN Cooked_it ORDER BY rating DESC";
    $statement = $db->prepare($query);
    $statement->execute();
    $results = $statement->fetchAll(); // do I need a fetch all
    $statement->closeCursor();
    return $results;
}

function sortRecipeByRatingLow()
{
    global $db;
    $query = "select * from Recipe NATURAL JOIN Cooked_it ORDER BY rating";
    $statement = $db->prepare($query);
    $statement->execute();
    $results = $statement->fetchAll(); // do I need a fetch all
    $statement->closeCursor();
    return $results;
}

function sortRecipeBySkillLevelEasy()
{
    global $db;
    $query = "select * from Recipe ORDER BY FIELD(skill_level, 'Easy','Medium','Hard')";
    $statement = $db->prepare($query);
    $statement->execute();
    $results = $statement->fetchAll(); // do I need a fetch all
    $statement->closeCursor();
    return $results;
}

function sortRecipeBySkillLevelHard()
{
    global $db;
    $query = "select * from Recipe ORDER BY FIELD(skill_level, 'Easy','Medium','Hard') DESC";
    $statement = $db->prepare($query);
    $statement->execute();
    $results = $statement->fetchAll(); // do I need a fetch all
    $statement->closeCursor();
    return $results;
}

function sortRecipeByCookTimeHigh()
{
    global $db;
    $query = "select * from Recipe ORDER BY cook_time DESC";
    $statement = $db->prepare($query);
    $statement->execute();
    $results = $statement->fetchAll(); // do I need a fetch all
    $statement->closeCursor();
    return $results;
}


function sortRecipeByCookTimeLow()
{
    global $db;
    $query = "select * from Recipe ORDER BY cook_time";
    $statement = $db->prepare($query);
    $statement->execute();
    $results = $statement->fetchAll(); // do I need a fetch all
    $statement->closeCursor();
    return $results;
}

function sortRecipeByNameA()
{
    global $db;
    $query = "select * from Recipe ORDER BY recipe_name";
    $statement = $db->prepare($query);
    $statement->execute();
    $results = $statement->fetchAll(); // do I need a fetch all
    $statement->closeCursor();
    return $results;
}

function sortRecipeByNameZ()
{
    global $db;
    $query = "select * from Recipe ORDER BY recipe_name DESC";
    $statement = $db->prepare($query);
    $statement->execute();
    $results = $statement->fetchAll(); // do I need a fetch all
    $statement->closeCursor();
    return $results;
}

function sortRecipeByCuisine()
{
    global $db;
    $query = "select * from Recipe GROUP BY cuisine";
    $statement = $db->prepare($query);
    $statement->execute();
    $results = $statement->fetchAll(); // do I need a fetch all
    $statement->closeCursor();
    return $results;
}


//pranchis 
function getPeopleMade($recipe_id)
{
    try {
        global $db;
        $query = "SELECT count(*) FROM `Cooked_it` where recipe_id = :recipe_id";
        $statement = $db->prepare($query);
        $statement->bindValue(":recipe_id", $recipe_id);
        $statement->execute();
        $result = $statement->fetch();
        $statement->closeCursor();
        return $result[0];
    } catch (PDOException $e) {
        echo $e->getMessage();
    } catch (Exception $x) {
        echo $x->getMessage();
    }
}

function getDirections($recipe_id)
{
    try {
        global $db;
        $query = "SELECT intr_data FROM `Instruction` natural join Recipe where recipe_id = :recipe_id";
        $statement = $db->prepare($query);
        $statement->bindValue(":recipe_id", $recipe_id);
        $statement->execute();
        $result = $statement->fetch();
        $statement->closeCursor();
        return $result[0];
    } catch (PDOException $e) {
        echo $e->getMessage();
    } catch (Exception $x) {
        echo $x->getMessage();
    }
}

function getImageURL($recipe_id)
{
    try {
        global $db;
        $query = "SELECT image_data from Recipe where recipe_id = :recipe_id";
        $statement = $db->prepare($query);
        $statement->bindValue(":recipe_id", $recipe_id);
        $statement->execute();
        $result = $statement->fetch();
        $statement->closeCursor();
        return $result[0];
    } catch (PDOException $e) {
        echo $e->getMessage();
    } catch (Exception $x) {
        echo $x->getMessage();
    }
}

function getIngredients($recipe_id)
{
    try {
        global $db;
        $query = "SELECT name, amount FROM `Ingredient` NATURAL JOIN `Contains` where recipe_id = :recipe_id";
        $statement = $db->prepare($query);
        $statement->bindValue(":recipe_id", $recipe_id);
        $statement->execute();
        $result = $statement->fetchAll();
        $statement->closeCursor();
        return $result;
    } catch (PDOException $e) {
        echo $e->getMessage();
    } catch (Exception $x) {
        echo $x->getMessage();
    }
}

function getSkillLevel($recipe_id)
{
    // ini_set('display_errors', 1);
    // ini_set('display_startup_errors', 1);
    // error_reporting(E_ALL);
    try {
        global $db;
        $query = "SELECT skill_level FROM Recipe WHERE recipe_id = :recipe_id";
        $statement = $db->prepare($query);
        $statement->bindValue(":recipe_id", $recipe_id);
        $statement->execute();
        $result = $statement->fetch();
        $statement->closeCursor();
        return $result[0];
    } catch (PDOException $e) {
        echo $e->getMessage();
    } catch (Exception $x) {
        echo $x->getMessage();
    }
}

function getCookTime($recipe_id)
{
    try {
        global $db;
        $query = "SELECT cook_time FROM Recipe WHERE recipe_id = :recipe_id";
        $statement = $db->prepare($query);
        $statement->bindValue(":recipe_id", $recipe_id);
        $statement->execute();
        $result = $statement->fetch();
        $statement->closeCursor();
        return $result[0];
    } catch (PDOException $e) {
        echo $e->getMessage();
    } catch (Exception $x) {
        echo $x->getMessage();
    }
}

function getComments($recipe_id)
{
    try {
        global $db;
        $query = "SELECT username, comment_text FROM `Comments` where recipe_id = :recipe_id";
        $statement = $db->prepare($query);
        $statement->bindValue(":recipe_id", $recipe_id);
        $statement->execute();
        $result = $statement->fetchAll();
        $statement->closeCursor();
        return $result;
    } catch (PDOException $e) {
        echo $e->getMessage();
    } catch (Exception $x) {
        echo $x->getMessage();
    }
}

function addComment($username, $recipe_id, $comment_text)
{
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    global $db;
    $query = "INSERT INTO Comments (username, recipe_id, comment_text) VALUES (:username, :recipe_id, :comment_text) ON DUPLICATE KEY UPDATE comment_text = :comment_text";
    $statement = $db->prepare($query);
    $statement->bindValue(':username', $username);
    $statement->bindValue(':recipe_id', $recipe_id);
    $statement->bindValue(':comment_text', $comment_text);
    $statement->execute();
    $statement->closeCursor();
}

function addRating($recipe_id, $username, $rating) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    global $db;
    $query = "INSERT INTO Cooked_it (recipe_id, username, rating) VALUES(:recipe_id, :username, :rating) ON DUPLICATE KEY UPDATE rating = :rating";
    $statement = $db->prepare($query);
    $statement ->bindValue(':recipe_id', $recipe_id);
    $statement ->bindValue(':username', $username);
    $statement ->bindValue(':rating', $rating);
    $statement -> execute();
    $statement ->closeCursor();
    }

function getRecipeRating($id)
{
    global $db;
    $query = "SELECT ROUND(AVG(rating)) as rating FROM Cooked_it WHERE recipe_id = :id";
    $statement = $db->prepare($query);
    $statement->bindValue(':id', $id);
    $statement->execute();
    $result = $statement->fetchColumn();
    $statement->closeCursor();
    return $result;
}
