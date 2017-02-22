<html> <main>
<?php
session_start();
$config_array = include "../config.php";
$mysqli = $config_array['conn'];

ini_set('display_errors', '1');
error_reporting(E_ALL);

echo "hi";

$user_name = $_POST['user_name'];
$user_pass = $_POST['user_pass'];
$user_email = $_POST['user_email'];
$confirm_pass = $_POST['confirm_pass'];

if (!$user_pass == $confirm_pass) {
  $_POST['wrong_confirm'] = 1;
  header("Location: ../Signup/");
}

$newuser = new NewUser($user_name,$user_pass, $user_email);

if (!$newuser->name_is_unique($mysqli)) {
  $_POST['name_taken'] = 1;
  header("Location: ../Signup/");
}

$user = $newuser->enter_user($mysqli);
$_SESSION['user_id'] =$user->user_id;
$_SESSION['user_name'] = $user->user_name;

header("Location: ../Home/")
 ?>
 </main></html>
