<html>
<main>
<?php
session_start();
$config_array = include "../config.php";
$mysqli = $config_array['conn'];

ini_set('display_errors', '1');
error_reporting(E_ALL);



$edit_text = $mysqli->real_escape_string($_POST['edit_text']);
$user_id = $_SESSION['user_id'];
$id = $_POST['id'];
$post_id = $_POST['post_id'];
$is_post = $_POST['is_post'];
$is_top = $_POST['is_top'];


if ($is_post) {
    $content = new Post($mysqli, $id);
    
}
else {
    $content = new Response($mysqli, $id, $is_top);
    $_SESSION['focus_id'] = "#response" . $id;
}


$content->edit_content($mysqli, $edit_text, $user_id);






//send to 
$theurl = "Location: https://math.uchicago.edu/~drewmoore/MathForum/Post?id=" . $post_id;
header($theurl);


?>
  </main>  </html>