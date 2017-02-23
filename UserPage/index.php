<html>

<?php
//Post/index.php
$mathjax_on = True; //k


//Config file connects to database and defines resource file strings.
$config_array = include '../config.php';


    ini_set('display_errors', '1');
    error_reporting(E_ALL);

//Connection to database
$mysqli = $config_array['conn'];

//Resources from config file
$header = $config_array['header'];
$stylesheet = $config_array['stylesheet'];
$icon = $config_array['icon'];
$javascript = $config_array['javascript'];

session_start();
if(!isset($_SESSION['user_name'])){
    $_SESSION['current_page'] = $_SERVER['REQUEST_URI'];
    header("Location: https://math.uchicago.edu/~drewmoore/MathForum/Login/");
}




echo "
    <head>
        <link href='https://fonts.googleapis.com/css?family=Roboto:400italic,400' rel='stylesheet'                   type='text/css'>

        <title>User Page
        </title>

        <!--Stylesheet-->
        <link rel='stylesheet' type='text/css' href='" . $stylesheet . "'/>

        <!--Icon - FIX -->
        <link rel='shortcut icon' href='../favicon.ico'/>
    ";


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






echo "<body>";


include $header;

echo "<main>";

$the_user = User::from_id($_SESSION['user_id']);
$new_response_ids = $the_user->new_response_id_list();
foreach ($new_response_ids as id) {
  echo id;
  echo "\n";
}

echo "</main>";

?>






</body>
</html>
