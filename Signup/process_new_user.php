<?php
session_start();
$config_array = include "../config.php";
$mysqli = $config_array['conn'];


$user_name = $_POST['user_name'];
$user_pass = $_POST['user_pass'];
$confirm_pass = $_POST['confirm_pass'];

if (!$user_pass == $confirm_pass) {
  $_POST['wrong_confirm'] = 1;
  header("Location: ../Signup/");
}

$newuser = new NewUser($user_name,$user_pass);

if (!$newuser->name_is_unique($mysqli)) {
  $_POST['name_taken'] = 1;
  header("Location: ../Signup/");
}

$user = $newuser->enter_user($mysqli);
$_SESSION['user_id'] =$user->user_id;
$_SESSION['user_name'] = $user->user_name;

header("Location: ../Home/")
 ?>
