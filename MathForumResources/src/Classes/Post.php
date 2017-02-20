<?php
ini_set('display_errors', '1');
error_reporting(E_ALL);

require_once "MathContent.php";

class Post extends MathContent
{
    
    //***** Constructor
    //
    function __construct($conn, $id) {
        parent::__construct($conn, $id);
        $this->subject = $this->property_row['subject'];
    }
    
    
    //****** Additional Properties defined for Post in Constructor
    //
    public  $subject;
    
    
    
    
    
    //******** Protected Query Strings ***************
    //******** (and some other protected methods) ****
    
    protected function get_children_query_prestring() {
        return "SELECT 
        response_top_id
    FROM 
        responses_top
    WHERE 
        response_parent =";
    }
    protected function get_child_column() {
        return "response_top_id";
    }
    protected function get_construct_query_prestring() {
        return "
        SELECT 
            post_date AS date, 
            post_author AS author, 
            post_content AS content,
            post_subject AS subject
        FROM posts
        WHERE post_id=
    ";
    }
    protected function get_table() {
        return "posts";
    }
    protected function get_prefix() {
        return "post";
    }
    
    protected function children_are_top() {
        return True;
    }

    
    protected function is_post() { 
        return True;
    }
    
    protected function is_top() {
        return False;
    }

    
    
    
    
    
    
    
 //********* Parents and Related Methods ******   
    //***** A trivial method for Posts, but nontrivial for responses
    public function get_post_id($conn) {
        return $this->id;
    }
    
    //returns string
    public function form_edit_subject() {
        $html = "<div class='subject_footer'>
                <button class='pull_right interact_panel edit_subject'>
                    Edit Subject
                </button>
                                </div>
                <form class = 'submission edit_subject' method = 'post' action = 'process_edit_subject.php'>
                    <textarea name = 'edit_subject_text' class = 'edit_subject content' rows = 2>". $this->subject ."</textarea>
                <div class = 'submission_footer'>
                    <button class = 'interact_panel pull_right submit_edit_subject'> Submit Edit </button>
                    <button class = 'interact_panel pull_right cancel_edit_subject' type='button'> Cancel </button>
                
                    <input type ='hidden' name = 'id' value = ". $this->id .">
                    </div>
                </form>
                ";
        return $html;
    }
    
    public function edit_subject($conn, $edited_subject, $user_id){
        if ($this->author_id == $user_id) {
            $this->subject = $edited_subject;
            $query = "UPDATE  posts
                        SET post_subject= '" 
                . $edited_content
                ."' WHERE post_id = " . $this->id;
                echo $query;
            $result = $conn->query($query);
            if (!$result) {
                echo $query;
                die($conn->error);
            }
        }
    }
    
    public static function get_top_posts_array($conn, $limit) {
        $query = "
            SELECT post_id AS id
            FROM posts
            ORDER BY post_date DESC
            LIMIT "  . $limit;
        $result = $conn->query($query);
        if (!$result) {
            echo $query;
            die($conn->error);
        }
        $post_array = array();
        while ($row = $result->fetch_assoc()) {
            $post_array[] = new Post($conn, $row['id']);
        }
        return $post_array;
    }
    
    public function display_in_list($conn) {
        if ($this->subject == "") {
            $subject_str = "[NO SUBJECT]";
        }
        else {
            $subject_str = $this->subject;
        }
        $html = 
            "<div class = 'postlink'>
                <p> <a href = '../Post/?id=" . $this->id . "'>"
                . $subject_str . " </a></p>
                <div class = 'postlink_footer'>
                <span class = 'postlink_author'> - "
                . $this->author_name . " </span>
                <span class='postlink_count'> #Responses: "
                . $this->count_children($conn) . "
                </div></div>
            ";
        return $html;
    }
    
    public static function display_top_posts($conn, $lim) {
        $post_array = Post::get_top_posts_array($conn, $lim);
        $html = "";
        foreach ($post_array as $post) {
            $post_str = $post->display_in_list($conn);
            $html .= $post_str;
        }
        return $html;
    }
    
    

    

    
  

}
?>