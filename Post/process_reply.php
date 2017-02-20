<?php
session_start();
$config_array = include "../MathForumResources/config.php";
$mysqli = $config_array['conn'];

ini_set('display_errors', '1');
error_reporting(E_ALL);

//form_reply gives 
//'id' (the id of response user is replying to), 
//'post_id' (the "eventual" parent response),
//'reply_text' which contains the content, and 
//'is_top' - which is "True" if this is a response to the post. 
//'is_anonymous' - which is set iff the author wants to remain anonymous.


$post_id = $_POST['post_id']; //NOTE for object oriented, used 'post_id'
$reply_text = $mysqli->real_escape_string($_POST['reply_text']); //NOTE for object oriented, used 'reply_text'
$is_top = $_POST['is_top']; //NOTE for object oriented, used 'is_top'
$parent_id = $_POST['id'];


$is_anonymous = isset($_POST['is_anonymous']);
$user_id = $_SESSION['user_id'];
if ($is_anonymous) {
    $user_id = 1;
}

$unsub_response = new ResponseUnsubmitted ($reply_text, $user_id, $parent_id, $is_top);

$sub_response = $unsub_response->submit_content($mysqli);

$response_id = $sub_response->id;

$theurl = "Location: https://math.uchicago.edu/~drewmoore/MathForum/Post?id=" . $post_id;
$_SESSION['focus_id'] = "#response" . $response_id;

header($theurl);





?>