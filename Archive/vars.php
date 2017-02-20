<?php
ini_set('display_errors', '1');
error_reporting(E_ALL);

//Assume: $mysqli has already been defined.

//In this document, the following variables are defined:
//$postid
//$post_query
//$post_result
//$response_query
//$response_result

//The post_id is embedded in the url (obtained via GET)
$postid = $mysqli->real_escape_string($_GET['id']);


//String for querying about the post (and user who wrote post)
$post_query =  
    "SELECT 
        posts.post_id, posts.post_subject, posts.post_content, posts.post_date, posts.post_author, users.user_id, users.user_name
    FROM 
        posts 
    LEFT JOIN 
        users 
    ON 
        posts.post_author = users.user_id 
    WHERE
         posts.post_id =" . $postid
    ;

//Result from querying about post.
$post_result = $mysqli->query($post_query);

if (!$post_result) {
    echo "Could not find post.";
    echo $mysqli->error;
}


//String for querying about the (top) responses to this post.
$response_query =
    "SELECT 
        responses.response_id, responses.response_author, responses.response_date, responses.response_content, responses_top.response_top_id, responses_top.response_parent, users.user_id, users.user_name
    FROM 
        responses
    INNER JOIN 
        responses_top
    ON 
        responses.response_id = responses_top.response_top_id
    INNER JOIN
        users
    ON
        responses.response_author = users.user_id
    WHERE 
        responses_top.response_parent =" . $postid
    ;


//Response from querying about top responses.
$response_result = $mysqli->query($response_query);
if (!$response_result) {
    echo "Could not find responses.";
    die($mysqli->error);
}


?>
