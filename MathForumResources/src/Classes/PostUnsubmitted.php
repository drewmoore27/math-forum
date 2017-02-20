<?php

require_once "MathContentUnsubmitted.php";

class PostUnsubmitted extends MathContentUnsubmitted {
    function __construct($content, $author_id, $subject) {
        parent::__construct($content, $author_id);
        $this->subject = $subject;
    }
    
    public $subject;
    
    protected function get_post_query() {
        return "
            INSERT INTO 
                posts 
                (post_content, post_date, post_author, post_subject) 
            VALUES 
                ('" . $this->content . "', 
                (SELECT NOW()), " . $this->author_id . ", '" . $this->subject . "')";
    }
    
    public function submit_content($conn) {
        $query = $this->get_post_query();
        $result = $conn->query($query);
        if (!$result) {
            echo $query;
            die($conn->error);
        }
        $post_id = $conn->insert_id;
        return new Post($conn, $post_id);
    }
}
    
?>