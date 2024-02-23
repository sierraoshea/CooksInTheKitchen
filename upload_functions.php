<?php

//returns false (0) if not found, ingredient_id otherwise
function checkIngredient($name)
{
	global $db;

	$query = 'SELECT ingredient_id FROM `Ingredient` WHERE name = :name';
	$statement = $db->prepare($query);
	$statement->bindValue(':name', $name);
	$statement->execute();
	$results = $statement->fetch();
	$statement->closecursor();


	if ($statement->rowCount() == 0) {
		return false;
	} else {
		return $results;
	}
}

function upload($recipe_id, $username)
{
	global $db;

	$query = 'INSERT INTO `Upload` (recipe_id, username, date_upload) VALUES (:recipe_id, :username, CURRENT_TIMESTAMP)';

	try {
		$statement = $db->prepare($query);
		$statement->bindValue(':recipe_id', $recipe_id);
		$statement->bindValue(':username', $username);
		$statement->execute();

		//echo 'number of rows affected = ' . $statement->rowCount() . '##';
		// if ($statement->rowCount() == 0)
		//echo 'Failed to modify Upload table<br/>';
	} catch (PDOException $e) {
		// 	pass
		// 	//echo $e->getMessage();
	}
	$statement->closeCursor();
}

function getIngredients($recipe_id)
{
	global $db;
	$query = 'SELECT Contains.ingredient_id, name, amount FROM Contains NATURAL JOIN Ingredient WHERE recipe_id=:recipe_id';
	$statement = $db->prepare($query);
	$statement->bindValue(':recipe_id', $recipe_id);
	$statement->execute();
	$results = $statement->fetchAll();
	$statement->closecursor();

	return $results;
}

function deleteInstructions($recipe_id)
{

	global $db;

	$query = 'DELETE FROM Instruction WHERE recipe_id = :recipe_id';

	try {
		$statement = $db->prepare($query);

		$statement->bindValue(':recipe_id', $recipe_id);
		$statement->execute();

		//echo 'number of rows affected = ' . $statement->rowCount() . '##';

		if ($statement->rowCount() == 0)
			//echo 'No row has been updated <br/>';	

			$statement->closeCursor();
	} catch (PDOException $e) {
		//echo $e->getMessage();
	}
}

//only deletes from contains table, not ingredient table
function deleteIngredients($recipe_id)
{

	global $db;

	$query = 'DELETE FROM Contains WHERE recipe_id = :recipe_id';

	try {
		$statement = $db->prepare($query);

		$statement->bindValue(':recipe_id', $recipe_id);
		$statement->execute();

		//echo 'number of rows affected = ' . $statement->rowCount() . '##';

		if ($statement->rowCount() == 0)
			//echo 'No row has been updated <br/>';	

			$statement->closeCursor();
	} catch (PDOException $e) {
		//echo $e->getMessage();
	}
}

//returns recipe id (int)
function getLastRecipe()
{
	global $db;

	$query = 'SELECT MAX(recipe_id) as max FROM Recipe';
	$statement = $db->prepare($query);
	$statement->execute();
	$results = $statement->fetch();
	$statement->closecursor();

	return $results['max'];
}

function getLastIngredient()
{
	global $db;

	$query = 'SELECT MAX(ingredient_id) as max FROM Ingredient';
	$statement = $db->prepare($query);
	$statement->execute();
	$results = $statement->fetch();
	$statement->closecursor();

	return $results['max'];
}

function createRecipe($image_data, $skill_level, $recipe_name, $cuisine, $cook_time)
{
	global $db;
	error_reporting(E_ALL);

	$query = 'INSERT INTO Recipe (image_data, skill_level, recipe_name, cuisine, cook_time) VALUES (:image_data, :skill_level, :recipe_name, :cuisine, :cook_time)';
	echo "before try";
	try {
		$statement = $db->prepare($query);
		$statement->bindValue(':image_data', $image_data);
		$statement->bindValue(':skill_level', $skill_level);
		$statement->bindValue(':recipe_name', $recipe_name);
		$statement->bindValue(':cuisine', $cuisine);
		$statement->bindValue(':cook_time', $cook_time);
		$statement->execute();
		$error_code = $statement->errorInfo()[0];
		if ($error_code == '23000') {
			return -1;
		}
		else {
			return $db->lastInsertId();
		}

		if ($statement->rowCount() == 0) {
			//echo 'No row has been updated <br/>';	
		}
		$statement->closeCursor();
		return 0;
	} catch (PDOException $e) {
		if (strpos($e->getMessage(), 'CONSTRAINT `checkSkill` failed') !== false) {
			echo "Error: The skill level must be Easy, Medium, or Hard";
			return -1;
		}
	}
}


