<?php
    ini_set('display_errors', '1');
    error_reporting(E_ALL);

class MathContent
{
    //*******To Implement*******
    //
    //vote_count()
    //
    //content_interaction_panel($user_id)
    //content_reply_form()


    //*******Constructor*********
    //
    //using the mysql connection $conn, construct the response with id $id
    function __construct($conn, $the_id) {
        $this->id = $the_id;
        $query = $this->get_construct_query_prestring() . $the_id;
        $result = $conn->query($query);
        if (!$result) {
            echo $query;
            die($conn->error);
        }
        $result_row = $result->fetch_assoc();
        $this->property_row = $result_row;
        $this->date_created = $result_row['date'];
        $this->author_id = $result_row['author'];
        $this->content = $result_row['content'];
        $this->author_name = $this->get_author_name($conn);

    }

    //********Properties set in the Constructor******
    //
    //depending on content type, will use property row to set properties.
    protected  $property_row;
    public  $id;
    public  $date_created;
    public  $author_id;
    public  $content;
    public  $vote_count;
    public $author_name;




    //******** Protected Query Strings ************************************
    //******** (and other protected methods set by  descendents) ************
    //
    //prestring in the sense that, when querying, will need to append the id.
    protected function get_children_query_prestring() {}
    protected function get_child_column() {}
    protected function get_construct_query_prestring() {}
    protected function get_table() {}
    protected function get_prefix() {}
    protected function children_are_top() {}
    protected function is_post() {}
    protected function is_top() {}




    //******* Methods for Children and Parents *******
    //
    public function get_children_id_array($conn) {
        //appending id - see get_children_query_prestring
        $result = $conn->query($this->get_children_query_prestring() . $this->id);
        if (!$result) {
            echo $query;
            die($conn->error);
        }

        $storeArray = array();
        while ($row = $result->fetch_assoc()) {
            $storeArray[] = $row[$this->get_child_column()];
        }
        return $storeArray;
    }

    public function count_children($conn) {
        $child_array = $this->get_children_array($conn);
        $count = 1;
        if (count($child_array) == 0) {
            $count = 1; //count self once
        }
        else {
            foreach ($child_array as $child) {
                //count each child
                $count += $child->count_children($conn) + 1;
            }
        }
        return $count - 1; //subtract 1 for self
    }

    public function get_children_array($conn) {
        $children_id_array = $this->get_children_id_array($conn);
        $storeArray = array();

        foreach ($children_id_array as $child_id) {
            $storeArray[] = new Response($conn, $child_id, $this->children_are_top());
        }
        return $storeArray;
    }


    public function get_post_id($conn) {
    }


    //********* Methods for User Interaction *******
    //
    public function edit_content($conn, $edited_content, $user_id){
        if ($this->author_id == $user_id) {
            $this->content = $edited_content;
            $query = "UPDATE " . $this->get_table() .
                " SET " . $this->get_prefix() . "_content= '"
                . $edited_content
                ."' WHERE " .
                $this->get_prefix() . "_id = " . $this->id;
                echo $query;
            $result = $conn->query($query);
            if (!$result) {
                echo $query;
                die($conn->error);
            }
        }
    }


    public function is_owned_by($user_id) {
        return ($this->author_id == $user_id);
    }

    protected function get_author_name($conn) {
        $query = "
        SELECT user_name
        FROM users
        WHERE user_id = " . $this->author_id;
        $result = $conn->query($query);
        if (!$result) {
            echo $query;
            die($conn->error);
        }
        else {
            $result_row = $result->fetch_assoc();
            return $result_row['user_name'];
        }

    }



    //***** Methods for Displaying Content ****
    public function interaction_panel($user_id) {
        $htmlf =
            "<div class='content_footer'>
                <span> - %1\$s </span>
                <button class='interact_panel pull_right %2\$s'>
                    Reply
                </button>
                %3\$s " . //This will either be an edit, or up/downvotes
            "</div>";

        $repl1 = $this->author_name;
        if ($this->is_post()) {
            $repl2 = "reply";
            $edit_str = "edit";
        }
        else {
            $repl2 = "reply";
            $edit_str = "edit";
        }
        if ($this->is_owned_by($user_id)) {
            $repl3 =
                "<button class='interact_panel pull_right " . $edit_str .   "'>
                    Edit
                </button>";
        }
        else {
            $repl3 =
                "<img class='interact_panel pull_right' src='../PublicResources/down.png'></img>
                <img class='interact_panel pull_right' src='../PublicResources/up.png'></img>";
        }

        $html = sprintf($htmlf, $repl1, $repl2, $repl3);
        return $html;

    }

