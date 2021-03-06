<!-- //Login/index.php -->

<html>
<?php
$config_array = include '../config.php';
$mysqli = $config_array['conn'];
$header = $config_array['header'];
$stylesheet = $config_array['stylesheet'];
$icon = $config_array['icon'];
$javascript = $config_array['javascript'];
ini_set('display_errors', '1');
error_reporting(E_ALL);


echo "
    <head>
        <link href='https://fonts.googleapis.com/css?family=Roboto:400italic,400' rel='stylesheet'                   type='text/css'>

        <title>All Posts
        </title>

        <!--Stylesheet-->
        <link rel='stylesheet' type='text/css' href='" . $stylesheet . "'/>

        <!--Icon - FIX -->
        <link rel='shortcut icon' href='../favicon.ico'/>
    ";

$mathjax_on = True; //k
if ($mathjax_on) {
    echo "
        <!--MathJaX-->
        <script type='text/javascript'
        src='https://cdn.mathjax.org/mathjax/latest/MathJax.js?config=TeX-AMS-MML_HTMLorMML'>
        </script>
        ";
}

echo "
        <!--jQuery-->
        <script src='https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js'>
        </script>
    </head>";
?>

<main>

    <?php

session_start();
if($_SERVER["REQUEST_METHOD"]=="POST") {
    // username and password sent
    $username = $mysqli->real_escape_string(
        $_POST['username']);
    $password = $mysqli->real_escape_string(
        $_POST['password']);


    if (User::user_exists($mysqli, $username)) {
      $the_user = User::from_name($mysqli,$username);

      if ($the_user->is_password($password)) {
        $_SESSION['user_id'] = $the_user->user_id;
        $_SESSION['user_name'] = $the_user->user_name;
        $the_user->update_last_active($mysqli);
        if(isset($_SESSION['current_page'])) {
            header ("Location: " . $_SESSION['current_page']);
        }
        else{
            header("Location: ../Home/");
        }
      }
      else {
        $_SESSION['wrong_pass'] = 1;
        //header("Location: ..");
      }

    }
    else {
      $_SESSION['name_no_exist'] = 1;
      //header("Location: ..");
    }

}
?>
<body>
    <h3>Login</h3>
    <form method='post'>
        <label>Username :</label><input type="text" name = "username"><br /><br />
        <label>Password :</label><input type="password" name = "password"><br /><br />
        <input type='submit'>
    </form>
    <p>Or <a href="../Signup">signup here.</a></p>
    <?php
    if (isset($_SESSION['wrong_pass'])) {
      echo "<p>Wrong password</p>";
      unset($_SESSION['wrong_pass']);
    }
    if (isset($_SESSION['name_no_exist'])) {
      echo "<p>Username does not exist.</p>";
      unset($_SESSION['name_no_exist']);
    }
     ?>

</body>
</main>

</html>
