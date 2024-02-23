<?php

function checkUser($username, $password)
{ 
	global $db;
	$query = "SELECT * FROM Users WHERE username=:username";
	$statement = $db->prepare($query);
	$statement->bindValue(':username', $username);
	$statement->execute();
	$user = $statement->fetch();
	$statement->closeCursor();

	if ($user && md5($password) == $user['password']) {
		// username and password match
		return true;
	} else {
		// username and password do not match or user does not exist
		return false;
	}
}

function getUser($username)
{
	global $db;
	$query = "SELECT * FROM Users WHERE username=:username";
	$statement = $db->prepare($query);
	$statement->bindValue(':username', $username);
	$statement->execute();
	$user = $statement->fetch();
	$statement->closeCursor();

	return $user;

}


function usernameCheck($username) {
    global $db;
    $query = "SELECT COUNT(*) FROM Users WHERE username = :username";
    $statement = $db->prepare($query);
    $statement->bindValue(':username', $username);
    $statement->execute();
    $result = $statement->fetchColumn();
    $statement->closeCursor();
	if ($result > 0) {
		return false;
	}
	else {
		return true;
	}

}


function newUser($username, $password, $first_name, $last_name, $email)
{
	global $db;

	// Hash the password using md5 algorithm
	$hashed_password = md5($password);

	$query = "INSERT INTO Users (username, password, first_name, last_name, email) VALUES (:username, :password, :first_name, :last_name, :email)";
	$statement = $db->prepare($query);
	$statement->bindValue(':username', $username);
	$statement->bindValue(':password', $hashed_password);
	$statement->bindValue(':first_name', $first_name);
	$statement->bindValue(':last_name', $last_name);
	$statement->bindValue(':email', $email);
	$statement->execute();
	$statement->closeCursor();
}

function updateUser($username, $first_name, $last_name, $email)
{
    global $db;

    // Hash the password using md5 algorithm

    $query = "UPDATE Users SET first_name = :first_name, last_name = :last_name, email = :email WHERE username = :username";
    $statement = $db->prepare($query);
    $statement->bindValue(':username', $username);
    $statement->bindValue(':first_name', $first_name);
    $statement->bindValue(':last_name', $last_name);
    $statement->bindValue(':email', $email);
    $statement->execute();
	$statement->closeCursor();
}

function updateAuthor($username, $bio)
{
    global $db;

    // Hash the password using md5 algorithm

    $query = "UPDATE Author SET bio = :bio WHERE username = :username";
    $statement = $db->prepare($query);
    $statement->bindValue(':username', $username);
    $statement->bindValue(':bio', $bio);
    $statement->execute();
	$statement->closeCursor();
}


