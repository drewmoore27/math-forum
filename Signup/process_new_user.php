<html>
   <style type="text/css">
    .container {
        width: 500px;
        clear: both;
    }
    .container input {
        width: 100%;
        clear: both;
    }

    </style>
    
<main>
<?php


$config_array = include "../config.php";
$mysqli = $config_array['conn'];
session_start();

ini_set('display_errors', '1');
error_reporting(E_ALL);


$user_name = $_POST['user_name'];
$user_pass = $_POST['user_pass'];
$user_email = $_POST['user_email'];
$confirm_pass = $_POST['confirm_pass'];
$key = $_POST['key'];


if ($key != "forumkey") {
    $_SESSION['wrong_key'] = 1;
    header("Location: ../Signup/");
}

else{

if ($user_pass != $confirm_pass) {
    $_SESSION['wrong_confirm'] = 1;
    header("Location: ../Signup/");
}

else {
    $newuser = new NewUser($user_name,$user_pass, $user_email);

    if (!$newuser->name_is_unique($mysqli)) {
      $_SESSION['name_taken'] = 1;
      header("Location: ../Signup/");
    }
    else {
        $user = $newuser->enter_user($mysqli);
        $_SESSION['user_id'] =$user->user_id;
        $_SESSION['user_name'] = $user->user_name;

        header("Location: ../Home/");
    }
}
}
 ?>
 </main></html>