function updateRecipe($image_data, $skill_level, $recipe_name, $cuisine, $cook_time, $recipe_id)
{

	global $db;

	$query = 'UPDATE Recipe SET image_data=:image_data, skill_level=:skill_level, recipe_name = :recipe_name, cuisine = :cuisine, cook_time = :cook_time WHERE recipe_id=:recipe_id';

	try {
		$statement = $db->prepare($query);
		$statement->bindValue(':image_data', $image_data);
		$statement->bindValue(':skill_level', $skill_level);
		$statement->bindValue(':recipe_name', $recipe_name);
		$statement->bindValue(':cuisine', $cuisine);
		$statement->bindValue(':cook_time', $cook_time);
		$statement->bindValue(':recipe_id', $recipe_id);
		$statement->execute();
		$error_code = $statement->errorInfo()[0];
		if ($error_code == '23000') {
			return -1;
		}

		//echo 'number of rows affected = ' . $statement->rowCount() . '##';

		if ($statement->rowCount() == 0)
			//echo 'No row has been updated <br/>';	

			$statement->closeCursor();
	} catch (PDOException $e) {
		if (str_contains($e->getMessage(), "checkSkill")) {
			return -1;
		}
	}
}

function addInstructions($intr_data, $recipe_id)
{
	global $db;

	$query = 'INSERT INTO Instruction (intr_data, recipe_id) VALUES (:intr_data, :recipe_id)';

	try {
		$statement = $db->prepare($query);
		$statement->bindValue(':intr_data', $intr_data);
		$statement->bindValue(':recipe_id', $recipe_id);
		$statement->execute();

		//echo 'number of rows affected = ' . $statement->rowCount() . '##';
		if ($statement->rowCount() == 0)
			//echo 'No row has been updated <br/>';	

			$statement->closeCursor();
	} catch (PDOException $e) {
		//echo $e->getMessage();
	}
}

function addIngredient($name)
{
	global $db;

	$query = 'INSERT INTO Ingredient (name) VALUES (:name)';

	try {
		$statement = $db->prepare($query);
		$statement->bindValue(':name', $name);
		$statement->execute();

		//echo 'number of rows affected = ' . $statement->rowCount() . '##';
		if ($statement->rowCount() == 0)
			//echo 'No row has been updated <br/>';	

			$statement->closeCursor();
	} catch (PDOException $e) {
		//echo $e->getMessage();
	}
}

function linkIngredient($recipe_id, $ingredient_id, $amount)
{
	global $db;

	$query = 'INSERT INTO Contains VALUES (:recipe_id, :ingredient_id, :amount)';

	try {
		$statement = $db->prepare($query);
		$statement->bindValue(':recipe_id', $recipe_id);
		$statement->bindValue(':ingredient_id', $ingredient_id);
		$statement->bindValue(':amount', $amount);
		$statement->execute();

		//echo 'number of rows affected = ' . $statement->rowCount() . '##';
		if ($statement->rowCount() == 0)
			//echo 'No row has been updated <br/>';	

			$statement->closeCursor();
	} catch (PDOException $e) {
		//echo $e->getMessage();
	}
}


function isAuthor($username)
{
	global $db;
	$query = 'SELECT username FROM Author WHERE username = :username';
	$statement = $db->prepare($query);
	$statement->bindValue(':username', $username);
	$statement->execute();
	$results = ($statement->fetch());
	$statement->closecursor();

	return ($statement->rowCount());
}

