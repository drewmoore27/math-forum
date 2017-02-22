<?php

require_once "MathContentUnsubmitted.php";

class ResponseUnsubmitted extends MathContentUnsubmitted {
    function __construct($content, $author_id, $parent_id, $is_top) {
        parent::__construct($content, $author_id);
        $this->is_top = $is_top;
        $this->parent_id = $parent_id;
    }

    public $is_top;
    public $parent_id;


    protected function get_response_query() {
        return "
            INSERT INTO
                responses
                (response_content, response_date, response_author)
            VALUES
                ('" . $this->content . "', (SELECT NOW()), " . $this->author_id . ")";
    }

    protected function get_response_parent_query($response_id) {
        if ($this->is_top) {
            $query = "
                INSERT INTO
                    responses_top
                    (response_top_id, response_parent)
                VALUES
                    (" . $response_id . ", " . $this->parent_id . ")";
        }
        else {
            $query = "
                INSERT INTO
                    responses_below
                    (response_below_id, response_parent)
                VALUES
                    (" . $response_id . ", " . $this->parent_id . ")";
        }
        return $query;
    }

    public function submit_content($conn) {
        $query1 = $this->get_response_query();
        $result1 = $conn->query($query1);
        if (!$result1) {
            echo $query1;
            die($conn->error);
        }
        $response_id = $conn->insert_id;

        $query2 = $this->get_response_parent_query($response_id);
        $result2 = $conn->query($query2);
        if (!$result2) {
            echo $query2;
            die($conn->error);
        }
        return new Response($conn, $response_id, $is_top);
    }
}

?>
