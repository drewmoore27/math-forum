<?php

ini_set('display_errors', '1');
error_reporting(E_ALL);

require_once "MathContent.php";



class Response extends MathContent
{
    function __construct($conn, $id, $is_top) {
        parent::__construct($conn, $id);
        $this->is_top = $is_top;
        $this->parent_id = $this->get_parent_id($conn);
    }
    
    

    public  $is_top;
    public  $parent_id;
    
    
    
    //******** Query strings  ************
    
    protected function get_children_query_prestring() {
        return "
    SELECT 
        response_below_id
    FROM 
        responses_below
    WHERE 
        response_parent =";
    }
    protected function get_child_column() {
        return "response_below_id";
    }
    protected function get_construct_query_prestring() {
        return "
        SELECT 
            response_date AS date, 
            response_author AS author, 
            response_content AS content 
        FROM responses
        WHERE response_id=
    ";
    }
    protected function get_table() {
        return "responses";
    }
    protected function get_prefix() {
        return "response";
    }
    protected function children_are_top() {
        return False;
    }

    protected function is_post() { return False;}

    //******** Query strings ************
    
    
    
    
 
    
    
    //********* Parents and Related Methods ******
    protected  function parent_query_is_top() {
        return "
        SELECT
            response_parent AS parent_id
        FROM
            responses_top
        WHERE
            response_top_id =
            ";
    }
    protected  function parent_query_not_top() {
        return "
        SELECT
            response_parent AS parent_id
        FROM
            responses_below
        WHERE
            response_below_id =
            ";
    }
    
    public function get_parent_id($conn) {
        if ($this->is_top) {
            $query = $this->parent_query_is_top() . $this->id;
        }
        else {
            $query = $this->parent_query_not_top() . $this->id;
        }
        $result = $conn->query($query);
        if (!$result) {
            echo $query;
            die($conn->error);
        }
        $result_row = $result->fetch_assoc();
        return $result_row['parent_id'];
    }
    
    //Returns either a Post or a Response, depending on $is_top
    public function get_parent($conn) {
        $parent_id = $this->get_parent_id($conn);
        if ($this->is_top) {
            $parent = new Post($conn, $parent_id);
        }
        else {
            $parent_is_top = $this->check_is_top($conn, $parent_id);
            $parent = new Response($conn, $parent_id, $parent_is_top);
        }
        return $parent;
    }
    
    //Each response ultimately has a parent who is a post. Return that id.
    public function get_post_id($conn) {
        $next_parent = $this;
        while (!$next_parent->is_top) {
            $next_parent = $next_parent->get_parent($conn);
        }
        return  $next_parent->get_parent_id($conn);
    }
    

    //***** Method for determining whether a response_id is top level or not.
    public function check_is_top($conn, $response_id) {
        $query = "
            SELECT response_top_id 
            FROM responses_top 
            WHERE response_top_id = " . $response_id;
        $result = $conn->query($query);
        if (!$result) {
            echo $response_id;
            die($conn->error);
        }
        if ($result->num_rows > 0) {
            return True;
        }
        else {
            return False;
        }
        
        
    }
    
}
?>