    public function form_reply($conn) {
        $htmlf =
            "<form class='submission reply' method='post' action='process_reply.php'>
                <textarea name='reply_text' class='reply content below' rows=10>  </textarea>
                <div class='submission_footer'>
                    <label class ='interact_panel'>Anonymous?</label>
                    <input type='checkbox' name='is_anonymous'>
                    <button class='interact_panel pull_right submit'>
                        Submit
                    </button>

                    <button class='interact_panel pull_right cancel' type='button'>
                        Cancel
                    </button>

                    <input type='hidden' name='id' value=%1\$s>
                    <input type='hidden' name='post_id' value=%2\$s>
                    <input type='hidden' name='is_top' value=%3\$s>
                </div>
            </form>";
        $repl1 = $this->id;
        $repl2 = $this->get_post_id($conn);
        $repl3 = $this->is_post();
        $html = sprintf($htmlf, $repl1, $repl2, $repl3);
        return $html;
    }

    public function form_edit($conn) {
        $htmlf =
            "<form class='submission edit' method='post' action='process_edit.php'>
                <textarea name='edit_text' class='edit content below' rows=10> %1\$s</textarea>
                <div class='submission_footer'>
                    <button class='interact_panel pull_right submit_edit'>". //Top or not. Next similar.
                        "Submit Edit
                    </button>

                    <button class='interact_panel pull_right cancel_edit' type='button'>
                        Cancel
                    </button>

                    <input type='hidden' name='id' value='%2\$s'>
                    <input type='hidden' name='post_id' value='%3\$s'>
                    <input type='hidden' name='is_post' value='%4\$s'>
                    <input type='hidden' name='is_top' value='%5\$s'>
                </div>
            </form>";


        $repl1 = $this->content;
        $repl2 = $this->id;
        $repl3 = $this->get_post_id($conn);
        $repl4 = $this->is_post();
        $repl5 = !$this->is_post() and $this->is_top();
        $html = sprintf($htmlf, $repl1, $repl2, $repl3, $repl4, $repl5);
        return $html;
    }


    //return string displaying content without final div </div>
    public function display_content($conn, $user_id) {
        $html = "";

        //Subject, in case it's a post. We also set the string for the response section header.
        if ($this->is_post()) {
            $subject = "<h2 class ='subject'> " . $this->subject . "</h2>";
            $html .= $subject;
            if ($this->is_owned_by($user_id)) {
                $html .= $this->form_edit_subject();
            }
        }




        //Open div tag, then content

        if ($this->is_post()) {
            $class = "content";
            $id = "post" . $this->id;
        }
        elseif ($this->is_top) {
            $class = "content";
            $id = "response" . $this->id;
        }
        else {
            $class = "content below";
            $id = "response" . $this->id;
        }

        if (isset($_SESSION['focus_id']) and $_SESSION['focus_id'] == $this->id) {
            $pclass = " class = 'focus' ";
        }
        else {
            $pclass = "";
        }



        $html .= "<div class = '" . $class . "'>
                    <p " . $pclass  . " id ='" . $id . "'> " . nl2br($this->content) . " </p>";



        //Add interaction panel
        $html .= $this->interaction_panel($user_id);



        //Add forms
        if ($this->is_owned_by($user_id)) {
            $forms = $this->form_edit($conn) . $this->form_reply($conn);
        }
        else {
            $forms = $this->form_reply($conn);
        }
        $html .= $forms;



        //Close div, and add reponses section title, if post.
        if ($this->is_post()) {
            $html .= "</div>";
            $html .= "<h3>Responses:</h3>";
        }

        $children = $this->get_children_array($conn);

        foreach ($children as $child) {
            $html .= $child->display_content($conn, $user_id);
        }



        //Close div, if not post
        if (!$this->is_post()) {
            $html .= "</div>";
        }

        return $html;



    }

}
?>
