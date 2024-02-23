<?php
session_start();

require("connect-db.php");
require("login-user.php");

$errorMsg = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	if (!empty($_POST['actionBtn']) && ($_POST['actionBtn'] == "Login")) {
		$boolean = checkUser($_POST['username'], $_POST['password']);

		if ($boolean == true) {
			// Redirect to the homepage if login is successful
			$_SESSION['username'] = $_POST['username'];
			header("Location: index.php");
			exit();
		} else {
			$errorMsg = "Username or Password was incorrect";
		}
	}
}

?>

<!DOCTYPE html>
<html>

<head>
	<title>Login Page</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
	<style>
		.login-form {
			margin: 0 auto;
			max-width: 600px;
			padding: 15px;
			border: 1px solid #ccc;
			border-radius: 5px;
			box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
			background-color: #f8f9fa;
		}
	</style>
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
        <a class="navbar-brand d-flex align-items-center" >
          <img src="stove.png" width="50" height="50" class="d-inline-block align-top" alt="">
          <span class="ml-2" style="font-size: 3.5rem; font-family: Copperplate, fantasy; ">Cooks in the Kitchen</span>
        </a>
      </div>
    </nav>

  </header>


	<div class="container mt-5">
		<div class="login-form">
			<h2 class="text-center mb-4">Login</h2>

			<?php if ($errorMsg != "") { ?>
				<div class="alert alert-danger" role="alert">
					<?php echo $errorMsg; ?>
				</div>
			<?php } ?>

			<form name="loginForm" action="login.php" method="post">
				<div class="form-group">
					<label for="username">Username:</label>
					<input type="text" class="form-control" name="username" id="username" placeholder="Enter username">
				</div>
				<div class="form-group">
					<label for="password">Password:</label>
					<input type="password" class="form-control" name="password" id="password" placeholder="Enter password">
				</div>
				<button type="submit" name="actionBtn" value="Login" class="btn btn-primary btn-block">Login</button>
			</form>
			<div class="mt-3 text-center">
				<p>Don't have an account? <a href="new-user.php">Create one here</a></p>
			</div>
		</div>
	</div>

</body>

</html>