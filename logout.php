<?php
require("connect-db.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!empty($_POST['actionBtn']) && ($_POST['actionBtn'] == "Logout")) {
        session_start();
        unset($_SESSION);
        session_destroy();
        session_write_close();
        header('Location: login.php');
        die;
    }
}
