<?php
//variables defined include: 
//$postid
//$post_query
//$post_result
//$response_query
//$response_result


include "vars.php";


//Display Errors when they appear
ini_set('display_errors', '1');
error_reporting(E_ALL);


//Under the text for every response, there are some extra tidbits - the author's name, some buttons.
function response_panel($author, $isowned, $buttonclass="reply") {
    echo "<div class='content_footer'>
        <span>-" . $author .
        "</span>
        <button class='interact_panel pull_right " . $buttonclass . "'>Reply</button>";
    if ($isowned) {
        echo "<button class='interact_panel pull_right edit'>Edit</button>";
    }
    else {
        echo 
        "<img class='interact_panel pull_right' src='down.png'></img>
        <img class='interact_panel pull_right' src='up.png'></img>";
    }
    echo "</div>";
}

//given a response, find necessary data about its children
function response_below_query($parent_id) {
    return "SELECT 
        responses.response_id, responses.response_author, responses.response_date, responses.response_content, responses_below.response_below_id, responses_below.response_parent, users.user_id, users.user_name
    FROM 
        responses
    INNER JOIN 
        responses_below
    ON 
        responses.response_id = responses_below.response_below_id
    INNER JOIN
        users
    ON
        responses.response_author = users.user_id
    WHERE 
        responses_below.response_parent =" . $parent_id;
}

function form_edit_string($response_content, $response_id, $postid, $isbelow=True) {
     return "<form class='submission edit' method='post' action='process_edit.php'>
    <textarea name='edit_text' class='edit content below' rows=10>" . $response_content . "</textarea>
    <div class='submission_footer'>
        <button class='interact_panel pull_right submit_edit'>Submit Edit</button>
        <button class='interact_panel pull_right cancel_edit' type='button'>Cancel</button>
        <input type='hidden' name='id' value="
        . $response_id .
        ">
        <input type='hidden' name='isbelow' value="
        . $isbelow .
        ">
        <input type='hidden' name='postid' value="
        . $postid .
        "> </div>
    </form>";
}


function form_reply_string($response_id, $postid, $isbelow=True) {
    return "<form class='submission reply' method='post' action='process_reply.php'>
    <textarea name='replytext' class='reply content below' rows=10></textarea>
    <div class='submission_footer'>
        <button class='interact_panel pull_right submit'>Submit</button>
        <button class='interact_panel pull_right cancel' type='button'>Cancel</button>
        <input type='hidden' name='id' value="
        . $response_id .
        ">
        <input type='hidden' name='isbelow' value="
        . $isbelow .
        ">
        <input type='hidden' name='postid' value="
        . $postid .
        "> </div>
    </form>";
}


//Display content without final </div>
function display_content_nodiv($postid, $isbelow, $isowned, $content, $user_name, $content_id, $class, $buttonclass) {
    echo
        "<div class='content "; 
    echo $class;
    if ($isbelow) {
        echo " below'> ";
    }
    else {
        echo "'>";  
    }
    echo "<p class='text'>" .
        //child_row['response_content'] .
        $content .
        "</p>";
    //response_panel($child_row['user_name'], $current_isowned);
    response_panel($user_name, $isowned, $buttonclass);

    //if ($current_isowned) {
    if ($isowned) {
        //echo form_edit_string($child_row['response_content'],$child_row['response_id'], $postid);
        echo form_edit_string($content, $content_id, $postid);
    }

    echo form_reply_string($content_id, $postid);
}

//$parent_result is a result of query obtained by listing data for all the responses to a given parent. $conn is the mysqli connection. 
function display_responses($parent_result, $conn, $postid, $current_user_name, $buttonclass, $isbelow=False) {    
    while ($child_row = $parent_result->fetch_assoc())
    {
        $current_isowned = ($child_row['user_name'] == $current_user_name);

        display_content_nodiv($postid, $isbelow, $current_isowned, $child_row['response_content'],$child_row['user_name'], $child_row['response_id'], "response", $buttonclass);
        
        
        $child_query = response_below_query($child_row['response_id']);
        $child_result = $conn->query($child_query);
        display_responses($child_result, $conn, $postid, $current_user_name, $buttonclass, True);
        echo "</div>";
    }
}
?>