<?php
//config.php
$config_array = array();

//Define autoloader
function my_autoloader($class) {
    require_once "../MathForumResources/src/Classes/" . $class . ".php";
}
spl_autoload_register('my_autoloader');



//Connect to mathforum database
$localhost = 'localhost';
$username = 'USERNAME';
$password = 'PASSWORD';
$database = 'DATABASE';

$mysqli = new mysqli($localhost, $username, $password, $database);

if ($mysqli->connect_error) {
    die('Connect Error: ' . $mysqli->connect_error);   
}
$config_array['conn'] = $mysqli;

//Resource strings
$config_array['header'] =  "../MathForumResources/header.php";
$config_array['stylesheet']
    = "../PublicResources/mathforum_stylesheet.css";
$config_array['icon']
    =   "../favicon.ico";
$config_array['javascript']
    = "../PublicResources/Javascript";





return $config_array;



?>