// function getAuthorInfo($username) {
// 	global $db;
// 	$query = 'SELECT first_name, last_name, bio FROM author NATURAL JOIN users WHERE author.username = :username';
// 	$statement = $db->prepare($query);
// 	$statement->bindValue(':username', $username);
// 	$statement->execute();
// 	$results = $statement->fetch();
// 	$statement->closecursor();

// 	return $results;

// }

// function getRecipes($username) {
// 	global $db;
// 	$query = 'SELECT recipe_name, cuisine, date_upload, image_data, cook_time, skill_level FROM recipe NATURAL JOIN upload where username = :username';
// 	$statement = $db->prepare($query);
// 	$statement->bindValue(':username', $username);
// 	$statement->execute();
// 	$results = $statement->fetchAll();
// 	$statement->closecursor();

// 	return $results;
// }

function getRecipe($recipe_id)
{
	global $db;
	$query = 'SELECT recipe_name, cuisine, date_upload, image_data, cook_time, skill_level, username FROM `Recipe` natural join `Upload` where recipe_id = :recipe_id';
	$statement = $db->prepare($query);
	$statement->bindValue(':recipe_id', $recipe_id);
	$statement->execute();
	$results = $statement->fetch();
	$statement->closecursor();
	return $results;
}

function getInstructions($recipe_id)
{
	global $db;
	$query = 'SELECT intr_data FROM `Instruction` where recipe_id = :recipe_id';
	$statement = $db->prepare($query);
	$statement->bindValue(':recipe_id', $recipe_id);
	$statement->execute();
	$results = $statement->fetch();
	$statement->closecursor();

	return $results;
}

function createAuthor($username)
{
	global $db;

	$query = 'INSERT INTO Author VALUES (:username, "")';

	try {
		$statement = $db->prepare($query);
		$statement->bindValue(':username', $username);
		$statement->execute();

		//echo 'number of rows affected = ' . $statement->rowCount() . '##';
		// if ($statement->rowCount() == 0)
		//echo 'Failed to create an author <br/>';
	} catch (PDOException $e) {
	}
	$statement->closeCursor();
}

// //for first time bio creation and updating
// function updateBio($username, $bio) {
// 	global $db;
	
// 	$query = 'UPDATE author SET bio=:bio WHERE username=:username';

// 	try {
// 		$statement = $db->prepare($query);
// 		$statement->bindValue(':username', $username);
// 		$statement->bindValue(':bio', $bio);
// 		$statement->execute();
	
// 		//echo 'number of rows affected = ' . $statement->rowCount() . '##';
// 		if ($statement->rowCount() == 0)
// 	   		//echo 'No row has been updated <br/>';	
	
// 		$statement->closeCursor();
// 	} catch (PDOException $e){
// 		//echo $e->getMessage();
// 	}
// }

// function updateFirstName($username, $name) {
// 	global $db;
	
// 	$query = 'UPDATE users SET first_name=:name WHERE username=:username';

// 	try {
// 		$statement = $db->prepare($query);
// 		$statement->bindValue(':username', $username);
// 		$statement->bindValue(':name', $name);
// 		$statement->execute();
	
// 		//echo 'number of rows affected = ' . $statement->rowCount() . '##';
// 		if ($statement->rowCount() == 0)
// 	   		//echo 'No row has been updated <br/>';	
	
// 		$statement->closeCursor();
// 	} catch (PDOException $e){
// 		//echo $e->getMessage();
// 	}
// }

// function updateLastName($username, $name) {
// 	global $db;
	
// 	$query = 'UPDATE users SET last_name=:name WHERE username=:username';

// 	try {
// 		$statement = $db->prepare($query);
// 		$statement->bindValue(':username', $username);
// 		$statement->bindValue(':name', $name);
// 		$statement->execute();
	
// 		//echo 'number of rows affected = ' . $statement->rowCount() . '##';
// 		if ($statement->rowCount() == 0)
// 	   		//echo 'No row has been updated <br/>';	
	
// 		$statement->closeCursor();
// 	} catch (PDOException $e){
// 		//echo $e->getMessage();
// 	}
// }
