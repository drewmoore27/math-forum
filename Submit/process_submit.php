<?php
session_start();
$config_array = include "../config.php";
$mysqli = $config_array['conn'];

ini_set('display_errors', '1');
error_reporting(E_ALL);



$subject = $mysqli->real_escape_string($_POST['subject']);
$content = $mysqli->real_escape_string($_POST['content']);
$user_id = $_SESSION['user_id'];

$is_anonymous = isset($_POST['is_anonymous']);
$user_id = $_SESSION['user_id'];
if ($is_anonymous) {
    $user_id = 1;
}


$unsub_post = new PostUnsubmitted ($content, $user_id, $subject);

$sub_post = $unsub_post->submit_content($mysqli);

$post_id = $sub_post->id;

$theurl = "Location: https://math.uchicago.edu/~drewmoore/MathForum/Post?id=" . $post_id;
header($theurl);





?>
