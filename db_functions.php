<?php

function isAuthor($username) {
    global $db;
    $query = "SELECT COUNT(*) FROM Author WHERE username = :username";
    $statement = $db->prepare($query);
    $statement->bindValue(':username', $username);
    $statement->execute();
    $result = $statement->fetchColumn();
    $statement->closeCursor();
	if ($result > 0) {
		return true;
	}
	else {
		return false;
	}

}

function getAuthor($username)
{
	global $db;
	$query = "SELECT * FROM Author WHERE username=:username";
	$statement = $db->prepare($query);
	$statement->bindValue(':username', $username);
	$statement->execute();
	$author = $statement->fetch();
	$statement->closeCursor();

	return $author;

}


function getRecipes($username) {
	global $db;
	$query = "SELECT recipe_name, cuisine, date_upload, recipe_id FROM Recipe NATURAL JOIN Upload where username = :username";
	$statement = $db->prepare($query);
	$statement->bindValue(':username', $username);
	$statement->execute();
	$results = $statement->fetchAll();
	$statement->closecursor();
	
	return $results;
}

function createAuthor($username) {
	global $db;

	$emptybio = "";
	$query = "INSERT INTO Author VALUES (:username, $emptybio)";
	
	try {
		$statement = $db->prepare($query);
		$statement->bindValue(':username', $username);
	   	$statement->execute();

		echo "number of rows affected = " . $statement->rowCount() . "##";
		if ($statement->rowCount() == 0)
			 echo "Failed to create an author <br/>";
	} catch (PDOException $e) {
		echo $e->getMessage();
	}
	$statement->closeCursor();
}

//for first time bio creation and updating
function updateBio($username, $bio) {
	global $db;
	
	$query = "UPDATE Author SET bio=:bio WHERE username=:username";

	try {
		$statement = $db->prepare($query);
		$statement->bindValue(':username', $username);
		$statement->bindValue(':bio', $bio);
		$statement->execute();
	
		echo "number of rows affected = " . $statement->rowCount() . "##";
		if ($statement->rowCount() == 0)
	   		echo "No row has been updated <br/>";	
	
		$statement->closeCursor();
	} catch (PDOException $e){
		echo $e->getMessage();
	}
}

function deleteRecipe($recipe_id) {
    global $db;
   
    // Delete records from Upload table
    $query = "DELETE FROM Upload WHERE recipe_id = :recipe_id";
    $statement = $db->prepare($query);
    $statement->bindValue(':recipe_id', $recipe_id);
    $statement->execute();

	// Delete records from Cooked_it table
    $query = "DELETE FROM Cooked_it WHERE recipe_id = :recipe_id";
    $statement = $db->prepare($query);
    $statement->bindValue(':recipe_id', $recipe_id);
    $statement->execute();

	// Delete records from Comments table
    $query = "DELETE FROM Comments WHERE recipe_id = :recipe_id";
    $statement = $db->prepare($query);
    $statement->bindValue(':recipe_id', $recipe_id);
    $statement->execute();
   
    // Delete records from Contains table
    $query = "DELETE FROM Contains WHERE recipe_id = :recipe_id";
    $statement = $db->prepare($query);
    $statement->bindValue(':recipe_id', $recipe_id);
    $statement->execute();
   
    // Delete records from Modify table
    $query = "DELETE FROM 'Modify' WHERE recipe_id = :recipe_id";
    $statement = $db->prepare($query);
    $statement->bindValue(':recipe_id', $recipe_id);
    $statement->execute();
   
    // Delete records from Instruction table
    $query = "DELETE FROM Instruction WHERE recipe_id = :recipe_id";
    $statement = $db->prepare($query);
    $statement->bindValue(':recipe_id', $recipe_id);
    $statement->execute();
   
    // Delete record from Recipe table
    $query = "DELETE FROM Recipe WHERE recipe_id = :recipe_id";
    $statement = $db->prepare($query);
    $statement->bindValue(':recipe_id', $recipe_id);
    $result = $statement->execute();
   
    $statement->closeCursor();
    return $result;
}


?>