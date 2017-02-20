<?php
//connect.php
$localhost = 'localhost';
$username = 'drewmoore';
$password = 'drewmoorepa$$w0rd';
$database = 'mathforum';

$mysqli = new mysqli($localhost, $username, $password, $database);

if ($mysqli->connect_error) {
    die('Connect Error: ' . $mysqli->connect_error);   
}

?>