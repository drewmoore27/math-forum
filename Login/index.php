<!-- //Login/index.php -->

<html>
<?php
$config_array = include '/ta/drewmoore/public_html/MathForum/MathForumResources/config.php';
$mysqli = $config_array['conn'];
$header = $config_array['header'];
$stylesheet = $config_array['stylesheet'];
$icon = $config_array['icon'];
$javascript = $config_array['javascript'];

session_start();
if($_SERVER["REQUEST_METHOD"]=="POST") {
    // username and password sent
    $username = $mysqli->real_escape_string(
        $_POST['username']);
    $password = $mysqli->real_escape_string(
        $_POST['password']);
     
    $login_query = "
    SELECT user_id, user_name 
    FROM users 
    WHERE user_name = '"
        . $username .
        "'
    AND user_pass = '"
        . $password .
        "'";
    $login_result = $mysqli->query($login_query);
    
    if($login_row = $login_result->fetch_assoc()) {
        $_SESSION['user_id'] =$login_row['user_id'];
        $_SESSION['user_name'] = $login_row['user_name'];
        $active_query = "
            UPDATE users 
            SET last_active = (select(now())) 
            WHERE user_id =" . $login_row['user_id'];
        $mysqli->query($active_query);
        
        if(isset($_SESSION['current_page'])) {
            header ("Location: " . $_SESSION['current_page']);
        }
        else{
            header("Location: ../Home/");
        }
    }
    
    else {
     echo "Error." ;
     header("Location: ..");
    }
}

echo "
    <head>
        <link href='https://fonts.googleapis.com/css?family=Roboto:400italic,400' rel='stylesheet'                   type='text/css'>
        
        <title>All Posts
    ";

echo $_GET['id'];

echo "
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
<body>
    <h3>Login</h3>
    <form method='post'>
        <label>Username :</label><input type="text" name = "username"><br /><br />
        <label>Password :</label><input type="password" name = "password"><br /><br />
        <input type='submit'>
    </form>
    
</body>
</main>

</html>