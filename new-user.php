<?php
session_start();

require("connect-db.php");
require("login-user.php");
$errorMsg = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (!empty($_POST['actionBtn']) && ($_POST['actionBtn'] == "Submit")) {
    if (usernameCheck($_POST['username'])) {
      newUser($_POST['username'], $_POST['password'], $_POST['first_name'], $_POST['last_name'], $_POST['email']);
      $_SESSION['username'] = $_POST['username'];
      header("Location: index.php");
      exit();
    } else {
      $errorMsg = "The username is already taken. Please choose another one.";
    }
  }
}

?>


<!DOCTYPE html>
<html>

<head>
  <title>User Registration</title>
  <!-- Required meta tags -->
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous" />
  <style>
    body {
      background-color: white;
    }

    .form-container {
      margin: 0 auto;
      max-width: 600px;
      padding: 40px;
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
        <a class="navbar-brand d-flex align-items-center" href="login.php">
          <img src="stove.png" width="50" height="50" class="d-inline-block align-top" alt="">
          <span class="ml-2" style="font-size: 3.5rem; font-family: Copperplate, fantasy; ">Cooks in the Kitchen</span>
        </a>
      </div>
    </nav>

  </header>

  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="form-container">
          <h1 class="text-center mb-4">User Registration</h1>

          <?php if ($errorMsg != "") { ?>
            <div class="alert alert-danger" role="alert">
              <?php echo $errorMsg; ?>
            </div>
          <?php } ?>

          <form name="userForm" action="new-user.php" method="post">
            <div class="form-group">
              <label for="firstNameInput">First Name</label>
              <input type="text" class="form-control" name="first_name" id="first_name" placeholder="Enter First Name" />
            </div>
            <div class="form-group">
              <label for="lastNameInput">Last Name</label>
              <input type="text" class="form-control" name="last_name" id="last_name" placeholder="Enter Last Name" />
            </div>
            <div class="form-group">
              <label for="usernameInput">Username</label>
              <input type="text" class="form-control" name="username" id="username" placeholder="Enter Username" />
            </div>
            <div class="form-group">
              <label for="passwordInput">Password</label>
              <input type="password" class="form-control" name="password" id="password" placeholder="Enter Password" />
            </div>
            <div class="form-group">
              <label for="emailInput">Email</label>
              <input type="email" class="form-control" name="email" id="email" placeholder="Enter Email" />
            </div>
            <button type="submit" name="actionBtn" value="Submit" class="btn btn-primary btn-block mt-4">Submit</button>
          </form>
        </div>
      </div>
    </div>
  </div>

</body>

</html>