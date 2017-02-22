<html>
<main>
<?php
session_start();
$config_array = include "../MathForumResources/config.php";
$mysqli = $config_array['conn'];

ini_set('display_errors', '1');
error_reporting(E_ALL);



$edit_subject_text = $mysqli->real_escape_string($_POST['edit_subject_text']);
$user_id = $_SESSION['user_id'];
$id = $_POST['id'];



$content = new Post($mysqli, $id);


$content->edit_subject($mysqli, $edit_subject_text, $user_id);






//send to
$theurl = "Location: https://math.uchicago.edu/~drewmoore/MathForum/Post?id=" . $id;
header($theurl);


?>
  </main>  </html>
